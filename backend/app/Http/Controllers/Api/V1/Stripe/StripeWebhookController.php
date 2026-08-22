<?php

namespace App\Http\Controllers\Api\V1\Stripe;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlacedMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    //

    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');
        try {
            $event = Webhook::constructEvent($payload, $signature, $secret);

            if ($event->type === 'payment_intent.succeeded') {
                $intent = $event->data->object;
                $orderId = $intent->metadata->order_id ?? null;

                $order = Order::query()->find($orderId);

                if ($order && $order->payment_status !== 'paid') {
                    $order->update(['payment_status' => 'paid']);
                    Mail::to($order->user->email)->send(
                        new OrderPlacedMail($order->load('items'))
                    );
                }
            }
        } catch (UnexpectedValueException | SignatureVerificationException $e) {
            report($e);
            return response()->json(['message' => 'Invalid payload'], 400);
        }
        // sledeći korak: switch po $event->type
        return response()->json(['received' => true]);
    }
}
