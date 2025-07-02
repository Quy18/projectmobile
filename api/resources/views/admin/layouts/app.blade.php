<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị Shop Điện Thoại')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-hover: #3a56d4;
            --secondary-color: #3f37c9;
            --success-color: #0bb363;
            --info-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #ef476f;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            --sidebar-width: 280px;
            --border-radius: 0.75rem;
            --box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
            --box-shadow-lg: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --transition-speed: 0.3s;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f5f7fa;
            color: var(--gray-700);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: var(--gray-800);
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
            padding-top: 1.5rem;
            color: white;
            box-shadow: var(--box-shadow-lg);
            z-index: 1000;
            transition: all var(--transition-speed);
            overflow-y: auto;
        }
        
        .sidebar .logo {
            padding: 1rem 1.5rem 2rem;
            font-size: 1.4rem;
            font-weight: 700;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
        }
        
        .sidebar .logo i {
            margin-right: 0.5rem;
            font-size: 1.5rem;
        }
        
        .sidebar .nav-item {
            margin: 0.25rem 0.8rem;
        }
        
        .sidebar .nav-link {
            padding: 0.85rem 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            font-size: 0.95rem;
            border-radius: var(--border-radius);
            transition: all var(--transition-speed);
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 1.25rem;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            transition: all var(--transition-speed);
        }
        
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 0 1.25rem 0;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            background: white;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .user-info img {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            margin-right: 0.75rem;
            border: 3px solid var(--gray-200);
        }
        
        .user-info div {
            line-height: 1.2;
        }
        
        .user-info div:first-child {
            font-weight: 600;
            color: var(--gray-800);
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: transform var(--transition-speed), box-shadow var(--transition-speed);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow-lg);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--gray-200);
            font-weight: 600;
            padding: 1.25rem 1.5rem;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-header .card-title {
            margin-bottom: 0;
            font-size: 1.1rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 600;
            color: var(--gray-700);
            border-top: none;
            white-space: nowrap;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all var(--transition-speed);
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.85rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .breadcrumb {
            margin-bottom: 0;
            padding: 0.75rem 0;
            background-color: transparent;
        }
        
        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            text-decoration: underline;
        }
        
        .form-control {
            border-radius: 0.5rem;
            padding: 0.6rem 1rem;
            border: 1px solid var(--gray-300);
            transition: border-color var(--transition-speed), box-shadow var(--transition-speed);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--gray-700);
        }
        
        .alert {
            border-radius: var(--border-radius);
            padding: 1rem 1.25rem;
            font-weight: 500;
        }
        
        .badge {
            font-weight: 500;
            padding: 0.4em 0.7em;
            border-radius: 0.4rem;
        }
        
        .text-primary { color: var(--primary-color) !important; }
        .text-success { color: var(--success-color) !important; }
        .text-info { color: var(--info-color) !important; }
        .text-warning { color: var(--warning-color) !important; }
        .text-danger { color: var(--danger-color) !important; }
        
        .bg-primary { background-color: var(--primary-color) !important; }
        .bg-success { background-color: var(--success-color) !important; }
        .bg-info { background-color: var(--info-color) !important; }
        .bg-warning { background-color: var(--warning-color) !important; }
        .bg-danger { background-color: var(--danger-color) !important; }
        
        @media (max-width: 992px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            
            .sidebar.show {
                margin-left: 0;
            }
            
            .content {
                margin-left: 0;
                padding: 1.5rem;
            }
            
            .content.sidebar-open {
                margin-left: var(--sidebar-width);
            }
            
            .toggle-sidebar {
                display: block !important;
            }
        }
        
        .toggle-sidebar {
            display: none;
            cursor: pointer;
            font-size: 1.25rem;
            color: var(--gray-700);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--box-shadow);
            margin-right: 1rem;
        }
        
        /* Animation effects */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Table styling */
        .table-hover tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        /* Custom checkboxes and radio buttons */
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        /* Select2 Custom Styling (if used) */
        .select2-container--bootstrap .select2-selection {
            border-radius: 0.5rem;
            border: 1px solid var(--gray-300);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--gray-400);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-500);
        }
        
        /* Modal fixes */
        .modal-backdrop {
            z-index: 1040;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
        }
        
        .modal {
            z-index: 1050;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            outline: 0;
            pointer-events: auto;
        }
        
        .modal-dialog {
            margin: 1.75rem auto;
            transition: transform 0.3s ease-out;
            position: relative;
            pointer-events: auto;
            max-width: 500px;
        }
        
        .modal.fade .modal-dialog {
            transform: translateY(-30px);
            transition: transform 0.3s ease-out;
        }
        
        .modal.show .modal-dialog {
            transform: translateY(0);
        }
        
        .modal-content {
            position: relative;
            pointer-events: auto;
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
        }
        
        body.modal-open {
            overflow: hidden;
            padding-right: 15px;
        }
        
        body.modal-open-fixed {
            overflow: hidden;
            padding-right: 15px;
            position: relative;
            height: 100%;
        }
        
        .modal-header, .modal-body, .modal-footer {
            pointer-events: auto;
        }
        
        .modal-header {
            border-bottom: 1px solid #dee2e6;
            padding: 1rem;
        }
        
        .modal-body {
            padding: 1rem;
        }
        
        .modal-footer {
            border-top: 1px solid #dee2e6;
            padding: 1rem;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none">
                <i class="fas fa-mobile-alt"></i> Shop Điện Thoại
            </a>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Người dùng
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-list"></i> Danh mục
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <i class="fas fa-tag"></i> Thương hiệu
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-mobile"></i> Sản phẩm
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i> Đơn hàng
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i> Đánh giá
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.chatbot.index') }}" class="nav-link {{ request()->routeIs('admin.chatbot.*') ? 'active' : '' }}">
                    <i class="fas fa-robot"></i> Chatbot
                </a>
            </li>
          
            <li class="nav-item">
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Báo cáo
                </a>
            </li>
            
            <li class="nav-item mt-4">
                <a href="{{ route('admin.logout') }}" class="nav-link text-danger">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="topbar">
            <div class="d-flex align-items-center">
                <span class="toggle-sidebar me-3">
                    <i class="fas fa-bars"></i>
                </span>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
            <div class="user-info">
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=4361ee&color=fff" alt="User Avatar">
                <div>
                    <div>{{ Auth::user()->name }}</div>
                    <small class="text-muted">{{ ucfirst(Auth::user()->role) }}</small>
                </div>
            </div>
        </div>

        <div class="container-fluid fade-in">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS & jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Toggle sidebar on mobile
            $('.toggle-sidebar').click(function() {
                $('.sidebar').toggleClass('show');
                $('.content').toggleClass('sidebar-open');
            });
            
            // Auto hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
            
            // Add animation class to cards
            $('.card').addClass('fade-in');
            
            // Tooltip initialization
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Xử lý modal để sửa lỗi khi di chuột
            $(document).on('show.bs.modal', '.modal', function () {
                $(this).appendTo('body');
                $(this).css('z-index', 1050);
                $('.modal-backdrop').css('z-index', 1040);
                $('body').addClass('modal-open-fixed');
            });
            
            $(document).on('hidden.bs.modal', '.modal', function () {
                $('body').removeClass('modal-open-fixed');
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html> 