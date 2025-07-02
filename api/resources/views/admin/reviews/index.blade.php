@extends('admin.layouts.app')

@section('title', 'Quản lý đánh giá')

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý đánh giá</h1>
    </div>

    <!-- Card chứa bộ lọc -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Bộ lọc</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Tùy chọn:</div>
                    <a class="dropdown-item" href="{{ route('admin.reviews.index') }}">Xóa bộ lọc</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reviews.index') }}" method="GET" id="filter-form">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="product">Sản phẩm</label>
                        <select class="form-control" id="product" name="product_id">
                            <option value="">Tất cả sản phẩm</option>
                            @foreach(\App\Models\Product::orderBy('name')->get() as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="user">Người dùng</label>
                        <select class="form-control" id="user" name="user_id">
                            <option value="">Tất cả người dùng</option>
                            @foreach(\App\Models\User::orderBy('name')->get() as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="rating">Đánh giá</label>
                        <select class="form-control" id="rating" name="rating">
                            <option value="">Tất cả</option>
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} sao
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status">Trạng thái</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Tất cả</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-filter"></i> Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Card chứa bảng dữ liệu -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách đánh giá</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người dùng</th>
                            <th>Sản phẩm</th>
                            <th>Đánh giá</th>
                            <th>Bình luận</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>{{ $review->id }}</td>
                                <td>{{ $review->user->name ?? 'N/A' }}</td>
                                <td>{{ $review->product->name ?? 'N/A' }}</td>
                                <td>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                    ({{ $review->rating }})
                                </td>
                                <td>{{ Str::limit($review->comment, 50) }}</td>
                                <td>
                                    @if($review->status == 'pending')
                                        <span class="badge text-white" style="background-color: #f8961e; font-size: 0.9rem; padding: 0.5em 0.75em;">Chờ duyệt</span>
                                    @elseif($review->status == 'approved')
                                        <span class="badge text-white" style="background-color: #0bb363; font-size: 0.9rem; padding: 0.5em 0.75em;">Đã duyệt</span>
                                    @elseif($review->status == 'rejected')
                                        <span class="badge text-white" style="background-color: #ef476f; font-size: 0.9rem; padding: 0.5em 0.75em;">Từ chối</span>
                                    @else
                                        <span class="badge text-white" style="background-color: #6c757d; font-size: 0.9rem; padding: 0.5em 0.75em;">{{ $review->status }}</span>
                                    @endif
                                </td>
                                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-info btn-sm mb-1" style="color: white; cursor: pointer;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($review->status == 'pending')
                                        <button type="button" class="btn btn-success btn-sm mb-1 review-action-btn" 
                                                onclick="updateStatus({{ $review->id }}, 'approved');" style="cursor: pointer;">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm mb-1 review-action-btn" 
                                                onclick="updateStatus({{ $review->id }}, 'rejected');" style="cursor: pointer;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-warning btn-sm mb-1 review-action-btn" 
                                                onclick="updateStatus({{ $review->id }}, 'pending');" style="cursor: pointer;">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    @endif
                                    
                                    <button type="button" class="btn btn-danger btn-sm mb-1 review-action-btn" 
                                            onclick="confirmDelete({{ $review->id }});" style="cursor: pointer;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Không có đánh giá nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $reviews->withQueryString()->links() }}
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa đánh giá này không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Hàm xác nhận xóa đánh giá
    function confirmDelete(reviewId) {
        const form = document.getElementById('deleteForm');
        form.action = `{{ url('admin/reviews') }}/${reviewId}`;
        $('#deleteModal').modal('show');
    }
    
    // Hàm cập nhật trạng thái đánh giá
    function updateStatus(reviewId, status) {
        if (!confirm(`Bạn có chắc chắn muốn ${status === 'approved' ? 'duyệt' : (status === 'rejected' ? 'từ chối' : 'đặt lại')} đánh giá này không?`)) {
            return;
        }
        
        const url = `{{ url('admin/reviews') }}/${reviewId}/status`;
        
        // Lấy CSRF token an toàn hơn
        let token = "";
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (tokenMeta) {
            token = tokenMeta.getAttribute('content');
        } else {
            // Nếu không tìm thấy meta tag, hiển thị thông báo lỗi
            alert('Không tìm thấy CSRF token. Vui lòng tải lại trang và thử lại.');
            return;
        }
        
        // Hiển thị hiệu ứng đang tải
        const loadingOverlay = document.createElement('div');
        loadingOverlay.style.position = 'fixed';
        loadingOverlay.style.top = '0';
        loadingOverlay.style.left = '0';
        loadingOverlay.style.width = '100%';
        loadingOverlay.style.height = '100%';
        loadingOverlay.style.backgroundColor = 'rgba(0,0,0,0.3)';
        loadingOverlay.style.display = 'flex';
        loadingOverlay.style.justifyContent = 'center';
        loadingOverlay.style.alignItems = 'center';
        loadingOverlay.style.zIndex = '9999';
        loadingOverlay.innerHTML = '<div class="spinner-border text-light" role="status"><span class="sr-only">Đang xử lý...</span></div>';
        document.body.appendChild(loadingOverlay);
        
        fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            // Xóa hiệu ứng đang tải
            document.body.removeChild(loadingOverlay);
            
            if (data.success) {
                // Hiển thị thông báo thành công
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-dismissible fade show';
                successAlert.style.position = 'fixed';
                successAlert.style.top = '20px';
                successAlert.style.right = '20px';
                successAlert.style.zIndex = '9999';
                successAlert.innerHTML = `
                    <strong>Thành công!</strong> ${data.message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                `;
                document.body.appendChild(successAlert);
                
                // Tự động tải lại trang sau 1 giây
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.body.removeChild(loadingOverlay);
            alert('Có lỗi xảy ra khi cập nhật trạng thái');
        });
    }
    
    // Cải thiện select box
    $(document).ready(function() {
        $('#product, #user').select2({
            placeholder: 'Chọn...',
        });
        
        // Thêm hiệu ứng hover cho các nút thao tác
        $(".review-action-btn").hover(
            function() {
                $(this).css("transform", "scale(1.1)");
            },
            function() {
                $(this).css("transform", "scale(1)");
            }
        );
    });
</script>

<style>
    /* Cải thiện độ tương phản và hiển thị của badges */
    .badge {
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Cải thiện nút thao tác */
    .review-action-btn {
        transition: all 0.3s ease;
        margin-right: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Đảm bảo các nút thao tác có màu chữ trắng */
    .btn-info, .btn-success, .btn-danger, .btn-warning {
        color: white !important;
    }
    
    /* Khoảng cách giữa các hàng trong bảng */
    .table tbody tr {
        transition: background-color 0.3s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0,0,0,0.025);
    }
</style>
@endsection
