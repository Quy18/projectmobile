@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa người dùng')

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa người dùng</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
            <li class="breadcrumb-item active">Chỉnh sửa</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Thông tin người dùng -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin người dùng</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Thông tin cơ bản -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Họ tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Mật khẩu <small class="text-muted">(để trống nếu không thay đổi)</small></label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Vai trò <span class="text-danger">*</span></label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Người dùng</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                                        <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Nhân viên</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="avatar">Ảnh đại diện</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="avatar" name="avatar" accept="image/*">
                                        <label class="custom-file-label" for="avatar">Chọn file mới</label>
                                    </div>
                                    <small class="form-text text-muted">Cho phép JPG, PNG. Tối đa 2MB.</small>
                                    
                                    @if($user->avatar)
                                        <div class="mt-2">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="img-thumbnail mr-2" style="height: 50px; width: 50px; object-fit: cover;">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="remove_avatar" name="remove_avatar" value="1">
                                                    <label class="custom-control-label" for="remove_avatar">Xóa ảnh hiện tại</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="active" name="active" value="1" {{ old('active', $user->active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="active">Kích hoạt tài khoản</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nút submit -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Lưu thay đổi
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Tóm tắt người dùng -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tóm tắt hoạt động</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Ngày tạo:</span>
                        <span>{{ $user->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Cập nhật:</span>
                        <span>{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Đơn hàng:</span>
                        <span class="badge badge-info">{{ $user->orders_count ?? 0 }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Đánh giá:</span>
                        <span class="badge badge-info">{{ $user->reviews_count ?? 0 }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Tổng chi tiêu:</span>
                        <span>{{ number_format($user->total_spent ?? 0, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Trạng thái:</span>
                        <span class="badge {{ $user->active ? 'badge-success' : 'badge-danger' }}">
                            {{ $user->active ? 'Đang hoạt động' : 'Đã khóa' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Thay đổi mật khẩu -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Đặt lại mật khẩu</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <button type="submit" class="btn btn-warning btn-block" onclick="return confirm('Bạn có chắc chắn muốn đặt lại mật khẩu cho người dùng này?')">
                                <i class="fas fa-key mr-1"></i> Đặt lại mật khẩu
                            </button>
                            <small class="form-text text-muted">
                                Hệ thống sẽ tạo mật khẩu ngẫu nhiên và gửi đến email của người dùng.
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Hiển thị tên file khi chọn ảnh
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
</script>
@endsection 