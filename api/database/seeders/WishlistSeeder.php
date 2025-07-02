<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', '!=', 'admin')->take(25)->get();
        $products = Product::all();

        // Thêm sản phẩm vào danh sách yêu thích cho mỗi người dùng
        foreach ($users as $user) {
            // Mỗi người dùng sẽ có 3-10 sản phẩm yêu thích
            $numProducts = rand(3, 10);
            $selectedProducts = $products->random($numProducts);

            foreach ($selectedProducts as $product) {
                Wishlist::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 