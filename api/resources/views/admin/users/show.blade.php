@extends('admin.layouts.app')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết người dùng</h1>
        <div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-edit mr-1"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin cơ bản -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin cơ bản</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="img-profile rounded-circle" style="width: 120px; height: 120px; object-fit: cover;" alt="{{ $user->name }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4e73df&color=ffffff&size=120'">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4e73df&color=ffffff&size=120" class="img-profile rounded-circle" style="width: 120px; height: 120px; object-fit: cover;" alt="{{ $user->name }}">
                        @endif
                        <h5 class="mt-3 mb-0">{{ $user->name }}</h5>
                        <p class="small text-muted">{{ ucfirst($user->role) }}</p>
                        <div class="mt-2">
                            @if($user->status == 'active')
                                <span class="badge badge-success">Đang hoạt động</span>
                            @else
                                <span class="badge badge-danger">Bị khóa</span>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width: 120px;">ID:</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Số điện thoại:</th>
                                <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                            </tr>
                            <tr>
                                <th>Địa chỉ:</th>
                                <td>{{ $user->address ?? 'Chưa cập nhật' }}</td>
                            </tr>
                            <tr>
                                <th>Ngày tạo:</th>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Cập nhật:</th>
                                <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Đơn hàng gần đây -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Đơn hàng gần đây</h6>
                    @if($user->orders->count() > 0)
                        <a href="#" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                    @endif
                </div>
                <div class="card-body">
                    @if($user->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Ngày đặt</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders->take(5) as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                            <td>
                                                @if($order->status == 'pending')
                                                    <span class="badge badge-warning">Chờ xác nhận</span>
                                                @elseif($order->status == 'processing')
                                                    <span class="badge badge-info">Đang xử lý</span>
                                                @elseif($order->status == 'shipped')
                                                    <span class="badge badge-primary">Đang giao hàng</span>
                                                @elseif($order->status == 'delivered')
                                                    <span class="badge badge-success">Hoàn thành</span>
                                                @elseif($order->status == 'cancelled')
                                                    <span class="badge badge-danger">Đã hủy</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $order->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-shopping-cart fa-3x text-gray-300 mb-3"></i>
                            <p class="mb-0">Người dùng chưa có đơn hàng nào</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Đánh giá gần đây -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Đánh giá gần đây</h6>
                    @if($user->reviews->count() > 0)
                        <a href="#" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                    @endif
                </div>
                <div class="card-body">
                    @if($user->reviews->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Đánh giá</th>
                                        <th>Bình luận</th>
                                        <th>Ngày đánh giá</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->reviews->take(5) as $review)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.products.show', $review->product_id) }}">
                                                    {{ $review->product->name ?? 'Sản phẩm đã bị xóa' }}
                                                </a>
                                            </td>
                                            <td>
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </td>
                                            <td>{{ Str::limit($review->comment, 50) }}</td>
                                            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-star fa-3x text-gray-300 mb-3"></i>
                            <p class="mb-0">Người dùng chưa có đánh giá nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .img-profile {
        border: 3px solid #4e73df;
    }
    
    .table th {
        background-color: #f8f9fc;
    }
    
    .badge {
        font-size: 85%;
    }
</style>
@endsection 