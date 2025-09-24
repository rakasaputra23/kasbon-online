@extends('layouts.app')

@section('title', 'Dashboard - Kasbon System')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i> Home
                </a>
            </li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<!-- Welcome Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm dashboard-welcome-card">
            <div class="card-body dashboard-welcome-body text-white p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2 font-weight-bold welcome-title">Selamat Datang, {{ $user->nama }}</h3>
                        <p class="mb-0 welcome-subtitle">{{ $user->posisi }} • {{ $user->userGroup ? $user->userGroup->name : 'No Group' }}</p>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <div class="welcome-time-section">
                            <div class="h4 mb-0 welcome-time" id="current-time"></div>
                            <small class="welcome-date">{{ now()->format('l, d F Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100 stat-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="stat-icon stat-primary">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h6 class="text-muted mb-1 stat-label">Total Kasbon</h6>
                        <h2 class="mb-0 font-weight-bold text-dark">156</h2>
                        <small class="text-success">
                            <i class="fas fa-arrow-up"></i> +12% dari bulan lalu
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100 stat-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="stat-icon stat-warning">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h6 class="text-muted mb-1 stat-label">Pending</h6>
                        <h2 class="mb-0 font-weight-bold text-dark">5</h2>
                        <small class="text-info">
                            <i class="fas fa-hourglass-half"></i> Menunggu persetujuan
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100 stat-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="stat-icon stat-success">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h6 class="text-muted mb-1 stat-label">Approved</h6>
                        <h2 class="mb-0 font-weight-bold text-dark">142</h2>
                        <small class="text-success">
                            <i class="fas fa-check"></i> Sudah disetujui
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100 stat-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="stat-icon stat-info">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h6 class="text-muted mb-1 stat-label">Total Users</h6>
                        <h2 class="mb-0 font-weight-bold text-dark">{{ $totalUsers ?? 5 }}</h2>
                        <small class="text-muted">
                            <i class="fas fa-user-check"></i> {{ $activeUsers ?? 3 }} aktif
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0">
                <h5 class="mb-0 section-title">
                    <i class="fas fa-bolt text-primary mr-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="#" class="action-link">
                            <div class="action-item">
                                <div class="action-content">
                                    <div class="action-icon action-primary">
                                        <i class="fas fa-plus fa-lg"></i>
                                    </div>
                                    <div class="action-text">
                                        <h6 class="mb-1">Create SPPD</h6>
                                        <small class="text-muted">Buat surat perjalanan dinas</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="#" class="action-link">
                            <div class="action-item">
                                <div class="action-content">
                                    <div class="action-icon action-warning">
                                        <i class="fas fa-file-invoice fa-lg"></i>
                                    </div>
                                    <div class="action-text">
                                        <h6 class="mb-1">Create PPK</h6>
                                        <small class="text-muted">Buat permintaan pembayaran</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('user') }}" class="action-link">
                            <div class="action-item">
                                <div class="action-content">
                                    <div class="action-icon action-success">
                                        <i class="fas fa-users fa-lg"></i>
                                    </div>
                                    <div class="action-text">
                                        <h6 class="mb-1">Manage Users</h6>
                                        <small class="text-muted">Kelola pengguna sistem</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('user.group') }}" class="action-link">
                            <div class="action-item">
                                <div class="action-content">
                                    <div class="action-icon action-secondary">
                                        <i class="fas fa-users-cog fa-lg"></i>
                                    </div>
                                    <div class="action-text">
                                        <h6 class="mb-1">User Groups</h6>
                                        <small class="text-muted">Kelola grup pengguna</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 section-title">
                        <i class="fas fa-history text-primary mr-2"></i>
                        Recent Activity
                    </h5>
                    <button class="btn btn-outline-primary btn-sm">
                        View All
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="activity-placeholder">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-clock fa-2x mb-2 text-secondary"></i>
                        <p class="mb-0 font-weight-medium">No recent activity</p>
                        <small class="text-muted">Data aktivitas akan muncul di sini</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Dashboard Styles - KAI Access Theme yang konsisten dengan app.blade.php */

/* Welcome Banner dengan class yang spesifik untuk mencegah konflik */
.dashboard-welcome-card {
    border-radius: 15px;
    position: relative;
    overflow: hidden;
}

.dashboard-welcome-body {
    background: linear-gradient(135deg, var(--kai-primary-dark) 0%, var(--kai-primary) 50%, var(--kai-primary-light) 100%);
    position: relative;
    z-index: 1;
}

