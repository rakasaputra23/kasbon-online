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
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
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
    
    <!-- Custom CSS dengan tema biru yang konsisten -->
    <style>
        /* Brand image dengan background yang konsisten */
        .brand-image {
            opacity: 1;
            width: 32px;
            height: 32px;
            object-fit: contain;
            background: transparent;
            margin-top: -3px;
            box-shadow: none !important;
        }
        
        /* Sidebar styling yang match dengan navbar */
        .sidebar-dark-primary {
            background-color: #2c5282;
        }
        
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link {
            transition: all 0.3s ease;
            border-radius: 4px;
            margin: 2px 4px;
        }
        
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(2px);
        }
        
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            background: linear-gradient(135deg, #3182ce, #4299e1) !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        .sidebar-dark-primary .nav-sidebar > .nav-item.menu-open > .nav-link {
            background-color: #3182ce;
            color: #fff;
        }
        
        .sidebar-dark-primary .nav-sidebar > .nav-item .nav-treeview > .nav-item > .nav-link.active {
            background-color: #4299e1;
            color: #fff;
        }
        
        .sidebar-dark-primary .nav-sidebar > .nav-item .nav-treeview > .nav-item > .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #fff;
        }
        
        .sidebar-dark-primary .nav-header {
            background-color: inherit;
            color: rgba(255, 255, 255, 0.7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 8px;
            padding-bottom: 8px;
        }
        
        .sidebar-dark-primary .brand-link {
            background-color: #2a4a6b;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: background-color 0.3s ease;
        }
        
        .sidebar-dark-primary .brand-link:hover {
            background-color: #2d4f75;
        }
        
        /* Content wrapper dengan background yang lembut */
        .content-wrapper {
            background: linear-gradient(135deg, #f4f6f9 0%, #f8fafc 100%);
            min-height: calc(100vh - 57px);
        }
        
        /* Card styling yang modern */
        .card {
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border: none;
            border-radius: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }
        
        /* Navbar nav links yang konsisten */
        .navbar-nav .nav-link {
            display: flex;
            align-items: center;
        }
        
        /* Badge styling yang konsisten */
        .badge-warning {
            background: linear-gradient(135deg, #f6ad55, #ed8936);
            color: #ffffff;
        }
        
        /* Custom scrollbar untuk sidebar */
        .main-sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .main-sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }
        
        .main-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        
        .main-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }
        
        /* Breadcrumb styling yang match */
        .breadcrumb-item a {
            color: #2c5282;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        
        .breadcrumb-item a:hover {
            color: #4299e1;
        }
        
        .breadcrumb-item.active {
            color: #6c757d;
        }
        
        /* Footer styling */
        .main-footer {
            background-color: #fff;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
        }
        
        .main-footer a {
            color: #2c5282;
            text-decoration: none;
        }
        
        .main-footer a:hover {
            color: #4299e1;
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
            
            // Fallback favicon jika logo INKA tidak tersedia
            const fallbackFavicon = "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><circle cx='16' cy='16' r='16' fill='%232c5282'/><text x='16' y='22' text-anchor='middle' fill='white' font-family='Arial, sans-serif' font-size='18' font-weight='bold'>K</text></svg>";
            
            // Test apakah favicon bisa dimuat
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
        
        // Global SweetAlert2 functions dengan tema biru
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
                background: type === 'success' ? '#d4edda' : type === 'error' ? '#f8d7da' : '#d1ecf1',
                color: '#2c5282'
            });
        };
        
        window.showConfirm = function(title, text, callback) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#2c5282',
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
        
        // Initialize pada document ready
        $(document).ready(function() {
            // Ensure favicon is properly set
            ensureFavicon();
            
            // Initialize Select2 dengan tema yang konsisten
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih...',
                allowClear: true
            });
            
            // Initialize DataTables dengan styling yang match
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
        });
    </script>
    
    <!-- Global Functions untuk seluruh aplikasi -->
    <script>
        // System Info dengan tema yang konsisten
        function showSystemInfo() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '<span style="color: #2c5282;">System Information</span>',
                    html: `
                        <div style="text-align: left; font-size: 14px;">
                            <table style="width: 100%; border-spacing: 8px;">
                                <tr><td><strong style="color: #2c5282;">Application:</strong></td><td>Kasbon Online System</td></tr>
                                <tr><td><strong style="color: #2c5282;">Laravel:</strong></td><td>{{ app()->version() }}</td></tr>
                                <tr><td><strong style="color: #2c5282;">PHP:</strong></td><td>{{ phpversion() }}</td></tr>
                                <tr><td><strong style="color: #2c5282;">Environment:</strong></td><td>{{ config('app.env') }}</td></tr>
                                @if(Auth::check())
                                <tr><td><strong style="color: #2c5282;">User:</strong></td><td>{{ Auth::user()->nama ?? 'Unknown' }}</td></tr>
                                @if(Auth::user()->userGroup)
                                <tr><td><strong style="color: #2c5282;">Role:</strong></td><td>{{ Auth::user()->userGroup->name ?? 'No Group' }}</td></tr>
                                @endif
                                @endif
                                <tr><td><strong style="color: #2c5282;">Session Time:</strong></td><td>${new Date().toLocaleString('id-ID')}</td></tr>
                            </table>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Close',
                    confirmButtonColor: '#2c5282',
                    width: 450,
                    customClass: {
                        popup: 'rounded-3'
                    }
                });
            } else {
                alert('System Information:\\n\\nApplication: Kasbon Online System');
            }
        }

        function showComingSoon(featureName) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '<span style="color: #2c5282;">Coming Soon</span>',
                    html: `
                        <div style="text-align: center;">
                            <i class="fas fa-tools fa-3x mb-3" style="color: #2c5282;"></i>
                            <p class="mb-2"><strong>${featureName}</strong> sedang dalam tahap pengembangan.</p>
                            <p class="text-muted">Fitur ini akan segera tersedia!</p>
                        </div>
                    `,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2c5282',
                    width: 400,
                    customClass: {
                        popup: 'rounded-3'
                    }
                });
            } else {
                alert(`${featureName} - Coming Soon!\\n\\nFitur ini sedang dalam tahap pengembangan.`);
            }
        }

        // Function untuk update waktu di dashboard
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

        // Initialize time update jika ada element current-time
        $(document).ready(function() {
            if (document.getElementById('current-time')) {
                updateTime();
                setInterval(updateTime, 1000);
            }
            
            // Smooth scroll untuk anchor links
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if( target.length ) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 100
                    }, 500);
                }
            });
            
            // Auto-hide alerts after certain time
            $('.alert').delay(5000).fadeOut(300);
            
            // Loading overlay untuk form submissions
            $('form').on('submit', function() {
                const submitBtn = $(this).find('button[type="submit"]');
                if (submitBtn.length) {
                    submitBtn.prop('disabled', true);
                    submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Loading...');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>