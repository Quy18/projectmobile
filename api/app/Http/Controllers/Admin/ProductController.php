<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Lọc theo từ khóa tìm kiếm
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('sku', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Lọc theo danh mục
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        // Lọc theo thương hiệu
        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }
        
        // Tính trung bình đánh giá và đếm số lượng đánh giá
        $query->withCount('reviews')
              ->withAvg('reviews', 'rating');
        
        // Sắp xếp
        $query->orderBy('id', 'desc');
        
        // Phân trang
        $perPage = $request->input('per_page', 20);
        $products = $query->with(['category', 'brand'])->paginate($perPage);
        
        // Danh sách danh mục và thương hiệu cho bộ lọc
        $categories = Category::orderBy('name', 'asc')->get();
        $brands = Brand::orderBy('name', 'asc')->get();
        
        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Hiển thị form tạo sản phẩm mới
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $brands = Brand::orderBy('name', 'asc')->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Lưu sản phẩm mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products',
            'description' => 'nullable|string',
            'specifications' => 'nullable|array',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'active' => 'nullable|boolean',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['active'] = $request->has('active') && $request->active == '1' ? 1 : 0;
        $data['featured'] = $request->has('featured') ? 1 : 0;
        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        
        // Tạo sản phẩm
        $product = Product::create($data);
        
        // Xử lý ảnh chính
        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image');
            $mainImageName = time() . '_main_' . $mainImage->getClientOriginalName();
            $mainImagePath = $mainImage->storeAs('products', $mainImageName, 'public');
            
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $mainImagePath,
                'is_main' => true
            ]);
        }
        
        // Xử lý ảnh bổ sung
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                    'is_main' => false
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Đã tạo sản phẩm thành công');
    }

    /**
     * Hiển thị thông tin sản phẩm
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'images', 'reviews.user', 'orderDetails.order']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $brands = Brand::orderBy('name', 'asc')->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Cập nhật thông tin sản phẩm
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'specifications' => 'nullable|array',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'active' => 'nullable|boolean',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deleted_images' => 'nullable|array',
            'deleted_images.*' => 'exists:product_images,id',
            'main_image_id' => 'nullable|exists:product_images,id',
        ]);

        $data = $request->all();
        
        if ($request->name != $product->name) {
            $data['slug'] = Str::slug($request->name);
        }
        
        $data['active'] = $request->has('active') && $request->active == '1' ? 1 : 0;
        $data['featured'] = $request->has('featured') ? 1 : 0;
        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        
        // Cập nhật sản phẩm
        $product->update($data);
        
        // Xử lý xóa hình ảnh
        if ($request->has('deleted_images')) {
            $deletedImages = $request->input('deleted_images', []);
            foreach ($deletedImages as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image) {
                    // Xóa file hình ảnh
                    Storage::disk('public')->delete($image->image);
                    
                    // Xóa record
                    $image->delete();
                }
            }
        }
        
        // Xử lý ảnh chính nếu có tải lên ảnh mới
        if ($request->hasFile('main_image')) {
            // Reset tất cả các ảnh về không phải ảnh chính
            ProductImage::where('product_id', $product->id)->update(['is_main' => false]);
            
            // Lưu ảnh chính mới
            $mainImage = $request->file('main_image');
            $mainImageName = time() . '_main_' . $mainImage->getClientOriginalName();
            $mainImagePath = $mainImage->storeAs('products', $mainImageName, 'public');
            
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $mainImagePath,
                'is_main' => true
            ]);
        } 
        // Xử lý khi chọn ảnh chính từ các ảnh đã có
        else if ($request->has('main_image_id') && $request->main_image_id) {
            // Reset tất cả các ảnh về không phải ảnh chính
            ProductImage::where('product_id', $product->id)->update(['is_main' => false]);
            
            // Set ảnh được chọn làm ảnh chính
            ProductImage::where('id', $request->main_image_id)->update(['is_main' => true]);
        }
        
        // Xử lý ảnh bổ sung
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                    'is_main' => false
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Đã cập nhật sản phẩm thành công');
    }
    
    /**
     * Thay đổi trạng thái hiển thị của sản phẩm
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle(Product $product)
    {
        $product->active = !$product->active;
        $product->save();
        
        $status = $product->active ? 'đang bán' : 'ngừng bán';
        return back()->with('success', "Đã cập nhật trạng thái sản phẩm thành {$status}");
    }

    /**
     * Xóa sản phẩm
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        // Kiểm tra xem sản phẩm có đơn hàng không
        if ($product->orderDetails()->count() > 0) {
            return back()->with('error', 'Không thể xóa sản phẩm này vì có đơn hàng liên quan');
        }
        
        // Xóa tất cả hình ảnh của sản phẩm
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
            $image->delete();
        }
        
        // Xóa các liên kết với giỏ hàng và wishlist
        $product->carts()->delete();
        $product->wishlist()->delete();
        
        // Xóa sản phẩm
        $product->delete();
        
        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm thành công');
    }
} 