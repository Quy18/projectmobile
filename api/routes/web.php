<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ChatbotController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Routes cho admin
Route::prefix('admin')->name('admin.')->group(function () {
    // Routes không yêu cầu xác thực
    Route::get('/login', [AdminController::class, 'login'])->name('login');
    Route::post('/login', [AdminController::class, 'doLogin'])->name('doLogin');
    
    // Routes yêu cầu xác thực
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Quản lý người dùng (CRUD hoàn chỉnh)
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        });
        
        // Quản lý danh mục (CRUD hoàn chỉnh)
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        
        // Quản lý thương hiệu (CRUD hoàn chỉnh)
        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands/{brand}', [BrandController::class, 'show'])->name('brands.show');
        Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
        
        // Quản lý sản phẩm (CRUD hoàn chỉnh)
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        
        // Quản lý đơn hàng
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::put('/orders/{order}/payment', [OrderController::class, 'updatePayment'])->name('orders.updatePayment');
        Route::put('/orders/{order}/tracking', [OrderController::class, 'updateTracking'])->name('orders.updateTracking');
        Route::get('/orders/statistics', [OrderController::class, 'statistics'])->name('orders.statistics');
        
        // Quản lý đánh giá
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/statistics', [ReviewController::class, 'statistics'])->name('reviews.statistics');
        Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
        Route::put('/reviews/{review}/status', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
        Route::post('/reviews/{review}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
        
        // Quản lý chatbot
        Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
        Route::get('/chatbot/{id}', [ChatbotController::class, 'show'])->name('chatbot.show');
        Route::delete('/chatbot/{id}', [ChatbotController::class, 'destroy'])->name('chatbot.destroy');
        Route::get('/chatbot/statistics', [ChatbotController::class, 'statistics'])->name('chatbot.statistics');
        
        // Quản lý mẫu câu trả lời
        Route::get('/chatbot/responses', [ChatbotController::class, 'responses'])->name('chatbot.responses');
        Route::post('/chatbot/responses', [ChatbotController::class, 'storeResponse'])->name('chatbot.responses.store');
        Route::put('/chatbot/responses/{id}', [ChatbotController::class, 'updateResponse'])->name('chatbot.responses.update');
        Route::delete('/chatbot/responses/{id}', [ChatbotController::class, 'destroyResponse'])->name('chatbot.responses.destroy');
        
        // Quản lý media
        Route::get('/media', [MediaController::class, 'index'])->name('media.index');
        Route::post('/media/upload', [MediaController::class, 'upload'])->name('media.upload');
        Route::delete('/media/delete', [MediaController::class, 'delete'])->name('media.delete');
        Route::post('/media/create-folder', [MediaController::class, 'createFolder'])->name('media.createFolder');
        
        // Báo cáo và thống kê
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/overview', [ReportController::class, 'overview'])->name('reports.overview');
        Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('/reports/top-products', [ReportController::class, 'topProducts'])->name('reports.topProducts');
        Route::get('/reports/top-customers', [ReportController::class, 'topCustomers'])->name('reports.topCustomers');
        Route::get('/reports/export-csv', [ReportController::class, 'exportCSV'])->name('reports.exportCSV');
        
        // Cài đặt hệ thống
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
