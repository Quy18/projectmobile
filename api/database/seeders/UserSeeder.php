<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo tài khoản người dùng
        $users = [
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'nguyenvana@example.com',
                'password' => 'password',
                'phone' => '0901234567',
                'address' => '123 Đường Nguyễn Huệ, Quận 1, TP HCM',
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'tranthib@example.com',
                'password' => 'password',
                'phone' => '0912345678',
                'address' => '456 Đường Lê Lợi, Quận 2, TP HCM',
            ],
            [
                'name' => 'Lê Văn C',
                'email' => 'levanc@example.com',
                'password' => 'password',
                'phone' => '0923456789',
                'address' => '789 Đường Võ Văn Tần, Quận 3, TP HCM',
            ],
            [
                'name' => 'Phạm Thị D',
                'email' => 'phamthid@example.com',
                'password' => 'password',
                'phone' => '0934567890',
                'address' => '101 Đường Cách Mạng Tháng 8, Quận 10, TP HCM',
            ],
            [
                'name' => 'Hoàng Văn E',
                'email' => 'hoangvane@example.com',
                'password' => 'password',
                'phone' => '0945678901',
                'address' => '202 Đường 3/2, Quận 11, TP HCM',
            ],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'phone' => $userData['phone'],
                'address' => $userData['address'],
                'role' => 'user',
                'email_verified_at' => now(),
            ]);
        }
    }
} 