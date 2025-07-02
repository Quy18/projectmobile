<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Sử dụng Bootstrap 5 cho pagination
        Paginator::defaultView('vendor.pagination.bootstrap-5');
        Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-5');

        // Cấu hình và kiểm tra kết nối Gemini service
        $this->configureGeminiService();
    }

    /**
     * Cấu hình dịch vụ Gemini
     */
    private function configureGeminiService(): void
    {
        // Kiểm tra xem Gemini API có khả dụng không
        if (config('app.env') === 'production') {
            try {
                $apiUrl = config('services.gemini.api_url');
                $apiKey = config('services.gemini.api_key');
                
                // Gọi API đơn giản để kiểm tra kết nối
                $testUrl = 'https://generativelanguage.googleapis.com/v1/models?key=' . $apiKey;
                
                $response = Http::timeout(5)->get($testUrl);
                
                if (!$response->successful()) {
                    Log::warning('Gemini API không khả dụng. Chatbot sẽ sử dụng phương thức dự phòng.');
                    Log::debug('Gemini API Error: ' . $response->body());
                }
            } catch (\Exception $e) {
                Log::warning('Không thể kết nối đến Gemini API: ' . $e->getMessage());
            }
        }
    }
}
