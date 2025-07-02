@extends('admin.layouts.app')

@section('title', 'Bảng điều khiển')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-3">Bảng điều khiển</h1>
            <p class="text-muted">Xem tổng quan về hoạt động kinh doanh của bạn</p>
        </div>
    </div>
    
    <!-- Thẻ thống kê -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-3 bg-primary-subtle rounded">
                                <i class="fas fa-shopping-bag fs-4 text-primary d-flex align-items-center justify-content-center h-100"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-0">{{ $totalOrders ?? 0 }}</h5>
                                <p class="text-muted mb-0">Đơn hàng</p>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-ghost-secondary" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">Xem chi tiết</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success me-2">
                            <i class="fas fa-arrow-up me-1"></i>{{ $orderGrowth ?? '0' }}%
                        </span>
                        <span class="text-muted small">so với tháng trước</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-3 bg-success-subtle rounded">
                                <i class="fas fa-sack-dollar fs-4 text-success d-flex align-items-center justify-content-center h-100"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-0">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}đ</h5>
                                <p class="text-muted mb-0">Doanh thu</p>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-ghost-secondary" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">Xem báo cáo</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success me-2">
                            <i class="fas fa-arrow-up me-1"></i>{{ $revenueGrowth ?? '0' }}%
                        </span>
                        <span class="text-muted small">so với tháng trước</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-3 bg-info-subtle rounded">
                                <i class="fas fa-users fs-4 text-info d-flex align-items-center justify-content-center h-100"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-0">{{ $totalUsers ?? 0 }}</h5>
                                <p class="text-muted mb-0">Người dùng</p>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-ghost-secondary" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Xem chi tiết</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success me-2">
                            <i class="fas fa-arrow-up me-1"></i>{{ $userGrowth ?? '0' }}%
                        </span>
                        <span class="text-muted small">người dùng mới</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-3 bg-warning-subtle rounded">
                                <i class="fas fa-box-open fs-4 text-warning d-flex align-items-center justify-content-center h-100"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-0">{{ $totalProducts ?? 0 }}</h5>
                                <p class="text-muted mb-0">Sản phẩm</p>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-ghost-secondary" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.products.index') }}">Xem chi tiết</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success me-2">
                            <i class="fas fa-arrow-up me-1"></i>{{ $productGrowth ?? '0' }}%
                        </span>
                        <span class="text-muted small">sản phẩm mới</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Doanh thu theo thời gian -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Doanh thu theo thời gian</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Xem chi tiết:</div>
                            <a class="dropdown-item" href="{{ route('admin.reports.revenue') }}">
                                <i class="fas fa-chart-line fa-sm fa-fw mr-2 text-gray-400"></i>
                                Báo cáo doanh thu
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Đơn hàng gần đây -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Đơn hàng gần đây</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @if(isset($recentOrders) && count($recentOrders) > 0)
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <p class="mb-0 fw-medium">#{{ $order->order_number }}</p>
                                                    <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $order->status == 'delivered' ? 'bg-success' : ($order->status == 'pending' ? 'bg-warning' : ($order->status == 'cancelled' ? 'bg-danger' : 'bg-info')) }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-semibold">{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center py-4">Chưa có đơn hàng nào</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Sản phẩm bán chạy -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sản phẩm bán chạy</h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Đã bán</th>
                                    <th class="text-end">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($bestSellingProducts) && count($bestSellingProducts) > 0)
                                    @foreach($bestSellingProducts as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($product->main_image)
                                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="rounded me-3" width="40" height="40" style="object-fit: cover;">
                                                @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                                @endif
                                                <div>
                                                    <p class="mb-0 fw-medium">{{ $product->name }}</p>
                                                    <small class="text-muted">{{ $product->sku }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $product->sold_count }}</td>
                                        <td class="text-end fw-semibold">{{ number_format($product->revenue, 0, ',', '.') }}đ</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center py-4">Chưa có dữ liệu</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hoạt động gần đây -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Hoạt động gần đây</h5>
                </div>
                <div class="card-body p-0">
                    <div class="timeline p-3">
                        @if(isset($recentActivities) && count($recentActivities) > 0)
                            @foreach($recentActivities as $activity)
                            <div class="timeline-item pb-3 mb-3 border-bottom">
                                <span class="timeline-item-icon {{ $activity->type == 'order' ? 'bg-primary' : ($activity->type == 'user' ? 'bg-info' : ($activity->type == 'product' ? 'bg-warning' : 'bg-success')) }}">
                                    <i class="fas {{ $activity->type == 'order' ? 'fa-shopping-bag' : ($activity->type == 'user' ? 'fa-user' : ($activity->type == 'product' ? 'fa-box' : 'fa-cog')) }}"></i>
                                </span>
                                <div class="timeline-item-wrapper">
                                    <div class="timeline-item-description">
                                        <span class="fw-semibold">{{ $activity->user->name }}</span> 
                                        <span>{{ $activity->description }}</span>
                                    </div>
                                    <div class="timeline-item-time">
                                        <i class="far fa-clock me-1"></i> {{ $activity->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <p class="mb-0 text-muted">Chưa có hoạt động nào được ghi lại</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
    }
    
    .timeline {
        position: relative;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 40px;
        display: flex;
    }
    
    .timeline-item-icon {
        position: absolute;
        left: 0;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.75rem;
    }
    
    .timeline-item-wrapper {
        flex: 1;
    }
    
    .timeline-item-time {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
//<![CDATA[
    document.addEventListener('DOMContentLoaded', function() {
        // Debug: Hiển thị dữ liệu từ server
        console.log('Monthly Revenue:', {!! json_encode($monthlyRevenue ?? []) !!});
        console.log('Month Names:', {!! json_encode($monthNames ?? []) !!});
        
        // Biểu đồ doanh thu hàng tháng
        var ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
        var monthlyLabels = [];
        var monthlyData = [];
        
        // Lấy dữ liệu từ controller - sử dụng phương pháp an toàn
        var monthlyRevenueData = {!! json_encode($monthlyRevenue ?? []) !!};
        var monthNamesData = {!! json_encode($monthNames ?? []) !!};
        
        // Xử lý dữ liệu
        for (var month in monthlyRevenueData) {
            if (monthlyRevenueData.hasOwnProperty(month)) {
                var monthLabel = monthNamesData[month] || 'Tháng ' + month;
                monthlyLabels.push(monthLabel);
                monthlyData.push(monthlyRevenueData[month]);
            }
        }
        
        console.log('Processed Labels:', monthlyLabels);
        console.log('Processed Data:', monthlyData);
        
        var monthlyRevenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: monthlyData,
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + ' đ';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' đ';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
//]]>
</script>
@endsection
