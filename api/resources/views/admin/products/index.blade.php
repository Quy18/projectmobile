@extends('admin.layouts.app')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-3">Sản phẩm</h1>
            <p class="text-muted">Quản lý tất cả sản phẩm trong hệ thống</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-3 mt-md-0">
                <i class="fas fa-plus me-1"></i> Thêm sản phẩm mới
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5 class="card-title mb-0">Danh sách sản phẩm</h5>
                </div>
                <div class="col-md-8">
                    <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex flex-column flex-md-row gap-2">
                        <div class="input-group flex-grow-1">
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên, mã sản phẩm...">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        
                        <select class="form-select" name="order" style="width: auto;" onchange="this.form.submit()">
                            <option value="latest" {{ request('order') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="oldest" {{ request('order') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                            <option value="price_asc" {{ request('order') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="price_desc" {{ request('order') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                            <option value="name_asc" {{ request('order') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="name_desc" {{ request('order') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                        </select>
                        
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="fas fa-filter me-1"></i> Lọc
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(isset($products) && $products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th style="width: 80px;">Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th style="width: 150px;">Danh mục</th>
                                <th style="width: 150px;">Giá (VNĐ)</th>
                                <th style="width: 100px;">Kho</th>
                                <th style="width: 100px;">Trạng thái</th>
                                <th style="width: 150px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="text-center fw-medium">{{ $product->id }}</td>
                                    <td>
                                        <div class="product-img-wrapper">
                                            @php
                                                $mainImage = $product->images()->where('is_main', true)->first();
                                            @endphp
                                            @if($mainImage)
                                                <img src="{{ asset('storage/' . $mainImage->image) }}" alt="{{ $product->name }}" class="product-img">
                                            @else
                                                <div class="product-img-placeholder">
                                                    <i class="fas fa-box text-secondary"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('admin.products.show', $product->id) }}" class="text-dark fw-medium text-decoration-none">
                                                {{ $product->name }}
                                            </a>
                                            <div class="product-details">
                                                <small class="text-muted">SKU: {{ $product->sku }}</small>
                                                
                                                @if($product->featured)
                                                    <span class="badge bg-warning text-dark ms-1" data-bs-toggle="tooltip" title="Sản phẩm nổi bật">
                                                        <i class="fas fa-star"></i>
                                                    </span>
                                                @endif
                                                
                                                @if($product->is_new)
                                                    <span class="badge bg-info text-dark ms-1" data-bs-toggle="tooltip" title="Sản phẩm mới">
                                                        <i class="fas fa-certificate"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->category)
                                            <a href="{{ route('admin.categories.show', $product->category_id) }}" class="badge bg-secondary text-decoration-none">
                                                {{ $product->category->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Chưa phân loại</span>
                                        @endif
                                        
                                        @if($product->brand)
                                            <div class="mt-1">
                                                <a href="{{ route('admin.brands.show', $product->brand_id) }}" class="badge bg-light text-dark text-decoration-none">
                                                    {{ $product->brand->name }}
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <div>
                                                <span class="fw-semibold text-danger">{{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted text-decoration-line-through">{{ number_format($product->price, 0, ',', '.') }}</span>
                                                @php
                                                $discountPercent = round((($product->price - $product->sale_price) / $product->price) * 100);
                                                @endphp
                                                <span class="badge bg-danger ms-1">-{{ $discountPercent }}%</span>
                                            </div>
                                        @else
                                            <span class="fw-semibold">{{ number_format($product->price, 0, ',', '.') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->quantity > 0)
                                            <span class="badge bg-success">Còn {{ $product->quantity }} sản phẩm</span>
                                        @else
                                            <span class="badge bg-danger">Hết hàng</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->active)
                                            <span class="badge bg-success">Đang bán</span>
                                        @else
                                            <span class="badge bg-danger">Dừng bán</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Modal Xóa -->
                                        <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $product->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $product->id }}">Xác nhận xóa</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Bạn có chắc chắn muốn xóa sản phẩm <strong>{{ $product->name }}</strong>?</p>
                                                        
                                                        @if($product->orders_count > 0)
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                <span>Sản phẩm này đã có <strong>{{ $product->orders_count }}</strong> đơn hàng. Xóa sản phẩm có thể ảnh hưởng đến dữ liệu đơn hàng.</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Xóa</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center justify-content-md-between align-items-center flex-wrap px-3 py-3 border-top">
                    <div class="mb-3 mb-md-0">
                        <span class="text-muted">Hiển thị {{ $products->firstItem() ? $products->firstItem() : 0 }} đến {{ $products->lastItem() ? $products->lastItem() : 0 }} của {{ $products->total() }} sản phẩm</span>
                    </div>
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('assets/img/empty-box.svg') }}" alt="Không có dữ liệu" style="max-width: 150px; opacity: 0.5;">
                    <h4 class="text-muted mt-4">Chưa có sản phẩm nào</h4>
                    <p class="text-muted mb-4">Bạn chưa thêm sản phẩm nào vào hệ thống hoặc không có sản phẩm nào phù hợp với bộ lọc.</p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Thêm sản phẩm mới
                    </a>
                    @if(request()->query())
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-times me-1"></i> Xóa bộ lọc
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Lọc -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.products.index') }}" method="GET">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Lọc sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Tên, mã, mô tả sản phẩm...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @if($category->children && $category->children->count() > 0)
                                    @foreach($category->children as $childCategory)
                                        <option value="{{ $childCategory->id }}" {{ request('category_id') == $childCategory->id ? 'selected' : '' }}>
                                            -- {{ $childCategory->name }}
                                        </option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="brand_id" class="form-label">Thương hiệu</label>
                        <select class="form-select" id="brand_id" name="brand_id">
                            <option value="">Tất cả thương hiệu</option>
                            @foreach($brands ?? [] as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="min_price" class="form-label">Giá tối thiểu</label>
                            <input type="number" class="form-control" id="min_price" name="min_price" value="{{ request('min_price') }}" placeholder="VNĐ">
                        </div>
                        <div class="col-md-6">
                            <label for="max_price" class="form-label">Giá tối đa</label>
                            <input type="number" class="form-control" id="max_price" name="max_price" value="{{ request('max_price') }}" placeholder="VNĐ">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang bán</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Dừng bán</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="stock" class="form-label">Kho hàng</label>
                        <select class="form-select" id="stock" name="stock">
                            <option value="">Tất cả</option>
                            <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                            <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                            <option value="low_stock" {{ request('stock') == 'low_stock' ? 'selected' : '' }}>Sắp hết hàng (≤ 10)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Đặc điểm</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" {{ request('featured') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="featured">Sản phẩm nổi bật</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_new" name="is_new" value="1" {{ request('is_new') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_new">Sản phẩm mới</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="on_sale" name="on_sale" value="1" {{ request('on_sale') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="on_sale">Đang giảm giá</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="order" class="form-label">Sắp xếp theo</label>
                        <select class="form-select" id="order" name="order">
                            <option value="latest" {{ request('order') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="oldest" {{ request('order') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                            <option value="price_asc" {{ request('order') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="price_desc" {{ request('order') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                            <option value="name_asc" {{ request('order') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="name_desc" {{ request('order') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                            <option value="stock_asc" {{ request('order') == 'stock_asc' ? 'selected' : '' }}>Tồn kho thấp đến cao</option>
                            <option value="stock_desc" {{ request('order') == 'stock_desc' ? 'selected' : '' }}>Tồn kho cao đến thấp</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    @if(request()->query())
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Xóa bộ lọc
                        </a>
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Áp dụng</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
    .product-img-wrapper {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f8f9fa;
        border-radius: 4px;
    }
    
    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        font-size: 24px;
    }
    
    .product-details {
        display: flex;
        align-items: center;
        margin-top: 2px;
    }
    
    @media (max-width: 767.98px) {
        .table-responsive {
            font-size: 0.875rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo tooltip
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection
