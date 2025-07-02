@extends('admin.layouts.app')

@section('title', 'Quản lý thương hiệu - Quản trị Shop Điện Thoại')

@section('breadcrumb')
    <li class="breadcrumb-item active">Quản lý thương hiệu</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-trademark me-2"></i> Danh sách thương hiệu
            </h5>
            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Thêm thương hiệu mới
            </a>
        </div>
        
        <div class="card-body">
            <!-- Bộ lọc -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <form action="{{ route('admin.brands.index') }}" method="GET" class="mb-0">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên...">
                                </div>
                            </div>
                         
                            
                            <div class="col-md-2">
                                <select name="featured" class="form-select">
                                    <option value="">-- Tính năng nổi bật --</option>
                                    <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Nổi bật</option>
                                    <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Bình thường</option>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <select name="sort" class="form-select">
                                    <option value="">-- Sắp xếp --</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-filter me-1"></i> Lọc
                                    </button>
                                    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-sync-alt me-1"></i> Đặt lại
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Hiển thị thông báo -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Danh sách thương hiệu -->
            @if($brands->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th style="width: 80px;">Logo</th>
                                <th>Tên thương hiệu</th>
                                <th>Slug</th>
                                <th style="width: 120px;">Sản phẩm</th>
                                <th style="width: 150px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brands as $brand)
                                <tr>
                                    <td>{{ $brand->id }}</td>
                                    <td>
                                        @if($brand->logo)
                                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: contain;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $brand->name }}
                                        @if($brand->featured)
                                            <span class="badge bg-primary ms-1">Nổi bật</span>
                                        @endif
                                    </td>
                                    <td>{{ $brand->slug }}</td>
                                    <td class="text-center">
                                        {{ $brand->products_count ?? 0 }}
                                    </td>
                                  
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.brands.show', $brand->id) }}" class="btn btn-info btn-sm" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-primary btn-sm" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $brand->id }}" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Modal xác nhận xóa -->
                                        <div class="modal fade" id="deleteModal{{ $brand->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $brand->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $brand->id }}">Xác nhận xóa thương hiệu</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Bạn có chắc chắn muốn xóa thương hiệu <strong>{{ $brand->name }}</strong>?</p>
                                                        
                                                        @if($brand->products_count > 0)
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle me-2"></i> Thương hiệu này đang có <strong>{{ $brand->products_count }}</strong> sản phẩm. Việc xóa có thể ảnh hưởng đến dữ liệu liên quan.
                                                            </div>
                                                        @endif
                                                        
                                                        <p class="mb-0">Hành động này không thể hoàn tác.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                                                        <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
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
                
                <!-- Phân trang -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $brands->appends(request()->query())->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Không tìm thấy thương hiệu nào phù hợp với điều kiện tìm kiếm.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
