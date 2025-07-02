<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        
        // Lấy danh sách users và products
        $users = User::where('role', '!=', 'admin')->get();
        $products = Product::all();
        
        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->info('Không đủ người dùng hoặc sản phẩm để tạo đơn hàng!');
            return;
        }
        
        // Tình trạng đơn hàng
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        // Phương thức thanh toán
        $paymentMethods = ['cod', 'banking', 'momo', 'zalopay'];
        
        // Tình trạng thanh toán
        $paymentStatuses = ['pending', 'paid', 'failed'];
        
        // Tạo khoảng 200-300 đơn hàng
        $numberOfOrders = rand(200, 300);
        $this->command->info("Đang tạo $numberOfOrders đơn hàng...");
        
        $startDate = Carbon::now()->subMonths(6);
        $endDate = Carbon::now();
        
        $progressBar = $this->command->getOutput()->createProgressBar($numberOfOrders);
        $progressBar->start();
        
        for ($i = 0; $i < $numberOfOrders; $i++) {
            // Chọn ngẫu nhiên một người dùng
            $user = $users->random();
            
            // Chọn ngẫu nhiên một ngày đặt hàng trong khoảng 6 tháng gần đây
            $orderDate = $faker->dateTimeBetween($startDate, $endDate);
            
            // Chọn ngẫu nhiên trạng thái đơn hàng
            // Tăng xác suất các đơn hàng cũ hơn có trạng thái delivered/cancelled
            $createdDaysAgo = Carbon::now()->diffInDays(Carbon::parse($orderDate));
            
            if ($createdDaysAgo > 30) {
                // Đơn hàng cũ hơn 30 ngày có 80% khả năng đã hoàn thành hoặc hủy
                $status = $faker->randomElement([
                    'delivered', 'delivered', 'delivered', 'delivered', // 80% delivered
                    'cancelled', // 20% cancelled
                ]);
            } elseif ($createdDaysAgo > 14) {
                // Đơn hàng 14-30 ngày có 60% đã hoàn thành, 30% đang vận chuyển, 10% hủy
                $status = $faker->randomElement([
                    'delivered', 'delivered', 'delivered', // 60% delivered
                    'shipped', 'shipped', // 30% shipped
                    'cancelled', // 10% cancelled
                ]);
            } elseif ($createdDaysAgo > 7) {
                // Đơn hàng 7-14 ngày có 40% đã hoàn thành, 40% đang vận chuyển, 10% đang xử lý, 10% hủy
                $status = $faker->randomElement([
                    'delivered', 'delivered', // 40% delivered
                    'shipped', 'shipped', // 40% shipped
                    'processing', // 10% processing
                    'cancelled', // 10% cancelled
                ]);
            } else {
                // Đơn hàng trong vòng 7 ngày có 20% đã hoàn thành, 30% đang vận chuyển, 40% đang xử lý, 5% hủy, 5% chờ xử lý
                $status = $faker->randomElement([
                    'delivered', // 20% delivered
                    'shipped', 'shipped', 'shipped', // 30% shipped
                    'processing', 'processing', 'processing', 'processing', // 40% processing
                    'cancelled', // 5% cancelled
                    'pending', // 5% pending
                ]);
            }
            
            // Chọn phương thức thanh toán
            $paymentMethod = $faker->randomElement($paymentMethods);
            
            // Xác định trạng thái thanh toán dựa trên phương thức và trạng thái đơn hàng
            if ($paymentMethod == 'cod') {
                // COD chỉ được thanh toán khi giao hàng thành công
                $paymentStatus = ($status == 'delivered') ? 'paid' : 'pending';
            } else {
                // Các phương thức thanh toán khác
                if ($status == 'cancelled') {
                    // Nếu đơn hàng bị hủy, 30% đã thanh toán (cần hoàn tiền), 70% chưa thanh toán
                    $paymentStatus = $faker->randomElement(['pending', 'pending', 'pending', 'pending', 'pending', 'pending', 'pending', 'paid', 'paid', 'paid']);
                } elseif (in_array($status, ['pending', 'processing'])) {
                    // Đơn hàng mới có 80% đã thanh toán, 15% đang chờ, 5% thất bại
                    $paymentStatus = $faker->randomElement(['paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'pending', 'pending', 'pending', 'failed']);
                } else {
                    // Đơn hàng đang giao hoặc đã giao có 95% đã thanh toán, 5% thất bại
                    $paymentStatus = $faker->randomElement(['paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'failed']);
                }
            }
            
            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => 0, // Sẽ cập nhật sau
                'status' => $status,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'shipped_address' => $faker->address,
                'shipped_phone' => $faker->phoneNumber,
                'shipped_name' => $faker->boolean(80) ? $user->name : $faker->name,
                'note' => $faker->boolean(30) ? $faker->sentence : null,
                'tracking_number' => in_array($status, ['shipped', 'delivered']) ? strtoupper($faker->bothify('??###???###')) : null,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);
            
            // Số lượng sản phẩm trong đơn hàng (1-5)
            $numberOfProducts = rand(1, 5);
            $orderProducts = $products->random($numberOfProducts);
            
            $totalAmount = 0;
            
            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->sale_price ?? $product->price;
                $subtotal = $price * $quantity;
                $totalAmount += $subtotal;
                
                $order->orderDetails()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);
            }
            
            // Cập nhật tổng tiền đơn hàng
            $order->update(['total_amount' => $totalAmount]);
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->command->info("\nĐã tạo xong $numberOfOrders đơn hàng!");
    }
}
