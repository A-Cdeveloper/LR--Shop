<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Laptops', 'slug' => 'laptops', 'description' => 'Notebooks and laptops'],
            ['name' => 'Phones', 'slug' => 'phones', 'description' => 'Mobile phones'],
            ['name' => 'Tablets', 'slug' => 'tablets', 'description' => 'Tablets and e-readers'],
            ['name' => 'Monitors', 'slug' => 'monitors', 'description' => 'Computer monitors'],
            ['name' => 'Keyboards', 'slug' => 'keyboards', 'description' => 'Wired and wireless keyboards'],
            ['name' => 'Mice', 'slug' => 'mice', 'description' => 'Computer mice and trackpads'],
            ['name' => 'Headphones', 'slug' => 'headphones', 'description' => 'Headphones and headsets'],
            ['name' => 'Speakers', 'slug' => 'speakers', 'description' => 'Desktop and portable speakers'],
            ['name' => 'Cameras', 'slug' => 'cameras', 'description' => 'Cameras and lenses'],
            ['name' => 'Storage', 'slug' => 'storage', 'description' => 'SSDs, HDDs and USB drives'],
            ['name' => 'Networking', 'slug' => 'networking', 'description' => 'Routers, switches and Wi-Fi'],
            ['name' => 'Wearables', 'slug' => 'wearables', 'description' => 'Smartwatches and fitness bands'],
            ['name' => 'Gaming', 'slug' => 'gaming', 'description' => 'Consoles, controllers and gear'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Cables, cases and more'],
            ['name' => 'Smart Home', 'slug' => 'smart-home', 'description' => 'Smart lights, plugs and hubs'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}