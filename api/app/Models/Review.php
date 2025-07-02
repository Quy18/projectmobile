<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
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
        'rating',
        'comment',
        'status',
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
}
