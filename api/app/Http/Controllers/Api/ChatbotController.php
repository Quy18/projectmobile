<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
use App\Models\ChatbotResponse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    /**
     * Xử lý tin nhắn từ người dùng gửi đến chatbot
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $userMessage = $request->input('message');
        $userId = auth('sanctum')->id() ?? 0; // Sử dụng 0 làm giá trị mặc định cho khách
        $sessionId = $request->input('session_id', Str::uuid()->toString());

        // Ghi log tin nhắn người dùng để debug
        Log::info('Nhận tin nhắn mới từ user', [
            'message' => $userMessage,
            'session_id' => $sessionId,
            'user_id' => $userId
        ]);

        // Lưu cuộc trò chuyện và tin nhắn
        $conversation = ChatbotConversation::firstOrCreate([
            'session_id' => $sessionId,
            'user_id' => $userId,
        ]);

        // Lưu tin nhắn của người dùng
        ChatbotMessage::create([
            'conversation_id' => $conversation->id,
            'message' => $userMessage,
            'is_bot' => false,
        ]);

        // Lấy lịch sử cuộc hội thoại hiện tại (tối đa 10 tin nhắn gần nhất)
        $recentMessages = ChatbotMessage::where('conversation_id', $conversation->id)
                            ->orderBy('created_at', 'desc')
                            ->take(10)
                            ->get()
                            ->reverse();
        
        // Tạo nội dung văn bản từ lịch sử tin nhắn để chuyển sang Gemini
        $conversationHistory = [];
        foreach ($recentMessages as $message) {
            $conversationHistory[] = [
                'role' => $message->is_bot ? 'model' : 'user',
                'parts' => [
                    ['text' => $message->message]
                ]
            ];
        }
        
        // Thêm tin nhắn hiện tại nếu chưa có trong lịch sử
        if (empty($conversationHistory) || end($conversationHistory)['parts'][0]['text'] !== $userMessage) {
            $conversationHistory[] = [
                'role' => 'user',
                'parts' => [
                    ['text' => $userMessage]
                ]
            ];
        }
        
        // Lấy dữ liệu sản phẩm để gợi ý cho người dùng
        $productSuggestions = $this->getProductSuggestions();
        
        // Ghi log thông tin sản phẩm đã lấy được
        Log::debug('Dữ liệu sản phẩm gợi ý', [
            'featured_count' => count($productSuggestions['featured']),
            'new_count' => count($productSuggestions['new']),
            'sale_count' => count($productSuggestions['sale']),
            'popular_count' => count($productSuggestions['popular']),
            'categories_count' => count($productSuggestions['categories']),
            'by_category_count' => count($productSuggestions['by_category'])
        ]);
        
        // Tạo nội dung cho system instruction - Định dạng dữ liệu sản phẩm tốt hơn
        $productData = $this->formatProductDataForPrompt($productSuggestions);
        
        // Chuẩn bị tin nhắn cho Gemini API với hướng dẫn chi tiết hơn
        $systemInstruction = "Bạn là trợ lý AI của cửa hàng Fashion NeoShop. Nhiệm vụ của bạn là hỗ trợ khách hàng, trả lời câu hỏi và LUÔN GIỚI THIỆU SẢN PHẨM PHÙ HỢP khi được hỏi.
        
LUÔN TRẢ LỜI BẰNG TIẾNG VIỆT và LUÔN có thái độ thân thiện, nhiệt tình giúp đỡ.

HƯỚNG DẪN KHI GIỚI THIỆU SẢN PHẨM:
- Khi khách hỏi về sản phẩm (ví dụ: vali, áo, quần, v.v.), hãy LUÔN giới thiệu ít nhất 1-2 sản phẩm phù hợp từ dữ liệu được cung cấp
- Luôn cung cấp tên, giá và mô tả ngắn gọn về sản phẩm
- Nếu sản phẩm có giá khuyến mãi, hãy nhấn mạnh điều đó (ví dụ: \"Giảm 20%\")
- Kèm theo đường dẫn URL của sản phẩm để khách hàng có thể xem chi tiết
- Nếu không tìm thấy sản phẩm phù hợp, hãy giới thiệu các danh mục sản phẩm liên quan

THÔNG TIN SẢN PHẨM CỬA HÀNG:

{$productData}

Trong mọi trường hợp, LUÔN cố gắng giúp khách hàng và giới thiệu các sản phẩm của cửa hàng một cách nhiệt tình.";

        // Thêm hướng dẫn hệ thống vào đầu cuộc hội thoại
        array_unshift($conversationHistory, [
            'role' => 'model',
            'parts' => [
                ['text' => $systemInstruction]
            ]
        ]);
        
        try {
            // Gọi API của Gemini
            $response = $this->callGeminiApi($conversationHistory);
            
            // Nếu không nhận được phản hồi từ Gemini, sử dụng câu trả lời dự phòng
            if (!$response) {
                Log::warning('Không nhận được phản hồi từ Gemini API, chuyển sang phương án dự phòng');
                
                // Tìm câu trả lời từ kho câu trả lời tự động
                $botResponse = ChatbotResponse::findBestMatch($userMessage);
                
                // Nếu không tìm thấy câu trả lời phù hợp, dùng câu trả lời mặc định
                if (!$botResponse) {
                    $botResponse = $this->getDefaultResponse();
                }
            } else {
                $botResponse = $response;
            }
        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            $botResponse = $this->getDefaultResponse();
        }

        // Ghi log phản hồi để debug
        Log::info('Phản hồi từ chatbot', [
            'response' => Str::limit($botResponse, 100),
            'length' => strlen($botResponse)
        ]);

        // Lưu tin nhắn của bot
        ChatbotMessage::create([
            'conversation_id' => $conversation->id,
            'message' => $botResponse,
            'is_bot' => true,
        ]);

        return response()->json([
            'session_id' => $sessionId,
            'response' => $botResponse,
        ]);
    }

    /**
     * Gọi API Gemini để lấy phản hồi
     *
     * @param array $messages
     * @return string|null
     */
    private function callGeminiApi(array $messages)
    {
        try {
            // Lấy cấu hình từ config/services.php
            $apiUrl = config('services.gemini.api_url');
            $apiKey = config('services.gemini.api_key');
            $model = config('services.gemini.model');
            $maxOutputTokens = config('services.gemini.max_output_tokens');
            $temperature = config('services.gemini.temperature');
            
            // Chuẩn bị payload cho Gemini API
            $payload = [
                'contents' => $messages,
                'generationConfig' => [
                    'temperature' => $temperature,
                    'maxOutputTokens' => $maxOutputTokens,
                    'topP' => 0.8,
                    'topK' => 40
                ]
            ];
            
            // Thêm API key vào URL
            $fullUrl = $apiUrl . '?key=' . $apiKey;
            
            Log::info('Gọi Gemini API', [
                'url' => $apiUrl,
                'model' => $model
            ]);
            
            $response = Http::timeout(15)->contentType('application/json')->post($fullUrl, $payload);
            
            if ($response->successful()) {
                $result = $response->json();
                Log::info('Gemini API trả về thành công', [
                    'status' => $response->status()
                ]);
                return $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }
            
            Log::warning('Gemini API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cung cấp câu trả lời mặc định khi không tìm thấy kết quả phù hợp
     *
     * @return string
     */
    private function getDefaultResponse()
    {
        $responses = [
            'Xin lỗi, tôi không hiểu câu hỏi của bạn. Bạn có thể hỏi về sản phẩm, đơn hàng hoặc chính sách của chúng tôi.',
            'Tôi không có thông tin về vấn đề này. Bạn có thể liên hệ với nhân viên hỗ trợ qua số điện thoại hoặc email.',
            'Câu hỏi của bạn hơi phức tạp. Vui lòng thử diễn đạt lại hoặc liên hệ trực tiếp với chúng tôi để được hỗ trợ.',
            'Hiện tại tôi không thể xử lý yêu cầu này. Vui lòng thử lại sau hoặc liên hệ bộ phận chăm sóc khách hàng.'
        ];
        
        return $responses[array_rand($responses)];
    }

    /**
     * Lấy dữ liệu sản phẩm để gợi ý cho chatbot
     *
     * @return array
     */
    private function getProductSuggestions()
    {
        try {
            $result = [
                'featured' => [],
                'new' => [],
                'sale' => [],
                'popular' => [],
                'categories' => [],
                'by_category' => []
            ];
            
            // Lấy danh sách sản phẩm nổi bật
            $featuredProducts = Product::with(['images' => function($query) {
                $query->where('is_main', true);
            }, 'category', 'brand'])
                ->where('featured', true)
                ->where('active', true)
                ->take(5)
                ->get();
            
            // Lấy sản phẩm mới
            $newProducts = Product::with(['images' => function($query) {
                $query->where('is_main', true);
            }, 'category', 'brand'])
                ->where('is_new', true)
                ->where('active', true)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            // Lấy sản phẩm giảm giá
            $saleProducts = Product::with(['images' => function($query) {
                $query->where('is_main', true);
            }, 'category', 'brand'])
                ->whereNotNull('sale_price')
                ->whereRaw('sale_price < price')
                ->where('active', true)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            // Lấy sản phẩm phổ biến (có nhiều đơn hàng nhất)
            $popularProductIds = DB::table('order_details')
                ->select('product_id', DB::raw('COUNT(*) as order_count'))
                ->groupBy('product_id')
                ->orderByDesc('order_count')
                ->limit(5)
                ->pluck('product_id');
                
            $popularProducts = Product::with(['images' => function($query) {
                $query->where('is_main', true);
            }, 'category', 'brand'])
                ->whereIn('id', $popularProductIds)
                ->where('active', true)
                ->get();
            
            // Lấy danh mục sản phẩm
            $categories = \App\Models\Category::withCount('products')
                ->orderBy('products_count', 'desc')
                ->take(10)
                ->get();
                
            // Lấy sản phẩm theo danh mục
            $productsByCategory = [];
            foreach ($categories as $category) {
                $products = Product::with(['images' => function($query) {
                    $query->where('is_main', true);
                }, 'brand'])
                    ->where('category_id', $category->id)
                    ->where('active', true)
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
                    
                if ($products->count() > 0) {
                    $productsByCategory[$category->id] = [
                        'category_name' => $category->name,
                        'products' => $products->map(function($product) {
                            return $this->formatProductData($product);
                        })
                    ];
                }
            }
            
            // Định dạng dữ liệu sản phẩm
            foreach ($featuredProducts as $product) {
                $result['featured'][] = $this->formatProductData($product);
            }
            
            foreach ($newProducts as $product) {
                $result['new'][] = $this->formatProductData($product);
            }
            
            foreach ($saleProducts as $product) {
                $result['sale'][] = $this->formatProductData($product);
            }
            
            foreach ($popularProducts as $product) {
                $result['popular'][] = $this->formatProductData($product);
            }
            
            // Định dạng dữ liệu danh mục
            foreach ($categories as $category) {
                $result['categories'][] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'product_count' => $category->products_count
                ];
            }
            
            $result['by_category'] = $productsByCategory;
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Error getting product suggestions: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            // Trả về mảng có cấu trúc đầy đủ nhưng trống để tránh lỗi undefined index
            return [
                'featured' => [],
                'new' => [],
                'sale' => [],
                'popular' => [],
                'categories' => [],
                'by_category' => []
            ];
        }
    }
    
    /**
     * Định dạng dữ liệu sản phẩm cho chatbot
     *
     * @param Product $product
     * @return array
     */
    private function formatProductData($product)
    {
        $discount_percent = null;
        if ($product->sale_price && $product->price > 0) {
            $discount_percent = round((($product->price - $product->sale_price) / $product->price) * 100);
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'sale_price' => $product->sale_price,
            'discount_percent' => $discount_percent,
            'category' => $product->category ? $product->category->name : null,
            'brand' => $product->brand ? $product->brand->name : null,
            'description' => Str::limit($product->description, 150),
            'image' => $product->images->first() ? $product->images->first()->image : null,
            'url' => "/product/{$product->id}",
            'is_new' => $product->is_new,
            'featured' => $product->featured
        ];
    }

    /**
     * Định dạng dữ liệu sản phẩm thành chuỗi văn bản có cấu trúc cho prompt
     *
     * @param array $productData
     * @return string
     */
    private function formatProductDataForPrompt($productData)
    {
        $result = "";
        
        // Định dạng sản phẩm nổi bật
        if (!empty($productData['featured'])) {
            $result .= "SẢN PHẨM NỔI BẬT:\n";
            foreach ($productData['featured'] as $index => $product) {
                $price = number_format($product['price'], 0, ',', '.') . 'đ';
                $discountText = '';
                
                if (!empty($product['sale_price'])) {
                    $salePrice = number_format($product['sale_price'], 0, ',', '.') . 'đ';
                    $discountText = " (Giảm giá: {$salePrice}, giảm {$product['discount_percent']}%)";
                }
                
                $result .= ($index + 1) . ". {$product['name']} - {$price}{$discountText}\n";
                $result .= "   Mô tả: " . Str::limit($product['description'], 100) . "\n";
                $result .= "   Danh mục: {$product['category']}, Thương hiệu: {$product['brand']}\n";
                $result .= "   URL: {$product['url']}\n\n";
            }
        }
        
        // Định dạng sản phẩm mới
        if (!empty($productData['new'])) {
            $result .= "SẢN PHẨM MỚI:\n";
            foreach ($productData['new'] as $index => $product) {
                $price = number_format($product['price'], 0, ',', '.') . 'đ';
                $discountText = '';
                
                if (!empty($product['sale_price'])) {
                    $salePrice = number_format($product['sale_price'], 0, ',', '.') . 'đ';
                    $discountText = " (Giảm giá: {$salePrice}, giảm {$product['discount_percent']}%)";
                }
                
                $result .= ($index + 1) . ". {$product['name']} - {$price}{$discountText}\n";
                $result .= "   Mô tả: " . Str::limit($product['description'], 100) . "\n";
                $result .= "   Danh mục: {$product['category']}, Thương hiệu: {$product['brand']}\n";
                $result .= "   URL: {$product['url']}\n\n";
            }
        }
        
        // Định dạng sản phẩm giảm giá
        if (!empty($productData['sale'])) {
            $result .= "SẢN PHẨM GIẢM GIÁ:\n";
            foreach ($productData['sale'] as $index => $product) {
                $price = number_format($product['price'], 0, ',', '.') . 'đ';
                $salePrice = number_format($product['sale_price'], 0, ',', '.') . 'đ';
                
                $result .= ($index + 1) . ". {$product['name']} - Giá gốc: {$price}, Giá giảm: {$salePrice} (Giảm {$product['discount_percent']}%)\n";
                $result .= "   Mô tả: " . Str::limit($product['description'], 100) . "\n";
                $result .= "   Danh mục: {$product['category']}, Thương hiệu: {$product['brand']}\n";
                $result .= "   URL: {$product['url']}\n\n";
            }
        }
        
        // Định dạng sản phẩm phổ biến
        if (!empty($productData['popular'])) {
            $result .= "SẢN PHẨM PHỔ BIẾN:\n";
            foreach ($productData['popular'] as $index => $product) {
                $price = number_format($product['price'], 0, ',', '.') . 'đ';
                $discountText = '';
                
                if (!empty($product['sale_price'])) {
                    $salePrice = number_format($product['sale_price'], 0, ',', '.') . 'đ';
                    $discountText = " (Giảm giá: {$salePrice}, giảm {$product['discount_percent']}%)";
                }
                
                $result .= ($index + 1) . ". {$product['name']} - {$price}{$discountText}\n";
                $result .= "   Mô tả: " . Str::limit($product['description'], 100) . "\n";
                $result .= "   Danh mục: {$product['category']}, Thương hiệu: {$product['brand']}\n";
                $result .= "   URL: {$product['url']}\n\n";
            }
        }
        
        // Định dạng danh mục sản phẩm
        if (!empty($productData['categories'])) {
            $result .= "DANH MỤC SẢN PHẨM:\n";
            foreach ($productData['categories'] as $index => $category) {
                $result .= ($index + 1) . ". {$category['name']} ({$category['product_count']} sản phẩm)\n";
            }
            $result .= "\n";
        }
        
        // Định dạng sản phẩm theo danh mục
        if (!empty($productData['by_category'])) {
            $result .= "SẢN PHẨM THEO DANH MỤC:\n";
            foreach ($productData['by_category'] as $categoryId => $categoryData) {
                $result .= "Danh mục: {$categoryData['category_name']}\n";
                
                foreach ($categoryData['products'] as $index => $product) {
                    $price = number_format($product['price'], 0, ',', '.') . 'đ';
                    $discountText = '';
                    
                    if (!empty($product['sale_price'])) {
                        $salePrice = number_format($product['sale_price'], 0, ',', '.') . 'đ';
                        $discountText = " (Giảm giá: {$salePrice}, giảm {$product['discount_percent']}%)";
                    }
                    
                    $result .= "  " . ($index + 1) . ". {$product['name']} - {$price}{$discountText}\n";
                    $result .= "     Mô tả: " . Str::limit($product['description'], 80) . "\n";
                    $result .= "     URL: {$product['url']}\n";
                }
                $result .= "\n";
            }
        }
        
        return $result;
    }
} 