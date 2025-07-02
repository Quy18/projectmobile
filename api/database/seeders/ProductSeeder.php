<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // iPhone
        $iphoneCategory = Category::where('name', 'iPhone')->first();
        $appleBrand = Brand::where('name', 'Apple')->first();
        
        $iphones = [
            [
                'name' => 'iPhone 15 Pro Max 256GB',
                'description' => 'iPhone 15 Pro Max mới nhất với chip A17 Pro, camera 48MP cải tiến và sạc USB-C.',
                'specifications' => json_encode([
                    'Màn hình' => 'OLED 6.7 inch, Super Retina XDR',
                    'Chip' => 'A17 Pro',
                    'RAM' => '8GB',
                    'Bộ nhớ trong' => '256GB',
                    'Camera sau' => 'Chính 48MP, Ultra-wide 12MP, Telephoto 12MP',
                    'Camera trước' => '12MP',
                    'Pin' => '4422mAh',
                ]),
                'price' => 33990000,
                'sale_price' => 32490000,
                'quantity' => 50,
                'featured' => true,
                'images' => [
                    'products/iphone15promax_1.jpg',
                    'products/iphone15promax_2.jpg',
                    'products/iphone15promax_3.jpg',
                ],
            ],
            [
                'name' => 'iPhone 15 128GB',
                'description' => 'iPhone 15 với Dynamic Island, camera 48MP và hiệu năng mạnh mẽ.',
                'specifications' => json_encode([
                    'Màn hình' => 'OLED 6.1 inch, Super Retina XDR',
                    'Chip' => 'A16 Bionic',
                    'RAM' => '6GB',
                    'Bộ nhớ trong' => '128GB',
                    'Camera sau' => 'Chính 48MP, Ultra-wide 12MP',
                    'Camera trước' => '12MP',
                    'Pin' => '3349mAh',
                ]),
                'price' => 22990000,
                'sale_price' => 21490000,
                'quantity' => 75,
                'featured' => true,
                'images' => [
                    'products/iphone15_1.jpg',
                    'products/iphone15_2.jpg',
                ],
            ],
            [
                'name' => 'iPhone 14 Pro 256GB',
                'description' => 'iPhone 14 Pro với Dynamic Island, camera 48MP và hiệu năng mạnh mẽ.',
                'specifications' => json_encode([
                    'Màn hình' => 'OLED 6.1 inch, Super Retina XDR',
                    'Chip' => 'A16 Bionic',
                    'RAM' => '6GB',
                    'Bộ nhớ trong' => '256GB',
                    'Camera sau' => 'Chính 48MP, Ultra-wide 12MP, Telephoto 12MP',
                    'Camera trước' => '12MP',
                    'Pin' => '3200mAh',
                ]),
                'price' => 27990000,
                'sale_price' => 25990000,
                'quantity' => 30,
                'featured' => false,
                'images' => [
                    'products/iphone14pro_1.jpg',
                    'products/iphone14pro_2.jpg',
                ],
            ],
        ];

        $this->createProducts($iphones, $iphoneCategory->id, $appleBrand->id);

        // Samsung
        $samsungCategory = Category::where('name', 'Samsung')->first();
        $samsungBrand = Brand::where('name', 'Samsung')->first();
        
        $samsungs = [
            [
                'name' => 'Samsung Galaxy S24 Ultra 256GB',
                'description' => 'Flagship mới nhất của Samsung với bút S-Pen, camera 200MP và chip Snapdragon 8 Gen 3.',
                'specifications' => json_encode([
                    'Màn hình' => 'Dynamic AMOLED 2X 6.8 inch, QHD+',
                    'Chip' => 'Snapdragon 8 Gen 3',
                    'RAM' => '12GB',
                    'Bộ nhớ trong' => '256GB',
                    'Camera sau' => 'Chính 200MP, Ultra-wide 12MP, Telephoto 50MP, Telephoto 10MP',
                    'Camera trước' => '12MP',
                    'Pin' => '5000mAh',
                ]),
                'price' => 31990000,
                'sale_price' => 29990000,
                'quantity' => 40,
                'featured' => true,
                'images' => [
                    'products/s24ultra_1.jpg',
                    'products/s24ultra_2.jpg',
                ],
            ],
            [
                'name' => 'Samsung Galaxy Z Fold5 512GB',
                'description' => 'Điện thoại màn hình gập cao cấp của Samsung với hiệu năng mạnh mẽ.',
                'specifications' => json_encode([
                    'Màn hình chính' => 'Dynamic AMOLED 2X 7.6 inch, QXGA+',
                    'Màn hình ngoài' => 'Dynamic AMOLED 2X 6.2 inch, HD+',
                    'Chip' => 'Snapdragon 8 Gen 2',
                    'RAM' => '12GB',
                    'Bộ nhớ trong' => '512GB',
                    'Camera sau' => 'Chính 50MP, Ultra-wide 12MP, Telephoto 10MP',
                    'Camera trước' => '10MP (màn hình ngoài), 4MP (dưới màn hình)',
                    'Pin' => '4400mAh',
                ]),
                'price' => 47990000,
                'sale_price' => 40990000,
                'quantity' => 20,
                'featured' => true,
                'images' => [
                    'products/zfold5_1.jpg',
                    'products/zfold5_2.jpg',
                ],
            ],
        ];

        $this->createProducts($samsungs, $samsungCategory->id, $samsungBrand->id);

        // Laptop Gaming
        $gamingLaptopCategory = Category::where('name', 'Laptop Gaming')->first();
        $acerBrand = Brand::where('name', 'Acer')->first();
        $asusBrand = Brand::where('name', 'Asus')->first();
        $msiBrand = Brand::where('name', 'MSI')->first();
        
        $gamingLaptops = [
            [
                'name' => 'Acer Nitro 5 AN515',
                'description' => 'Laptop gaming tầm trung với hiệu năng mạnh mẽ và tản nhiệt hiệu quả.',
                'specifications' => json_encode([
                    'CPU' => 'Intel Core i7-12700H',
                    'GPU' => 'NVIDIA GeForce RTX 3060 6GB',
                    'RAM' => '16GB DDR4 3200MHz',
                    'Ổ cứng' => 'SSD NVMe 512GB',
                    'Màn hình' => '15.6 inch FHD IPS 144Hz',
                    'Hệ điều hành' => 'Windows 11 Home',
                ]),
                'price' => 28990000,
                'sale_price' => 26990000,
                'quantity' => 25,
                'featured' => true,
                'images' => [
                    'products/nitro5_1.jpg',
                    'products/nitro5_2.jpg',
                ],
            ],
            [
                'name' => 'Asus ROG Strix G15',
                'description' => 'Laptop gaming cao cấp với đèn RGB và hiệu năng vượt trội.',
                'specifications' => json_encode([
                    'CPU' => 'AMD Ryzen 9 6900HX',
                    'GPU' => 'NVIDIA GeForce RTX 3070 Ti 8GB',
                    'RAM' => '32GB DDR5 4800MHz',
                    'Ổ cứng' => 'SSD NVMe 1TB',
                    'Màn hình' => '15.6 inch QHD IPS 165Hz',
                    'Hệ điều hành' => 'Windows 11 Home',
                ]),
                'price' => 42990000,
                'sale_price' => 39990000,
                'quantity' => 15,
                'featured' => true,
                'images' => [
                    'products/rogstrix_1.jpg',
                    'products/rogstrix_2.jpg',
                ],
            ],
            [
                'name' => 'MSI Katana GF66',
                'description' => 'Laptop gaming mỏng nhẹ với hiệu năng ổn định.',
                'specifications' => json_encode([
                    'CPU' => 'Intel Core i5-12500H',
                    'GPU' => 'NVIDIA GeForce RTX 3050 Ti 4GB',
                    'RAM' => '16GB DDR4 3200MHz',
                    'Ổ cứng' => 'SSD NVMe 512GB',
                    'Màn hình' => '15.6 inch FHD IPS 144Hz',
                    'Hệ điều hành' => 'Windows 11 Home',
                ]),
                'price' => 24990000,
                'sale_price' => 22990000,
                'quantity' => 30,
                'featured' => false,
                'images' => [
                    'products/katana_1.jpg',
                    'products/katana_2.jpg',
                ],
            ],
        ];

        $this->createProducts([
            $gamingLaptops[0]
        ], $gamingLaptopCategory->id, $acerBrand->id);
        
        $this->createProducts([
            $gamingLaptops[1]
        ], $gamingLaptopCategory->id, $asusBrand->id);
        
        $this->createProducts([
            $gamingLaptops[2]
        ], $gamingLaptopCategory->id, $msiBrand->id);
    }

    /**
     * Tạo sản phẩm và hình ảnh sản phẩm
     */
    private function createProducts($products, $categoryId, $brandId)
    {
        foreach ($products as $productData) {
            $sku = 'SKU' . rand(100000, 999999);
            
            $product = Product::create([
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'sku' => $sku,
                'description' => $productData['description'],
                'specifications' => $productData['specifications'],
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'],
                'quantity' => $productData['quantity'],
                'featured' => $productData['featured'],
                'status' => 'active',
                'category_id' => $categoryId,
                'brand_id' => $brandId,
            ]);

            // Tạo hình ảnh sản phẩm
            foreach ($productData['images'] as $index => $image) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $image,
                    'is_main' => $index === 0, // Hình đầu tiên là hình chính
                ]);
            }
        }
    }
} 