@extends('admin.layouts.app')

@section('title', $product->name . ' - Chi tiết sản phẩm')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-2">{{ $product->name }}</h1>
            <p class="text-muted mb-0">
                <span class="badge {{ $product->active ? 'bg-success' : 'bg-danger' }}">
                    {{ $product->active ? 'Đang bán' : 'Dừng bán' }}
                </span>
                <span class="mx-2">•</span>
                <span>SKU: {{ $product->sku }}</span>
                <span class="mx-2">•</span>
                <span>Đã xem: {{ $product->view_count ?? 0 }}</span>
            </p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="btn-group">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Chỉnh sửa
                </a>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash me-1"></i> Xóa
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Thông tin cơ bản -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Thông tin sản phẩm</h5>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-1"></i> Sửa
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="product-main-image">
                                @php
                                    $mainImage = $product->images()->where('is_main', true)->first();
                                @endphp
                                @if($mainImage)
                                    <img src="{{ asset('storage/' . $mainImage->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                                @else
                                    <div class="product-no-image">
                                        <i class="fas fa-image fa-4x text-secondary"></i>
                                        <p class="mt-2 text-muted">Không có hình ảnh</p>
                                    </div>
                                @endif
                            </div>

                            @if($product->images && $product->images->count() > 0)
                                <div class="product-gallery mt-3">
                                    <div class="row g-2">
                                        @foreach($product->images as $image)
                                            <div class="col-3">
                                                <img src="{{ asset('storage/' . $image->image) }}" class="img-thumbnail" alt="{{ $product->name }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-7">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <th class="text-secondary" style="width: 140px;">Tên sản phẩm:</th>
                                        <td class="fw-medium">{{ $product->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-secondary">Slug:</th>
                                        <td>{{ $product->slug }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-secondary">Mã sản phẩm:</th>
                                        <td>{{ $product->sku }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-secondary">Danh mục:</th>
                                        <td>
                                            @if($product->category)
                                                <a href="{{ route('admin.categories.show', $product->category_id) }}" class="badge bg-secondary text-decoration-none">
                                                    {{ $product->category->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">Chưa phân loại</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-secondary">Thương hiệu:</th>
                                        <td>
                                            @if($product->brand)
                                                <a href="{{ route('admin.brands.show', $product->brand_id) }}" class="badge bg-secondary text-decoration-none">
                                                    {{ $product->brand->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">Không có</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-secondary">Giá gốc:</th>
                                        <td class="fw-semibold">{{ number_format($product->price, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                    <tr>
                                        <th class="text-secondary">Giá khuyến mãi:</th>
                                        <td>
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <span class="text-danger">{{ number_format($product->sale_price, 0, ',', '.') }} VNĐ</span>
                                                <span class="text-muted text-decoration-line-through ms-2">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                                                @php
                                                $discountPercent = round((($product->price - $product->sale_price) / $product->price) * 100);
                                                @endphp
                                                <span class="badge bg-danger ms-1">-{{ $discountPercent }}%</span>
                                            @else
                                                <span class="text-muted">Không có</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-secondary">Tồn kho:</th>
                                        <td>
                                            @if($product->quantity > 10)
                                                <span class="badge bg-success">{{ $product->quantity }} sản phẩm</span>
                                            @elseif($product->quantity > 0)
                                                <span class="badge bg-warning text-dark">Sắp hết hàng ({{ $product->quantity }})</span>
                                            @else
                                                <span class="badge bg-danger">Hết hàng</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-secondary">Trạng thái:</th>
                                        <td>
                                            @if($product->active)
                                                <span class="badge bg-success">Đang bán</span>
                                            @else
                                                <span class="badge bg-danger">Dừng bán</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-secondary">Đặc điểm:</th>
                                        <td>
                                            @if($product->featured)
                                                <span class="badge bg-warning text-dark">Nổi bật</span>
                                            @endif
                                            
                                            @if($product->is_new)
                                                <span class="badge bg-info text-dark ms-1">Mới</span>
                                            @endif
                                            
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <span class="badge bg-danger ms-1">Giảm giá</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-secondary">Thời gian:</th>
                                        <td>
                                            <div>Tạo: {{ $product->created_at->format('d/m/Y H:i') }}</div>
                                            <div>Cập nhật: {{ $product->updated_at->format('d/m/Y H:i') }}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mô tả sản phẩm -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Mô tả sản phẩm</h5>
                </div>
                <div class="card-body">
                    @if($product->description)
                        <div class="product-description">
                            {!! $product->description !!}
                        </div>
                    @else
                        <p class="text-muted fst-italic">Chưa có mô tả cho sản phẩm này.</p>
                    @endif
                </div>
            </div>

            <!-- Thông số kỹ thuật -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông số kỹ thuật</h5>
                </div>
                <div class="card-body">
                    @if($product->specifications && is_array(json_decode($product->specifications, true)))
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach(json_decode($product->specifications, true) as $key => $value)
                                        <tr>
                                            <th style="width: 30%;">{{ $key }}</th>
                                            <td>{{ $value }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted fst-italic">Chưa có thông số kỹ thuật cho sản phẩm này.</p>
                    @endif
                </div>
            </div>

            <!-- Đánh giá sản phẩm -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Đánh giá từ khách hàng</h5>
                    <span class="badge bg-secondary">{{ $product->reviews_count ?? 0 }} đánh giá</span>
                </div>
                <div class="card-body">
                    @if(isset($product->reviews) && $product->reviews->count() > 0)
                        <div class="product-rating mb-4">
                            <div class="rating-summary d-flex align-items-center">
                                <div class="rating-average me-3">
                                    <span class="display-4 fw-bold">{{ number_format($product->reviews_avg_rating ?? 0, 1) }}</span>
                                    <div class="text-muted">trên 5</div>
                                </div>
                                <div class="rating-stars fs-3 text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($product->reviews_avg_rating ?? 0))
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= $product->reviews_avg_rating)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <div class="reviews-list">
                            @foreach($product->reviews as $review)
                                <div class="review-item mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="fw-bold">{{ $review->user->name }}</span>
                                            <div class="text-muted small">{{ $review->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                        <div class="review-rating text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="mb-1">{{ $review->comment }}</p>
                                    
                                    @if($review->images && count($review->images) > 0)
                                        <div class="review-images mt-2">
                                            <div class="row g-2">
                                                @foreach($review->images as $image)
                                                    <div class="col-2">
                                                        <a href="{{ asset('storage/' . $image->path) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $image->path) }}" class="img-thumbnail" alt="Review image">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($review->admin_response)
                                        <div class="admin-response mt-2 ms-4 p-2 bg-light rounded">
                                            <div class="fw-semibold text-primary small">Phản hồi từ quản trị viên:</div>
                                            <p class="mb-0 small">{{ $review->admin_response }}</p>
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#replyModal{{ $review->id }}">
                                                <i class="fas fa-reply me-1"></i> Phản hồi
                                            </button>
                                            
                                            <!-- Modal phản hồi -->
                                            <div class="modal fade" id="replyModal{{ $review->id }}" tabindex="-1" aria-labelledby="replyModalLabel{{ $review->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('admin.reviews.reply', $review->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="replyModalLabel{{ $review->id }}">Phản hồi đánh giá</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="admin_response" class="form-label">Nội dung phản hồi</label>
                                                                    <textarea class="form-control" id="admin_response" name="admin_response" rows="3" required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted fst-italic">Sản phẩm này chưa có đánh giá nào.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Tóm tắt sản phẩm -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Tóm tắt</h5>
                </div>
                <div class="card-body">
                    <div class="summary-item d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Đơn đặt hàng:</span>
                        <span class="fw-semibold">{{ $product->orders_count ?? 0 }}</span>
                    </div>
                    <div class="summary-item d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Số lượng đã bán:</span>
                        <span class="fw-semibold">{{ $product->sold_count ?? 0 }}</span>
                    </div>
                    <div class="summary-item d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Lượt xem:</span>
                        <span class="fw-semibold">{{ $product->view_count ?? 0 }}</span>
                    </div>
                    <div class="summary-item d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Đánh giá:</span>
                        <div>
                            <span class="fw-semibold">{{ $product->reviews_count ?? 0 }}</span>
                            <span class="text-warning ms-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($product->reviews_avg_rating ?? 0))
                                        <i class="fas fa-star small"></i>
                                    @else
                                        <i class="far fa-star small"></i>
                                    @endif
                                @endfor
                            </span>
                        </div>
                    </div>
                    <div class="summary-item d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Yêu thích:</span>
                        <span class="fw-semibold">{{ $product->favorites_count ?? 0 }}</span>
                    </div>
                    <div class="summary-item d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Tồn kho:</span>
                        <span class="fw-semibold">{{ $product->quantity }}</span>
                    </div>
                    <div class="summary-item d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Trạng thái:</span>
                        <span class="badge {{ $product->active ? 'bg-success' : 'bg-danger' }}">
                            {{ $product->active ? 'Đang bán' : 'Dừng bán' }}
                        </span>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Chỉnh sửa sản phẩm
                        </a>
                    </div>
                </div>
            </div>

            <!-- Các sản phẩm liên quan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Sản phẩm liên quan</h5>
                </div>
                <div class="card-body p-0">
                    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($relatedProducts as $relatedProduct)
                                <a href="{{ route('admin.products.show', $relatedProduct->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0" style="width: 40px; height: 40px;">
                                            @if($relatedProduct->main_image)
                                                <img src="{{ asset('storage/' . $relatedProduct->main_image) }}" class="img-fluid rounded" alt="{{ $relatedProduct->name }}" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-box text-secondary"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0">{{ $relatedProduct->name }}</h6>
                                            <small class="text-muted">{{ number_format($relatedProduct->price, 0, ',', '.') }} VNĐ</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Không có sản phẩm liên quan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa sản phẩm <strong>{{ $product->name }}</strong>?</p>
                
                @if($product->orders_count > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span>Sản phẩm này đã có <strong>{{ $product->orders_count }}</strong> đơn hàng. Xóa sản phẩm có thể ảnh hưởng đến dữ liệu đơn hàng.</span>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('styles')
<style>
    .product-main-image {
        width: 100%;
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    .product-main-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .product-no-image {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 100%;
    }
    
    .product-description img {
        max-width: 100%;
        height: auto;
    }
    
    .rating-average {
        text-align: center;
    }
    
    @media (max-width: 767.98px) {
        .product-main-image {
            height: 250px;
        }
    }
</style>
@endsection 