<?php

namespace Database\Seeders;

use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
use App\Models\ChatbotResponse;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ChatbotMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $conversations = ChatbotConversation::all();
        $chatbotResponses = ChatbotResponse::all();
        
        // Các câu hỏi mẫu từ người dùng
        $userQuestions = [
            'Làm thế nào để đặt hàng?',
            'Tôi muốn đổi trả sản phẩm',
            'Chính sách bảo hành thế nào?',
            'Có mã giảm giá nào không?',
            'Thời gian giao hàng mất bao lâu?',
            'Làm sao để theo dõi đơn hàng?',
            'Cửa hàng có chi nhánh ở đâu?',
            'Cách thanh toán như thế nào?',
            'Tôi muốn hủy đơn hàng',
            'Sản phẩm này có còn hàng không?',
            'Làm sao để liên hệ với nhân viên hỗ trợ?',
            'Cửa hàng có bán sản phẩm X không?'
        ];

        foreach ($conversations as $conversation) {
            // Mỗi cuộc hội thoại sẽ có 3-10 tin nhắn
            $numMessages = rand(3, 10);
            $createdAt = $conversation->created_at;
            
            for ($i = 0; $i < $numMessages; $i++) {
                // Nếu là tin nhắn chẵn thì là tin nhắn của người dùng
                $isUser = ($i % 2 == 0);
                
                if ($isUser) {
                    // Tin nhắn từ người dùng
                    $questionIndex = array_rand($userQuestions);
                    $content = $userQuestions[$questionIndex];
                } else {
                    // Tin nhắn từ chatbot
                    $response = $chatbotResponses->random();
                    $content = $response->response;
                }
                
                // Tăng thời gian lên để tin nhắn có thứ tự thời gian hợp lý
                $createdAt = $faker->dateTimeBetween($createdAt, "+30 minutes");
                
                ChatbotMessage::create([
                    'conversation_id' => $conversation->id,
                    'content' => $content,
                    'is_user' => $isUser,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }
} 