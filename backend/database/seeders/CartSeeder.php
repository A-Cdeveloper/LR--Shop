<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $cart = Cart::query()->updateOrCreate(
            ['token' => '00000000-0000-4000-8000-000000000001'],
            ['token' => '00000000-0000-4000-8000-000000000001']
        );

        $products = Product::query()->active()->limit(5)->get();

        foreach ($products as $index => $product) {
            CartItem::query()->updateOrCreate(
                [
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                ],
                [
                    'quantity' => $index + 1,
                ]
            );
        }
    }
}
