<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $products = Product::all();
        $users = User::where('role', '!=', 'admin')->get();

        $reviewComments = [
            // Đánh giá tích cực
            'Sản phẩm rất tốt, giao hàng nhanh!',
            'Chất lượng sản phẩm tuyệt vời, đáng đồng tiền.',
            'Tôi rất hài lòng với sản phẩm này, sẽ mua lại.',
            'Đóng gói cẩn thận, sản phẩm đúng như mô tả.',
            'Giá cả hợp lý, chất lượng cao.',
            'Dịch vụ khách hàng tốt, sản phẩm chất lượng.',
            
            // Đánh giá trung bình
            'Sản phẩm tạm được, giao hàng hơi chậm.',
            'Chất lượng ổn, nhưng giá hơi cao.',
            'Sản phẩm đúng như mô tả, không có gì đặc biệt.',
            'Tương đối hài lòng với sản phẩm.',
            
            // Đánh giá tiêu cực
            'Sản phẩm không như mong đợi.',
            'Chất lượng kém, không đáng giá tiền.',
            'Giao hàng chậm, đóng gói không cẩn thận.',
            'Sản phẩm bị lỗi, cần cải thiện chất lượng.'
        ];

        // Tạo 200-300 đánh giá ngẫu nhiên
        $numReviews = rand(200, 300);
        for ($i = 0; $i < $numReviews; $i++) {
            $product = $products->random();
            $user = $users->random();
            $rating = rand(1, 5);
            
            // Chọn comment dựa trên rating
            $commentIndex = 0;
            if ($rating >= 4) {
                // Đánh giá tích cực
                $commentIndex = rand(0, 5);
            } elseif ($rating >= 3) {
                // Đánh giá trung bình
                $commentIndex = rand(6, 9);
            } else {
                // Đánh giá tiêu cực
                $commentIndex = rand(10, 13);
            }
            
            Review::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'rating' => $rating,
                'comment' => $reviewComments[$commentIndex],
                'status' => 'approved',
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
} 