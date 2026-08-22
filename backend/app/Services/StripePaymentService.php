<?php

namespace App\Services;

use App\Models\Order;
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
}
