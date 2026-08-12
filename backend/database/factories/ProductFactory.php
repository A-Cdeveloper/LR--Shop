<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;




class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::query()->inRandomOrder()->value('id'),
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 19, 1999),
            'stock' => fake()->numberBetween(0, 80),
            'image' => null,
            'is_active' => true,
        ];
    }
}