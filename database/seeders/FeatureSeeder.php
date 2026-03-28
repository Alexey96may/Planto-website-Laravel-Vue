<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'title' => 'We Have Small And Best O2 Plants Collection’s',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</br><strong>Lorem ipsum</strong> dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua',
                'image' => 'plants/Plant7.png',
                'link' => '/shop',
                'is_active' => true,
            ],
            [
                'title' => 'We Have Small And Best Plants Collection’s',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'image' => 'plants/Plant5.png',
                'link' => '/shop',
                'is_active' => true,
            ],
            [
                'title' => 'We Have Small And Best',
                'description' => '24/7 plant care advice',
                'image' => 'plants/Plant6.png',
                'link' => '/shop',
                'is_active' => true,
            ],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(['title' => $feature['title']], $feature);
        }
    }
}
