<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Lấy giỏ hàng của người dùng hiện tại
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCart(Request $request)
    {
        $userId = auth()->id();
        
        $cartItems = Cart::with(['product', 'product.images' => function($query) {
                $query->where('is_main', true)->first();
            }])
            ->where('user_id', $userId)
            ->get();
        
        // Tính tổng tiền
        $total = 0;
        foreach ($cartItems as $item) {
            $price = $item->product->sale_price ?? $item->product->price;
            $total += $price * $item->quantity;
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'items' => $cartItems,
                'total' => $total,
                'count' => $cartItems->count()
            ]
        ]);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xác thực dữ liệu',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $userId = auth()->id();
        $productId = $request->product_id;
        $quantity = $request->quantity;
        
        // Kiểm tra sản phẩm
        $product = Product::findOrFail($productId);
        
        // Kiểm tra số lượng tồn kho
        if ($product->quantity < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng sản phẩm trong kho không đủ'
            ], 400);
        }
        
        // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        $existingItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
        
        if ($existingItem) {
            // Nếu đã có, cập nhật số lượng
            $existingItem->quantity += $quantity;
            $existingItem->save();
            
            $cartItem = $existingItem;
            $message = 'Đã cập nhật số lượng trong giỏ hàng';
        } else {
            // Nếu chưa có, thêm mới
            $cartItem = Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
            
            $message = 'Đã thêm sản phẩm vào giỏ hàng';
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $cartItem
        ]);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCartItem($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xác thực dữ liệu',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $userId = auth()->id();
        $quantity = $request->quantity;
        
        // Tìm item trong giỏ hàng
        $cartItem = Cart::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
        
        // Kiểm tra số lượng tồn kho
        $product = Product::findOrFail($cartItem->product_id);
        if ($product->quantity < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng sản phẩm trong kho không đủ'
            ], 400);
        }
        
        // Cập nhật số lượng
        $cartItem->quantity = $quantity;
        $cartItem->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật số lượng sản phẩm',
            'data' => $cartItem
        ]);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeCartItem($id)
    {
        $userId = auth()->id();
        
        // Tìm item trong giỏ hàng
        $cartItem = Cart::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
        
        // Xóa item
        $cartItem->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng'
        ]);
    }

    /**
     * Xóa toàn bộ giỏ hàng
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCart()
    {
        $userId = auth()->id();
        
        // Xóa tất cả items trong giỏ hàng
        Cart::where('user_id', $userId)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa toàn bộ giỏ hàng'
        ]);
    }

    /**
     * Lấy danh sách sản phẩm yêu thích
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWishlist()
    {
        $userId = auth()->id();
        
        $wishlistItems = Wishlist::with(['product', 'product.images' => function($query) {
                $query->where('is_main', true)->first();
            }])
            ->where('user_id', $userId)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $wishlistItems
        ]);
    }

    /**
     * Thêm sản phẩm vào danh sách yêu thích
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToWishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xác thực dữ liệu',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $userId = auth()->id();
        $productId = $request->product_id;
        
        // Kiểm tra sản phẩm đã có trong wishlist chưa
        $existingItem = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
        
        if ($existingItem) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm đã có trong danh sách yêu thích'
            ], 400);
        }
        
        // Thêm vào wishlist
        $wishlistItem = Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào danh sách yêu thích',
            'data' => $wishlistItem
        ]);
    }

    /**
     * Xóa sản phẩm khỏi danh sách yêu thích
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromWishlist($id)
    {
        $userId = auth()->id();
        
        // Tìm item trong wishlist
        $wishlistItem = Wishlist::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
        
        // Xóa item
        $wishlistItem->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích'
        ]);
    }
} 