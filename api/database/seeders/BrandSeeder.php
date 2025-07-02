<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Apple',
                'logo' => 'brands/apple.png',
                'description' => 'Thương hiệu Apple Inc.',
            ],
            [
                'name' => 'Samsung',
                'logo' => 'brands/samsung.png',
                'description' => 'Tập đoàn Samsung',
            ],
            [
                'name' => 'Xiaomi',
                'logo' => 'brands/xiaomi.png',
                'description' => 'Xiaomi Corporation',
            ],
            [
                'name' => 'Oppo',
                'logo' => 'brands/oppo.png',
                'description' => 'OPPO Electronics Corp',
            ],
            [
                'name' => 'Dell',
                'logo' => 'brands/dell.png',
                'description' => 'Dell Technologies',
            ],
            [
                'name' => 'HP',
                'logo' => 'brands/hp.png',
                'description' => 'Hewlett-Packard',
            ],
            [
                'name' => 'Asus',
                'logo' => 'brands/asus.png',
                'description' => 'ASUSTeK Computer Inc.',
            ],
            [
                'name' => 'Acer',
                'logo' => 'brands/acer.png',
                'description' => 'Acer Inc.',
            ],
            [
                'name' => 'MSI',
                'logo' => 'brands/msi.png',
                'description' => 'Micro-Star International',
            ],
            [
                'name' => 'Sony',
                'logo' => 'brands/sony.png',
                'description' => 'Sony Corporation',
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'logo' => $brand['logo'],
                'description' => $brand['description'],
            ]);
        }
    }
} 