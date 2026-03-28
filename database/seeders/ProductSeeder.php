<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plants = [
            [
                'title' => 'Saintpaulia ionantha',
                'price' => 250,
                'stock' => 25,
                'is_trending' => true,
                'trending_order' => 14,
                'description' => 'A popular houseplant known for its delicate, colorful blooms and fuzzy, dark green leaves. It blooms nearly year‑round under proper conditions and comes in shades of purple, blue, pink, and white.',
                'image' => 'Plant1.png',
            ],
            [
                'title' => 'Mimosa pudica',
                'price' => 320,
                'is_trending' => true,
                'trending_order' => 4,
                'stock' => 18,
                'description' => 'A fascinating tropical plant famous for its sensitive leaves that fold inward and droop when touched or disturbed—a defense mechanism. It produces small, pink, puffball‑like flowers.',
                'image' => 'Plant2.png',
            ],
            [
                'title' => 'Ficus benjamina',
                'price' => 90,
                'is_trending' => false,
                'trending_order' => 0,
                'stock' => 15,
                'description' => 'A widely grown indoor tree with glossy, slender leaves that droop elegantly. It can grow quite large and is prized for its air‑purifying qualities and graceful form.',
                'image' => 'Plant3.png',
            ],
            [
                'title' => 'Hibiscus rosa‑sinensis',
                'price' => 210,
                'stock' => 6,
                'is_trending' => true,
                'trending_order' => 44,
                'description' => 'A vibrant shrub with large, showy flowers in red, pink, orange, yellow, or white. It’s a tropical favorite, often used in landscaping and potted displays.',
                'image' => 'Plant4.png',
            ],
            [
                'title' => 'Impatiens walleriana',
                'price' => 169,
                'trending_order' => 0,
                'is_trending' => false,
                'stock' => 5,
                'description' => 'Also known as “busy Lizzie,” this plant boasts abundant, colorful flowers and lush foliage. Its seed pods burst open when touched, hence the name. It’s a top choice for shady gardens and containers.',
                'image' => 'Plant5.png',
            ],
            [
                'title' => 'Hoya carnosa',
                'price' => 780,
                'is_trending' => true,
                'trending_order' => 32,
                'stock' => 45,
                'description' => 'A trailing or climbing succulent with thick, waxy leaves and star‑shaped, fragrant flower clusters. It’s a low‑maintenance houseplant that blooms repeatedly under the right conditions.',
                'image' => 'Plant6.png',
            ],
            [
                'title' => 'Crassula ovata',
                'price' => 520,
                'trending_order' => 0,
                'is_trending' => false,
                'stock' => 3,
                'description' => 'A sturdy, easy‑care succulent with fleshy, oval leaves that resemble jade stones. It can develop a woody trunk and occasional white or pink star‑shaped flowers with age.',
                'image' => 'Plant7.png',
            ],
        ];

        foreach ($plants as $plant) {
            $product = Product::create([
                'title' => $plant['title'],
                'price' => $plant['price'],
                'is_trending' => $plant['is_trending'],
                'trending_order' => $plant['trending_order'],
                'description' => $plant['description'],
                'stock' => $plant['stock'],
            ]);

            $imagePath = database_path('seeders/images/plants/' . $plant['image']);

            if (File::exists($imagePath)) {
                $product->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('gallery');
            }
        }
    }
}
