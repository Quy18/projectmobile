<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Thông tin cửa hàng
            [
                'key' => 'store_name',
                'value' => 'MobileShop',
                'type' => 'text'
            ],
            [
                'key' => 'store_email',
                'value' => 'contact@mobileshop.com',
                'type' => 'text'
            ],
            [
                'key' => 'store_phone',
                'value' => '+84 123 456 789',
                'type' => 'text'
            ],
            [
                'key' => 'store_address',
                'value' => '123 Đường ABC, Quận 1, TP.HCM',
                'type' => 'text'
            ],
            [
                'key' => 'store_logo',
                'value' => 'images/logo.png',
                'type' => 'image'
            ],
            
            // Mạng xã hội
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/mobileshop',
                'type' => 'url'
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com/mobileshop',
                'type' => 'url'
            ],
            [
                'key' => 'youtube_url',
                'value' => 'https://youtube.com/mobileshop',
                'type' => 'url'
            ],
            
            // Cài đặt thanh toán
            [
                'key' => 'currency',
                'value' => 'VND',
                'type' => 'text'
            ],
            [
                'key' => 'payment_methods',
                'value' => json_encode(['COD', 'Bank Transfer', 'Momo', 'ZaloPay']),
                'type' => 'json'
            ],
            
            // Cài đặt giao hàng
            [
                'key' => 'shipped_fee',
                'value' => '30000',
                'type' => 'number'
            ],
            [
                'key' => 'free_shipped_min_amount',
                'value' => '500000',
                'type' => 'number'
            ],
            
            // Cài đặt hệ thống
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean'
            ],
            [
                'key' => 'tax_percentage',
                'value' => '10',
                'type' => 'number'
            ],
            [
                'key' => 'products_per_page',
                'value' => '12',
                'type' => 'number'
            ],
            [
                'key' => 'homepage_products_count',
                'value' => '8',
                'type' => 'number'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
} 