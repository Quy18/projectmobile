@extends('admin.layouts.app')

@section('title', 'Cập nhật đơn hàng #' . $order->id)

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2">Cập nhật đơn hàng #{{ $order->id }}</h1>
            <p class="text-muted">Chỉnh sửa thông tin đơn hàng và trạng thái</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary mt-3 mt-md-0">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-primary mt-3 mt-md-0 ms-2">
                <i class="fas fa-eye me-1"></i> Xem chi tiết
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mã đơn hàng</label>
                                <input type="text" class="form-control" value="#{{ $order->id }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày đặt hàng</label>
                                <input type="text" class="form-control" value="{{ $order->created_at->format('d/m/Y H:i:s') }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Trạng thái đơn hàng <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                    <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="shipped" {{ old('status', $order->status) == 'shipped' ? 'selected' : '' }}>Đang giao hàng</option>
                                    <option value="delivered" {{ old('status', $order->status) == 'delivered' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_status" class="form-label">Trạng thái thanh toán <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                                    <option value="pending" {{ old('payment_status', $order->payment_status) == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                                    <option value="paid" {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                    <option value="failed" {{ old('payment_status', $order->payment_status) == 'failed' ? 'selected' : '' }}>Thanh toán thất bại</option>
                                </select>
                                @error('payment_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tracking_number" class="form-label">Mã vận đơn</label>
                            <input type="text" class="form-control @error('tracking_number') is-invalid @enderror" id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}">
                            @error('tracking_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Thông tin người nhận</h5>

                        <div class="mb-3">
                            <label for="shipped_name" class="form-label">Tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('shipped_name') is-invalid @enderror" id="shipped_name" name="shipped_name" value="{{ old('shipped_name', $order->shipped_name) }}" required>
                            @error('shipped_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="shipped_phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('shipped_phone') is-invalid @enderror" id="shipped_phone" name="shipped_phone" value="{{ old('shipped_phone', $order->shipped_phone) }}" required>
                            @error('shipped_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="shipped_address" class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('shipped_address') is-invalid @enderror" id="shipped_address" name="shipped_address" rows="3" required>{{ old('shipped_address', $order->shipped_address) }}</textarea>
                            @error('shipped_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="3">{{ old('note', $order->note) }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary me-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Cập nhật đơn hàng</button>
                        </div>
                    </form>
                </div>
            </div>
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

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Phương thức thanh toán:</span>
                        <span class="fw-semibold">
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
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tổng số sản phẩm:</span>
                        <span class="fw-semibold">{{ $order->orderDetails->sum('quantity') }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tổng tiền:</span>
                        <span class="fw-semibold">{{ number_format($order->total_amount, 0, ',', '.') }} đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 