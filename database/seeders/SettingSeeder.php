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
            ['key' => 'footer_rights', 'value' => '© 2024 All rights reserved'],
            ['key' => 'footer_main_text', 'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
            ['key' => 'link_fb', 'value' => 'https://www.facebook.com'],
            ['key' => 'link_x', 'value' => 'https://x.com'],
            ['key' => 'link_li', 'value' => 'https://www.linkedin.com'],
            ['key' => 'is_open', 'value' => true],
            ['key' => 'products_per_page', 'value' => 5],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
