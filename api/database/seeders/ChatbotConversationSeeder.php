<?php

namespace Database\Seeders;

use App\Models\ChatbotConversation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ChatbotConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::where('role', '!=', 'admin')->take(15)->get();

        // Tạo cuộc hội thoại cho mỗi người dùng
        foreach ($users as $user) {
            // Mỗi người dùng sẽ có 1-3 cuộc hội thoại
            $numConversations = rand(1, 3);
            
            for ($i = 0; $i < $numConversations; $i++) {
                ChatbotConversation::create([
                    'user_id' => $user->id,
                    'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 