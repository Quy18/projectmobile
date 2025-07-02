@extends('admin.layouts.app')

@section('title', 'Quản lý danh mục')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-3">Danh mục sản phẩm</h1>
            <p class="text-muted">Quản lý tất cả danh mục sản phẩm trong hệ thống</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mt-3 mt-md-0">
                <i class="fas fa-plus me-1"></i> Thêm danh mục
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h5 class="card-title mb-0">Danh sách danh mục</h5>
                </div>
                <div class="col-md-8">
                    <form action="{{ route('admin.categories.index') }}" method="GET" class="d-flex justify-content-md-end mt-3 mt-md-0">
                        <div class="input-group me-2" style="max-width: 300px;">
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm...">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="fas fa-filter"></i>
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
            
            @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th style="width: 80px;">Hình ảnh</th>
                                <th>Tên danh mục</th>
                                <th>Danh mục cha</th>
                                <th style="width: 120px;">Số sản phẩm</th>
                                <th style="width: 100px;">Trạng thái</th>
                                <th style="width: 150px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td class="text-center">{{ $category->id }}</td>
                                    <td>
                                        <div class="category-image-container">
                                            @if($category->image)
                                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="category-image rounded" onerror="this.onerror=null; this.style.display='none'; this.parentNode.innerHTML='<div class=\'category-image-placeholder\'><i class=\'fas fa-folder text-secondary\'></i></div>'">
                                            @else
                                                <div class="category-image-placeholder">
                                                    <i class="fas fa-folder text-secondary"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.categories.show', $category->id) }}" class="text-dark fw-medium">
                                            {{ $category->name }}
                                        </a>
                                        <div>
                                            <small class="text-muted">{{ $category->slug }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($category->parent)
                                            <a href="{{ route('admin.categories.show', $category->parent_id) }}" class="text-dark">
                                                {{ $category->parent->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.categories.show', $category->id) }}?tab=products" class="badge bg-secondary">
                                            {{ $category->products_count ?? 0 }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="badge bg-success">Hiển thị</div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Modal Xóa -->
                                        <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $category->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $category->id }}">Xác nhận xóa</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Bạn có chắc chắn muốn xóa danh mục <strong>{{ $category->name }}</strong>?</p>
                                                        @if($category->products_count > 0 || $category->children->count() > 0)
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                <span>
                                                                    Danh mục này có 
                                                                    @if($category->products_count > 0)
                                                                        <strong>{{ $category->products_count }}</strong> sản phẩm 
                                                                    @endif
                                                                    
                                                                    @if($category->products_count > 0 && $category->children->count() > 0)
                                                                        và 
                                                                    @endif
                                                                    
                                                                    @if($category->children->count() > 0)
                                                                        <strong>{{ $category->children->count() }}</strong> danh mục con
                                                                    @endif
                                                                    . Xóa danh mục này có thể ảnh hưởng đến dữ liệu liên quan.
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
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
                        <span class="text-muted">Hiển thị {{ $categories->firstItem() ? $categories->firstItem() : 0 }} đến {{ $categories->lastItem() ? $categories->lastItem() : 0 }} của {{ $categories->total() }} danh mục</span>
                    </div>
                    {{ $categories->appends(request()->query())->links() }}
                </div>
                
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('assets/img/empty-box.svg') }}" alt="Không có dữ liệu" style="max-width: 150px; opacity: 0.5;">
                    <h4 class="text-muted mt-4">Chưa có danh mục nào</h4>
                    <p class="text-muted mb-4">Bạn chưa tạo danh mục sản phẩm nào hoặc không tìm thấy kết quả phù hợp.</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Thêm danh mục mới
                    </a>
                    @if(request()->has('search') || request()->has('status') || request()->has('parent_id'))
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary ms-2">
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
        <form action="{{ route('admin.categories.index') }}" method="GET">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Lọc danh mục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Tên danh mục, mô tả,...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Danh mục cha</label>
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="">Tất cả danh mục</option>
                            <option value="root" {{ request('parent_id') == 'root' ? 'selected' : '' }}>Danh mục gốc</option>
                            @foreach($parentCategories ?? [] as $parentCategory)
                                <option value="{{ $parentCategory->id }}" {{ request('parent_id') == $parentCategory->id ? 'selected' : '' }}>
                                    {{ $parentCategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    @if(request()->has('search') || request()->has('parent_id'))
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
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
    .category-image-container {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f8f9fa;
        border-radius: 4px;
    }
    
    .category-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .category-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        font-size: 18px;
    }
    
    .page-link {
        border-radius: 0 !important;
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
