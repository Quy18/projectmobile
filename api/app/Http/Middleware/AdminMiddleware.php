<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Kiểm tra xem người dùng có quyền admin hoặc staff không
        if (!in_array($request->user()->role, ['admin', 'staff'])) {
            return response()->json(['message' => 'Bạn không có quyền truy cập vào trang quản trị'], 403);
        }

        return $next($request);
    }
} 