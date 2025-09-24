<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kasbon Online System')</title>
    
    <!-- Favicon Fixed - Menggunakan logo INKA dengan fallback yang proper -->
    <link rel="icon" type="image/png" href="{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}" id="favicon-main">
    <link rel="shortcut icon" type="image/png" href="{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}">
    
    <!-- Multiple sizes untuk compatibility -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}">
    
    <!-- Google Font: Inter untuk kesan modern seperti KAI Access -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AdminLTE Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    
    <!-- Custom CSS dengan tema Access by KAI yang authentic -->
    <style>
        /* Override font family untuk seluruh aplikasi - menggunakan Inter seperti KAI Access */
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        /* Definisi warna Access by KAI yang authentic */
        :root {
            --kai-primary: #1E40AF;        /* Blue primary KAI */
            --kai-primary-dark: #1E3A8A;   /* Blue darker */
            --kai-primary-light: #3B82F6;  /* Blue lighter */
            --kai-secondary: #F97316;      /* Orange accent KAI */
            --kai-secondary-light: #FB923C;
            --kai-success: #10B981;        /* Green */
            --kai-warning: #F59E0B;        /* Amber */
            --kai-danger: #EF4444;         /* Red */
            --kai-light: #F8FAFC;          /* Light background */
            --kai-white: #FFFFFF;
            --kai-gray-50: #F9FAFB;
            --kai-gray-100: #F3F4F6;
            --kai-gray-200: #E5E7EB;
            --kai-gray-300: #D1D5DB;
            --kai-gray-500: #6B7280;
            --kai-gray-600: #4B5563;
            --kai-gray-700: #374151;
            --kai-gray-800: #1F2937;
            --kai-gray-900: #111827;
        }
        
        /* Content wrapper dengan background putih bersih seperti KAI Access */
        .content-wrapper {
            background: var(--kai-gray-50);
            min-height: calc(100vh - 57px);
        }
        
        /* Card styling yang modern dengan shadow biru KAI */
        .card {
            box-shadow: 0 1px 3px rgba(30, 64, 175, 0.1), 0 1px 2px rgba(30, 64, 175, 0.06);
            border: 1px solid var(--kai-gray-200);
            border-radius: 12px;
            transition: all 0.3s ease;
            overflow: hidden;
            background: var(--kai-white);
        }
        
        .card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(30, 64, 175, 0.15), 0 2px 4px rgba(30, 64, 175, 0.06);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--kai-primary), var(--kai-primary-light));
            color: white;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
            border-bottom: none;
        }
        
        /* Button styling dengan tema KAI Access yang authentic */
        .btn-primary {
            background: linear-gradient(135deg, var(--kai-primary), var(--kai-primary-light));
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--kai-primary-dark), var(--kai-primary));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
        }
        
        .btn-secondary {
            background: transparent;
            border: 2px solid var(--kai-primary);
            color: var(--kai-primary);
            border-radius: 8px;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: var(--kai-primary);
            color: white;
            transform: translateY(-1px);
        }
        
        /* Badge styling dengan warna KAI yang authentic */
        .badge-warning {
            background: var(--kai-secondary);
            color: white;
            font-weight: 600;
        }
        
        .badge-primary {
            background: var(--kai-primary);
            color: white;
        }
        
        .badge-success {
            background: var(--kai-success);
            color: white;
        }
        
        /* Breadcrumb styling yang match */
        .breadcrumb-item a {
            color: var(--kai-primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        
        .breadcrumb-item a:hover {
            color: var(--kai-secondary);
        }
        
        .breadcrumb-item.active {
            color: var(--kai-gray-600);
            font-weight: 600;
        }
        
        /* Footer styling */
        .main-footer {
            background: var(--kai-white);
            border-top: 1px solid var(--kai-gray-200);
            color: var(--kai-gray-600);
        }
        
        .main-footer a {
            color: var(--kai-primary);
            text-decoration: none;
            font-weight: 600;
        }
        
        .main-footer a:hover {
            color: var(--kai-secondary);
        }
        
        /* Info boxes dengan warna KAI yang authentic */
        .info-box {
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(30, 64, 175, 0.1);
            transition: all 0.3s ease;
            border: 1px solid var(--kai-gray-200);
        }
        
        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(30, 64, 175, 0.15);
        }
        
        .info-box-icon {
            background: linear-gradient(135deg, var(--kai-primary), var(--kai-primary-light)) !important;
            color: white !important;
        }
        
        /* DataTables styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--kai-primary) !important;
            border-color: var(--kai-primary) !important;
            color: white !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--kai-primary-light) !important;
            border-color: var(--kai-primary-light) !important;
            color: white !important;
        }
        
        /* Select2 custom styling */
        .select2-container--bootstrap4 .select2-selection--single {
            border-radius: 6px;
            border-color: var(--kai-gray-300);
        }
        
        .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] {
            background: var(--kai-primary);
        }
        
        /* Form controls dengan border biru KAI */
        .form-control:focus {
            border-color: var(--kai-primary-light);
            box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
        }
        
        /* Tab styling */
        .nav-tabs .nav-link.active {
            background: var(--kai-primary);
            color: white;
            border-color: var(--kai-primary);
        }
        
        .nav-tabs .nav-link:hover {
            border-color: var(--kai-primary-light);
            color: var(--kai-primary);
        }
        
        /* Alert styling dengan warna KAI */
        .alert-primary {
            background-color: rgba(30, 64, 175, 0.1);
            border-color: rgba(30, 64, 175, 0.2);
            color: var(--kai-primary-dark);
        }
        
        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
            color: #065F46;
        }
        
        .alert-warning {
            background-color: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.2);
            color: #92400E;
        }
        
        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.2);
            color: #991B1B;
        }
        
        /* Progress bar styling */
        .progress-bar {
            background: linear-gradient(135deg, var(--kai-primary), var(--kai-primary-light));
        }
        
        /* Custom styling untuk tampilan yang lebih clean */
        .content-header {
            padding: 1rem 0;
        }
        
        .content-header h1 {
            color: var(--kai-gray-800);
            font-weight: 700;
        }
        
        /* Table styling yang lebih modern */
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table thead th {
            background: var(--kai-gray-50);
            border-color: var(--kai-gray-200);
            color: var(--kai-gray-700);
            font-weight: 600;
        }
        
        .table tbody tr:hover {
            background-color: var(--kai-gray-50);
        }
        
        /* Modal styling */
        .modal-header {
            background: linear-gradient(135deg, var(--kai-primary), var(--kai-primary-light));
            color: white;
            border-bottom: none;
        }
        
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 25px rgba(30, 64, 175, 0.15);
        }
    </style>
    
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.navbar')
        
        <!-- Main Sidebar Container -->
        @include('layouts.sidebar')
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    @yield('header')
                </div>
            </div>
            
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>
        
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        
        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="#">Kasbon Online System</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    
    <!-- Favicon Fix Script -->
    <script>
        // Function untuk memastikan favicon tetap konsisten
        function ensureFavicon() {
            const favicon = document.getElementById('favicon-main');
            const faviconUrl = "{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}";
            
            // Fallback favicon dengan tema KAI Access yang authentic
            const fallbackFavicon = "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><defs><linearGradient id='grad' x1='0%' y1='0%' x2='100%' y2='100%'><stop offset='0%' style='stop-color:%231E40AF;stop-opacity:1' /><stop offset='100%' style='stop-color:%233B82F6;stop-opacity:1' /></linearGradient></defs><circle cx='16' cy='16' r='16' fill='url(%23grad)'/><text x='16' y='22' text-anchor='middle' fill='white' font-family='Inter, Arial, sans-serif' font-size='16' font-weight='bold'>K</text></svg>";
            
            const img = new Image();
            img.onload = function() {
                if (favicon && favicon.href !== faviconUrl) {
                    favicon.href = faviconUrl;
                }
            };
            img.onerror = function() {
                if (favicon) {
                    favicon.href = fallbackFavicon;
                }
            };
            img.src = faviconUrl;
        }
        
        // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Global SweetAlert2 functions dengan tema KAI
        window.showAlert = function(type, title, text = '') {
            Swal.fire({
                icon: type,
                title: title,
                text: text,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                customClass: {
                    popup: 'colored-toast'
                },
                background: type === 'success' ? '#d1fae5' : type === 'error' ? '#fee2e2' : '#dbeafe',
                color: '#1E40AF'
            });
        };
        
        window.showConfirm = function(title, text, callback) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#1E40AF',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        };
        
        $(document).ready(function() {
            ensureFavicon();
            
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih...',
                allowClear: true
            });
            
            $('.data-table').DataTable({
                responsive: true,
                autoWidth: false,
                dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-5"i><"col-sm-7"p>>',
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    },
                    emptyTable: "Tidak ada data yang tersedia"
                },
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                order: [[0, 'asc']]
            });
            
            if (document.getElementById('current-time')) {
                updateTime();
                setInterval(updateTime, 1000);
            }
            
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if( target.length ) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 100
                    }, 500);
                }
            });
            
            $('.alert').delay(5000).fadeOut(300);
            
            $('form').on('submit', function() {
                const submitBtn = $(this).find('button[type="submit"]');
                if (submitBtn.length) {
                    submitBtn.prop('disabled', true);
                    submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Loading...');
                }
            });
        });
        
        function showSystemInfo() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '<span style="color: #1E40AF;">System Information</span>',
                    html: `
                        <div style="text-align: left; font-size: 14px;">
                            <table style="width: 100%; border-spacing: 8px;">
                                <tr><td><strong style="color: #1E40AF;">Application:</strong></td><td>Kasbon Online System</td></tr>
                                <tr><td><strong style="color: #1E40AF;">Laravel:</strong></td><td>{{ app()->version() }}</td></tr>
                                <tr><td><strong style="color: #1E40AF;">PHP:</strong></td><td>{{ phpversion() }}</td></tr>
                                <tr><td><strong style="color: #1E40AF;">Environment:</strong></td><td>{{ config('app.env') }}</td></tr>
                                @if(Auth::check())
                                <tr><td><strong style="color: #1E40AF;">User:</strong></td><td>{{ Auth::user()->nama ?? 'Unknown' }}</td></tr>
                                @if(Auth::user()->userGroup)
                                <tr><td><strong style="color: #1E40AF;">Role:</strong></td><td>{{ Auth::user()->userGroup->name ?? 'No Group' }}</td></tr>
                                @endif
                                @endif
                                <tr><td><strong style="color: #1E40AF;">Session Time:</strong></td><td>${new Date().toLocaleString('id-ID')}</td></tr>
                            </table>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Close',
                    confirmButtonColor: '#1E40AF',
                    width: 450,
                    customClass: {
                        popup: 'rounded-3'
                    }
                });
            }
        }

        function showComingSoon(featureName) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '<span style="color: #1E40AF;">Coming Soon</span>',
                    html: `
                        <div style="text-align: center;">
                            <i class="fas fa-tools fa-3x mb-3" style="color: #1E40AF;"></i>
                            <p class="mb-2"><strong>${featureName}</strong> sedang dalam tahap pengembangan.</p>
                            <p class="text-muted">Fitur ini akan segera tersedia!</p>
                        </div>
                    `,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#1E40AF',
                    width: 400,
                    customClass: {
                        popup: 'rounded-3'
                    }
                });
            }
        }

        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const element = document.getElementById('current-time');
            if (element) {
                element.textContent = timeString;
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>