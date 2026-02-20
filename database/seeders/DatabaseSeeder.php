<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Boss',
            'email' => 'shulga_alexey@vk.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin', 
        ]);

        $this->call([
            ProductSeeder::class,
            CategorySeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
        ]);
    }
}
