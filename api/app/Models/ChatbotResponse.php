<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotResponse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'keyword',
        'response',
        'priority',
    ];

    /**
     * Liên kết với tin nhắn trong hội thoại mà có chứa từ khóa
     */
    public function conversationMessages()
    {
        return $this->hasManyThrough(
            ChatbotMessage::class,
            ChatbotConversation::class,
            'id', // Khóa ngoại trên bảng trung gian
            'conversation_id', // Khóa ngoại trên bảng đích
            'id', // Khóa chính trên bảng hiện tại
            'id' // Khóa chính trên bảng trung gian
        )->whereRaw('LOWER(chatbot_messages.message) LIKE ?', ['%' . mb_strtolower($this->keyword, 'UTF-8') . '%']);
    }

    /**
     * Tìm câu trả lời phù hợp nhất dựa trên từ khóa
     * 
     * @param string $question Câu hỏi của người dùng
     * @return string|null Câu trả lời hoặc null nếu không tìm thấy
     */
    public static function findBestMatch(string $question): ?string
    {
        // Chuẩn hóa câu hỏi (chuyển thành chữ thường, loại bỏ dấu)
        $normalizedQuestion = mb_strtolower($question, 'UTF-8');
        
        // Lấy tất cả câu trả lời, sắp xếp theo độ ưu tiên giảm dần
        $responses = self::orderBy('priority', 'desc')->get();
        
        foreach ($responses as $response) {
            // Kiểm tra xem từ khóa có trong câu hỏi không
            if (str_contains($normalizedQuestion, mb_strtolower($response->keyword, 'UTF-8'))) {
                return $response->response;
            }
        }
        
        // Không tìm thấy câu trả lời phù hợp
        return null;
    }
} 