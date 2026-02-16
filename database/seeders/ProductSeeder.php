<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'title' => 'First testing Plant',
            'description' => 'Plant description',
            'price' => 99.99,
            'image' => '/images/Plant3.png',
        ]);
    }
}
