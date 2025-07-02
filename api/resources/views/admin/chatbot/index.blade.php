@extends('admin.layouts.app')

@section('title', 'Quản lý Chatbot')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý Chatbot</h1>
    </div>

    <!-- Thẻ điều hướng -->
    <ul class="nav nav-tabs mb-4" id="chatbotTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="statistics-tab" data-toggle="tab" href="#statistics" role="tab" aria-controls="statistics" aria-selected="true">
                <i class="fas fa-chart-bar mr-1"></i> Thống kê
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="responses-tab" data-toggle="tab" href="#responses" role="tab" aria-controls="responses" aria-selected="false">
                <i class="fas fa-reply-all mr-1"></i> Mẫu câu trả lời
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="conversations-tab" data-toggle="tab" href="#conversations" role="tab" aria-controls="conversations" aria-selected="false">
                <i class="fas fa-comments mr-1"></i> Lịch sử hội thoại
            </a>
        </li>
    </ul>

    <!-- Nội dung tab -->
    <div class="tab-content" id="chatbotTabContent">
        <!-- Tab Thống kê -->
        <div class="tab-pane fade show active" id="statistics" role="tabpanel" aria-labelledby="statistics-tab">
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng cuộc hội thoại</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-conversations">0</div>
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
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tổng tin nhắn</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-messages">0</div>
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
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tin nhắn người dùng</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="user-messages">0</div>
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
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tin nhắn bot</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="bot-messages">0</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-robot fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Tab Mẫu câu trả lời -->
        <div class="tab-pane fade" id="responses" role="tabpanel" aria-labelledby="responses-tab">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách mẫu câu trả lời</h6>
                    <button class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addResponseModal">
                        <i class="fas fa-plus fa-sm text-white-50"></i> Thêm mới
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="responsesTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="5%">STT</th>
                                    <th width="30%">Từ khóa</th>
                                    <th width="50%">Phản hồi</th>
                                    <th width="15%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="responses-list">
                                <tr>
                                    <td colspan="4" class="text-center">Đang tải dữ liệu...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Lịch sử hội thoại -->
        <div class="tab-pane fade" id="conversations" role="tabpanel" aria-labelledby="conversations-tab">
            <div class="row">
                <div class="col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Danh sách cuộc hội thoại</h6>
                        </div>
                        <div class="card-body">
                            <div id="conversations-list" class="list-group">
                                <div class="text-center py-4">
                                    <p>Đang tải dữ liệu...</p>
                                </div>
                            </div>
                            <div class="mt-3 d-flex justify-content-center" id="conversations-pagination">
                                <!-- Phân trang sẽ được hiển thị ở đây -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Chi tiết hội thoại</h6>
                            <div>
                                <button id="btnDeleteConversation" class="btn btn-sm btn-danger" style="display: none;">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="conversation-detail">
                                <div class="text-center py-5">
                                    <p class="text-muted">Chọn một cuộc hội thoại để xem chi tiết</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal thêm mẫu câu trả lời -->
<div class="modal fade" id="addResponseModal" tabindex="-1" role="dialog" aria-labelledby="addResponseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addResponseModalLabel">Thêm mẫu câu trả lời mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addResponseForm">
                    <div class="form-group">
                        <label for="keyword">Từ khóa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="keyword" name="keyword" required placeholder="Nhập từ khóa để kích hoạt phản hồi">
                        <small class="form-text text-muted">Các từ khóa nên ngắn gọn và dễ nhớ. Ví dụ: "chào", "giờ mở cửa", "sản phẩm"...</small>
                    </div>
                    <div class="form-group">
                        <label for="response">Phản hồi <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="response" name="response" rows="5" required placeholder="Nhập nội dung phản hồi khi nhận được từ khóa"></textarea>
                        <small class="form-text text-muted">Phản hồi nên đầy đủ thông tin và hữu ích cho khách hàng.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="btnSaveResponse">Lưu</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa mẫu câu -->
<div class="modal fade" id="deleteResponseModal" tabindex="-1" role="dialog" aria-labelledby="deleteResponseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteResponseModalLabel">Xác nhận xóa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa mẫu câu trả lời này không?</p>
                <p class="text-danger">Lưu ý: Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="btnConfirmDeleteResponse">Xóa</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa cuộc hội thoại -->
