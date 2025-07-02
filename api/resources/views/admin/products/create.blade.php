@extends('admin.layouts.app')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-3">Thêm sản phẩm mới</h1>
            <p class="text-muted">Tạo sản phẩm mới với đầy đủ thông tin chi tiết</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <div>
                    <h5 class="alert-heading">Vui lòng kiểm tra lại thông tin!</h5>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <!-- Thông tin cơ bản -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin cơ bản</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Tên hiển thị của sản phẩm trong cửa hàng.</small>
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">URL thân thiện của sản phẩm (để trống để tự động tạo từ tên).</small>
                        </div>

                        <div class="mb-3">
                            <label for="sku" class="form-label">Mã sản phẩm (SKU) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku') }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Mã định danh duy nhất của sản phẩm.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories ?? [] as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @if($category->children && $category->children->count() > 0)
                                            @foreach($category->children as $childCategory)
                                                <option value="{{ $childCategory->id }}" {{ old('category_id') == $childCategory->id ? 'selected' : '' }}>
                                                    -- {{ $childCategory->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="brand_id" class="form-label">Thương hiệu</label>
                                <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id">
                                    <option value="">-- Chọn thương hiệu --</option>
                                    @foreach($brands ?? [] as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Giá gốc (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" step="1000" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="discount_price" class="form-label">Giá khuyến mãi (VNĐ)</label>
                                <input type="number" class="form-control @error('discount_price') is-invalid @enderror" id="discount_price" name="discount_price" value="{{ old('discount_price') }}" min="0" step="1000">
                                @error('discount_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Để trống nếu không có khuyến mãi.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', 0) }}" min="0" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="weight" class="form-label">Khối lượng (gram)</label>
                                <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight') }}" min="0">
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mô tả sản phẩm -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Mô tả sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="short_description" class="form-label">Mô tả ngắn</label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="3">{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Tóm tắt ngắn gọn về sản phẩm, sẽ hiển thị trong danh sách sản phẩm.</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả chi tiết</label>
                            <textarea class="form-control editor @error('description') is-invalid @enderror" id="description" name="description" rows="10">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Mô tả đầy đủ về sản phẩm, có thể sử dụng định dạng HTML.</small>
                        </div>
                    </div>
                </div>

                <!-- Thông số kỹ thuật -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Thông số kỹ thuật</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="addSpecification">
                            <i class="fas fa-plus me-1"></i> Thêm thông số
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="specifications-container">
                            @if(old('specifications'))
                                @foreach(old('specifications.name') as $index => $name)
                                    <div class="specification-item mb-3 border-bottom pb-3">
                                        <div class="row">
                                            <div class="col-md-5 mb-2 mb-md-0">
                                                <label class="form-label">Tên thông số</label>
                                                <input type="text" class="form-control" name="specifications[name][]" value="{{ $name }}" placeholder="Ví dụ: RAM, CPU, ...">
                                            </div>
                                            <div class="col-md-5 mb-2 mb-md-0">
                                                <label class="form-label">Giá trị</label>
                                                <input type="text" class="form-control" name="specifications[value][]" value="{{ old('specifications.value')[$index] }}" placeholder="Ví dụ: 8GB, Snapdragon 8 Gen 2, ...">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end mb-2 mb-md-0">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-specification">
                                                    <i class="fas fa-times"></i> Xóa
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div id="no-specifications" class="{{ old('specifications') ? 'd-none' : '' }}">
                            <p class="text-muted fst-italic">Chưa có thông số kỹ thuật nào. Nhấn "Thêm thông số" để bắt đầu thêm.</p>
                        </div>
                    </div>
                </div>

               
            </div>

            <div class="col-md-4">
                <!-- Trạng thái & Hiển thị -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Trạng thái & Hiển thị</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label d-block">Trạng thái</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="active" id="active1" value="1" {{ old('active', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="active1">Đang bán</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="active" id="active0" value="0" {{ old('active') == '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="active0">Dừng bán</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="featured">Sản phẩm nổi bật</label>
                            </div>
                            <small class="form-text text-muted">Sản phẩm nổi bật sẽ được hiển thị ở trang chủ và các vị trí đặc biệt.</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_new" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_new">Sản phẩm mới</label>
                            </div>
                            <small class="form-text text-muted">Sản phẩm mới sẽ được hiển thị nhãn "Mới" và xuất hiện trong danh sách sản phẩm mới.</small>
                        </div>
                    </div>
                </div>

                <!-- Hình ảnh -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Hình ảnh sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="main_image" class="form-label">Hình ảnh chính <span class="text-danger">*</span></label>
                            <input class="form-control @error('main_image') is-invalid @enderror" type="file" id="main_image" name="main_image" accept="image/*">
                            @error('main_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">File ảnh có kích thước tối đa 2MB và định dạng JPG, JPEG, PNG hoặc GIF.</small>
                            <div class="mt-3" id="main-image-preview"></div>
                        </div>

                        <div class="mb-3">
                            <label for="additional_images" class="form-label">Hình ảnh bổ sung</label>
                            <input class="form-control @error('additional_images') is-invalid @enderror" type="file" id="additional_images" name="additional_images[]" multiple accept="image/*">
                            @error('additional_images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Bạn có thể chọn nhiều hình ảnh cùng lúc. Mỗi file có kích thước tối đa 2MB.</small>
                            <div class="mt-3 row g-2" id="additional-images-preview"></div>
                        </div>
                    </div>
                </div>

                <!-- Nút lưu -->
                <div class="d-grid gap-2 mb-4">
                    <button type="submit" class="btn btn-lg btn-primary">
                        <i class="fas fa-save me-1"></i> Lưu sản phẩm
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Hủy
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('styles')
<style>
    .specification-item:last-child {
        border-bottom: none !important;
        padding-bottom: 0 !important;
    }
    
    #main-image-preview, #additional-images-preview {
        min-height: 40px;
    }
    
    .preview-image-wrapper {
        position: relative;
        height: 100px;
        width: 100%;
        overflow: hidden;
        border-radius: 4px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .preview-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo TinyMCE
        tinymce.init({
            selector: '.editor',
            height: 400,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | \
                alignleft aligncenter alignright alignjustify | \
                bullist numlist outdent indent | removeformat | help'
        });
        
        // Tự động tạo slug từ tên
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        
        if (nameInput && slugInput) {
            nameInput.addEventListener('keyup', function() {
                if (!slugInput.value) {
                    slugInput.value = generateSlug(nameInput.value);
                }
            });
        }
        
        function generateSlug(text) {
            return text
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[đĐ]/g, 'd')
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
        
        // Xử lý thêm/xóa thông số kỹ thuật
        const specificationsContainer = document.getElementById('specifications-container');
        const noSpecifications = document.getElementById('no-specifications');
        const addSpecificationBtn = document.getElementById('addSpecification');
        
        if (addSpecificationBtn) {
            addSpecificationBtn.addEventListener('click', function() {
                addSpecificationRow();
            });
        }
        
        function addSpecificationRow() {
            const html = `
                <div class="specification-item mb-3 border-bottom pb-3">
                    <div class="row">
                        <div class="col-md-5 mb-2 mb-md-0">
                            <label class="form-label">Tên thông số</label>
                            <input type="text" class="form-control" name="specifications[name][]" placeholder="Ví dụ: RAM, CPU, ...">
                        </div>
                        <div class="col-md-5 mb-2 mb-md-0">
                            <label class="form-label">Giá trị</label>
                            <input type="text" class="form-control" name="specifications[value][]" placeholder="Ví dụ: 8GB, Snapdragon 8 Gen 2, ...">
                        </div>
                        <div class="col-md-2 d-flex align-items-end mb-2 mb-md-0">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-specification">
                                <i class="fas fa-times"></i> Xóa
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            specificationsContainer.insertAdjacentHTML('beforeend', html);
            noSpecifications.classList.add('d-none');
            
            // Thêm event listener cho nút xóa
            const removeButtons = document.querySelectorAll('.remove-specification');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.specification-item').remove();
                    if (specificationsContainer.children.length === 0) {
                        noSpecifications.classList.remove('d-none');
                    }
                });
            });
        }
        
        // Xử lý xóa thông số kỹ thuật cho các mục đã có sẵn
        const existingRemoveButtons = document.querySelectorAll('.remove-specification');
        existingRemoveButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.specification-item').remove();
                if (specificationsContainer.children.length === 0) {
                    noSpecifications.classList.remove('d-none');
                }
            });
        });
        
        // Preview hình ảnh khi upload
        const mainImageInput = document.getElementById('main_image');
        const mainImagePreview = document.getElementById('main-image-preview');
        const additionalImagesInput = document.getElementById('additional_images');
        const additionalImagesPreview = document.getElementById('additional-images-preview');
        
        if (mainImageInput) {
            mainImageInput.addEventListener('change', function() {
                previewMainImage(this);
            });
        }
        
        if (additionalImagesInput) {
            additionalImagesInput.addEventListener('change', function() {
                previewAdditionalImages(this);
            });
        }
        
        function previewMainImage(input) {
            mainImagePreview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const html = `
                        <div class="preview-image-wrapper">
                            <img src="${e.target.result}" class="preview-image" alt="Preview">
                        </div>
                    `;
                    mainImagePreview.innerHTML = html;
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function previewAdditionalImages(input) {
            additionalImagesPreview.innerHTML = '';
            
            if (input.files) {
                for (let i = 0; i < input.files.length; i++) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const html = `
                            <div class="col-4 col-md-6">
                                <div class="preview-image-wrapper">
                                    <img src="${e.target.result}" class="preview-image" alt="Preview">
                                </div>
                            </div>
                        `;
                        additionalImagesPreview.insertAdjacentHTML('beforeend', html);
                    }
                    
                    reader.readAsDataURL(input.files[i]);
                }
            }
        }
    });
</script>
@endsection 