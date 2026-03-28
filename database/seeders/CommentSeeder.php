<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->warn('No users found. Run UserSeeder first!');
            return;
        }

        $comments = [
            'Amazing service! The delivery was fast and the packaging was eco-friendly.',
            'Best plant shop online. Their support team helped me choose the right soil.',
            'I am impressed by the quality of the plants and the professionalism of the staff.',
            'Great experience! The website is very easy to navigate and I found exactly what I needed.',
            'Wonderful community and great prices. Will definitely buy here again!',
            'The delivery took a bit longer than expected, but the support was very helpful.',
        ];

        for ($i = 0; $i < 20; $i++) {
            Comment::create([
                'user_id' => $userIds[array_rand($userIds)], 
                'body' => $comments[array_rand($comments)],
                'rating' => rand(4, 5),
                'is_active' => true,
            ]);
        }
    }
}
