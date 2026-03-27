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
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 5000),
            'stock' => $this->faker->numberBetween(0, 50),
            'is_trending' => $this->faker->boolean(20),
            'trending_order' => 0,
            'category_id' => \App\Models\Category::factory(), 
            'image'       => 'products/test-flower.jpg',
            'sales_count' => 0,
        ];
    }
}
