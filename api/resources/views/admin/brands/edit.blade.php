@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa thương hiệu - Quản trị Shop Điện Thoại')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Quản lý thương hiệu</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa thương hiệu</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-edit me-2"></i> Chỉnh sửa thương hiệu: {{ $brand->name }}
            </h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Thông báo lỗi -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <div class="row">
                    <div class="col-md-8">
                        <!-- Thông tin cơ bản -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Thông tin cơ bản</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $brand->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug <span class="text-muted">(tùy chọn)</span></label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $brand->slug) }}" placeholder="Để trống sẽ tự động tạo từ tên">
                                    <small class="text-muted">Chỉ chấp nhận chữ cái, số và dấu gạch ngang, không chứa khoảng trắng hoặc ký tự đặc biệt</small>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="website" class="form-label">Website chính thức <span class="text-muted">(tùy chọn)</span></label>
                                    <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $brand->website) }}" placeholder="https://example.com">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả <span class="text-muted">(tùy chọn)</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $brand->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                      
                    </div>
                    
                    <div class="col-md-4">
                        <!-- Hình ảnh -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Logo thương hiệu</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="image-preview mb-3" id="logoPreview" style="width: 200px; height: 200px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                            @if($brand->logo)
                                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" style="max-width: 100%; max-height: 100%;">
                                            @else
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            @endif
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                                            <small class="text-muted mt-1 d-block">Định dạng hỗ trợ: JPG, JPEG, PNG, GIF. Kích thước tối đa: 2MB</small>
                                            @error('logo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        @if($brand->logo)
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo" value="1">
                                                <label class="form-check-label text-danger" for="remove_logo">
                                                    Xóa logo hiện tại
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Trạng thái và hiển thị -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Trạng thái & hiển thị</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="active" class="form-label d-block">Trạng thái</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="active" id="activeYes" value="1" {{ old('active', $brand->active) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="activeYes">Kích hoạt</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="active" id="activeNo" value="0" {{ old('active', $brand->active) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="activeNo">Vô hiệu hóa</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="featured" class="form-label d-block">Hiển thị nổi bật</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="featured" id="featuredYes" value="1" {{ old('featured', $brand->featured) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="featuredYes">Có</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="featured" id="featuredNo" value="0" {{ old('featured', $brand->featured) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="featuredNo">Không</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="display_order" class="form-label">Thứ tự hiển thị</label>
                                    <input type="number" class="form-control @error('display_order') is-invalid @enderror" id="display_order" name="display_order" value="{{ old('display_order', $brand->display_order) }}" min="0">
                                    <small class="text-muted">Giá trị cao hơn sẽ hiển thị trước (mặc định: 0)</small>
                                    @error('display_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thông tin thêm -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Thông tin thêm</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-0">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between px-0">
                                            <span>Số lượng sản phẩm:</span>
                                            <span class="fw-semibold">{{ $brand->products_count ?? 0 }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between px-0">
                                            <span>Ngày tạo:</span>
                                            <span>{{ $brand->created_at->format('d/m/Y H:i') }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between px-0">
                                            <span>Cập nhật cuối:</span>
                                            <span>{{ $brand->updated_at->format('d/m/Y H:i') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Nút submit -->
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-1"></i> Hủy bỏ
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tự động tạo slug từ tên
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        
        if (nameInput && slugInput) {
            nameInput.addEventListener('keyup', function() {
                if (!slugInput.dataset.originalValue) {
                    slugInput.dataset.originalValue = slugInput.value;
                }
                
                // Chỉ tự động cập nhật nếu người dùng chưa chỉnh sửa slug
                if (slugInput.value === slugInput.dataset.originalValue) {
                    slugInput.value = generateSlug(nameInput.value);
                }
            });
        }
        
        // Hàm tạo slug
        function generateSlug(text) {
            return text
                .toLowerCase()
                .replace(/[^\w\s-]/g, '') // Loại bỏ ký tự đặc biệt
                .replace(/\s+/g, '-') // Thay thế khoảng trắng bằng dấu gạch ngang
                .replace(/--+/g, '-') // Loại bỏ nhiều dấu gạch ngang liên tiếp
                .trim();
        }
        
        // Hiển thị preview logo
        const logoInput = document.getElementById('logo');
        const logoPreview = document.getElementById('logoPreview');
        const removeLogoCheck = document.getElementById('remove_logo');
        
        if (logoInput && logoPreview) {
            logoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        logoPreview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview" style="max-width: 100%; max-height: 100%;">`;
                        
                        // Nếu có checkbox xóa logo, tự động bỏ chọn khi người dùng tải lên logo mới
                        if (removeLogoCheck) {
                            removeLogoCheck.checked = false;
                        }
                    };
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            // Xử lý khi checkbox xóa logo được chọn
            if (removeLogoCheck) {
                removeLogoCheck.addEventListener('change', function() {
                    if (this.checked) {
                        logoPreview.innerHTML = `<i class="fas fa-image fa-3x text-muted"></i>`;
                        logoInput.value = '';
                    } else {
                        // Khôi phục lại logo hiện tại nếu có
                        const currentLogo = logoPreview.querySelector('img');
                        if (!currentLogo) {
                            const brandLogo = "{{ $brand->logo ? asset('storage/' . $brand->logo) : '' }}";
                            const brandName = "{{ $brand->name }}";
                            if (brandLogo) {
                                logoPreview.innerHTML = `<img src="${brandLogo}" alt="${brandName}" style="max-width: 100%; max-height: 100%;">`;
                            }
                        }
                    }
                });
            }
        }
    });
</script>
@endsection
@endsection 