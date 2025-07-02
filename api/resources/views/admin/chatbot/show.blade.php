@extends('admin.layouts.app')

@section('title', 'Chi tiết cuộc hội thoại')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết cuộc hội thoại</h1>
        <div>
            <a href="{{ route('admin.chatbot.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
            </a>
            <button id="btnDelete" class="btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-trash fa-sm text-white-50"></i> Xóa
            </button>
        </div>
    </div>

    <!-- Thông tin hội thoại -->
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin hội thoại</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <h5 class="font-weight-bold">Người dùng</h5>
                        <p>{{ $conversation->user ? $conversation->user->name : 'Khách vãng lai' }}</p>
                    </div>
                    
                    <div class="mb-2">
                        <h5 class="font-weight-bold">Email</h5>
                        <p>{{ $conversation->user ? $conversation->user->email : 'Không có' }}</p>
                    </div>
                    
                    <div class="mb-2">
                        <h5 class="font-weight-bold">Thời gian bắt đầu</h5>
                        <p>{{ $conversation->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    
                    <div class="mb-2">
                        <h5 class="font-weight-bold">Thời gian cập nhật</h5>
                        <p>{{ $conversation->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    
                    <div class="mb-2">
                        <h5 class="font-weight-bold">Tổng số tin nhắn</h5>
                        <p>{{ $conversation->messages->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Nội dung hội thoại</h6>
                </div>
                <div class="card-body">
                    <div class="chat-container">
                        @if($conversation->messages->isEmpty())
                            <div class="text-center py-5">
                                <p class="text-muted">Không có tin nhắn trong cuộc hội thoại này</p>
                            </div>
                        @else
                            @foreach($conversation->messages as $message)
                                @if($message->is_bot)
                                    <!-- Tin nhắn từ bot -->
                                    <div class="chat-message bot-message">
                                        <div class="message-content">
                                            <div class="message-header">
                                                <span class="sender-name">Bot</span>
                                                <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                                            </div>
                                            <div class="message-body">
                                                {{ $message->message }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Tin nhắn từ người dùng -->
                                    <div class="chat-message user-message">
                                        <div class="message-content">
                                            <div class="message-header">
                                                <span class="sender-name">{{ $conversation->user ? $conversation->user->name : 'Khách vãng lai' }}</span>
                                                <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                                            </div>
                                            <div class="message-body">
                                                {{ $message->message }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa cuộc hội thoại này?</p>
                <p class="text-danger">Lưu ý: Hành động này không thể hoàn tác và sẽ xóa tất cả tin nhắn liên quan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <form action="{{ route('admin.chatbot.destroy', $conversation->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .chat-container {
        max-height: 500px;
        overflow-y: auto;
        padding: 10px;
    }
    
    .chat-message {
        display: flex;
        margin-bottom: 15px;
    }
    
    .user-message {
        justify-content: flex-end;
    }
    
    .bot-message {
        justify-content: flex-start;
    }
    
    .message-content {
        max-width: 80%;
        padding: 10px 15px;
        border-radius: 10px;
        position: relative;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .user-message .message-content {
        background-color: #4e73df;
        color: white;
        border-top-right-radius: 0;
    }
    
    .bot-message .message-content {
        background-color: #f8f9fc;
        color: #5a5c69;
        border-top-left-radius: 0;
        border: 1px solid #e3e6f0;
    }
    
    .message-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        font-size: 0.8rem;
    }
    
    .user-message .message-header {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .bot-message .message-header {
        color: #858796;
    }
    
    .message-body {
        word-break: break-word;
    }
    
    .sender-name {
        font-weight: bold;
    }
    
    .message-time {
        font-size: 0.75rem;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Cuộn xuống tin nhắn mới nhất
        const chatContainer = $('.chat-container');
        chatContainer.scrollTop(chatContainer[0].scrollHeight);
        
        // Xử lý sự kiện nút xóa
        $('#btnDelete').click(function() {
            $('#deleteModal').modal('show');
        });
    });
</script>
@endsection 