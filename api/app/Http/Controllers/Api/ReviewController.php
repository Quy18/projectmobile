<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Thêm đánh giá mới cho sản phẩm
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        // Kiểm tra xem người dùng đã đánh giá sản phẩm này chưa
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn đã đánh giá sản phẩm này rồi',
            ], 400);
        }

        // Tạo đánh giá mới
        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending', // Mặc định là chờ duyệt
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Thêm đánh giá thành công, đánh giá của bạn đang chờ được duyệt',
            'data' => $review
        ]);
    }

    /**
     * Lấy danh sách đánh giá của một sản phẩm
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProductReviews($id)
    {
        $product = Product::findOrFail($id);
        
        $reviews = Review::where('product_id', $id)
            ->where('status', 'approved') // Chỉ lấy đánh giá đã duyệt
            ->with('user:id,name') // Chỉ lấy tên người dùng, không lấy thông tin nhạy cảm
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Tính rating trung bình
        $avgRating = Review::where('product_id', $id)
            ->where('status', 'approved')
            ->avg('rating');
        
        // Đếm số lượng đánh giá cho mỗi số sao
        $ratingCounts = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingCounts[$i] = Review::where('product_id', $id)
                ->where('status', 'approved')
                ->where('rating', $i)
                ->count();
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách đánh giá thành công',
            'data' => [
                'product_id' => $id,
                'product_name' => $product->name,
                'average_rating' => round($avgRating, 1),
                'total_reviews' => $reviews->total(),
                'rating_counts' => $ratingCounts,
                'reviews' => $reviews
            ]
        ]);
    }

    /**
     * Lấy tất cả đánh giá của người dùng hiện tại
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserReviews()
    {
        try {
            $reviews = Review::where('user_id', Auth::id())
                ->with(['product' => function($query) {
                    $query->select('id', 'name', 'slug')
                        ->with(['images' => function($imageQuery) {
                            $imageQuery->where('is_main', true)->first();
                        }]);
                }])
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Định dạng dữ liệu trả về để dễ sử dụng ở frontend
            $formattedReviews = $reviews->map(function($review) {
                $mainImage = null;
                if ($review->product && $review->product->images && count($review->product->images) > 0) {
                    $mainImage = $review->product->images[0]->image;
                }
                
                return [
                    'id' => $review->id,
                    'user_id' => $review->user_id,
                    'product_id' => $review->product_id,
                    'product_name' => $review->product ? $review->product->name : 'Sản phẩm không tồn tại',
                    'product_slug' => $review->product ? $review->product->slug : '',
                    'product_image' => $mainImage,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'status' => $review->status,
                    'created_at' => $review->created_at,
                    'updated_at' => $review->updated_at,
                ];
            });
            
            return response()->json([
                'status' => true,
                'message' => 'Lấy danh sách đánh giá của bạn thành công',
                'data' => $formattedReviews
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Đã xảy ra lỗi khi lấy đánh giá: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 