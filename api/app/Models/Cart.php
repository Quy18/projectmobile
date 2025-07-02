<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    /**
     * Định nghĩa quan hệ với bảng users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Định nghĩa quan hệ với bảng products
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Tính tổng tiền của giỏ hàng
     */
    public function getTotalAttribute()
    {
        $price = $this->product->sale_price ?? $this->product->price;
        return $price * $this->quantity;
    }
}
