@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa sản phẩm')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
<style>
    .specifications-container .row {
        margin-bottom: 10px;
    }
    .product-images .thumbnail {
        position: relative;
        margin-bottom: 15px;
    }
    .product-images .thumbnail .remove-image {
        position: absolute;
        top: 5px;
        right: 20px;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        width: 24px;
        height: 24px;
        text-align: center;
        line-height: 24px;
        cursor: pointer;
        color: #e74a3b;
    }
    .product-images .thumbnail img {
        height: 120px;
        object-fit: cover;
    }
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #d1d3e2;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa sản phẩm</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
            <li class="breadcrumb-item active">Chỉnh sửa</li>
        </ol>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
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

        <div class="row">
            <div class="col-md-8">
                <!-- Thông tin cơ bản -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin cơ bản</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="slug">Slug <small class="text-muted">(tự động tạo nếu để trống)</small></label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $product->slug) }}">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sku">Mã sản phẩm (SKU)</label>
                                    <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="barcode">Mã vạch</label>
                                    <input type="text" class="form-control" id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Danh mục <span class="text-danger">*</span></label>
                                    <select class="form-control" id="category_id" name="category_id" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @if($category->children && count($category->children) > 0)
                                                @foreach($category->children as $childCategory)
                                                    <option value="{{ $childCategory->id }}" {{ old('category_id', $product->category_id) == $childCategory->id ? 'selected' : '' }}>
                                                        &nbsp;&nbsp;&nbsp;{{ $childCategory->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="brand_id">Thương hiệu</label>
                                    <select class="form-control" id="brand_id" name="brand_id">
                                        <option value="">-- Chọn thương hiệu --</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Mô tả ngắn</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="content">Nội dung chi tiết</label>
                            <textarea class="form-control summernote" id="content" name="content">{{ old('content', $product->content) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Hình ảnh sản phẩm -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Hình ảnh sản phẩm</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h6>Hình ảnh hiện tại</h6>
                                <div class="row product-images">
                                    @if($product->images && $product->images->count() > 0)
                                        @foreach($product->images as $image)
                                            <div class="col-md-3 col-sm-4 col-6 mb-3">
                                                <div class="card h-100">
                                                    <img src="{{ asset('storage/' . $image->image) }}" class="card-img-top" alt="Product Image" style="height: 120px; object-fit: cover;">
                                                    <div class="card-body p-2">
                                                        <div class="custom-control custom-checkbox mb-2">
                                                            <input type="checkbox" class="custom-control-input" id="main_image_id_{{ $image->id }}" name="main_image_id" value="{{ $image->id }}" {{ $image->is_main ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="main_image_id_{{ $image->id }}">Ảnh chính</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="delete_image_{{ $image->id }}" name="deleted_images[]" value="{{ $image->id }}">
                                                            <label class="custom-control-label" for="delete_image_{{ $image->id }}">Xóa ảnh</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                Chưa có hình ảnh nào cho sản phẩm này.
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h6>Thêm hình ảnh mới</h6>
                                <div class="form-group">
                                    <label for="main_image">Hình ảnh chính mới</label>
                                    <input type="file" class="form-control @error('main_image') is-invalid @enderror" id="main_image" name="main_image" accept="image/*">
                                    @error('main_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Nếu bạn tải lên ảnh chính mới, nó sẽ được đặt làm ảnh chính của sản phẩm.</small>
                                    <div class="mt-3" id="main-image-preview"></div>
                                </div>

                                <div class="form-group">
                                    <label for="additional_images">Hình ảnh bổ sung</label>
                                    <input type="file" class="form-control @error('additional_images') is-invalid @enderror" id="additional_images" name="additional_images[]" multiple accept="image/*">
                                    @error('additional_images')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Bạn có thể chọn nhiều hình ảnh cùng lúc để thêm vào sản phẩm.</small>
                                    <div class="mt-3 row" id="additional-images-preview"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông số kỹ thuật -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Thông số kỹ thuật</h6>
                        <button type="button" class="btn btn-sm btn-primary" id="add-specification">
                            <i class="fas fa-plus"></i> Thêm thông số
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="specifications-container">
                            @if($product->specifications && is_array(json_decode($product->specifications, true)))
                                @foreach(json_decode($product->specifications, true) as $key => $value)
                                    <div class="row specification-item">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="specification_keys[]" placeholder="Tên thông số" value="{{ $key }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="specification_values[]" placeholder="Giá trị" value="{{ $value }}">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger btn-sm remove-specification">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row specification-item">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="specification_keys[]" placeholder="Tên thông số">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="specification_values[]" placeholder="Giá trị">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-specification">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Giá và tồn kho -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Giá & Tồn kho</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="price">Giá bán <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" step="1000" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="sale_price">Giá khuyến mãi</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0" step="1000">
                                <div class="input-group-append">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                            <small class="form-text text-muted">Để trống nếu không có khuyến mãi</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="quantity">Số lượng tồn kho <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="weight">Trọng lượng (gram)</label>
                            <input type="number" class="form-control" id="weight" name="weight" value="{{ old('weight', $product->weight) }}" min="0">
                        </div>
                    </div>
                </div>


                <!-- Trạng thái và tùy chọn -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Trạng thái & Tùy chọn</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="active" name="active" value="1" {{ old('active', $product->active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="active">Hiển thị sản phẩm</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="featured" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="featured">Sản phẩm nổi bật</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="new" name="new" value="1" {{ old('new', $product->new) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="new">Sản phẩm mới</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nút lưu -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Lưu sản phẩm
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-block mt-2">
                            <i class="fas fa-arrow-left mr-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        // Khởi tạo Select2
        $('.select2').select2();
        
        // Khởi tạo Summernote cho trình soạn thảo
        $('.summernote').summernote({
            height: 300,
            minHeight: null,
            maxHeight: null,
            focus: false,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        
        // Xử lý tạo slug tự động
        $('#name').on('keyup', function() {
            if (!$('#slug').val()) {
                $('#slug').val(createSlug($(this).val()));
            }
        });
        
        function createSlug(text) {
            return text
                .toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        }
        
        // Xử lý thêm thông số kỹ thuật
        $('#add-specification').on('click', function() {
            var html = `
                <div class="row specification-item">
                    <div class="col-md-5">
                        <div class="form-group">
                            <input type="text" class="form-control" name="specification_keys[]" placeholder="Tên thông số">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" name="specification_values[]" placeholder="Giá trị">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-specification">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('.specifications-container').append(html);
        });
        
        // Xử lý xóa thông số kỹ thuật
        $(document).on('click', '.remove-specification', function() {
            $(this).closest('.specification-item').remove();
        });
        
        // Preview ảnh khi upload
        $('#main_image').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#main-image-preview').html(`
                        <div class="card" style="width: 150px;">
                            <img src="${e.target.result}" class="card-img-top" alt="Preview" style="height: 120px; object-fit: cover;">
                            <div class="card-body p-2">
                                <p class="card-text small text-center">Ảnh chính mới</p>
                            </div>
                        </div>
                    `);
                }
                reader.readAsDataURL(file);
            }
        });
        
        $('#additional_images').on('change', function() {
            const files = this.files;
            $('#additional-images-preview').html('');
            
            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        $('#additional-images-preview').append(`
                            <div class="col-md-3 col-sm-4 col-6 mb-3">
                                <div class="card">
                                    <img src="${e.target.result}" class="card-img-top" alt="Preview" style="height: 120px; object-fit: cover;">
                                </div>
                            </div>
                        `);
                    }
                    
                    reader.readAsDataURL(file);
                }
            }
        });
        
        // Xử lý khi chọn ảnh chính từ ảnh hiện có
        $('input[name="main_image_id"]').on('change', function() {
            if ($(this).is(':checked')) {
                $('input[name="main_image_id"]').not(this).prop('checked', false);
            }
        });
    });
</script>
@endsection 