<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'shipped_address',
        'shipped_phone',
        'shipped_name',
        'note',
        'tracking_number',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'float',
    ];

    /**
     * Định nghĩa quan hệ với bảng users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Định nghĩa quan hệ với bảng order_details
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Định nghĩa quan hệ với bảng products thông qua order_details
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_details')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
    
    /**
     * Lấy tên khách hàng từ thông tin shipped hoặc user
     */
    public function getCustomerNameAttribute()
    {
        return $this->shipped_name ?? $this->user->name ?? 'N/A';
    }
    
    /**
     * Lấy email khách hàng từ user
     */
    public function getCustomerEmailAttribute() 
    {
        return $this->user->email ?? 'N/A';
    }
    
    /**
     * Lấy số điện thoại khách hàng từ thông tin shipped
     */
    public function getCustomerPhoneAttribute()
    {
        return $this->shipped_phone ?? 'N/A';
    }
    
    /**
     * Lấy tổng tiền đơn hàng (alias cho total_amount)
     */
    public function getTotalAttribute()
    {
        return $this->total_amount;
    }
    
    /**
     * Lấy mã đơn hàng dưới dạng định dạng (#ID)
     */
    public function getOrderNumberAttribute()
    {
        return $this->id;
    }
}
