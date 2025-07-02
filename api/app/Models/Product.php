<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'specifications',
        'price',
        'sale_price',
        'quantity',
        'featured',
        'is_new',
        'active',
        'category_id',
        'brand_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'specifications' => 'json',
        'featured' => 'boolean',
        'is_new' => 'boolean',
        'active' => 'boolean',
        'price' => 'float',
        'sale_price' => 'float',
    ];

    /**
     * Định nghĩa quan hệ với bảng categories
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Định nghĩa quan hệ với bảng brands
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Định nghĩa quan hệ với bảng product_images
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Định nghĩa quan hệ với bảng carts
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Định nghĩa quan hệ với bảng order_details
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Định nghĩa quan hệ với bảng reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Định nghĩa quan hệ với bảng wishlist
     */
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Lấy hình ảnh chính của sản phẩm
     */
    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    /**
     * Tính giá hiển thị (giá khuyến mãi nếu có, ngược lại là giá gốc)
     */
    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }
}
