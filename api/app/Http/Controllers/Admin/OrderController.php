<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Order::with('user');
        
        // Tìm kiếm theo từ khóa
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('shipped_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('shipped_phone', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('user', function($uq) use ($searchTerm) {
                      $uq->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }
        
        // Lọc theo trạng thái đơn hàng
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Lọc theo trạng thái thanh toán
        if ($request->has('payment_status') && !empty($request->payment_status)) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Lọc theo phương thức thanh toán
        if ($request->has('payment_method') && !empty($request->payment_method)) {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Lọc theo khoảng thời gian
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Sắp xếp
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);
        
        // Lấy dữ liệu có phân trang
        $orders = $query->paginate(15);
        
        // Thống kê đơn hàng
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
        
        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Hiển thị thông tin chi tiết đơn hàng
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderDetails.product.brand', 'orderDetails.product.category', 'orderDetails.product.images']);
        return view('admin.orders.show', compact('order'));
    }
    
    /**
     * Hiển thị form cập nhật đơn hàng
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function edit(Order $order)
    {
        $order->load(['user', 'orderDetails.product']);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Cập nhật thông tin đơn hàng
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'shipped_name' => 'required|string|max:255',
            'shipped_phone' => 'required|string|max:20',
            'shipped_address' => 'required|string',
            'note' => 'nullable|string',
            'tracking_number' => 'nullable|string|max:100',
        ]);
        
        $order->status = $request->status;
        $order->payment_status = $request->payment_status;
        $order->shipped_name = $request->shipped_name;
        $order->shipped_phone = $request->shipped_phone;
        $order->shipped_address = $request->shipped_address;
        $order->note = $request->note;
        $order->tracking_number = $request->tracking_number;
        $order->save();
        
        return redirect()->route('admin.orders.show', $order)->with('success', 'Đã cập nhật đơn hàng thành công');
    }

    /**
     * Cập nhật trạng thái đơn hàng
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái đơn hàng thành công');
    }

    /**
     * Cập nhật trạng thái thanh toán
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order->payment_status = $request->payment_status;
        $order->save();

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái thanh toán thành công');
    }

    /**
     * Cập nhật thông tin vận chuyển
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:100',
        ]);

        $order->tracking_number = $request->tracking_number;
        $order->save();

        return redirect()->back()->with('success', 'Đã cập nhật thông tin vận chuyển thành công');
    }

    /**
     * Xuất hóa đơn đơn hàng
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function invoice(Order $order)
    {
        $order->load(['user', 'orderDetails.product']);
        return view('admin.orders.invoice', compact('order'));
    }
    
    /**
     * Xuất dữ liệu đơn hàng
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $format = $request->format ?? 'excel';
        
        // Xử lý logic xuất dữ liệu theo định dạng khác nhau
        switch ($format) {
            case 'csv':
                // Xuất CSV
                return response()->json(['message' => 'Tính năng xuất CSV đang được phát triển']);
                break;
                
            case 'pdf':
                // Xuất PDF
                return response()->json(['message' => 'Tính năng xuất PDF đang được phát triển']);
                break;
                
            case 'excel':
            default:
                // Xuất Excel
                return response()->json(['message' => 'Tính năng xuất Excel đang được phát triển']);
                break;
        }
    }
} 