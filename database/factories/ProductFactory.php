<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'title' => $faker->words(3, true),
            'description' => $faker->paragraph(),
            'price' => $faker->randomFloat(2, 10, 5000),
            'stock' => $faker->numberBetween(0, 50),
            'is_trending' => $faker->boolean(20),
            'trending_order' => 0,
            'category_id' => \App\Models\Category::factory(), 
            'image'       => 'products/test-flower.jpg',
            'sales_count' => 0,
        ];
    }
}
