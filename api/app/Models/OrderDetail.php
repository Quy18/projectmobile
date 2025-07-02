<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'float',
    ];

    /**
     * Định nghĩa quan hệ với bảng orders
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Định nghĩa quan hệ với bảng products
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Tính tổng tiền của chi tiết đơn hàng
     */
    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
