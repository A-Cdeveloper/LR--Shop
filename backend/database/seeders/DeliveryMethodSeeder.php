<?php

namespace Database\Seeders;

use App\Models\DeliveryMethod;
use Illuminate\Database\Seeder;

class DeliveryMethodSeeder extends Seeder
{
    public function run(): void
    {
        DeliveryMethod::query()->updateOrCreate(
            ['name' => 'Pickup in store'],
            [
                'description' => 'Pick up your order at our store',
                'price' => 0,
                'free_over' => null,
                'eta_days_min' => 1,
                'eta_days_max' => 2,
                'is_active' => true,
            ],
        );

        DeliveryMethod::query()->updateOrCreate(
            ['name' => 'Delivery to address'],
            [
                'description' => 'We deliver to your door',
                'price' => 500,
                'free_over' => 5000,
                'eta_days_min' => 2,
                'eta_days_max' => 5,
                'is_active' => true,
            ],
        );
    }
}