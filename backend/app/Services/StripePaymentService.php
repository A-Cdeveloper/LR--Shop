<?php

namespace App\Services;

use App\Models\Order;
use RuntimeException;
use Stripe\StripeClient;

class StripePaymentService
{
    public function createPaymentIntent(Order $order): array
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        $intent = $stripe->paymentIntents->create([
            'amount' => (int) round($order->total * 100),
            'currency' => strtolower($order->currency),
            'metadata' => [
                'order_id' => $order->id,
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never',
            ],
        ]);

        return [
            'id' => $intent->id,
            'client_secret' => $intent->client_secret,
        ];
    }


    public function refund(Order $order): void
    {
        if (! $order->stripe_payment_intent_id) {
            throw new RuntimeException('Order has no Stripe payment intent.');
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        $stripe->refunds->create([
            'payment_intent' => $order->stripe_payment_intent_id,
        ]);
    }
}
