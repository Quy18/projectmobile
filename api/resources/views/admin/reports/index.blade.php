@extends('admin.layouts.app')

@section('title', 'Báo Cáo & Thống Kê')

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Báo Cáo & Thống Kê</h1>
    </div>

    <!-- Content Row - Các loại báo cáo -->
    <div class="row">
        <!-- Báo cáo tổng quan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng quan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Doanh thu & Đơn hàng</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-primary stretched-link" href="{{ route('admin.reports.overview') }}">Xem chi tiết</a>
                    <div class="small text-primary"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Báo cáo doanh thu -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Doanh thu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Phân tích doanh thu</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-success stretched-link" href="{{ route('admin.reports.revenue') }}">Xem chi tiết</a>
                    <div class="small text-success"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Sản phẩm bán chạy -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Sản phẩm</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Top sản phẩm bán chạy</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-info stretched-link" href="{{ route('admin.reports.topProducts') }}">Xem chi tiết</a>
                    <div class="small text-info"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Khách hàng tiềm năng -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Khách hàng</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Khách hàng tiềm năng</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-warning stretched-link" href="{{ route('admin.reports.topCustomers') }}">Xem chi tiết</a>
                    <div class="small text-warning"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row - Xuất dữ liệu -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Xuất báo cáo dữ liệu</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reports.exportCSV') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="report-type">Loại báo cáo</label>
                                <select class="form-control" id="report-type" name="type">
                                    <option value="orders">Đơn hàng</option>
                                    <option value="products">Sản phẩm</option>
                                    <option value="users">Khách hàng</option>
                                </select>
                            </div>

                            <div class="col-md-3 form-group">
                                <label for="start-date">Từ ngày</label>
                                <input type="date" class="form-control" id="start-date" name="start_date" 
                                    value="{{ \Carbon\Carbon::now()->startOfMonth()->toDateString() }}">
                            </div>

                            <div class="col-md-3 form-group">
                                <label for="end-date">Đến ngày</label>
                                <input type="date" class="form-control" id="end-date" name="end_date" 
                                    value="{{ \Carbon\Carbon::now()->endOfMonth()->toDateString() }}">
                            </div>

                            <div class="col-md-2 form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-download fa-sm text-white-50 mr-1"></i> Xuất CSV
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i> 
                            Báo cáo CSV sẽ bao gồm tất cả dữ liệu trong khoảng thời gian bạn đã chọn.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row - Điều hướng nhanh -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h4 class="small font-weight-bold">Đơn hàng hôm nay: 
                            <span class="float-right">{{ \App\Models\Order::whereDate('created_at', \Carbon\Carbon::today())->count() }}</span>
                        </h4>
                    </div>
                    <div class="mb-3">
                        <h4 class="small font-weight-bold">Doanh thu hôm nay: 
                            <span class="float-right">{{ number_format(\App\Models\Order::whereDate('created_at', \Carbon\Carbon::today())->where('status', 'delivered')->sum('total_amount'), 0, ',', '.') }} đ</span>
                        </h4>
                    </div>
                    <div class="mb-3">
                        <h4 class="small font-weight-bold">Đơn hàng mới: 
                            <span class="float-right">{{ \App\Models\Order::where('status', 'pending')->count() }}</span>
                        </h4>
                    </div>
                    <div class="mb-3">
                        <h4 class="small font-weight-bold">Số lượng sản phẩm: 
                            <span class="float-right">{{ \App\Models\Product::count() }}</span>
                        </h4>
                    </div>
                    <div class="mb-3">
                        <h4 class="small font-weight-bold">Tổng số khách hàng: 
                            <span class="float-right">{{ \App\Models\User::where('role', 'user')->count() }}</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Phương thức thanh toán</h6>
                </div>
                <div class="card-body">
                    @php
                        $paymentMethods = \App\Models\Order::select('payment_method', DB::raw('count(*) as total'))
                            ->groupBy('payment_method')
                            ->pluck('total', 'payment_method')
                            ->toArray();
                        $totalOrders = array_sum($paymentMethods);
                    @endphp

                    @foreach($paymentMethods as $method => $count)
                        @php 
                            $percent = $totalOrders > 0 ? round(($count / $totalOrders) * 100) : 0;
                            $color = ['cod' => 'danger', 'vnpay' => 'primary', 'momo' => 'success', 'bank_transfer' => 'info', 'paypal' => 'warning'];
                            $methodName = [
                                'cod' => 'Thanh toán khi nhận hàng',
                                'vnpay' => 'VNPay',
                                'momo' => 'MoMo',
                                'bank_transfer' => 'Chuyển khoản',
                                'paypal' => 'PayPal'
                            ];
                        @endphp
                        <div class="mb-3">
                            <h4 class="small font-weight-bold">
                                {{ $methodName[$method] ?? $method }} 
                                <span class="float-right">{{ $percent }}%</span>
                            </h4>
                            <div class="progress">
                                <div class="progress-bar bg-{{ $color[$method] ?? 'secondary' }}" role="progressbar" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
