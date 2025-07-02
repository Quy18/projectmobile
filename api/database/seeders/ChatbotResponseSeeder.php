<?php

namespace Database\Seeders;

use App\Models\ChatbotResponse;
use Illuminate\Database\Seeder;

class ChatbotResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $responses = [
            // Thông tin sản phẩm
            [
                'keyword' => 'sản phẩm mới',
                'response' => 'Chúng tôi thường xuyên cập nhật sản phẩm mới vào mỗi tuần. Bạn có thể xem các sản phẩm mới nhất tại trang chủ hoặc mục "Sản phẩm mới".',
                'priority' => 5,
            ],
            [
                'keyword' => 'khuyến mãi',
                'response' => 'Hiện tại chúng tôi có nhiều chương trình khuyến mãi hấp dẫn. Bạn có thể xem tại mục "Sản phẩm khuyến mãi" trên trang chủ.',
                'priority' => 5,
            ],
            [
                'keyword' => 'giảm giá',
                'response' => 'Chúng tôi thường xuyên có các đợt giảm giá vào các dịp lễ lớn. Hiện tại các sản phẩm giảm giá đang được hiển thị trong mục "Sản phẩm khuyến mãi".',
                'priority' => 5,
            ],
            
            // Đơn hàng
            [
                'keyword' => 'đơn hàng',
                'response' => 'Bạn có thể xem thông tin đơn hàng của mình trong mục "Đơn hàng" tại trang cá nhân sau khi đăng nhập.',
                'priority' => 4,
            ],
            [
                'keyword' => 'tình trạng đơn hàng',
                'response' => 'Để xem tình trạng đơn hàng, vui lòng đăng nhập và vào mục "Đơn hàng" trong trang cá nhân của bạn.',
                'priority' => 4,
            ],
            [
                'keyword' => 'hủy đơn hàng',
                'response' => 'Bạn có thể hủy đơn hàng trong vòng 24 giờ sau khi đặt hàng nếu đơn hàng chưa được xử lý. Vui lòng vào mục "Đơn hàng" trong trang cá nhân để thực hiện.',
                'priority' => 4,
            ],
            
            // Thanh toán
            [
                'keyword' => 'phương thức thanh toán',
                'response' => 'Chúng tôi hỗ trợ thanh toán khi nhận hàng (COD) và thanh toán qua chuyển khoản ngân hàng.',
                'priority' => 3,
            ],
            [
                'keyword' => 'thanh toán online',
                'response' => 'Hiện tại chúng tôi hỗ trợ thanh toán qua chuyển khoản ngân hàng. Chúng tôi sẽ cung cấp thông tin tài khoản sau khi bạn đặt hàng.',
                'priority' => 3,
            ],
            
            // Vận chuyển
            [
                'keyword' => 'phí vận chuyển',
                'response' => 'Phí vận chuyển sẽ được tính dựa trên vị trí của bạn và hiển thị khi bạn thanh toán. Với đơn hàng trên 500.000đ, bạn sẽ được miễn phí vận chuyển.',
                'priority' => 3,
            ],
            [
                'keyword' => 'thời gian giao hàng',
                'response' => 'Thời gian giao hàng thông thường là 2-3 ngày đối với khu vực thành phố và 3-5 ngày đối với các tỉnh khác.',
                'priority' => 3,
            ],
            
            // Tài khoản
            [
                'keyword' => 'đăng ký',
                'response' => 'Bạn có thể đăng ký tài khoản bằng cách nhấn vào biểu tượng người dùng và chọn "Đăng ký". Sau đó, điền thông tin cần thiết để hoàn tất quá trình đăng ký.',
                'priority' => 2,
            ],
            [
                'keyword' => 'đăng nhập',
                'response' => 'Để đăng nhập, vui lòng nhấn vào biểu tượng người dùng và chọn "Đăng nhập", sau đó nhập email và mật khẩu của bạn.',
                'priority' => 2,
            ],
            [
                'keyword' => 'quên mật khẩu',
                'response' => 'Nếu bạn quên mật khẩu, vui lòng nhấn vào liên kết "Quên mật khẩu" trên trang đăng nhập và làm theo hướng dẫn để đặt lại mật khẩu.',
                'priority' => 2,
            ],
            
            // Chào hỏi
            [
                'keyword' => 'xin chào',
                'response' => 'Xin chào! Tôi là trợ lý ảo của NeoShop. Tôi có thể giúp gì cho bạn hôm nay?',
                'priority' => 1,
            ],
            [
                'keyword' => 'chào',
                'response' => 'Chào bạn! Rất vui được hỗ trợ bạn. Bạn cần giúp đỡ về vấn đề gì?',
                'priority' => 1,
            ],
            [
                'keyword' => 'tạm biệt',
                'response' => 'Cảm ơn bạn đã liên hệ với chúng tôi. Chúc bạn một ngày tốt lành!',
                'priority' => 1,
            ],
            [
                'keyword' => 'cảm ơn',
                'response' => 'Không có gì! Rất vui khi được giúp đỡ bạn. Nếu có bất kỳ câu hỏi nào khác, đừng ngần ngại hỏi tôi.',
                'priority' => 1,
            ],

            // Sản phẩm cụ thể - Vali
            [
                'keyword' => 'vali',
                'response' => 'Chúng tôi có nhiều loại vali chất lượng cao với đa dạng kích thước và màu sắc. Bạn có thể tìm thấy vali tại danh mục "Vali & Túi du lịch". Chúng tôi đề xuất bộ vali Samsonite Cruizor với chất liệu polycarbonate cao cấp, chịu lực tốt và khóa TSA an toàn.',
                'priority' => 7,
            ],
            [
                'keyword' => 'túi du lịch',
                'response' => 'Chúng tôi có nhiều mẫu túi du lịch từ các thương hiệu nổi tiếng như Adidas, Nike và The North Face. Túi du lịch của chúng tôi có nhiều ngăn tiện dụng, chống thấm nước và bền bỉ. Bạn có thể xem chi tiết tại danh mục "Vali & Túi du lịch".',
                'priority' => 7,
            ],

            // Sản phẩm cụ thể - Quần áo
            [
                'keyword' => 'áo',
                'response' => 'Chúng tôi có đa dạng các mẫu áo thời trang cho cả nam và nữ, từ áo thun, áo sơ mi đến áo khoác. Hiện tại có nhiều mẫu áo mới và đang được giảm giá hấp dẫn. Bạn có thể xem chi tiết trong các danh mục tương ứng trên ứng dụng.',
                'priority' => 7,
            ],
            [
                'keyword' => 'quần',
                'response' => 'NeoShop có các mẫu quần đa dạng từ quần jean, quần tây công sở đến quần thể thao. Các sản phẩm được thiết kế theo xu hướng mới nhất và có nhiều size phù hợp. Hãy ghé thăm danh mục quần áo để xem các mẫu mới nhất.',
                'priority' => 7,
            ],
            [
                'keyword' => 'giày',
                'response' => 'Chúng tôi cung cấp nhiều mẫu giày từ các thương hiệu nổi tiếng với kiểu dáng đa dạng: giày thể thao, giày tây, giày cao gót... Tất cả đều được làm từ chất liệu cao cấp, đảm bảo thoải mái khi sử dụng. Hãy xem các mẫu giày mới nhất trong danh mục "Giày dép".',
                'priority' => 7,
            ],

            // Sản phẩm cụ thể - Phụ kiện
            [
                'keyword' => 'phụ kiện',
                'response' => 'NeoShop có đa dạng các phụ kiện thời trang như đồng hồ, túi xách, thắt lưng, trang sức... với thiết kế tinh tế và hợp thời trang. Các sản phẩm phụ kiện là lựa chọn hoàn hảo để hoàn thiện phong cách của bạn.',
                'priority' => 6,
            ],
            [
                'keyword' => 'túi xách',
                'response' => 'Chúng tôi có các mẫu túi xách thời trang từ nhiều thương hiệu nổi tiếng với đa dạng kiểu dáng và màu sắc. Các sản phẩm túi xách của chúng tôi được làm từ chất liệu cao cấp, bền đẹp và tinh tế. Hãy xem ngay danh mục "Túi xách" để chọn cho mình mẫu túi ưng ý.',
                'priority' => 6,
            ],
            [
                'keyword' => 'mắt kính',
                'response' => 'NeoShop cung cấp các mẫu mắt kính thời trang từ các thương hiệu nổi tiếng như Ray-Ban, Oakley và nhiều thương hiệu khác. Các sản phẩm mắt kính của chúng tôi có thiết kế hiện đại, chống tia UV và bảo vệ mắt hiệu quả.',
                'priority' => 6,
            ],
        ];

        foreach ($responses as $response) {
            ChatbotResponse::create($response);
        }
    }
} 