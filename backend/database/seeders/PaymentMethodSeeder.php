<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethod::query()->updateOrCreate(
            ['name' => 'Cash on delivery'],
            [
                'description' => 'Pay when you receive your order',
                'is_active' => true,
            ],
        );

        PaymentMethod::query()->updateOrCreate(
            ['name' => 'Stripe'],
            [
                'description' => 'Pay securely with Stripe',
                'is_active' => true,
            ],
        );
    }
}
