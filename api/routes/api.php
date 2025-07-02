<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChatbotController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController as ApiCategoryController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewController as ApiReviewController;
use App\Http\Controllers\Api\ChatbotController as ApiChatbotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes cho public API (sẽ bổ sung sau)

// Route mở - không cần đăng nhập
Route::prefix('v1')->group(function () {
    // Route xác thực
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
    
    // Route sản phẩm công khai
    Route::get('/products', [ApiProductController::class, 'getProducts']);
    Route::get('/products/search', [ApiProductController::class, 'searchProducts']);
    Route::get('/products/featured', [ApiProductController::class, 'getFeaturedProducts']);
    Route::get('/products/new', [ApiProductController::class, 'getNewProducts']);
    Route::get('/products/sale', [ApiProductController::class, 'getSaleProducts']);
    Route::get('/products/{id}', [ApiProductController::class, 'getProduct']);
    Route::get('/products/{id}/reviews', [ApiReviewController::class, 'getProductReviews']);
    
    // Route danh mục
    Route::get('/categories', [ApiCategoryController::class, 'getCategories']);
    Route::get('/categories/{id}', [ApiCategoryController::class, 'getCategory']);
    Route::get('/categories/{id}/products', [ApiCategoryController::class, 'getCategoryProducts']);
    
    // Route chatbot
    Route::post('/chatbot/message', [ApiChatbotController::class, 'processMessage']);
});

// Route yêu cầu xác thực
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Route xác thực
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    
    // Route profile
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
    
    // Route giỏ hàng
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::put('/cart/update/{id}', [CartController::class, 'updateCartItem']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeCartItem']);
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);
    
    // Route đơn hàng
    Route::post('/orders', [ApiOrderController::class, 'createOrder']);
    Route::get('/orders', [ApiOrderController::class, 'getOrders']);
    Route::get('/orders/{id}', [ApiOrderController::class, 'getOrder']);
    Route::put('/orders/{id}/cancel', [ApiOrderController::class, 'cancelOrder']);
    
    // Route đánh giá
    Route::post('/reviews', [ApiReviewController::class, 'addReview']);
    Route::get('/reviews/my-reviews', [ApiReviewController::class, 'getUserReviews']);
});
