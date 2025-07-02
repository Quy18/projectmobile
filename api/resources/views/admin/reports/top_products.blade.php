@extends('admin.layouts.app')

@section('title', 'Báo Cáo Sản Phẩm Bán Chạy')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Báo Cáo Sản Phẩm Bán Chạy</h1>
        <div>
            <button class="btn btn-sm btn-primary shadow-sm" id="exportPDF">
                <i class="fas fa-download fa-sm text-white-50"></i> Xuất PDF
            </button>
            <a href="{{ route('admin.reports.exportCSV', ['type' => 'products']) }}" class="btn btn-sm btn-success shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Xuất Excel
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Lọc Báo Cáo</h6>
                </div>
                <div class="card-body">
                    <form id="filterForm" method="GET" action="{{ route('admin.reports.topProducts') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="start_date">Từ ngày:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_date">Đến ngày:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="limit">Số lượng hiển thị:</label>
                                <select class="form-control" id="limit" name="limit">
                                    <option value="10" {{ $limit == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ $limit == 20 ? 'selected' : '' }}>20</option>
                                    <option value="50" {{ $limit == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $limit == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Lọc</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Danh Sách Sản Phẩm Bán Chạy</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="topProductsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng bán</th>
                                    <th>Doanh thu</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td class="text-center">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 50px;">
                                        @else
                                            <span class="badge badge-secondary">Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->total_quantity }}</td>
                                    <td>{{ number_format($product->total_amount, 0, ',', '.') }} VNĐ</td>
                                    <td>
                                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Biểu Đồ Sản Phẩm Bán Chạy</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Khởi tạo DataTable
        $('#topProductsTable').DataTable({
            "ordering": true,
            "paging": false,
            "searching": false,
            "info": false
        });

        // Dữ liệu biểu đồ
        var productNames = [];
        var productQuantities = [];
        
        @foreach($topProducts as $product)
            productNames.push("{{ $product->name }}");
            productQuantities.push({{ $product->total_quantity }});
        @endforeach

        var ctx = document.getElementById('topProductsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: productNames,
                datasets: [{
                    label: 'Số lượng bán',
                    data: productQuantities,
                    backgroundColor: 'rgba(78, 115, 223, 0.6)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Xử lý xuất PDF
        $('#exportPDF').click(function() {
            window.print();
        });
    });
</script>
@endsection 