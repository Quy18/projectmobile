@extends('admin.layouts.app')

@section('title', 'Chi tiết thương hiệu - Quản trị Shop Điện Thoại')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Quản lý thương hiệu</a></li>
    <li class="breadcrumb-item active">{{ $brand->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-trademark me-2"></i> Chi tiết thương hiệu
                    </h5>
                    <div>
                        <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Chỉnh sửa
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash-alt me-1"></i> Xóa
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px;">ID</th>
                                    <td>{{ $brand->id }}</td>
                                </tr>
                                <tr>
                                    <th>Tên thương hiệu</th>
                                    <td>{{ $brand->name }}</td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td>{{ $brand->slug }}</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td>
                                        @if($brand->active)
                                            <span class="badge bg-success">Đang hoạt động</span>
                                        @else
                                            <span class="badge bg-danger">Đã vô hiệu hóa</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nổi bật</th>
                                    <td>
                                        @if($brand->featured)
                                            <span class="badge bg-primary">Nổi bật</span>
                                        @else
                                            <span class="badge bg-secondary">Bình thường</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Thứ tự hiển thị</th>
                                    <td>{{ $brand->display_order }}</td>
                                </tr>
                                <tr>
                                    <th>Website</th>
                                    <td>
                                        @if($brand->website)
                                            <a href="{{ $brand->website }}" target="_blank">{{ $brand->website }} <i class="fas fa-external-link-alt ms-1 small"></i></a>
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Mô tả</th>
                                    <td>
                                        @if($brand->description)
                                            {!! nl2br(e($brand->description)) !!}
                                        @else
                                            <span class="text-muted">Không có mô tả</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td>{{ $brand->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Cập nhật lần cuối</th>
                                    <td>{{ $brand->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">Logo thương hiệu</h6>
                                </div>
                                <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                                    @if($brand->logo)
                                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="img-fluid mb-3" style="max-height: 200px;">
                                        <a href="{{ asset('storage/' . $brand->logo) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-external-link-alt me-1"></i> Xem ảnh gốc
                                        </a>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fas fa-image fa-5x text-muted mb-3"></i>
                                            <p class="text-muted">Không có logo</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-mobile-alt me-2"></i> Sản phẩm thuộc thương hiệu
            </h5>
        </div>
        <div class="card-body">
            @if($brand->products && $brand->products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brand->products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        @if($product->thumbnail)
                                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($product->price, 0, ',', '.') }}₫</td>
                                    <td>
                                        @if($product->active)
                                            <span class="badge bg-success">Đang bán</span>
                                        @else
                                            <span class="badge bg-danger">Ẩn</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Chưa có sản phẩm nào thuộc thương hiệu này.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa thương hiệu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa thương hiệu <strong>{{ $brand->name }}</strong>?</p>
                
                @if($brand->products && $brand->products->count() > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> Thương hiệu này đang có <strong>{{ $brand->products->count() }}</strong> sản phẩm. Việc xóa có thể ảnh hưởng đến dữ liệu liên quan.
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
@endsection 