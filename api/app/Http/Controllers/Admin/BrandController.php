<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Hiển thị danh sách thương hiệu
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Brand::query();
        
        // Lọc theo từ khóa tìm kiếm
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
        }
        
        // Lọc theo trạng thái
        if ($request->has('status')) {
            $query->where('active', $request->status);
        }
        
        // Sắp xếp
        $query->orderBy('name', 'asc');
        
        // Đếm số sản phẩm của mỗi thương hiệu
        $query->withCount('products');
        
        // Phân trang
        $perPage = $request->input('per_page', 15);
        $brands = $query->paginate($perPage);
        
        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Hiển thị form tạo thương hiệu mới
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Lưu thương hiệu mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website' => 'nullable|url',
            'origin' => 'nullable|string|max:100',
            'active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['active'] = $request->has('active') ? 1 : 0;
        
        // Xử lý upload logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = time() . '_' . $logo->getClientOriginalName();
            $path = $logo->storeAs('brands', $filename, 'public');
            $data['logo'] = $path;
        }

        Brand::create($data);
        return redirect()->route('admin.brands.index')->with('success', 'Đã tạo thương hiệu thành công');
    }

    /**
     * Hiển thị thông tin thương hiệu
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\View\View
     */
    public function show(Brand $brand)
    {
        $brand->load('products');
        return view('admin.brands.show', compact('brand'));
    }

    /**
     * Hiển thị form chỉnh sửa thương hiệu
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\View\View
     */
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Cập nhật thông tin thương hiệu
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,'.$brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website' => 'nullable|url',
            'origin' => 'nullable|string|max:100',
            'active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        
        if ($request->name != $brand->name) {
            $data['slug'] = Str::slug($request->name);
        }
        
        $data['active'] = $request->has('active') ? 1 : 0;
        
        // Xử lý upload logo
        if ($request->hasFile('logo')) {
            // Xóa logo cũ nếu có
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            
            $logo = $request->file('logo');
            $filename = time() . '_' . $logo->getClientOriginalName();
            $path = $logo->storeAs('brands', $filename, 'public');
            $data['logo'] = $path;
        }

        $brand->update($data);
        return redirect()->route('admin.brands.index')->with('success', 'Đã cập nhật thương hiệu thành công');
    }
    
    /**
     * Thay đổi trạng thái hiển thị của thương hiệu
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle(Brand $brand)
    {
        $brand->active = !$brand->active;
        $brand->save();
        
        $status = $brand->active ? 'hiển thị' : 'ẩn';
        return back()->with('success', "Đã {$status} thương hiệu thành công");
    }

    /**
     * Xóa thương hiệu
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Brand $brand)
    {
        // Kiểm tra xem thương hiệu có sản phẩm không
        if ($brand->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa thương hiệu này vì có sản phẩm liên quan');
        }
        
        // Xóa logo nếu có
        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }
        
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Đã xóa thương hiệu thành công');
    }
} 