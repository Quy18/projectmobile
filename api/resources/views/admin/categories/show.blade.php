@extends('admin.layouts.app')

@section('title', 'Chi tiết danh mục - Quản trị Shop Điện Thoại')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Quản lý danh mục</a></li>
    <li class="breadcrumb-item active">Chi tiết danh mục</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i> Chi tiết danh mục
                </h5>
                <div>
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 200px">ID:</th>
                            <td>{{ $category->id }}</td>
                        </tr>
                        <tr>
                            <th>Tên danh mục:</th>
                            <td>{{ $category->name }}</td>
                        </tr>
                        <tr>
                            <th>Slug:</th>
                            <td><code>{{ $category->slug }}</code></td>
                        </tr>
                        <tr>
                            <th>Thuộc danh mục:</th>
                            <td>
                                @if($category->parent)
                                    <a href="{{ route('admin.categories.show', $category->parent->id) }}">
                                        {{ $category->parent->name }}
                                    </a>
                                @else
                                    <span class="text-muted">Danh mục gốc</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Mô tả:</th>
                            <td>{{ $category->description ?? 'Không có mô tả' }}</td>
                        </tr>
                        <tr>
                            <th>Thứ tự hiển thị:</th>
                            <td>{{ $category->order }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                @if($category->active)
                                    <span class="badge bg-success">Hiển thị</span>
                                @else
                                    <span class="badge bg-secondary">Ẩn</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ngày tạo:</th>
                            <td>{{ $category->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Ngày cập nhật:</th>
                            <td>{{ $category->updated_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    @if($category->image)
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Hình ảnh danh mục</h6>
                            </div>
                            <div class="card-body text-center">
                                <img src="{{ asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="img-fluid"
                                     style="max-height: 300px;">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            @if($category->children->count() > 0)
                <div class="mt-4">
                    <h5>Danh mục con ({{ $category->children->count() }})</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th style="width: 50px">ID</th>
                                    <th style="width: 80px">Ảnh</th>
                                    <th>Tên danh mục</th>
                                    <th>Số sản phẩm</th>
                                    <th style="width: 100px">Trạng thái</th>
                                    <th style="width: 150px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->children as $child)
                                    <tr>
                                        <td>{{ $child->id }}</td>
                                        <td>
                                            @if($child->image)
                                                <img src="{{ asset('storage/' . $child->image) }}" 
                                                     alt="{{ $child->name }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="text-center">
                                                    <i class="fas fa-folder fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.categories.show', $child->id) }}" class="fw-bold text-decoration-none">
                                                {{ $child->name }}
                                            </a>
                                        </td>
                                        <td>{{ $child->products->count() }}</td>
                                        <td class="text-center">
                                            @if($child->active)
                                                <span class="badge bg-success">Hiển thị</span>
                                            @else
                                                <span class="badge bg-secondary">Ẩn</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.categories.show', $child->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.categories.edit', $child->id) }}" class="btn btn-sm btn-primary" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            
            @if($category->products->count() > 0)
                <div class="mt-4">
                    <h5>Sản phẩm trong danh mục ({{ $category->products->count() }})</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th style="width: 50px">ID</th>
                                    <th style="width: 80px">Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th style="width: 150px">Giá</th>
                                    <th style="width: 100px">Tồn kho</th>
                                    <th style="width: 100px">Trạng thái</th>
                                    <th style="width: 150px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            @if($product->main_image)
                                                <img src="{{ asset('storage/' . $product->main_image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="text-center">
                                                    <i class="fas fa-image fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            @if($product->discount_price && $product->discount_price < $product->price)
                                                <span class="text-danger fw-bold">{{ number_format($product->discount_price) }}đ</span>
                                                <br>
                                                <del class="small text-muted">{{ number_format($product->price) }}đ</del>
                                            @else
                                                <span class="fw-bold">{{ number_format($product->price) }}đ</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($product->quantity > 0)
                                                <span class="badge bg-success">{{ $product->quantity }}</span>
                                            @else
                                                <span class="badge bg-danger">Hết hàng</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($product->active)
                                                <span class="badge bg-primary">Đang bán</span>
                                            @else
                                                <span class="badge bg-secondary">Ngừng bán</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle me-2"></i> Danh mục này chưa có sản phẩm nào.
                </div>
            @endif
            
            <div class="mt-4">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 