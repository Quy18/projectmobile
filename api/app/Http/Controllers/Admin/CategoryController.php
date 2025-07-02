<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách danh mục
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Category::query();
        
        // Lọc theo từ khóa tìm kiếm
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('description', 'LIKE', "%{$searchTerm}%");
        }
        
        // Lọc theo danh mục cha
        if ($request->has('parent_id')) {
            if ($request->parent_id === 'root') {
                $query->whereNull('parent_id');
            } else if ($request->parent_id) {
                $query->where('parent_id', $request->parent_id);
            }
        }
        
        // Sắp xếp
        $query->orderBy('name', 'asc');
        
        // Phân trang
        $perPage = $request->input('per_page', 15);
        $categories = $query->with('parent')->paginate($perPage);
        
        // Danh sách danh mục cha cho filter
        $parentCategories = Category::whereNull('parent_id')->orderBy('name', 'asc')->get();
        
        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    /**
     * Hiển thị form tạo danh mục mới
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Lưu danh mục mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        
        // Xử lý upload hình ảnh
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('categories', $filename, 'public');
            $data['image'] = $path;
        }

        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Đã tạo danh mục thành công');
    }

    /**
     * Hiển thị thông tin danh mục
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\View\View
     */
    public function show(Category $category)
    {
        $category->load('parent', 'children', 'products');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Hiển thị form chỉnh sửa danh mục
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\View\View
     */
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->orderBy('name', 'asc')->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Cập nhật thông tin danh mục
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        if ($request->name != $category->name) {
            $data['slug'] = Str::slug($request->name);
        }
        
        // Xử lý upload hình ảnh
        if ($request->hasFile('image')) {
            // Xóa hình ảnh cũ nếu có
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('categories', $filename, 'public');
            $data['image'] = $path;
        }

        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Đã cập nhật danh mục thành công');
    }

    /**
     * Xóa danh mục
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        // Kiểm tra xem danh mục có sản phẩm hay con không
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục này vì có sản phẩm liên quan');
        }
        
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục này vì có danh mục con');
        }
        
        // Xóa hình ảnh nếu có
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục thành công');
    }
} 