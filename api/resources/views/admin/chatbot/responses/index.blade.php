@extends('admin.layouts.app')

@section('title', 'Quản lý mẫu câu trả lời')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý mẫu câu trả lời</h1>
        <a href="#" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addResponseModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Thêm mẫu câu mới
        </a>
    </div>

    <!-- Content Row -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách mẫu câu trả lời</h6>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Từ khóa</th>
                            <th width="45%">Câu trả lời</th>
                            <th width="15%">Độ ưu tiên</th>
                            <th width="15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($responses as $response)
                            <tr>
                                <td>{{ $response->id }}</td>
                                <td>{{ $response->keyword }}</td>
                                <td>{{ Str::limit($response->response, 100) }}</td>
                                <td>{{ $response->priority }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm btn-edit" 
                                            data-id="{{ $response->id }}"
                                            data-keyword="{{ $response->keyword }}"
                                            data-response="{{ $response->response }}"
                                            data-priority="{{ $response->priority }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $response->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Không có dữ liệu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <div class="d-flex justify-content-end">
                    {{ $responses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm mẫu câu trả lời -->
<div class="modal fade" id="addResponseModal" tabindex="-1" role="dialog" aria-labelledby="addResponseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addResponseModalLabel">Thêm mẫu câu trả lời mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.chatbot.responses.store') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="keyword">Từ khóa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="keyword" name="keyword" required>
                        <small class="form-text text-muted">Nhập từ khóa để bot nhận diện. Nhiều từ khóa có thể cách nhau bởi dấu phẩy.</small>
                    </div>
                    <div class="form-group">
                        <label for="response">Câu trả lời <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="response" name="response" rows="4" required></textarea>
                        <small class="form-text text-muted">Nhập câu trả lời mà bot sẽ gửi khi phát hiện từ khóa.</small>
                    </div>
                    <div class="form-group">
                        <label for="priority">Độ ưu tiên</label>
                        <input type="number" class="form-control" id="priority" name="priority" value="1" min="1" max="10">
                        <small class="form-text text-muted">Số càng cao thì độ ưu tiên càng cao (1-10).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa mẫu câu trả lời -->
<div class="modal fade" id="editResponseModal" tabindex="-1" role="dialog" aria-labelledby="editResponseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editResponseModalLabel">Sửa mẫu câu trả lời</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editResponseForm" method="POST">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit_keyword">Từ khóa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_keyword" name="keyword" required>
                        <small class="form-text text-muted">Nhập từ khóa để bot nhận diện. Nhiều từ khóa có thể cách nhau bởi dấu phẩy.</small>
                    </div>
                    <div class="form-group">
                        <label for="edit_response">Câu trả lời <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_response" name="response" rows="4" required></textarea>
                        <small class="form-text text-muted">Nhập câu trả lời mà bot sẽ gửi khi phát hiện từ khóa.</small>
                    </div>
                    <div class="form-group">
                        <label for="edit_priority">Độ ưu tiên</label>
                        <input type="number" class="form-control" id="edit_priority" name="priority" min="1" max="10">
                        <small class="form-text text-muted">Số càng cao thì độ ưu tiên càng cao (1-10).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa -->
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
                <p>Bạn có chắc chắn muốn xóa mẫu câu trả lời này?</p>
                <p class="text-danger">Lưu ý: Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <form id="deleteResponseForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Xử lý nút sửa
        $('.btn-edit').click(function() {
            const id = $(this).data('id');
            const keyword = $(this).data('keyword');
            const response = $(this).data('response');
            const priority = $(this).data('priority');
            
            $('#edit_keyword').val(keyword);
            $('#edit_response').val(response);
            $('#edit_priority').val(priority);
            
            const actionUrl = "{{ route('admin.chatbot.responses.update', ['id' => ':id']) }}".replace(':id', id);
            $('#editResponseForm').attr('action', actionUrl);
            
            $('#editResponseModal').modal('show');
        });
        
        // Xử lý nút xóa
        $('.btn-delete').click(function() {
            const id = $(this).data('id');
            
            const actionUrl = "{{ route('admin.chatbot.responses.destroy', ['id' => ':id']) }}".replace(':id', id);
            $('#deleteResponseForm').attr('action', actionUrl);
            
            $('#deleteResponseModal').modal('show');
        });
    });
</script>
@endsection 