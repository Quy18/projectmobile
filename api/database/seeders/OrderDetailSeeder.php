<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $products = Product::all();

        foreach ($orders as $order) {
            // Mỗi đơn hàng sẽ có 1-5 sản phẩm
            $numProducts = rand(1, 5);
            $selectedProducts = $products->random($numProducts);
            $total = 0;

            foreach ($selectedProducts as $product) {
                // Số lượng từ 1-3 cho mỗi sản phẩm
                $quantity = rand(1, 3);
                
                // Lấy giá hiển thị (sale_price nếu có, không thì price)
                $price = $product->sale_price ?? $product->price;
                
                // Tính tổng tiền cho mỗi sản phẩm và thêm vào tổng đơn hàng
                $itemTotal = $price * $quantity;
                $total += $itemTotal;
                
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                ]);
            }
            
            // Cập nhật tổng tiền cho đơn hàng
            $order->total_amount = $total;
            $order->save();
        }
    }
} 