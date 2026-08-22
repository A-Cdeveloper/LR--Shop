<?php

namespace App\Http\Controllers\Api\V1\Stripe;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlacedMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $signature, $secret);
        } catch (UnexpectedValueException | SignatureVerificationException $e) {
            report($e);

            return response()->json(['message' => 'Invalid payload'], 400);
        }

        match ($event->type) {
            'payment_intent.succeeded' => $this->handlePaymentSucceeded($event),
            'payment_intent.payment_failed' => $this->handlePaymentFailed($event),
            default => null,
        };

        return response()->json(['received' => true]);
    }

    private function handlePaymentSucceeded(Event $event): void
    {
        $order = $this->orderFromPaymentIntent($event);

        if (! $order || $order->payment_status === 'paid') {
            return;
        }

        $order->update(['payment_status' => 'paid']);

        Mail::to($order->user->email)->send(
            new OrderPlacedMail($order->load('items'))
        );
    }

    private function handlePaymentFailed(Event $event): void
    {
        $order = $this->orderFromPaymentIntent($event);

        if (! $order || $order->payment_status !== 'pending') {
            return;
        }

        $order->update(['payment_status' => 'failed']);
    }

    private function orderFromPaymentIntent(Event $event): ?Order
    {
        /** @var PaymentIntent $intent */
        $intent = $event->data->object;
        $orderId = $intent->metadata->order_id ?? null;

        return Order::query()->find($orderId);
    }
}
