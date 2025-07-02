@extends('admin.layouts.app')

@section('title', 'Báo Cáo Doanh Thu')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .stats-card {
        border-left: 4px solid #4e73df;
        border-radius: 4px;
    }
    .filter-card {
        background-color: #f8f9fc;
        border-radius: 6px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang và breadcrumb -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Báo Cáo Doanh Thu</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Báo Cáo</a></li>
            <li class="breadcrumb-item active">Doanh Thu</li>
        </ol>
    </div>

    <!-- Bộ lọc -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Bộ lọc dữ liệu</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reports.revenue') }}" method="GET" id="filter-form">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="start-date">Từ ngày:</label>
                        <input type="date" class="form-control" id="start-date" name="start_date" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="end-date">Đến ngày:</label>
                        <input type="date" class="form-control" id="end-date" name="end_date" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="group-by">Nhóm theo:</label>
                        <select class="form-control" id="group-by" name="group_by">
                            <option value="day" {{ $groupBy == 'day' ? 'selected' : '' }}>Ngày</option>
                            <option value="month" {{ $groupBy == 'month' ? 'selected' : '' }}>Tháng</option>
                            <option value="year" {{ $groupBy == 'year' ? 'selected' : '' }}>Năm</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-filter"></i> Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tổng doanh thu -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tổng doanh thu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalRevenue, 0, ',', '.') }} đ</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Khoảng thời gian</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ doanh thu -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Biểu đồ doanh thu</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Xuất dữ liệu:</div>
                    <a class="dropdown-item" href="{{ route('admin.reports.exportCSV', ['type' => 'orders', 'start_date' => $startDate, 'end_date' => $endDate]) }}">
                        <i class="fas fa-file-csv fa-sm fa-fw mr-2 text-gray-400"></i>
                        Xuất CSV
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-area">
                <canvas id="revenueChart" style="min-height: 300px;"></canvas>
            </div>
            <hr>
            <div class="small text-muted text-center">
                Biểu đồ doanh thu theo {{ $groupBy == 'day' ? 'ngày' : ($groupBy == 'month' ? 'tháng' : 'năm') }}
            </div>
        </div>
    </div>

    <!-- Bảng dữ liệu -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Dữ liệu chi tiết</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ $groupBy == 'day' ? 'Ngày' : ($groupBy == 'month' ? 'Tháng' : 'Năm') }}</th>
                            <th>Doanh thu</th>
                            <th>Tỷ lệ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result as $key => $value)
                            <tr>
                                <td>
                                    @if($groupBy == 'day')
                                        {{ date('d/m/Y', strtotime($key)) }}
                                    @elseif($groupBy == 'month')
                                        {{ date('m/Y', strtotime($key.'-01')) }}
                                    @else
                                        {{ $key }}
                                    @endif
                                </td>
                                <td>{{ number_format($value, 0, ',', '.') }} đ</td>
                                <td>
                                    @if($totalRevenue > 0)
                                        {{ number_format(($value / $totalRevenue) * 100, 2) }}%
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Flatpickr cho date picker
    flatpickr("#start-date", {
        dateFormat: "Y-m-d",
    });
    
    flatpickr("#end-date", {
        dateFormat: "Y-m-d",
    });
    
    // Chart.js
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($result as $key => $value)
                    @if($groupBy == 'day')
                        '{{ date('d/m', strtotime($key)) }}',
                    @elseif($groupBy == 'month')
                        '{{ date('m/Y', strtotime($key.'-01')) }}',
                    @else
                        '{{ $key }}',
                    @endif
                @endforeach
            ],
            datasets: [{
                label: 'Doanh thu (VND)',
                data: [
                    @foreach($result as $value)
                        {{ $value }},
                    @endforeach
                ],
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
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', { 
                                style: 'currency', 
                                currency: 'VND',
                                maximumFractionDigits: 0
                            }).format(value);
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('vi-VN', { 
                                style: 'currency', 
                                currency: 'VND',
                                maximumFractionDigits: 0
                            }).format(context.raw);
                        }
                    }
                }
            }
        }
    });
</script>
@endsection 