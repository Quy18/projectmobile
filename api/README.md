<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Chatbot với Google Gemini API

Hệ thống chatbot trong API đã được tích hợp với Google Gemini API để cung cấp chức năng AI thông minh, hỗ trợ khách hàng và gợi ý sản phẩm.

### Cấu hình Gemini API

1. API key của Gemini đã được cấu hình sẵn trong hệ thống. Tuy nhiên, bạn vẫn có thể thay đổi cấu hình bằng cách cập nhật file .env:
```
GEMINI_API_URL=https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent
GEMINI_API_KEY=AIzaSyAwXt98OGXRnOJB4CiRdRRwtaW5kouuDGA
GEMINI_MODEL=gemini-pro
GEMINI_MAX_TOKENS=300
GEMINI_TEMPERATURE=0.7
```

2. Endpoint chatbot hiện có tại:
```
POST /api/v1/chatbot/message
```

3. Định dạng yêu cầu:
```json
{
    "message": "Tôi muốn mua một chiếc áo khoác",
    "session_id": "optional-session-id"
}
```

4. Nếu Gemini API không khả dụng, hệ thống sẽ sử dụng dự phòng với các câu trả lời được định nghĩa sẵn.

### Đảm bảo ứng dụng hoạt động

Để đảm bảo chatbot hoạt động tốt:
- Nếu thay đổi API key, hãy đảm bảo key mới có quyền truy cập đến Gemini API
- Kiểm tra logs nếu có vấn đề khi kết nối với Gemini API
- Thử nghiệm endpoint `/api/v1/chatbot/message` để xác nhận kết nối

### Tính năng của chatbot

Chatbot được tích hợp với khả năng:
- Trả lời câu hỏi bằng tiếng Việt về sản phẩm, đơn hàng, chính sách
- Gợi ý sản phẩm phù hợp với nhu cầu của khách hàng
- Lưu trữ và phân tích lịch sử cuộc hội thoại
- Tự động chuyển sang chế độ dự phòng nếu có lỗi API
