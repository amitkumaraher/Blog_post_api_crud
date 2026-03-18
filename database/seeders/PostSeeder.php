<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
       public function run(): void
    {
        // Create 5 users, each with 3 posts
        User::factory(5)->create()->each(function (User $user) {
            Post::factory(3)->create([
                'user_id' => $user->id,
            ]);
        });

        $this->command->info('Seeded 5 users with 3 posts each (15 posts total).');
    }
}