<div class="modal fade" id="deleteConversationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConversationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConversationModalLabel">Xác nhận xóa cuộc hội thoại</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa cuộc hội thoại này và tất cả tin nhắn trong đó?</p>
                <p class="text-danger">Lưu ý: Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="btnConfirmDeleteConversation">Xóa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let currentConversationId = null;
    let currentResponseIndex = null;
    let currentPage = 1;
    let totalPages = 1;

    $(document).ready(function() {
        // Tải thống kê ban đầu
        loadStatistics();
        
        // Xử lý chuyển tab
        $('#chatbotTab a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
            
            const tabId = $(this).attr('href');
            if (tabId === '#responses' && $('#responses-list td').length === 1) {
                loadResponses();
            } else if (tabId === '#conversations' && $('#conversations-list div').hasClass('text-center')) {
                loadConversations(1);
            }
        });
        
        // Xử lý thêm mẫu câu trả lời
        $('#btnSaveResponse').on('click', function() {
            saveResponse();
        });
        
        // Xử lý xóa mẫu câu trả lời
        $('#btnConfirmDeleteResponse').on('click', function() {
            deleteResponse();
        });
        
        // Xử lý xóa cuộc hội thoại
        $('#btnDeleteConversation').on('click', function() {
            $('#deleteConversationModal').modal('show');
        });
        
        $('#btnConfirmDeleteConversation').on('click', function() {
            deleteConversation();
        });
    });
    
    function loadStatistics() {
        $.ajax({
            url: '/admin/chatbot/statistics',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                updateStatisticsUI(data);
            },
            error: function(xhr) {
                console.error('Lỗi khi tải thống kê:', xhr.responseText);
                toastr.error('Có lỗi xảy ra khi tải dữ liệu thống kê.');
            }
        });
    }
    
    function updateStatisticsUI(data) {
        // Cập nhật số liệu thống kê
        $('#total-conversations').text(data.totalConversations);
        $('#total-messages').text(data.totalMessages);
        $('#user-messages').text(data.userMessages);
        $('#bot-messages').text(data.botMessages);
        
        // Hiển thị cuộc hội thoại gần đây
        const recentHtml = data.recentConversations.length > 0 
            ? data.recentConversations.map((conv, index) => {
                const lastMessage = conv.messages[0] ? conv.messages[0].message : 'Không có tin nhắn';
                const username = conv.user ? conv.user.name : 'Khách vãng lai';
                return `
                    <div class="d-flex align-items-center mb-3 ${index < data.recentConversations.length - 1 ? 'border-bottom pb-3' : ''}">
                        <div class="mr-3">
                            <div class="icon-circle bg-primary text-white">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">${moment(conv.created_at).format('DD/MM/YYYY HH:mm')}</div>
                            <div class="font-weight-bold">${username}</div>
                            <div class="text-truncate" style="max-width: 250px;">${lastMessage}</div>
                        </div>
                    </div>
                `;
            }).join('')
            : '<div class="text-center py-3"><p class="text-muted">Chưa có cuộc hội thoại nào</p></div>';
            
        $('#recent-conversations').html(recentHtml);
        
        // Vẽ biểu đồ
        drawMonthlyChart(data.monthlyConversations);
    }
    
    function drawMonthlyChart(monthlyData) {
        const labels = [];
        const data = [];
        
        for (let i = 1; i <= 12; i++) {
            labels.push('Tháng ' + i);
            data.push(monthlyData[i] || 0);
        }
        
        const ctx = document.getElementById('conversationsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Số cuộc hội thoại',
                    data: data,
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    
    function loadResponses() {
        $.ajax({
            url: '/admin/chatbot/responses',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                renderResponses(data);
            },
            error: function(xhr) {
                console.error('Lỗi khi tải mẫu câu trả lời:', xhr.responseText);
                toastr.error('Có lỗi xảy ra khi tải mẫu câu trả lời.');
            }
        });
    }
    
    function renderResponses(responses) {
        if (responses.length === 0) {
            $('#responses-list').html('<tr><td colspan="4" class="text-center">Chưa có mẫu câu trả lời nào</td></tr>');
            return;
        }
        
        const html = responses.map((item, index) => {
            return `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td>${escapeHtml(item.keyword)}</td>
                <td>${escapeHtml(item.response)}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-danger delete-response" data-index="${index}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            `;
        }).join('');
        
        $('#responses-list').html(html);
        
        // Gắn sự kiện xóa
        $('.delete-response').on('click', function() {
            currentResponseIndex = $(this).data('index');
            $('#deleteResponseModal').modal('show');
        });
    }
    
    function saveResponse() {
        const keyword = $('#keyword').val().trim();
        const response = $('#response').val().trim();
        
        if (!keyword || !response) {
            toastr.error('Vui lòng nhập đầy đủ thông tin.');
            return;
        }
        
        $.ajax({
            url: '/admin/chatbot/responses',
            type: 'POST',
            data: {
                keyword: keyword,
                response: response,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('#addResponseModal').modal('hide');
                $('#keyword').val('');
                $('#response').val('');
                renderResponses(data.data);
                toastr.success('Thêm mẫu câu trả lời thành công!');
            },
            error: function(xhr) {
                console.error('Lỗi khi thêm mẫu câu trả lời:', xhr.responseText);
                toastr.error('Có lỗi xảy ra khi thêm mẫu câu trả lời.');
            }
        });
    }
    
    function deleteResponse() {
        if (currentResponseIndex === null) return;
        
        $.ajax({
            url: '/admin/chatbot/responses/delete',
            type: 'POST',
            data: {
                index: currentResponseIndex,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('#deleteResponseModal').modal('hide');
                renderResponses(data.data);
                currentResponseIndex = null;
                toastr.success('Xóa mẫu câu trả lời thành công!');
            },
            error: function(xhr) {
                console.error('Lỗi khi xóa mẫu câu trả lời:', xhr.responseText);
                toastr.error('Có lỗi xảy ra khi xóa mẫu câu trả lời.');
            }
        });
    }
    
    function loadConversations(page) {
        currentPage = page;
        
        $.ajax({
            url: `/admin/chatbot/conversations?page=${page}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                renderConversations(data);
            },
            error: function(xhr) {
                console.error('Lỗi khi tải danh sách hội thoại:', xhr.responseText);
                toastr.error('Có lỗi xảy ra khi tải danh sách hội thoại.');
            }
        });
    }
    
    function renderConversations(data) {
        const conversations = data.data;
        totalPages = data.last_page;
        
        if (conversations.length === 0) {
            $('#conversations-list').html('<div class="text-center py-4"><p class="text-muted">Chưa có cuộc hội thoại nào</p></div>');
            $('#conversations-pagination').html('');
            return;
        }
        
        const html = conversations.map(conv => {
            const date = moment(conv.created_at).format('DD/MM/YYYY HH:mm');
            const username = conv.user ? conv.user.name : 'Khách vãng lai';
            const messageCount = conv.messages_count || 0;
            
            return `
            <a href="javascript:void(0)" class="list-group-item list-group-item-action conversation-item" data-id="${conv.id}">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">${username}</h5>
                    <small>${date}</small>
                </div>
                <p class="mb-1 text-muted">
                    <i class="fas fa-comments mr-1"></i> ${messageCount} tin nhắn
                </p>
            </a>
            `;
        }).join('');
        
        $('#conversations-list').html(html);
        
        // Tạo phân trang
        renderPagination(totalPages, currentPage);
        
        // Gắn sự kiện click vào cuộc hội thoại
        $('.conversation-item').on('click', function() {
            currentConversationId = $(this).data('id');
            $('.conversation-item').removeClass('active');
            $(this).addClass('active');
            loadConversationDetail(currentConversationId);
        });
    }
    
    function renderPagination(totalPages, currentPage) {
        if (totalPages <= 1) {
            $('#conversations-pagination').html('');
            return;
        }
        
        let html = '<ul class="pagination pagination-rounded justify-content-center mb-0">';
        
        // Nút Previous
        html += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" data-page="${currentPage - 1}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        `;
        
        // Các trang
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                html += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0)" data-page="${i}">${i}</a>
                </li>
                `;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                html += '<li class="page-item disabled"><a class="page-link" href="javascript:void(0)">...</a></li>';
            }
        }
        
        // Nút Next
        html += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" data-page="${currentPage + 1}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
        `;
        
        html += '</ul>';
        
        $('#conversations-pagination').html(html);
        
        // Gắn sự kiện click vào phân trang
        $('#conversations-pagination .page-link').on('click', function() {
            if (!$(this).parent().hasClass('disabled')) {
                const page = $(this).data('page');
                loadConversations(page);
            }
        });
    }
    
    function loadConversationDetail(conversationId) {
        $('#conversation-detail').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Đang tải...</span></div></div>');
        $('#btnDeleteConversation').hide();
        
        $.ajax({
            url: `/admin/chatbot/conversations/${conversationId}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                renderConversationDetail(data);
                $('#btnDeleteConversation').show();
            },
            error: function(xhr) {
                console.error('Lỗi khi tải chi tiết hội thoại:', xhr.responseText);
                toastr.error('Có lỗi xảy ra khi tải chi tiết hội thoại.');
                $('#conversation-detail').html('<div class="text-center py-5"><p class="text-danger">Không thể tải chi tiết hội thoại. Vui lòng thử lại sau.</p></div>');
            }
        });
    }
    
    function renderConversationDetail(conversation) {
        const messages = conversation.messages;
        const username = conversation.user ? conversation.user.name : 'Khách vãng lai';
        
        let html = `
        <div class="mb-3">
            <h5 class="mb-1">Thông tin người dùng</h5>
            <p class="mb-0"><strong>Tên:</strong> ${username}</p>
            <p class="mb-0"><strong>Thời gian bắt đầu:</strong> ${moment(conversation.created_at).format('DD/MM/YYYY HH:mm')}</p>
            <p class="mb-0"><strong>Thời gian cập nhật:</strong> ${moment(conversation.updated_at).format('DD/MM/YYYY HH:mm')}</p>
        </div>
        <hr>
        <h5 class="mb-3">Nội dung hội thoại</h5>
        `;
        
        if (messages.length === 0) {
            html += '<div class="text-center py-3"><p class="text-muted">Chưa có tin nhắn nào</p></div>';
        } else {
            html += '<div class="chat-messages">';
            
            messages.forEach(message => {
                const time = moment(message.created_at).format('HH:mm');
                const date = moment(message.created_at).format('DD/MM/YYYY');
                
                if (message.is_bot) {
                    // Tin nhắn từ bot
                    html += `
                    <div class="d-flex flex-row justify-content-start mb-4">
                        <div class="p-3 ms-3 rounded-3 bg-light">
                            <p class="small mb-0">${escapeHtml(message.message)}</p>
                            <p class="small text-muted mt-2 mb-0">${time} | ${date}</p>
                        </div>
                    </div>
                    `;
                } else {
                    // Tin nhắn từ người dùng
                    html += `
                    <div class="d-flex flex-row justify-content-end mb-4">
                        <div class="p-3 me-3 border rounded-3 bg-primary text-white">
                            <p class="small mb-0">${escapeHtml(message.message)}</p>
                            <p class="small text-muted mt-2 mb-0 text-white-50">${time} | ${date}</p>
                        </div>
                    </div>
                    `;
                }
            });
            
            html += '</div>';
        }
        
        $('#conversation-detail').html(html);
    }
    
    function deleteConversation() {
        if (!currentConversationId) return;
        
        $.ajax({
            url: `/admin/chatbot/conversations/${currentConversationId}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                $('#deleteConversationModal').modal('hide');
                toastr.success('Xóa cuộc hội thoại thành công!');
                
                // Reset UI và tải lại danh sách
                $('#btnDeleteConversation').hide();
                $('#conversation-detail').html('<div class="text-center py-5"><p class="text-muted">Chọn một cuộc hội thoại để xem chi tiết</p></div>');
                currentConversationId = null;
                loadConversations(currentPage);
            },
            error: function(xhr) {
                console.error('Lỗi khi xóa cuộc hội thoại:', xhr.responseText);
                toastr.error('Có lỗi xảy ra khi xóa cuộc hội thoại.');
            }
        });
    }
    
    // Hàm hỗ trợ để escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
</script>
@endsection
