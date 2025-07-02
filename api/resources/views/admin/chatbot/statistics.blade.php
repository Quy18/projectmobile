@extends('admin.layouts.app')

@section('title', 'Thống kê Chatbot')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thống kê Chatbot</h1>
        <a href="{{ route('admin.chatbot.index') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-comments fa-sm text-white-50"></i> Quản lý hội thoại
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng số cuộc hội thoại</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalConversations }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tổng số tin nhắn</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMessages }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Người dùng đã sử dụng</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usersUsingChatbot }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Khách vãng lai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $guestsUsingChatbot }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-secret fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Tin nhắn theo loại -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Phân bố tin nhắn</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="messageTypeChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Người dùng ({{ $messagesByType['user'] }})
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Chatbot ({{ $messagesByType['bot'] }})
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ tin nhắn theo ngày -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê tin nhắn 7 ngày qua</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="messagesPerDayChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Top từ khóa sử dụng nhiều nhất -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top từ khóa phổ biến</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="10%">STT</th>
                                    <th width="40%">Từ khóa</th>
                                    <th width="30%">Số lần sử dụng</th>
                                    <th width="20%">Độ ưu tiên</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topKeywords as $index => $keyword)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $keyword->keyword }}</td>
                                    <td>{{ $keyword->conversation_messages_count }}</td>
                                    <td>{{ $keyword->priority }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê theo thời gian -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê theo thời gian</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-lg-6 mb-4">
                            <div class="card bg-primary text-white shadow">
                                <div class="card-body">
                                    Hội thoại 7 ngày qua
                                    <div class="text-white-50 small">{{ $conversationsLast7Days }} cuộc hội thoại</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card bg-success text-white shadow">
                                <div class="card-body">
                                    Tin nhắn 7 ngày qua
                                    <div class="text-white-50 small">{{ $messagesPerDay->sum('count') }} tin nhắn</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>Thống kê hoạt động chatbot theo thời gian giúp theo dõi và phân tích xu hướng sử dụng của người dùng, qua đó cải thiện chất lượng phản hồi và tối ưu trải nghiệm người dùng.</p>
                    <p class="mb-0">Độ ưu tiên của từ khóa được thiết lập từ 1-10, với 10 là mức ưu tiên cao nhất. Hệ thống sẽ ưu tiên từ khóa có độ ưu tiên cao hơn khi có nhiều từ khóa phù hợp trong một câu hỏi.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ phân bố tin nhắn
    var ctx1 = document.getElementById("messageTypeChart").getContext('2d');
    var messageTypeChart = new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ["Người dùng", "Chatbot"],
            datasets: [{
                data: [{{ $messagesByType['user'] }}, {{ $messagesByType['bot'] }}],
                backgroundColor: ['#4e73df', '#1cc88a'],
                hoverBackgroundColor: ['#2e59d9', '#17a673'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,
        },
    });

    // Biểu đồ tin nhắn theo ngày
    var ctx2 = document.getElementById("messagesPerDayChart").getContext('2d');
    var messagesPerDayChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: [
                @foreach($messagesPerDay as $day)
                    "{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}",
                @endforeach
            ],
            datasets: [{
                label: "Số tin nhắn",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: [
                    @foreach($messagesPerDay as $day)
                        {{ $day->count }},
                    @endforeach
                ],
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        beginAtZero: true
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': ' + tooltipItem.yLabel;
                    }
                }
            }
        }
    });
</script>
@endsection 