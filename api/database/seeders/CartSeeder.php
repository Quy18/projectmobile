<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', '!=', 'admin')->take(20)->get();
        $products = Product::all();

        // Thêm sản phẩm vào giỏ hàng cho mỗi người dùng
        foreach ($users as $user) {
            // Mỗi người dùng sẽ có 1-5 sản phẩm trong giỏ hàng
            $numProducts = rand(1, 5);
            $selectedProducts = $products->random($numProducts);

            foreach ($selectedProducts as $product) {
                // Số lượng từ 1-3 cho mỗi sản phẩm
                $quantity = rand(1, 3);
                
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 