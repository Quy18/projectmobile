@extends('admin.layouts.app')

@section('title', 'Chi tiết đánh giá')

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết đánh giá</h1>
        <div>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại
            </a>
            @if($review->status == 'pending')
                <button type="button" class="btn btn-sm btn-success" 
                        onclick="updateStatus('approved')">
                    <i class="fas fa-check mr-1"></i> Duyệt
                </button>
                <button type="button" class="btn btn-sm btn-danger" 
                        onclick="updateStatus('rejected')">
                    <i class="fas fa-ban mr-1"></i> Từ chối
                </button>
            @elseif($review->status == 'approved')
                <button type="button" class="btn btn-sm btn-warning" 
                        onclick="updateStatus('pending')">
                    <i class="fas fa-undo mr-1"></i> Đặt về chờ duyệt
                </button>
            @elseif($review->status == 'rejected')
                <button type="button" class="btn btn-sm btn-warning" 
                        onclick="updateStatus('pending')">
                    <i class="fas fa-undo mr-1"></i> Đặt về chờ duyệt
                </button>
            @endif
            <button type="button" class="btn btn-sm btn-danger" 
                    onclick="confirmDelete()">
                <i class="fas fa-trash mr-1"></i> Xóa
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin cơ bản -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đánh giá</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3 text-center">
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star fa-2x text-warning"></i>
                                @else
                                    <i class="far fa-star fa-2x text-warning"></i>
                                @endif
                            @endfor
                        </div>
                        <h4 class="mb-0">{{ $review->rating }}/5</h4>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width: 120px;">ID:</th>
                                <td>{{ $review->id }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    @if($review->status == 'pending')
                                        <span class="badge badge-warning">Chờ duyệt</span>
                                    @elseif($review->status == 'approved')
                                        <span class="badge badge-success">Đã duyệt</span>
                                    @elseif($review->status == 'rejected')
                                        <span class="badge badge-danger">Từ chối</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $review->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Thời gian:</th>
                                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Cập nhật:</th>
                                <td>{{ $review->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin người dùng -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Người dùng</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if($review->user->avatar)
                            <img src="{{ asset('storage/' . $review->user->avatar) }}" alt="{{ $review->user->name }}" class="rounded-circle mr-3" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=4e73df&color=ffffff&size=50'">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=4e73df&color=ffffff&size=50" alt="{{ $review->user->name }}" class="rounded-circle mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                        @endif
                        <div>
                            <h6 class="mb-0">{{ $review->user->name }}</h6>
                            <small class="text-muted">{{ $review->user->email }}</small>
                        </div>
                    </div>
                    <div class="mb-0">
                        <a href="{{ route('admin.users.show', $review->user->id) }}" class="btn btn-sm btn-outline-primary btn-block">
                            <i class="fas fa-user mr-1"></i> Xem thông tin người dùng
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chi tiết đánh giá và sản phẩm -->
        <div class="col-lg-8">
            <!-- Nội dung đánh giá -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Nội dung đánh giá</h6>
                </div>
                <div class="card-body">
                    <div class="p-3 bg-light mb-3 rounded">
                        {{ $review->comment }}
                    </div>

                    @if($review->admin_response)
                        <div class="border-top pt-3 mt-3">
                            <h6 class="font-weight-bold mb-2"><i class="fas fa-reply text-primary mr-1"></i> Phản hồi của admin</h6>
                            <div class="p-3 bg-white border rounded">
                                <p class="mb-1">{{ $review->admin_response }}</p>
                                <small class="text-muted">Phản hồi lúc: {{ $review->admin_response_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    @endif

                    <div class="mt-3 {{ $review->admin_response ? 'border-top pt-3' : '' }}">
                        <h6 class="font-weight-bold mb-2">
                            {{ $review->admin_response ? 'Chỉnh sửa phản hồi' : 'Thêm phản hồi' }}
                        </h6>
                        <form id="replyForm">
                            <div class="form-group">
                                <textarea class="form-control" id="adminResponse" rows="3" placeholder="Nhập phản hồi của bạn...">{{ $review->admin_response }}</textarea>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="submitReply()">
                                <i class="fas fa-paper-plane mr-1"></i> 
                                {{ $review->admin_response ? 'Cập nhật phản hồi' : 'Gửi phản hồi' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin sản phẩm -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sản phẩm</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            @if($review->product && $review->product->images->first())
                                <img src="{{ asset('storage/' . $review->product->images->first()->image) }}" alt="{{ $review->product->name }}" class="img-fluid mb-3 border rounded" style="width: 100%; height: 120px; object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('assets/img/product-placeholder.jpg') }}'">
                            @else
                                <img src="{{ asset('assets/img/product-placeholder.jpg') }}" alt="Sản phẩm" class="img-fluid mb-3 border rounded" style="width: 100%; height: 120px; object-fit: cover;">
                            @endif
                        </div>
                        <div class="col-md-9">
                            @if($review->product)
                                <h5 class="mb-1">{{ $review->product->name }}</h5>
                                <p class="small text-muted mb-2">
                                    SKU: {{ $review->product->sku }}
                                </p>
                                <p class="mb-2">
                                    <strong>Giá:</strong> {{ number_format($review->product->price, 0, ',', '.') }}đ
                                    @if($review->product->sale_price)
                                        <del class="text-muted ml-2">{{ number_format($review->product->price, 0, ',', '.') }}đ</del>
                                    @endif
                                </p>
                                <a href="{{ route('admin.products.show', $review->product->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-box mr-1"></i> Xem sản phẩm
                                </a>
                            @else
                                <div class="text-muted text-center py-3">
                                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                    <p>Sản phẩm không còn tồn tại</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST">
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
    function confirmDelete() {
        $('#deleteModal').modal('show');
    }
    
    // Hàm cập nhật trạng thái đánh giá
    function updateStatus(status) {
        if (!confirm(`Bạn có chắc chắn muốn ${status === 'approved' ? 'duyệt' : (status === 'rejected' ? 'từ chối' : 'đặt lại')} đánh giá này không?`)) {
            return;
        }
        
        const url = `{{ route('admin.reviews.updateStatus', $review) }}`;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
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
            if (data.success) {
                window.location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi cập nhật trạng thái');
        });
    }
    
    // Hàm gửi phản hồi đánh giá
    function submitReply() {
        const adminResponse = document.getElementById('adminResponse').value;
        
        if (!adminResponse.trim()) {
            alert('Vui lòng nhập nội dung phản hồi');
            return;
        }
        
        const url = `{{ route('admin.reviews.reply', $review) }}`;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ admin_response: adminResponse })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi gửi phản hồi');
        });
    }
</script>
@endsection

@section('styles')
<style>
    .badge {
        font-size: 85%;
    }
    
    .table th {
        background-color: #f8f9fc;
    }
</style>
@endsection 