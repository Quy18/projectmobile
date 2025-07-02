<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Hiển thị danh sách đánh giá
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        // Áp dụng bộ lọc
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reviews = $query->latest()->paginate(10);
        
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Hiển thị chi tiết đánh giá
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        $review->load(['user', 'product']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Cập nhật trạng thái đánh giá
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Review $review)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Lưu trạng thái cũ để hiển thị thông báo phù hợp
            $oldStatus = $review->status;
            
            $review->status = $request->status;
            $review->save();
            
            // Chuẩn bị thông báo phù hợp dựa trên trạng thái mới
            $message = '';
            switch ($request->status) {
                case 'approved':
                    $message = 'Đã duyệt đánh giá thành công';
                    break;
                case 'rejected':
                    $message = 'Đã từ chối đánh giá thành công';
                    break;
                case 'pending':
                    $message = 'Đã đặt lại trạng thái đánh giá thành "Chờ duyệt"';
                    break;
                default:
                    $message = 'Đã cập nhật trạng thái đánh giá thành công';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'old_status' => $oldStatus,
                'new_status' => $review->status,
                'review_id' => $review->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa đánh giá
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Đã xóa đánh giá thành công');
    }

    /**
     * Hiển thị trang thống kê đánh giá
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        $totalReviews = Review::count();
        $averageRating = Review::avg('rating') ?: 0;
        
        // Đếm số lượng đánh giá theo từng mức sao
        $ratingCounts = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingCounts[$i] = Review::where('rating', $i)->count();
        }
        
        // Lấy đánh giá gần đây
        $recentReviews = Review::with(['user', 'product'])
            ->latest()
            ->take(5)
            ->get();
        
        // Đếm số lượng đánh giá theo trạng thái
        $pendingCount = Review::where('status', 'pending')->count();
        $approvedCount = Review::where('status', 'approved')->count();
        $rejectedCount = Review::where('status', 'rejected')->count();
        
        return view('admin.reviews.statistics', compact(
            'totalReviews', 
            'averageRating', 
            'ratingCounts', 
            'recentReviews',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Phản hồi đánh giá
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function reply(Request $request, Review $review)
    {
        $validator = Validator::make($request->all(), [
            'admin_response' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $review->admin_response = $request->admin_response;
        $review->admin_response_at = now();
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã phản hồi đánh giá thành công'
        ]);
    }
} 