<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
use App\Models\ChatbotResponse;
use App\Models\User;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    /**
     * Hiển thị danh sách cuộc hội thoại
     */
    public function index()
    {
        // Lấy tổng số cuộc hội thoại
        $totalConversations = ChatbotConversation::count();
        
        // Tổng số tin nhắn
        $totalMessages = ChatbotMessage::count();
        
        // Tổng số tin nhắn của người dùng
        $userMessages = ChatbotMessage::where('is_bot', false)->count();
        
        // Tổng số tin nhắn của bot
        $botMessages = ChatbotMessage::where('is_bot', true)->count();
        
        // Cuộc hội thoại gần đây
        $recentConversations = ChatbotConversation::with(['user', 'messages' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(1);
            }])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        // Lấy danh sách cuộc hội thoại
        $conversations = ChatbotConversation::with('user')
            ->withCount('messages')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
            
        return view('admin.chatbot.index', compact(
            'conversations',
            'totalConversations',
            'totalMessages',
            'userMessages',
            'botMessages',
            'recentConversations'
        ));
    }
    
    /**
     * Hiển thị chi tiết cuộc hội thoại
     */
    public function show($id)
    {
        $conversation = ChatbotConversation::with(['user', 'messages' => function($query) {
                $query->orderBy('created_at', 'asc');
            }])
            ->findOrFail($id);
            
        return view('admin.chatbot.show', compact('conversation'));
    }
    
    /**
     * Xóa cuộc hội thoại
     */
    public function destroy($id)
    {
        $conversation = ChatbotConversation::findOrFail($id);
        
        // Xóa tất cả tin nhắn thuộc cuộc hội thoại
        ChatbotMessage::where('conversation_id', $id)->delete();
        
        // Xóa cuộc hội thoại
        $conversation->delete();
        
        return redirect()
            ->route('admin.chatbot.index')
            ->with('success', 'Đã xóa cuộc hội thoại thành công');
    }
    
    /**
     * Hiển thị trang quản lý mẫu câu trả lời
     */
    public function responses()
    {
        $responses = ChatbotResponse::orderBy('priority', 'desc')
            ->paginate(15);
            
        return view('admin.chatbot.responses.index', compact('responses'));
    }
    
    /**
     * Lưu mẫu câu trả lời mới
     */
    public function storeResponse(Request $request)
    {
        $validated = $request->validate([
            'keyword' => 'required|string|max:255',
            'response' => 'required|string',
            'priority' => 'required|integer|min:1|max:10',
        ]);
        
        ChatbotResponse::create($validated);
        
        return redirect()
            ->route('admin.chatbot.responses')
            ->with('success', 'Đã thêm mẫu câu trả lời mới thành công');
    }
    
    /**
     * Cập nhật mẫu câu trả lời
     */
    public function updateResponse(Request $request, $id)
    {
        $validated = $request->validate([
            'keyword' => 'required|string|max:255',
            'response' => 'required|string',
            'priority' => 'required|integer|min:1|max:10',
        ]);
        
        $response = ChatbotResponse::findOrFail($id);
        $response->update($validated);
        
        return redirect()
            ->route('admin.chatbot.responses')
            ->with('success', 'Đã cập nhật mẫu câu trả lời thành công');
    }
    
    /**
     * Xóa mẫu câu trả lời
     */
    public function destroyResponse($id)
    {
        $response = ChatbotResponse::findOrFail($id);
        $response->delete();
        
        return redirect()
            ->route('admin.chatbot.responses')
            ->with('success', 'Đã xóa mẫu câu trả lời thành công');
    }
    
    /**
     * Thống kê chatbot
     */
    public function statistics()
    {
        // Tổng số cuộc hội thoại
        $totalConversations = ChatbotConversation::count();
        
        // Tổng số tin nhắn
        $totalMessages = ChatbotMessage::count();
        
        // Số người dùng đã dùng chatbot
        $usersUsingChatbot = ChatbotConversation::where('user_id', '!=', 0)->distinct('user_id')->count('user_id');
        
        // Số khách vãng lai đã dùng chatbot
        $guestsUsingChatbot = ChatbotConversation::where('user_id', 0)->count();
        
        // Số cuộc hội thoại trong 7 ngày qua
        $conversationsLast7Days = ChatbotConversation::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        
        // Số tin nhắn theo ngày trong 7 ngày qua
        $messagesPerDay = ChatbotMessage::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
            
        // Thống kê tin nhắn theo loại (user vs bot)
        $messagesByType = [
            'user' => ChatbotMessage::where('is_bot', false)->count(),
            'bot' => ChatbotMessage::where('is_bot', true)->count(),
        ];
        
        // Lấy 10 từ khóa được sử dụng nhiều nhất
        $topKeywords = ChatbotResponse::withCount(['conversationMessages' => function($query) {
                $query->where('is_bot', false);
            }])
            ->orderBy('conversation_messages_count', 'desc')
            ->limit(10)
            ->get();
            
        return view('admin.chatbot.statistics', compact(
            'totalConversations', 
            'totalMessages', 
            'usersUsingChatbot',
            'guestsUsingChatbot',
            'conversationsLast7Days',
            'messagesPerDay',
            'messagesByType',
            'topKeywords'
        ));
    }
} 