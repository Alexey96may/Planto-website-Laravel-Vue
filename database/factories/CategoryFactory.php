<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
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
            'title' => $faker->unique()->words(2, true),
            'slug' => fn (array $attributes) => \Illuminate\Support\Str::slug($attributes['title']),
        ];
    }
}
