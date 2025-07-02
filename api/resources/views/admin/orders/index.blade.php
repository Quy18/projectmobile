@extends('admin.layouts.app')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2">Quản lý đơn hàng</h1>
            <p class="text-muted">Danh sách tất cả đơn hàng trong hệ thống</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-chart-line me-1"></i> Xem báo cáo
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5 class="card-title mb-0">Danh sách đơn hàng</h5>
                </div>
                <div class="col-md-8">
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex flex-column flex-md-row gap-2">
                        <div class="input-group flex-grow-1">
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm theo mã đơn, tên khách hàng...">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        
                        <select class="form-select" name="status" style="width: auto;" onchange="this.form.submit()">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
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
            
            <div class="order-stats d-flex flex-wrap mb-0 px-3 pt-3">
                <div class="order-stat-item me-3 mb-3 py-2 px-3 border rounded bg-light">
                    <div class="text-muted small">Tổng đơn hàng</div>
                    <div class="fw-bold">{{ $stats['total'] ?? 0 }}</div>
                </div>
                <div class="order-stat-item me-3 mb-3 py-2 px-3 border rounded bg-light">
                    <div class="text-muted small">Chờ xác nhận</div>
                    <div class="fw-bold text-warning">{{ $stats['pending'] ?? 0 }}</div>
                </div>
                <div class="order-stat-item me-3 mb-3 py-2 px-3 border rounded bg-light">
                    <div class="text-muted small">Đang xử lý</div>
                    <div class="fw-bold text-primary">{{ $stats['processing'] ?? 0 }}</div>
                </div>
                <div class="order-stat-item me-3 mb-3 py-2 px-3 border rounded bg-light">
                    <div class="text-muted small">Đang giao hàng</div>
                    <div class="fw-bold text-info">{{ $stats['shipped'] ?? 0 }}</div>
                </div>
                <div class="order-stat-item me-3 mb-3 py-2 px-3 border rounded bg-light">
                    <div class="text-muted small">Hoàn thành</div>
                    <div class="fw-bold text-success">{{ $stats['delivered'] ?? 0 }}</div>
                </div>
                <div class="order-stat-item me-3 mb-3 py-2 px-3 border rounded bg-light">
                    <div class="text-muted small">Đã hủy</div>
                    <div class="fw-bold text-danger">{{ $stats['cancelled'] ?? 0 }}</div>
                </div>
            </div>
            
            @if(isset($orders) && $orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Khách hàng</th>
                                <th style="width: 140px;">Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Thanh toán</th>
                                <th>Trạng thái</th>
                                <th style="width: 150px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary fw-semibold">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <div>{{ $order->customer_name }}</div>
                                        <div class="small text-muted">{{ $order->customer_email }}</div>
                                        <div class="small text-muted">{{ $order->customer_phone }}</div>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="fw-semibold">{{ number_format($order->total, 0, ',', '.') }} đ</td>
                                    <td>
                                        @if($order->payment_status == 'paid')
                                            <span class="badge bg-success">Đã thanh toán</span>
                                        @elseif($order->payment_status == 'pending')
                                            <span class="badge bg-warning text-dark">Chờ thanh toán</span>
                                        @else
                                            <span class="badge bg-secondary">COD</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-primary">Đang xử lý</span>
                                        @elseif($order->status == 'shipped')
                                            <span class="badge bg-info">Đang giao hàng</span>
                                        @elseif($order->status == 'delivered')
                                            <span class="badge bg-success">Hoàn thành</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $order->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Cập nhật">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="In hóa đơn" onclick="window.open('{{ route('admin.orders.invoice', $order->id) }}', '_blank')">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center justify-content-md-between align-items-center flex-wrap px-3 py-3 border-top">
                    <div class="mb-3 mb-md-0 text-secondary">
                        Hiển thị {{ $orders->firstItem() ?? 0 }} đến {{ $orders->lastItem() ?? 0 }} của {{ $orders->total() }} đơn hàng
                    </div>
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('assets/img/empty-box.svg') }}" alt="Không có dữ liệu" style="max-width: 120px; opacity: 0.5;">
                    <h4 class="text-muted mt-4">Không tìm thấy đơn hàng nào</h4>
                    <p class="text-muted">Chưa có đơn hàng nào hoặc không có đơn hàng phù hợp với bộ lọc đã chọn.</p>
                    @if(request()->query())
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
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
        <form action="{{ route('admin.orders.index') }}" method="GET">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Lọc đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Mã đơn, tên khách hàng, email, số điện thoại...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái đơn hàng</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Trạng thái thanh toán</label>
                        <select class="form-select" id="payment_status" name="payment_status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                            <option value="cod" {{ request('payment_status') == 'cod' ? 'selected' : '' }}>Thanh toán khi nhận hàng (COD)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Phương thức thanh toán</label>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="">Tất cả phương thức</option>
                            <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>Thanh toán khi nhận hàng (COD)</option>
                            <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản ngân hàng</option>
                            <option value="momo" {{ request('payment_method') == 'momo' ? 'selected' : '' }}>Ví MoMo</option>
                            <option value="vnpay" {{ request('payment_method') == 'vnpay' ? 'selected' : '' }}>VNPay</option>
                            <option value="zalopay" {{ request('payment_method') == 'zalopay' ? 'selected' : '' }}>ZaloPay</option>
                        </select>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="date_from" class="form-label">Từ ngày</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="date_to" class="form-label">Đến ngày</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="min_total" class="form-label">Tổng tiền từ</label>
                            <input type="number" class="form-control" id="min_total" name="min_total" value="{{ request('min_total') }}" placeholder="VNĐ">
                        </div>
                        <div class="col-md-6">
                            <label for="max_total" class="form-label">Tổng tiền đến</label>
                            <input type="number" class="form-control" id="max_total" name="max_total" value="{{ request('max_total') }}" placeholder="VNĐ">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if(request()->query())
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
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
    .order-stats {
        flex-wrap: nowrap;
        overflow-x: auto;
    }
    
    .order-stat-item {
        min-width: 120px;
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
    $(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
