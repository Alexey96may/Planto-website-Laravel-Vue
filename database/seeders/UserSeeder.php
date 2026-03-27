<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'shulga_alexey@vk.com'],
            [
                'name' => 'Admin',
                'password' => 'qwerty123',
                'role' => 'admin',
            ]
        );

        \App\Models\User::factory(15)->create();
        
    }
}
