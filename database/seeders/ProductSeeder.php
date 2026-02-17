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
        $plants = [
            [
                'title' => 'Saintpaulia ionantha',
                'price' => 2500,
                'description' => 'A popular houseplant known for its delicate, colorful blooms and fuzzy, dark green leaves. It blooms nearly year‑round under proper conditions and comes in shades of purple, blue, pink, and white.',
                'image' => 'plants/Plant1.png',
            ],
            [
                'title' => 'Mimosa pudica',
                'price' => 3200,
                'description' => 'A fascinating tropical plant famous for its sensitive leaves that fold inward and droop when touched or disturbed—a defense mechanism. It produces small, pink, puffball‑like flowers.',
                'image' => 'plants/Plant2.png',
            ],
            [
                'title' => 'Ficus benjamina',
                'price' => 900,
                'description' => 'A widely grown indoor tree with glossy, slender leaves that droop elegantly. It can grow quite large and is prized for its air‑purifying qualities and graceful form.',
                'image' => 'plants/Plant3.png',
            ],
            [
                'title' => 'Hibiscus rosa‑sinensis',
                'price' => 2100,
                'description' => 'A vibrant shrub with large, showy flowers in red, pink, orange, yellow, or white. It’s a tropical favorite, often used in landscaping and potted displays.',
                'image' => 'plants/Plant4.png',
            ],
            [
                'title' => 'Impatiens walleriana',
                'price' => 1690,
                'description' => 'Also known as “busy Lizzie,” this plant boasts abundant, colorful flowers and lush foliage. Its seed pods burst open when touched, hence the name. It’s a top choice for shady gardens and containers.',
                'image' => 'plants/Plant5.png',
            ],
            [
                'title' => 'Hoya carnosa',
                'price' => 7800,
                'description' => 'A trailing or climbing succulent with thick, waxy leaves and star‑shaped, fragrant flower clusters. It’s a low‑maintenance houseplant that blooms repeatedly under the right conditions.',
                'image' => 'plants/Plant6.png',
            ],
            [
                'title' => 'Crassula ovata',
                'price' => 5250,
                'description' => 'A sturdy, easy‑care succulent with fleshy, oval leaves that resemble jade stones. It can develop a woody trunk and occasional white or pink star‑shaped flowers with age.',
                'image' => 'plants/Plant7.png',
            ],
        ];

        foreach ($plants as $plant) {
            Product::create($plant);
        }
    }
}
