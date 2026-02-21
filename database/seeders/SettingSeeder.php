<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Planto'],
            ['key' => 'contact_phone', 'value' => '7800999999'],
            ['key' => 'contact_email', 'value' => 'info@flowers.com'],
            ['key' => 'contact_address', 'value' => '103 Lefferts Place, Brooklyn, NY 11238'],
            ['key' => 'footer_rights', 'value' => '© 2024 All rights reserved'],
            ['key' => 'footer_main_text', 'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
            ['key' => 'link_fb', 'value' => 'https://www.facebook.com'],
            ['key' => 'link_x', 'value' => 'https://x.com'],
            ['key' => 'link_li', 'value' => 'https://www.linkedin.com'],
            ['key' => 'is_open', 'value' => true],
            ['key' => 'products_per_page', 'value' => 5],
            ['key' => 'slider_main_limit', 'value' => 4],
            ['key' => 'slider_features_limit', 'value' => 4],
            ['key' => 'section_trendy_title',  'value' => 'Our Trendy plants'],
            ['key' => 'section_selling_title', 'value' => 'Our Top Selling'],
            ['key' => 'section_reviews_title', 'value' => 'Customer Review'],
            ['key' => 'section_slider_title',      'value' => 'Our Best o2'],
            ['key' => 'top_plants_limit', 'value' => 4],
            ['key' => 'comments_limit', 'value' => 3],
            ['key' => 'features_limit', 'value' => 4],
            ['key' => 'reviews_limit', 'value' => 5],
            ['key' => 'trendy_limit', 'value' => 2],

        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
