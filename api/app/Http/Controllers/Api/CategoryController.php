<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Lấy danh sách tất cả danh mục
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategories()
    {
        // Lấy danh mục cha
        $parentCategories = Category::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->orderBy('name', 'asc');
            }])
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách danh mục thành công',
            'data' => $parentCategories
        ]);
    }

    /**
     * Lấy chi tiết danh mục theo ID
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCategory($id)
    {
        $category = Category::with(['children' => function ($query) {
                $query->orderBy('name', 'asc');
            }])->findOrFail($id);
        
        // Đếm số lượng sản phẩm trong danh mục và danh mục con
        $categoryIds = [$id];
        
        if ($category->children->count() > 0) {
            $childrenIds = $category->children->pluck('id')->toArray();
            $categoryIds = array_merge($categoryIds, $childrenIds);
        }
        
        $productCount = Product::whereIn('category_id', $categoryIds)
            ->where('active', true)
            ->count();
        
        $category->product_count = $productCount;
        
        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết danh mục thành công',
            'data' => $category
        ]);
    }

    /**
     * Lấy sản phẩm theo danh mục
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCategoryProducts($id)
    {
        $category = Category::findOrFail($id);
        
        // Lấy tất cả id của danh mục con (nếu có)
        $categoryIds = [$id];
        
        if ($category->children->count() > 0) {
            $childrenIds = $category->children->pluck('id')->toArray();
            $categoryIds = array_merge($categoryIds, $childrenIds);
        }
        
        // Lấy sản phẩm của danh mục và danh mục con
        $query = Product::whereIn('category_id', $categoryIds)
            ->where('active', true)
            ->with(['images', 'brand']);
        
        // Xử lý tìm kiếm nếu có
        if (request()->has('search') && !empty(request('search'))) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('sku', 'like', "%$search%");
            });
        }
        
        // Xử lý sắp xếp
        if (request()->has('sort')) {
            switch (request('sort')) {
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
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // Phân trang kết quả
        $products = $query->paginate(12);
        
        return response()->json([
            'success' => true, 
            'message' => 'Lấy sản phẩm theo danh mục thành công',
            'data' => $products
        ]);
    }
} 