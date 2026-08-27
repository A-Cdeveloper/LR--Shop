<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'shop.name' => 'LR DemoShop',
            'shop.email' => 'demo@example.com',
            'shop.phone' => '+381641234567',

            'shop.address_line1' => '123 Demo Street',
            'shop.address_line2' => 'Apt 4B',
            'shop.city' => 'Demo City',
            'shop.state' => 'Demo State',
            'shop.zip' => '12345',
            'shop.country' => 'Demo Country',

            'shop.logo_url' => 'https://demo.com/logo.png',

            'shop.currency' => 'EUR',
            'shop.locale' => 'en',
            'shop.timezone' => 'Europe/Belgrade',

            'shop.orders_per_page' => '10',
            'shop.products_per_page' => '20',
        ];

        foreach (Setting::KEYS as $key) {
            $value = $defaults[$key] ?? null;

            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }

        Setting::flushCache();
    }
}
