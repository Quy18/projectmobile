<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Lấy danh sách danh mục
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Lấy danh sách thương hiệu
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBrands()
    {
        $brands = Brand::all();

        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }

    /**
     * Lấy danh sách sản phẩm với bộ lọc
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images' => function($query) {
            $query->where('is_main', true);
        }])
        ->where('active', 1);
        
        // Lọc theo danh mục
        if ($request->has('category_id')) {
            $categoryIds = Category::where('id', $request->category_id)
                ->orWhere('parent_id', $request->category_id)
                ->pluck('id')
                ->toArray();
            
            $query->whereIn('category_id', $categoryIds);
        }
        
        // Lọc theo thương hiệu
        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        
        // Lọc theo giá
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Sắp xếp
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        
        $allowedSortFields = ['price', 'created_at', 'name'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        }
        
        // Tìm kiếm
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        // Chỉ lấy sản phẩm nổi bật
        if ($request->has('featured') && $request->featured) {
            $query->where('featured', true);
        }
        
        $perPage = $request->input('per_page', 10);
        $products = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Lấy chi tiết sản phẩm
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProduct($id)
    {
        $product = Product::with(['category', 'brand', 'images', 'reviews.user'])
            ->where('active', 1)
            ->findOrFail($id);
        
        // Tính trung bình rating
        $avgRating = $product->reviews->avg('rating');
        $product->avg_rating = round($avgRating, 1);
        
        // Đếm số lượng đánh giá
        $product->reviews_count = $product->reviews->count();
        
        // Lấy các sản phẩm liên quan (cùng danh mục)
        $relatedProducts = Product::with(['category', 'brand', 'images' => function($query) {
                $query->where('is_main', true)->first();
            }])
            ->where('active', 1)
            ->where('id', '!=', $id)
            ->where('category_id', $product->category_id)
            ->limit(6)
            ->get();
        
        $product->related_products = $relatedProducts;
        
        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Lấy đánh giá của sản phẩm
     *
     * @param int $productId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductReviews($productId, Request $request)
    {
        $perPage = $request->input('per_page', 10);
        
        $reviews = Review::with('user:id,name')
            ->where('product_id', $productId)
            ->where('active', 1)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    /**
     * Thêm đánh giá mới
     *
     * @param int $productId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addReview($productId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xác thực dữ liệu',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Kiểm tra sản phẩm tồn tại
        $product = Product::findOrFail($productId);
        
        // Tạo đánh giá
        $review = Review::create([
            'product_id' => $productId,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã thêm đánh giá thành công',
            'data' => $review
        ], 201);
    }

    /**
     * Tìm kiếm sản phẩm
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xác thực dữ liệu',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $perPage = $request->input('per_page', 10);
        $search = $request->search;
        $sort = $request->input('sort', 'newest');
        
        $query = Product::with(['category', 'brand', 'images' => function($query) {
            $query->where('is_main', true);
        }])
        ->where('active', 1)
        ->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
        });
        
        // Xử lý sắp xếp
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $products = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Lấy danh sách sản phẩm nổi bật
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFeaturedProducts()
    {
        $products = Product::with(['category', 'brand', 'images' => function($query) {
                $query->where('is_main', true);
            }])
            ->where('active', 1)
            ->where('featured', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Lấy danh sách sản phẩm mới nhất
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNewProducts()
    {
        $products = Product::with(['category', 'brand', 'images' => function($query) {
                $query->where('is_main', true);
            }])
            ->where('active', 1)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Lấy danh sách sản phẩm giảm giá
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSaleProducts()
    {
        $products = Product::with(['category', 'brand', 'images' => function($query) {
                $query->where('is_main', true);
            }])
            ->where('active', 1)
            ->whereNotNull('sale_price')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
} 