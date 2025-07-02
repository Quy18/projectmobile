<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Hiển thị trang báo cáo chính
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.reports.index');
    }
    
    /**
     * Báo cáo tổng quan
     *
     * @return \Illuminate\Http\Response
     */
    public function overview(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonth = Carbon::now()->subMonth()->month;
        $lastMonthYear = Carbon::now()->subMonth()->year;
        
        // Doanh thu tháng này
        $currentMonthRevenue = Order::where('status', 'delivered')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_amount');
            
        // Doanh thu tháng trước
        $lastMonthRevenue = Order::where('status', 'delivered')
            ->whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastMonthYear)
            ->sum('total_amount');
            
        // Số đơn hàng tháng này
        $currentMonthOrders = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
            
        // Số đơn hàng tháng trước
        $lastMonthOrders = Order::whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastMonthYear)
            ->count();
            
        // Số người dùng mới tháng này
        $currentMonthUsers = User::where('role', 'user')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
            
        // Số người dùng mới tháng trước
        $lastMonthUsers = User::where('role', 'user')
            ->whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastMonthYear)
            ->count();
            
        // Trạng thái đơn hàng
        $orderStatuses = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
            
        // Phương thức thanh toán
        $paymentMethods = Order::select('payment_method', DB::raw('count(*) as total'))
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method')
            ->toArray();
            
        $revenueData = [
            'current' => $currentMonthRevenue,
            'last' => $lastMonthRevenue,
            'change' => $lastMonthRevenue > 0 ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2) : 0,
        ];
        
        $ordersData = [
            'current' => $currentMonthOrders,
            'last' => $lastMonthOrders,
            'change' => $lastMonthOrders > 0 ? round((($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 2) : 0,
        ];
        
        $usersData = [
            'current' => $currentMonthUsers,
            'last' => $lastMonthUsers,
            'change' => $lastMonthUsers > 0 ? round((($currentMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 2) : 0,
        ];
        
        // Nếu là Ajax request, trả về JSON
        if ($request->ajax()) {
            return response()->json([
                'revenue' => $revenueData,
                'orders' => $ordersData,
                'users' => $usersData,
                'orderStatuses' => $orderStatuses,
                'paymentMethods' => $paymentMethods
            ]);
        }
        
        return view('admin.reports.overview', compact(
            'revenueData',
            'ordersData',
            'usersData',
            'orderStatuses',
            'paymentMethods'
        ));
    }

    /**
     * Báo cáo doanh thu theo khoảng thời gian
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $groupBy = $request->input('group_by', 'day'); // day, month, year
        
        $query = Order::where('status', 'delivered')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            
        if ($groupBy == 'day') {
            $data = $query->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
                ->groupBy('date')
                ->get();
                
            $result = $data->pluck('total', 'date')->toArray();
            
            // Đảm bảo có đủ dữ liệu cho mỗi ngày trong khoảng thời gian
            $period = new \DatePeriod(
                new \DateTime($startDate),
                new \DateInterval('P1D'),
                new \DateTime(date('Y-m-d', strtotime($endDate . ' +1 day')))
            );
            
            $completeResult = [];
            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
                $completeResult[$dateStr] = $result[$dateStr] ?? 0;
            }
            
            $result = $completeResult;
        } elseif ($groupBy == 'month') {
            $data = $query->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_amount) as total')
                ->groupBy('year', 'month')
                ->get();
                
            $result = [];
            foreach ($data as $item) {
                $key = $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
                $result[$key] = $item->total;
            }
            
            // Đảm bảo có đủ dữ liệu cho mỗi tháng trong khoảng thời gian
            $startMonth = Carbon::parse($startDate)->startOfMonth();
            $endMonth = Carbon::parse($endDate)->startOfMonth();
            $period = new \DatePeriod(
                $startMonth,
                new \DateInterval('P1M'),
                $endMonth->addMonth()
            );
            
            $completeResult = [];
            foreach ($period as $date) {
                $dateStr = $date->format('Y-m');
                $completeResult[$dateStr] = $result[$dateStr] ?? 0;
            }
            
            $result = $completeResult;
        } else { // year
            $data = $query->selectRaw('YEAR(created_at) as year, SUM(total_amount) as total')
                ->groupBy('year')
                ->get();
                
            $result = $data->pluck('total', 'year')->toArray();
            
            // Đảm bảo có đủ dữ liệu cho mỗi năm trong khoảng thời gian
            $startYear = Carbon::parse($startDate)->year;
            $endYear = Carbon::parse($endDate)->year;
            
            $completeResult = [];
            for ($year = $startYear; $year <= $endYear; $year++) {
                $completeResult[$year] = $result[$year] ?? 0;
            }
            
            $result = $completeResult;
        }
        
        // Tính tổng doanh thu
        $totalRevenue = array_sum($result);
        
        // Nếu là Ajax request, trả về JSON
        if ($request->ajax()) {
            return response()->json([
                'data' => $result,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'group_by' => $groupBy,
                'total_revenue' => $totalRevenue
            ]);
        }
        
        return view('admin.reports.revenue', compact(
            'result',
            'startDate',
            'endDate',
            'groupBy',
            'totalRevenue'
        ));
    }

    /**
     * Báo cáo sản phẩm bán chạy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function topProducts(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $limit = $request->input('limit', 10);
        
        $topProducts = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('orders.status', 'delivered')
            ->selectRaw('products.id, products.name, products.slug, SUM(order_details.quantity) as total_quantity, SUM(order_details.price * order_details.quantity) as total_amount')
            ->groupBy('products.id', 'products.name', 'products.slug')
            ->orderBy('total_quantity', 'desc')
            ->limit($limit)
            ->get();
        
        // Thêm hình ảnh cho mỗi sản phẩm
        foreach ($topProducts as $product) {
            $mainImage = DB::table('product_images')
                ->where('product_id', $product->id)
                ->where('is_main', true)
                ->first();
                
            $product->image = $mainImage ? $mainImage->image : null;
        }
            
        // Nếu là Ajax request, trả về JSON
        if ($request->ajax()) {
            return response()->json([
                'data' => $topProducts,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        }
        
        return view('admin.reports.top_products', compact(
            'topProducts',
            'startDate',
            'endDate',
            'limit'
        ));
    }

    /**
     * Báo cáo khách hàng tiềm năng
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function topCustomers(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $limit = $request->input('limit', 10);
        
        $topCustomers = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('orders.status', 'delivered')
            ->selectRaw('users.id, users.name, users.email, COUNT(orders.id) as total_orders, SUM(orders.total_amount) as total_spent')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->limit($limit)
            ->get();
            
        // Nếu là Ajax request, trả về JSON
        if ($request->ajax()) {
            return response()->json([
                'data' => $topCustomers,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        }
        
        return view('admin.reports.top_customers', compact(
            'topCustomers',
            'startDate',
            'endDate',
            'limit'
        ));
    }

    /**
     * Xuất báo cáo dạng CSV
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportCSV(Request $request)
    {
        $type = $request->input('type', 'orders');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        
        $fileName = $type . '_' . $startDate . '_to_' . $endDate . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        
        $callback = function () use ($type, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            if ($type == 'orders') {
                // Đầu dòng CSV
                fputcsv($file, [
                    'ID', 'Ngày đặt hàng', 'Khách hàng', 'Email', 'Số điện thoại', 
                    'Tổng tiền', 'Phương thức thanh toán', 'Trạng thái', 'Trạng thái thanh toán'
                ]);
                
                // Dữ liệu
                $orders = Order::with('user')
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->get();
                    
                foreach ($orders as $order) {
                    fputcsv($file, [
                        $order->id,
                        $order->created_at,
                        $order->user ? $order->user->name : $order->shipped_name,
                        $order->user ? $order->user->email : '',
                        $order->shipped_phone,
                        $order->total_amount,
                        $order->payment_method,
                        $order->status,
                        $order->payment_status,
                    ]);
                }
            } elseif ($type == 'products') {
                // Đầu dòng CSV
                fputcsv($file, [
                    'ID', 'Tên sản phẩm', 'SKU', 'Danh mục', 'Thương hiệu', 
                    'Giá gốc', 'Giá khuyến mãi', 'Số lượng tồn kho', 'Đã bán', 'Trạng thái'
                ]);
                
                // Dữ liệu
                $products = Product::with(['category', 'brand'])
                    ->get();
                
                foreach ($products as $product) {
                    // Tính tổng số đã bán
                    $totalSold = DB::table('order_details')
                        ->join('orders', 'order_details.order_id', '=', 'orders.id')
                        ->where('order_details.product_id', $product->id)
                        ->where('orders.status', 'delivered')
                        ->sum('order_details.quantity');
                    
                    fputcsv($file, [
                        $product->id,
                        $product->name,
                        $product->sku,
                        $product->category ? $product->category->name : '',
                        $product->brand ? $product->brand->name : '',
                        $product->price,
                        $product->sale_price,
                        $product->quantity,
                        $totalSold,
                        $product->status,
                    ]);
                }
            } elseif ($type == 'users') {
                // Đầu dòng CSV
                fputcsv($file, [
                    'ID', 'Tên', 'Email', 'Số điện thoại', 'Địa chỉ', 
                    'Ngày đăng ký', 'Số đơn hàng', 'Tổng chi tiêu'
                ]);
                
                // Dữ liệu
                $users = User::where('role', 'user')->get();
                
                foreach ($users as $user) {
                    // Tính số đơn hàng và tổng chi tiêu
                    $orderStats = DB::table('orders')
                        ->where('user_id', $user->id)
                        ->where('status', 'delivered')
                        ->selectRaw('COUNT(id) as total_orders, SUM(total_amount) as total_spent')
                        ->first();
                    
                    fputcsv($file, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->phone,
                        $user->address,
                        $user->created_at,
                        $orderStats->total_orders ?? 0,
                        $orderStats->total_spent ?? 0,
                    ]);
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
} 