@extends('admin.layouts.invoice')

@section('title', 'Hóa đơn đơn hàng #' . $order->id)

@section('content')
<div class="invoice-box">
    <div class="header mb-4">
        <div class="row align-items-center">
            <div class="col-6">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="logo">
            </div>
            <div class="col-6 text-end">
                <h1 class="invoice-title">HÓA ĐƠN</h1>
                <div class="invoice-number">Mã đơn hàng: #{{ $order->id }}</div>
                <div class="invoice-date">Ngày: {{ $order->created_at->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>
    
    <hr class="my-4">
    
    <div class="row mb-5">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Thông tin cửa hàng</h5>
                    <p class="mb-1"><strong>Shop Điện Thoại</strong></p>
                    <p class="mb-1">Địa chỉ: 123 Đường ABC, Quận XYZ, TP. HCM</p>
                    <p class="mb-1">Điện thoại: (028) 1234 5678</p>
                    <p class="mb-1">Email: contact@shopmobile.com</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Thông tin khách hàng</h5>
                    <p class="mb-1"><strong>{{ $order->shipped_name }}</strong></p>
                    <p class="mb-1">Địa chỉ: {{ $order->shipped_address }}</p>
                    <p class="mb-1">Điện thoại: {{ $order->shipped_phone }}</p>
                    @if($order->user)
                        <p class="mb-1">Email: {{ $order->user->email }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Thông tin đơn hàng</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">STT</th>
                                    <th>Sản phẩm</th>
                                    <th class="text-end" style="width: 120px;">Giá</th>
                                    <th class="text-center" style="width: 80px;">SL</th>
                                    <th class="text-end" style="width: 150px;">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderDetails as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $item->product->name }}</div>
                                        @if($item->product->sku)
                                            <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                        @endif
                                    </td>
                                    <td class="text-end">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end fw-bold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Tổng tiền sản phẩm:</td>
                                    <td class="text-end fw-bold">{{ number_format($order->subtotal ?? $order->total_amount, 0, ',', '.') }} đ</td>
                                </tr>
                                @if(isset($order->shipped_fee) && $order->shipped_fee > 0)
                                <tr>
                                    <td colspan="4" class="text-end">Phí vận chuyển:</td>
                                    <td class="text-end">{{ number_format($order->shipped_fee, 0, ',', '.') }} đ</td>
                                </tr>
                                @endif
                                @if(isset($order->discount) && $order->discount > 0)
                                <tr>
                                    <td colspan="4" class="text-end">Giảm giá:</td>
                                    <td class="text-end">-{{ number_format($order->discount, 0, ',', '.') }} đ</td>
                                </tr>
                                @endif
                                <tr class="table-light">
                                    <td colspan="4" class="text-end fw-bold">Tổng thanh toán:</td>
                                    <td class="text-end fw-bold fs-5">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Thông tin thanh toán</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 250px;">Phương thức thanh toán</th>
                                <td>
                                    @if($order->payment_method == 'cod')
                                        <span class="badge bg-secondary">Thanh toán khi nhận hàng (COD)</span>
                                    @elseif($order->payment_method == 'bank_transfer')
                                        <span class="badge bg-primary">Chuyển khoản ngân hàng</span>
                                    @elseif($order->payment_method == 'momo')
                                        <span class="badge" style="background-color: #ae2070;">Ví MoMo</span>
                                    @elseif($order->payment_method == 'vnpay')
                                        <span class="badge" style="background-color: #0068b3;">VNPay</span>
                                    @elseif($order->payment_method == 'zalopay')
                                        <span class="badge" style="background-color: #0068ff;">ZaloPay</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $order->payment_method }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Trạng thái thanh toán</th>
                                <td>
                                    @if($order->payment_status == 'paid')
                                        <span class="badge bg-success">Đã thanh toán</span>
                                    @elseif($order->payment_status == 'pending')
                                        <span class="badge bg-warning text-dark">Chờ thanh toán</span>
                                    @else
                                        <span class="badge bg-danger">Thanh toán thất bại</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Trạng thái đơn hàng</th>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                    @elseif($order->status == 'processing')
                                        <span class="badge bg-primary">Đang xử lý</span>
                                    @elseif($order->status == 'shipped')
                                        <span class="badge bg-info">Đang giao hàng</span>
                                    @elseif($order->status == 'delivered')
                                        <span class="badge bg-success">Hoàn thành</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge bg-danger">Đã hủy</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $order->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($order->note)
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Ghi chú</h5>
                    <div class="p-3 bg-light rounded">
                        {{ $order->note }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <div class="row my-5">
        <div class="col-6 text-center">
            <h5>Người mua hàng</h5>
            <p>(Ký và ghi rõ họ tên)</p>
            <div style="height: 80px;"></div>
        </div>
        <div class="col-6 text-center">
            <h5>Người bán hàng</h5>
            <p>(Ký và ghi rõ họ tên)</p>
            <div style="height: 80px;"></div>
        </div>
    </div>
    
    <div class="footer text-center mt-5">
        <p class="fw-bold mb-1">Cảm ơn quý khách đã mua hàng!</p>
        <p class="small text-muted mb-0">Hóa đơn này được tạo tự động bởi hệ thống. Vui lòng liên hệ với chúng tôi nếu bạn có bất kỳ câu hỏi nào.</p>
    </div>
</div>
@endsection

@section('styles')
<style>
    .invoice-box {
        max-width: 900px;
        margin: auto;
        padding: 30px;
        font-size: 14px;
        line-height: 1.5;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #333;
    }
    
    .logo {
        max-height: 60px;
        max-width: 200px;
    }
    
    .invoice-title {
        font-size: 28px;
        color: #3d85c6;
        margin-bottom: 5px;
    }
    
    .invoice-number {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .invoice-date {
        font-size: 14px;
        color: #777;
    }
    
    .footer {
        border-top: 1px solid #eee;
        padding-top: 20px;
    }
    
    @media print {
        body {
            background-color: #fff;
        }
        
        .invoice-box {
            box-shadow: none;
            border: 0;
        }
        
        .no-print {
            display: none;
        }
    }
</style>
@endsection 