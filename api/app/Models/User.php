<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Định nghĩa quan hệ với bảng orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Định nghĩa quan hệ với bảng carts
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Định nghĩa quan hệ với bảng wishlist
     */
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Định nghĩa quan hệ với bảng reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Định nghĩa quan hệ với bảng chatbot_conversations
     */
    public function chatbotConversations()
    {
        return $this->hasMany(ChatbotConversation::class);
    }

    /**
     * Kiểm tra xem người dùng có phải là admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Kiểm tra xem người dùng có phải là nhân viên
     */
    public function isStaff()
    {
        return $this->role === 'staff';
    }
}
