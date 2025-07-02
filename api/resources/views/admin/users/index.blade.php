@extends('admin.layouts.app')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-3">Quản lý người dùng</h1>
            <p class="text-muted">Danh sách tất cả người dùng trong hệ thống</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Thêm người dùng mới
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5 class="card-title mb-0">Danh sách người dùng</h5>
                </div>
                <div class="col-md-8">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex flex-column flex-md-row gap-2">
                        <div class="input-group flex-grow-1">
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên, email...">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        
                        <select class="form-select" name="role" style="width: auto;" onchange="this.form.submit()">
                            <option value="">Tất cả vai trò</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Khách hàng</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Nhân viên</option>
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
            
            <div class="user-stats d-flex flex-wrap mb-0 px-3 pt-3">
                <div class="user-stat-item me-3 mb-3 py-2 px-3 border rounded bg-light">
                    <div class="text-muted small">Tổng người dùng</div>
                    <div class="fw-bold">{{ $stats['total'] ?? 0 }}</div>
                </div>
                <div class="user-stat-item me-3 mb-3 py-2 px-3 border rounded bg-light">
                    <div class="text-muted small">Quản trị viên</div>
                    <div class="fw-bold text-primary">{{ $stats['admin'] ?? 0 }}</div>
                </div>
                <div class="user-stat-item me-3 mb-3 py-2 px-3 border rounded bg-light">
                    <div class="text-muted small">Khách hàng</div>
                    <div class="fw-bold text-success">{{ $stats['user'] ?? 0 }}</div>
                </div>
                <div class="user-stat-item me-3 mb-3 py-2 px-3 border rounded bg-light">
                    <div class="text-muted small">Nhân viên</div>
                    <div class="fw-bold text-info">{{ $stats['staff'] ?? 0 }}</div>
                </div>
                <div class="user-stat-item me-3 mb-3 py-2 px-3 border rounded bg-light">
                    <div class="text-muted small">Đã xác thực</div>
                    <div class="fw-bold text-success">{{ $stats['verified'] ?? 0 }}</div>
                </div>
                <div class="user-stat-item me-3 mb-3 py-2 px-3 border rounded bg-light">
                    <div class="text-muted small">Chưa xác thực</div>
                    <div class="fw-bold text-warning">{{ $stats['unverified'] ?? 0 }}</div>
                </div>
            </div>
            
            @if(isset($users) && $users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th style="width: 70px;">Ảnh</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th style="width: 150px;">Đã đăng ký</th>
                                <th style="width: 150px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="avatar-wrapper">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="avatar rounded-circle">
                                            @else
                                                <div class="avatar-placeholder rounded-circle">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-dark fw-medium">
                                            {{ $user->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span>{{ $user->email }}</span>
                                        @if($user->email_verified_at)
                                            <i class="fas fa-check-circle text-success ms-1" data-bs-toggle="tooltip" title="Email đã xác thực"></i>
                                        @else
                                            <i class="fas fa-exclamation-circle text-warning ms-1" data-bs-toggle="tooltip" title="Email chưa xác thực"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->isAdmin())
                                            <span class="badge bg-primary">Quản trị viên</span>
                                        @elseif($user->isStaff())
                                            <span class="badge bg-info">Nhân viên</span>
                                        @else
                                            <span class="badge bg-secondary">Khách hàng</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm {{ $user->status == 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}" data-bs-toggle="modal" data-bs-target="#statusModal{{ $user->id }}" title="{{ $user->status == 'active' ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}">
                                                <i class="fas {{ $user->status == 'active' ? 'fa-lock' : 'fa-unlock' }}"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Modal thay đổi trạng thái -->
                                        <div class="modal fade" id="statusModal{{ $user->id }}" tabindex="-1" aria-labelledby="statusModalLabel{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="statusModalLabel{{ $user->id }}">
                                                            {{ $user->status == 'active' ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Bạn có chắc chắn muốn {{ $user->status == 'active' ? 'khóa' : 'mở khóa' }} tài khoản <strong>{{ $user->name }}</strong>?</p>
                                                        
                                                        @if($user->status == 'active')
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                <span>Người dùng sẽ không thể đăng nhập vào hệ thống sau khi bị khóa.</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn {{ $user->status == 'active' ? 'btn-danger' : 'btn-success' }}">
                                                                {{ $user->status == 'active' ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center justify-content-md-between align-items-center flex-wrap px-3 py-3 border-top">
                    <div class="mb-3 mb-md-0 text-secondary">
                        Hiển thị {{ $users->firstItem() ?? 0 }} đến {{ $users->lastItem() ?? 0 }} của {{ $users->total() }} người dùng
                    </div>
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('assets/img/empty-box.svg') }}" alt="Không có dữ liệu" style="max-width: 120px; opacity: 0.5;">
                    <h4 class="text-muted mt-4">Không tìm thấy người dùng nào</h4>
                    <p class="text-muted">Chưa có người dùng nào hoặc không có người dùng phù hợp với bộ lọc đã chọn.</p>
                    @if(request()->query())
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
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
        <form action="{{ route('admin.users.index') }}" method="GET">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Lọc người dùng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Tên, email, số điện thoại...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Vai trò</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">Tất cả vai trò</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Khách hàng</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Nhân viên</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Đã khóa</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="verified" class="form-label">Xác thực email</label>
                        <select class="form-select" id="verified" name="verified">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Đã xác thực</option>
                            <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Chưa xác thực</option>
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
                    
                    <div class="mb-3">
                        <label for="order_by" class="form-label">Sắp xếp theo</label>
                        <select class="form-select" id="order_by" name="order_by">
                            <option value="latest" {{ request('order_by') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="oldest" {{ request('order_by') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                            <option value="name_asc" {{ request('order_by') == 'name_asc' ? 'selected' : '' }}>Tên (A-Z)</option>
                            <option value="name_desc" {{ request('order_by') == 'name_desc' ? 'selected' : '' }}>Tên (Z-A)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    @if(request()->query())
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
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
    .user-stats {
        flex-wrap: nowrap;
        overflow-x: auto;
    }
    
    .user-stat-item {
        min-width: 120px;
    }
    
    .avatar-wrapper {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .avatar {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #6c757d;
        color: #fff;
        font-weight: bold;
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
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo tooltip
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection
