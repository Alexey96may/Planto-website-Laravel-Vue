<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'title' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 5000),
            'stock' => $this->faker->numberBetween(0, 50),
            'is_trending' => fake()->boolean(20),
            'trending_order' => 0,
            'category_id' => \App\Models\Category::factory(), 
            'image'       => 'products/test-flower.jpg',
            'sales_count' => 0,
        ];
    }
}
