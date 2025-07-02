<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Danh mục cha
        $mainCategories = [
            [
                'name' => 'Điện thoại',
                'description' => 'Tất cả các loại điện thoại di động',
                'image' => 'categories/phones.png',
            ],
            [
                'name' => 'Laptop',
                'description' => 'Máy tính xách tay các loại',
                'image' => 'categories/laptops.png',
            ],
            [
                'name' => 'Máy tính bảng',
                'description' => 'Các loại máy tính bảng',
                'image' => 'categories/tablets.png',
            ],
            [
                'name' => 'Phụ kiện',
                'description' => 'Phụ kiện điện tử các loại',
                'image' => 'categories/accessories.png',
            ],
        ];

        foreach ($mainCategories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => $category['image'],
            ]);
        }

        // Danh mục con cho Điện thoại
        $phoneSubCategories = [
            'iPhone', 'Samsung', 'Xiaomi', 'Oppo', 'Vivo'
        ];

        $phoneCategory = Category::where('name', 'Điện thoại')->first();
        foreach ($phoneSubCategories as $subCategory) {
            Category::create([
                'name' => $subCategory,
                'slug' => Str::slug($subCategory),
                'description' => 'Điện thoại ' . $subCategory,
                'parent_id' => $phoneCategory->id,
            ]);
        }

        // Danh mục con cho Laptop
        $laptopSubCategories = [
            'Gaming', 'Văn phòng', 'MacBook', 'Workstation'
        ];

        $laptopCategory = Category::where('name', 'Laptop')->first();
        foreach ($laptopSubCategories as $subCategory) {
            Category::create([
                'name' => 'Laptop ' . $subCategory,
                'slug' => Str::slug('Laptop ' . $subCategory),
                'description' => 'Laptop dòng ' . $subCategory,
                'parent_id' => $laptopCategory->id,
            ]);
        }

        // Danh mục con cho Phụ kiện
        $accessorySubCategories = [
            'Tai nghe', 'Sạc dự phòng', 'Ốp lưng', 'Cáp sạc', 'Bàn phím', 'Chuột'
        ];

        $accessoryCategory = Category::where('name', 'Phụ kiện')->first();
        foreach ($accessorySubCategories as $subCategory) {
            Category::create([
                'name' => $subCategory,
                'slug' => Str::slug($subCategory),
                'description' => $subCategory . ' các loại',
                'parent_id' => $accessoryCategory->id,
            ]);
        }
    }
} 