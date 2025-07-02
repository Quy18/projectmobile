<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Tạo đơn hàng mới
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'payment_method' => 'required|in:cod,bank_transfer',
            'notes' => 'nullable|string',
            'cart_items' => 'required|array',
            'cart_items.*.product_id' => 'required|integer|exists:products,id',
            'cart_items.*.quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xác thực dữ liệu',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $userId = auth()->id();
        $isAuthenticated = (bool) $userId;
        
        // Kiểm tra giỏ hàng
        if (empty($request->cart_items)) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng trống, không thể đặt hàng'
            ], 400);
        }
        
        try {
            // Bắt đầu giao dịch (nếu tất cả các thao tác đều thành công thì sẽ commit, nếu có lỗi thì sẽ rollback)
            DB::beginTransaction();
            
            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $userId ?? null,
                'total_amount' => $request->total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipped_name' => $request->name,
                'shipped_phone' => $request->phone,
                'shipped_address' => $request->address,
                'note' => $request->notes,
            ]);
            
            // Tạo chi tiết đơn hàng
            foreach ($request->cart_items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $price = $item['price'] ?? ($product->sale_price ?? $product->price);
                
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $price
                ]);
                
                // Cập nhật số lượng sản phẩm
                $product->quantity -= $item['quantity'];
                $product->save();
            }
            
            // Xóa giỏ hàng nếu người dùng đã đăng nhập
            if ($isAuthenticated) {
                Cart::where('user_id', $userId)->delete();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Đặt hàng thành công',
                'data' => [
                    'order' => $order->load('orderDetails.product')
                ]
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi trong quá trình đặt hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách đơn hàng của người dùng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrders(Request $request)
    {
        $userId = auth()->id();
        $perPage = $request->input('per_page', 10);
        
        $query = Order::with('orderDetails.product')
            ->where('user_id', $userId);
        
        // Lọc theo trạng thái
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Sắp xếp theo thời gian tạo, mới nhất lên đầu
        $query->orderBy('created_at', 'desc');
        
        $orders = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Lấy chi tiết đơn hàng
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrder($id)
    {
        $userId = auth()->id();
        
        $order = Order::with(['orderDetails.product', 'orderDetails.product.images'])
            ->where('user_id', $userId)
            ->where('id', $id)
            ->firstOrFail();
        
        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Hủy đơn hàng
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelOrder($id)
    {
        $userId = auth()->id();
        
        $order = Order::with('orderDetails.product')
            ->where('user_id', $userId)
            ->where('id', $id)
            ->firstOrFail();
        
        // Chỉ được hủy đơn hàng khi trạng thái là pending hoặc processing
        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy đơn hàng ở trạng thái này'
            ], 400);
        }
        
        try {
            DB::beginTransaction();
            
            // Cập nhật trạng thái đơn hàng
            $order->status = 'cancelled';
            $order->save();
            
            // Hoàn lại số lượng sản phẩm
            foreach ($order->orderDetails as $detail) {
                $product = $detail->product;
                $product->quantity += $detail->quantity;
                $product->save();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Đã hủy đơn hàng thành công',
                'data' => $order
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi trong quá trình hủy đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }
} 