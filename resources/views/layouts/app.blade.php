<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kasbon Online System')</title>
    
    <!-- Favicon - Menggunakan logo INKA -->
    <link rel="icon" type="image/png" href="{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}">
    
    <!-- Multiple sizes untuk compatibility -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}">
    
    <!-- Fallback jika logo tidak bisa dimuat -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><circle cx='16' cy='16' r='16' fill='%233182CE'/><text x='16' y='20' text-anchor='middle' fill='white' font-family='Arial' font-size='14' font-weight='bold'>I</text></svg>"
          onerror="this.href='data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 32 32\'><circle cx=\'16\' cy=\'16\' r=\'16\' fill=\'%233182CE\'/><text x=\'16\' y=\'20\' text-anchor=\'middle\' fill=\'white\' font-family=\'Arial\' font-size=\'14\' font-weight=\'bold\'>I</text></svg>'"
    >
    
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
    
    <!-- Custom CSS -->
    <style>
        .brand-image {
            opacity: 1;
            width: 33px;
            height: 33px;
            object-fit: contain;
            border-radius: 50%;
            background-color: white;
            padding: 2px;
        }
        
        .main-sidebar .nav-link {
            transition: all 0.3s ease;
        }
        
        .main-sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .main-sidebar .nav-link.active {
            background-color: #007bff !important;
            color: white !important;
        }
        
        .content-wrapper {
            background: #f4f6f9;
        }
        
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        }
        
        .navbar-nav .nav-link {
            display: flex;
            align-items: center;
        }
        
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: #007bff;
        }
        
        /* Custom scrollbar for sidebar */
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
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">
                                        <i class="fas fa-home"></i> Home
                                    </a>
                                </li>
                                @if(isset($breadcrumbs))
                                    @foreach($breadcrumbs as $breadcrumb)
                                        @if($loop->last)
                                            <li class="breadcrumb-item active">
                                                {{ $breadcrumb['title'] }}
                                            </li>
                                        @else
                                            <li class="breadcrumb-item">
                                                <a href="{{ $breadcrumb['url'] }}">
                                                    {{ $breadcrumb['title'] }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                @else
                                    @if(request()->routeIs('user'))
                                        <li class="breadcrumb-item active">User Management</li>
                                    @elseif(request()->routeIs('user.group'))
                                        <li class="breadcrumb-item active">User Groups</li>
                                    @elseif(request()->routeIs('dashboard'))
                                        <li class="breadcrumb-item active">Dashboard</li>
                                    @endif
                                @endif
                            </ol>
                        </div>
                    </div>
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
    
    <script>
        // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Global SweetAlert2 functions
        window.showAlert = function(type, title, text = '') {
            Swal.fire({
                icon: type,
                title: title,
                text: text,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        };
        
        window.showConfirm = function(title, text, callback) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        };
        
        // Initialize Select2
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
            
            // Initialize DataTables
            $('.data-table').DataTable({
                responsive: true,
                autoWidth: false,
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
                }
            });
        });
    </script>
    
    <!-- Global Functions untuk seluruh aplikasi -->
    <script>
        // Global functions untuk menghindari konflik JavaScript
        function showSystemInfo() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'System Information',
                    html: `
                        <div style="text-align: left; font-size: 14px;">
                            <table style="width: 100%; border-spacing: 8px;">
                                <tr><td><strong>Application:</strong></td><td>Kasbon Online System</td></tr>
                                <tr><td><strong>Laravel:</strong></td><td>{{ app()->version() }}</td></tr>
                                <tr><td><strong>PHP:</strong></td><td>{{ phpversion() }}</td></tr>
                                <tr><td><strong>Environment:</strong></td><td>{{ config('app.env') }}</td></tr>
                                @if(Auth::check())
                                <tr><td><strong>User:</strong></td><td>{{ Auth::user()->nama ?? 'Unknown' }}</td></tr>
                                @if(Auth::user()->userGroup)
                                <tr><td><strong>Role:</strong></td><td>{{ Auth::user()->userGroup->name ?? 'No Group' }}</td></tr>
                                @endif
                                @endif
                                <tr><td><strong>Login Time:</strong></td><td>${new Date().toLocaleString('id-ID')}</td></tr>
                            </table>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Close',
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
                    title: 'Coming Soon',
                    html: `
                        <div style="text-align: center;">
                            <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                            <p class="mb-2"><strong>${featureName}</strong> sedang dalam tahap pengembangan.</p>
                            <p class="text-muted">Fitur ini akan segera tersedia!</p>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'OK',
                    width: 400,
                    customClass: {
                        popup: 'rounded-3'
                    }
                });
            } else {
                alert(`${featureName} - Coming Soon!\\n\\nFitur ini sedang dalam tahap pengembangan.`);
            }
        }

        // Function untuk update waktu di dashboard - DIPERBAIKI
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

        // Initialize time update jika ada element current-time - DIPERBAIKI
        $(document).ready(function() {
            // Pastikan interval tidak mengganggu favicon
            if (document.getElementById('current-time')) {
                updateTime();
                // Gunakan interval yang lebih lama untuk mengurangi beban
                setInterval(updateTime, 1000);
            }
            
            // Pastikan semua resource sudah loaded
            $(window).on('load', function() {
                // Semua resource termasuk gambar sudah selesai dimuat
                console.log('All resources loaded');
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>