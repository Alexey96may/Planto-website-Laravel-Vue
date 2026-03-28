<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Navigation;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $links = [
            ['title' => 'Home', 'location' => 'header', 'link' => '/', 'order' => 1],
            ['title' => 'Plants Type', 'location' => 'header', 'link' => '/shop', 'order' => 2],
            ['title' => 'Shop', 'location' => 'header', 'link' => '/shop', 'order' => 3],
            ['title' => 'Contact', 'location' => 'header', 'link' => '/contacts', 'order' => 4],
            ['title' => 'Reviews', 'location' => 'header', 'link' => '/reviews', 'order' => 5],
            ['title' => 'Home', 'location' => 'footer', 'link' => '/', 'order' => 1],
            ['title' => 'Type’s Of plant’s', 'location' => 'footer', 'link' => '/shop', 'order' => 2],
            ['title' => 'Contact', 'location' => 'footer', 'link' => '/contacts', 'order' => 3],
            ['title' => 'Privacy', 'location' => 'footer', 'link' => '/terms', 'order' => 4],
        ];

        foreach ($links as $link) {
            Navigation::updateOrCreate(['link' => $link['link'], 'title' => $link['title'], 'location' => $link['location']], $link);
        }
    }
}
