@extends('admin.layouts.app')

@section('title', 'Báo Cáo Tổng Quan')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css">
<style>
    .dashboard-stat {
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        color: #fff;
    }
    .dashboard-stat-revenue {
        background: linear-gradient(45deg, #4e73df, #224abe);
    }
    .dashboard-stat-orders {
        background: linear-gradient(45deg, #1cc88a, #13855c);
    }
    .dashboard-stat-users {
        background: linear-gradient(45deg, #36b9cc, #258391);
    }
    .change-indicator {
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 12px;
        margin-left: 8px;
    }
    .change-positive {
        background-color: rgba(28, 200, 138, 0.25);
        color: #1cc88a;
    }
    .change-negative {
        background-color: rgba(231, 74, 59, 0.25);
        color: #e74a3b;
    }
    .change-neutral {
        background-color: rgba(54, 185, 204, 0.25);
        color: #36b9cc;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang và breadcrumb -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Báo Cáo Tổng Quan</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Báo Cáo</a></li>
            <li class="breadcrumb-item active">Tổng Quan</li>
        </ol>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Thống kê doanh thu -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="dashboard-stat dashboard-stat-revenue">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-white">Doanh thu</h5>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                    </div>
                </div>
                <h2 class="text-white">{{ number_format($revenueData['current'], 0, ',', '.') }} đ</h2>
                <p class="mt-2 mb-0">
                    So với tháng trước: 
                    @if($revenueData['change'] > 0)
                        <span class="change-indicator change-positive">
                            <i class="fas fa-arrow-up"></i> {{ number_format($revenueData['change'], 2) }}%
                        </span>
                    @elseif($revenueData['change'] < 0)
                        <span class="change-indicator change-negative">
                            <i class="fas fa-arrow-down"></i> {{ number_format(abs($revenueData['change']), 2) }}%
                        </span>
                    @else
                        <span class="change-indicator change-neutral">0%</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Thống kê đơn hàng -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="dashboard-stat dashboard-stat-orders">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-white">Đơn hàng</h5>
                    <div class="icon">
                        <i class="fas fa-shopping-cart fa-2x opacity-50"></i>
                    </div>
                </div>
                <h2 class="text-white">{{ $ordersData['current'] }}</h2>
                <p class="mt-2 mb-0">
                    So với tháng trước: 
                    @if($ordersData['change'] > 0)
                        <span class="change-indicator change-positive">
                            <i class="fas fa-arrow-up"></i> {{ number_format($ordersData['change'], 2) }}%
                        </span>
                    @elseif($ordersData['change'] < 0)
                        <span class="change-indicator change-negative">
                            <i class="fas fa-arrow-down"></i> {{ number_format(abs($ordersData['change']), 2) }}%
                        </span>
                    @else
                        <span class="change-indicator change-neutral">0%</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Thống kê người dùng mới -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="dashboard-stat dashboard-stat-users">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-white">Người dùng mới</h5>
                    <div class="icon">
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
                <h2 class="text-white">{{ $usersData['current'] }}</h2>
                <p class="mt-2 mb-0">
                    So với tháng trước: 
                    @if($usersData['change'] > 0)
                        <span class="change-indicator change-positive">
                            <i class="fas fa-arrow-up"></i> {{ number_format($usersData['change'], 2) }}%
                        </span>
                    @elseif($usersData['change'] < 0)
                        <span class="change-indicator change-negative">
                            <i class="fas fa-arrow-down"></i> {{ number_format(abs($usersData['change']), 2) }}%
                        </span>
                    @else
                        <span class="change-indicator change-neutral">0%</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Chart trạng thái đơn hàng -->
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Trạng thái đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart phương thức thanh toán -->
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Phương thức thanh toán</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="paymentMethodChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row - Bảng chi tiết -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Chi tiết trạng thái đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Trạng thái</th>
                                    <th>Số lượng</th>
                                    <th>Phần trăm</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $statuses = [
                                        'pending' => 'Chờ xử lý',
                                        'processing' => 'Đang xử lý',
                                        'shipped' => 'Đang giao hàng',
                                        'delivered' => 'Đã giao hàng',
                                        'canceled' => 'Đã hủy',
                                        'returned' => 'Đã trả hàng'
                                    ];
                                    $colors = [
                                        'pending' => '#f6c23e',
                                        'processing' => '#4e73df', 
                                        'shipped' => '#36b9cc',
                                        'delivered' => '#1cc88a',
                                        'canceled' => '#e74a3b',
                                        'returned' => '#858796'
                                    ];
                                    $totalOrders = array_sum($orderStatuses);
                                @endphp
                                
                                @foreach($statuses as $status => $label)
                                    <tr>
                                        <td>
                                            <span class="badge" style="background-color: {{ $colors[$status] }}; color: white;">
                                                {{ $label }}
                                            </span>
                                        </td>
                                        <td>{{ $orderStatuses[$status] ?? 0 }}</td>
                                        <td>
                                            @if($totalOrders > 0)
                                                {{ number_format((($orderStatuses[$status] ?? 0) / $totalOrders) * 100, 2) }}%
                                            @else
                                                0%
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
    // Biểu đồ trạng thái đơn hàng
    const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($orderStatuses as $status => $count)
                    '{{ $statuses[$status] ?? $status }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($orderStatuses as $count)
                        {{ $count }},
                    @endforeach
                ],
                backgroundColor: [
                    @foreach($orderStatuses as $status => $count)
                        '{{ $colors[$status] ?? '#'.(substr(md5($status), 0, 6)) }}',
                    @endforeach
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'top',
            },
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });

    // Biểu đồ phương thức thanh toán
    const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
    const paymentChart = new Chart(paymentCtx, {
        type: 'pie',
        data: {
            labels: [
                @php
                    $methodLabels = [
                        'cod' => 'Thanh toán khi nhận hàng',
                        'vnpay' => 'VNPay',
                        'momo' => 'MoMo',
                        'bank_transfer' => 'Chuyển khoản',
                        'paypal' => 'PayPal'
                    ];
                    $paymentColors = [
                        'cod' => '#e74a3b',
                        'vnpay' => '#4e73df',
                        'momo' => '#1cc88a',
                        'bank_transfer' => '#36b9cc',
                        'paypal' => '#f6c23e'
                    ];
                @endphp
                @foreach($paymentMethods as $method => $count)
                    '{{ $methodLabels[$method] ?? $method }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($paymentMethods as $count)
                        {{ $count }},
                    @endforeach
                ],
                backgroundColor: [
                    @foreach($paymentMethods as $method => $count)
                        '{{ $paymentColors[$method] ?? '#'.(substr(md5($method), 0, 6)) }}',
                    @endforeach
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
</script>
@endsection 