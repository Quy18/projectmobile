<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Hóa đơn') - Shop Điện Thoại</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom styles -->
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
            --border-radius: 0.75rem;
            --box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
            --box-shadow-lg: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --transition-speed: 0.3s;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--gray-700);
            background: #f5f7fa;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: var(--gray-800);
        }
        
        .container {
            background: white;
            padding: 2rem;
            box-shadow: var(--box-shadow-lg);
            border-radius: var(--border-radius);
            margin-top: 2rem;
            margin-bottom: 2rem;
            max-width: 1000px;
        }
        
        .btn {
            font-weight: 500;
            padding: 0.6rem 1.25rem;
            border-radius: 0.5rem;
            transition: all var(--transition-speed);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        
        .btn-secondary {
            background-color: var(--gray-600);
            border-color: var(--gray-600);
        }
        
        .logo {
            max-height: 70px;
            max-width: 100%;
        }
        
        .invoice-title {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        
        .invoice-number {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--gray-800);
        }
        
        .invoice-date {
            font-size: 1rem;
            color: var(--gray-600);
        }
        
        .table {
            border-color: var(--gray-300);
        }
        
        .table th {
            font-weight: 600;
            background-color: var(--gray-100);
            color: var(--gray-800);
            border-color: var(--gray-300);
        }
        
        .table td {
            vertical-align: middle;
            border-color: var(--gray-300);
        }
        
        .table-bordered {
            border-color: var(--gray-300);
        }
        
        .badge {
            font-weight: 500;
            padding: 0.4em 0.7em;
            border-radius: 0.4rem;
        }
        
        .footer {
            border-top: 1px solid var(--gray-300);
            padding-top: 1.5rem;
        }
        
        @media print {
            body {
                background-color: #fff;
            }
            
            .container {
                box-shadow: none;
                max-width: 100%;
                margin: 0;
                padding: 1rem;
            }
            
            .no-print {
                display: none !important;
            }
            
            .table th {
                background-color: #f8f9fa !important;
                color: #212529 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div class="container fade-in">
        <div class="row mb-4 no-print">
            <div class="col-12 text-end">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print me-2"></i> In hóa đơn
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left me-2"></i> Quay lại
                </a>
            </div>
        </div>
        
        @yield('content')
    </div>

    <!-- Bootstrap & jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html> 