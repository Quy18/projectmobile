@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2">Chi tiết đơn hàng #{{ $order->id }}</h1>
            <p class="text-muted">Thông tin chi tiết về đơn hàng và trạng thái</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary mt-3 mt-md-0">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-outline-primary mt-3 mt-md-0 ms-2">
                <i class="fas fa-edit me-1"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-outline-info mt-3 mt-md-0 ms-2" target="_blank">
                <i class="fas fa-file-invoice me-1"></i> In hóa đơn
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Thông tin trạng thái</h5>
                    <span class="badge 
                        @if($order->status == 'pending') bg-warning
                        @elseif($order->status == 'processing') bg-info
                        @elseif($order->status == 'shipped') bg-primary
                        @elseif($order->status == 'delivered') bg-success
                        @elseif($order->status == 'cancelled') bg-danger
                        @endif">
                        @if($order->status == 'pending') Chờ xác nhận
                        @elseif($order->status == 'processing') Đang xử lý
                        @elseif($order->status == 'shipped') Đang giao hàng
                        @elseif($order->status == 'delivered') Hoàn thành
                        @elseif($order->status == 'cancelled') Đã hủy
                        @endif
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <h6 class="text-muted">Mã đơn hàng</h6>
                            <p class="mb-0 fw-semibold">#{{ $order->id }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h6 class="text-muted">Ngày đặt hàng</h6>
                            <p class="mb-0 fw-semibold">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h6 class="text-muted">Phương thức thanh toán</h6>
                            <p class="mb-0 fw-semibold">
                                @if($order->payment_method == 'cod')
                                    Thanh toán khi nhận hàng (COD)
                                @elseif($order->payment_method == 'bank_transfer')
                                    Chuyển khoản ngân hàng
                                @elseif($order->payment_method == 'momo')
                                    Ví MoMo
                                @elseif($order->payment_method == 'vnpay')
                                    VNPay
                                @elseif($order->payment_method == 'zalopay')
                                    ZaloPay
                                @else
                                    {{ $order->payment_method }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h6 class="text-muted">Trạng thái thanh toán</h6>
                            <span class="badge 
                                @if($order->payment_status == 'pending') bg-warning
                                @elseif($order->payment_status == 'paid') bg-success
                                @elseif($order->payment_status == 'failed') bg-danger
                                @endif">
                                @if($order->payment_status == 'pending') Chờ thanh toán
                                @elseif($order->payment_status == 'paid') Đã thanh toán
                                @elseif($order->payment_status == 'failed') Thanh toán thất bại
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="status-update-container">
                                <div class="d-flex mb-3">
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="me-3">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group">
                                            <select class="form-select" name="status" aria-label="Cập nhật trạng thái">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang giao hàng</option>
                                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Hoàn thành</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                            </select>
                                            <button class="btn btn-primary" type="submit">Cập nhật trạng thái</button>
                                        </div>
                                    </form>

                                    <form action="{{ route('admin.orders.updatePayment', $order->id) }}" method="POST" class="me-3">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group">
                                            <select class="form-select" name="payment_status" aria-label="Cập nhật trạng thái thanh toán">
                                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Thanh toán thất bại</option>
                                            </select>
                                            <button class="btn btn-success" type="submit">Cập nhật thanh toán</button>
                                        </div>
                                    </form>

                                    <form action="{{ route('admin.orders.updateTracking', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="tracking_number" placeholder="Mã vận đơn" value="{{ $order->tracking_number }}">
                                            <button class="btn btn-info" type="submit">Cập nhật vận đơn</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Chi tiết đơn hàng</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderDetails as $detail)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($detail->product && $detail->product->image)
                                                <img src="{{ asset('storage/' . $detail->product->image) }}" alt="{{ $detail->product_name }}" class="img-fluid rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $detail->product_name }}</h6>
                                                @if($detail->product)
                                                    <small class="text-muted">
                                                        <a href="{{ route('admin.products.edit', $detail->product_id) }}" target="_blank">
                                                            Xem sản phẩm <i class="fas fa-external-link-alt fa-xs"></i>
                                                        </a>
                                                    </small>
                                                @else
                                                    <small class="text-danger">Sản phẩm đã bị xóa</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ number_format($detail->price, 0, ',', '.') }} đ</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-end">{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }} đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tổng giá trị sản phẩm:</td>
                                    <td class="text-end fw-bold">{{ number_format($order->orderDetails->sum(function($detail) { return $detail->price * $detail->quantity; }), 0, ',', '.') }} đ</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Phí vận chuyển:</td>
                                    <td class="text-end fw-bold">{{ number_format($order->shipped_fee, 0, ',', '.') }} đ</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Giảm giá:</td>
                                    <td class="text-end fw-bold">{{ number_format($order->discount, 0, ',', '.') }} đ</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                                    <td class="text-end fw-bold fs-5 text-primary">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($order->note)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ghi chú đơn hàng</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->note }}</p>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin khách hàng</h5>
                </div>
                <div class="card-body">
                    @if($order->user)
                        <p><strong>Tên:</strong> {{ $order->user->name }}</p>
                        <p><strong>Email:</strong> {{ $order->user->email }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $order->user->phone ?? 'Không có' }}</p>
                        <p><strong>Tài khoản từ:</strong> {{ $order->user->created_at->format('d/m/Y') }}</p>
                        <a href="{{ route('admin.users.show', $order->user->id) }}" class="btn btn-sm btn-outline-primary">
                            Xem thông tin chi tiết
                        </a>
                    @else
                        <div class="alert alert-info mb-0">
                            Đơn hàng được đặt không thông qua tài khoản
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <p><strong>Người nhận:</strong> {{ $order->shipped_name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->shipped_phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->shipped_address }}</p>
                    @if($order->tracking_number)
                        <p><strong>Mã vận đơn:</strong> {{ $order->tracking_number }}</p>
                    @endif
                </div>
            </div>

            @if($order->created_at->diffInDays() < 7)
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Lịch sử đơn hàng</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0">
                            <div class="d-flex align-items-center">
                                <div class="timeline-bullet bg-success me-3"></div>
                                <div>
                                    <p class="mb-0 fw-semibold">Đơn hàng được tạo</p>
                                    <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i:s') }}</small>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Các trạng thái sau đây chỉ hiển thị nếu có --}}
                        @if($order->status == 'processing' || $order->status == 'shipped' || $order->status == 'delivered')
                        <div class="list-group-item border-0">
                            <div class="d-flex align-items-center">
                                <div class="timeline-bullet bg-info me-3"></div>
                                <div>
                                    <p class="mb-0 fw-semibold">Đơn hàng được xác nhận</p>
                                    <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i:s') }}</small>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status == 'shipped' || $order->status == 'delivered')
                        <div class="list-group-item border-0">
                            <div class="d-flex align-items-center">
                                <div class="timeline-bullet bg-primary me-3"></div>
                                <div>
                                    <p class="mb-0 fw-semibold">Đơn hàng đang được giao</p>
                                    <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i:s') }}</small>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status == 'delivered')
                        <div class="list-group-item border-0">
                            <div class="d-flex align-items-center">
                                <div class="timeline-bullet bg-success me-3"></div>
                                <div>
                                    <p class="mb-0 fw-semibold">Đơn hàng đã hoàn thành</p>
                                    <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i:s') }}</small>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status == 'cancelled')
                        <div class="list-group-item border-0">
                            <div class="d-flex align-items-center">
                                <div class="timeline-bullet bg-danger me-3"></div>
                                <div>
                                    <p class="mb-0 fw-semibold">Đơn hàng đã bị hủy</p>
                                    <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i:s') }}</small>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .timeline-bullet {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
</style>
@endsection 