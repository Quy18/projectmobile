<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Tạo 1 ảnh chính cho sản phẩm
            ProductImage::create([
                'product_id' => $product->id,
                'image' => 'products/' . $product->id . '/main.jpg',
                'is_main' => true,
            ]);

            // Tạo thêm 2-4 ảnh phụ cho sản phẩm
            $numImages = rand(2, 4);
            for ($i = 1; $i <= $numImages; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'products/' . $product->id . '/image' . $i . '.jpg',
                    'is_main' => false,
                ]);
            }
        }
    }
} 