.dashboard-welcome-body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, transparent 0%, rgba(249, 115, 22, 0.1) 100%);
    pointer-events: none;
    z-index: -1;
}

.welcome-title {
    color: white;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    font-size: 1.75rem;
}

.welcome-subtitle {
    color: rgba(255, 255, 255, 0.8);
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
}

.welcome-time-section {
    opacity: 0.9;
}

.welcome-time {
    color: white;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
}

.welcome-date {
    color: rgba(255, 255, 255, 0.7);
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
}

/* Stat Cards dengan shadow biru KAI yang konsisten */
.stat-card {
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 1px 3px rgba(30, 64, 175, 0.1), 0 1px 2px rgba(30, 64, 175, 0.06);
    border: 1px solid var(--kai-gray-200);
    background: var(--kai-white);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(30, 64, 175, 0.15), 0 2px 4px rgba(30, 64, 175, 0.06);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Icon colors yang konsisten dengan tema KAI Access */
.stat-primary {
    background: linear-gradient(135deg, rgba(30, 64, 175, 0.1), rgba(59, 130, 246, 0.1));
    color: var(--kai-primary);
}

.stat-warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(249, 115, 22, 0.1));
    color: var(--kai-warning);
}

.stat-success {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(34, 197, 94, 0.1));
    color: var(--kai-success);
}

.stat-info {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(99, 102, 241, 0.1));
    color: var(--kai-primary-light);
}

.stat-label {
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--kai-gray-600);
}

/* Quick Actions dengan styling yang konsisten */
.action-link {
    text-decoration: none;
    color: inherit;
}

.action-link:hover {
    text-decoration: none;
    color: inherit;
}

.action-item {
    background: linear-gradient(135deg, var(--kai-gray-50), var(--kai-white));
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
    border: 1px solid var(--kai-gray-200);
    height: 100%;
}

.action-item:hover {
    background: linear-gradient(135deg, var(--kai-white), var(--kai-gray-50));
    box-shadow: 0 4px 6px rgba(30, 64, 175, 0.15), 0 2px 4px rgba(30, 64, 175, 0.06);
    border-color: rgba(30, 64, 175, 0.2);
    transform: translateY(-2px);
}

.action-content {
    display: flex;
    align-items: center;
}

.action-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
}

/* Action icons dengan warna KAI Access */
.action-primary {
    background: linear-gradient(135deg, var(--kai-primary), var(--kai-primary-light));
    color: white;
}

.action-warning {
    background: linear-gradient(135deg, var(--kai-warning), #FB923C);
    color: white;
}

.action-success {
    background: linear-gradient(135deg, var(--kai-success), #22c55e);
    color: white;
}

.action-secondary {
    background: linear-gradient(135deg, var(--kai-secondary), var(--kai-secondary-light));
    color: white;
}

.action-text h6 {
    color: var(--kai-gray-800);
    font-weight: 600;
    margin-bottom: 2px;
}

/* Activity placeholder */
.activity-placeholder {
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Section titles */
.section-title {
    color: var(--kai-gray-800);
    font-weight: 600;
    font-size: 1.1rem;
}

.section-title .text-primary {
    color: var(--kai-primary) !important;
}

/* Button overrides untuk konsistensi */
.btn-outline-primary {
    border-color: var(--kai-primary);
    color: var(--kai-primary);
    font-weight: 600;
    border-radius: 8px;
    padding: 0.375rem 1rem;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, var(--kai-primary), var(--kai-primary-light));
    border-color: var(--kai-primary);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
}

/* Card styling yang konsisten dengan app.blade.php */
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

/* Card headers yang konsisten */
.card-header.bg-white {
    background: var(--kai-white) !important;
    border-bottom: 1px solid var(--kai-gray-200) !important;
}

/* Text colors yang konsisten */
.text-success {
    color: var(--kai-success) !important;
}

.text-info {
    color: var(--kai-primary-light) !important;
}

.text-secondary {
    color: var(--kai-gray-600) !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .stat-card:hover,
    .action-item:hover {
        transform: none;
    }
    
    .welcome-time-section {
        text-align: center !important;
        margin-top: 1rem;
    }
    
    .welcome-title {
        font-size: 1.5rem;
    }
}

/* Additional consistent styling */
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

/* Content header consistency */
.content-header h1 {
    color: var(--kai-gray-800);
    font-weight: 700;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Time update function
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit', 
            second: '2-digit'
        });
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }
    
    // Initialize and update time
    updateTime();
    setInterval(updateTime, 1000);
});
</script>
@endpush