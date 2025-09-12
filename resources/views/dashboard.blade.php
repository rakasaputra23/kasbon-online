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
        <div class="card border-0 shadow-sm dashboard-welcome">
            <div class="card-body text-white p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2 font-weight-bold">Selamat Datang, {{ $user->nama }}</h3>
                        <p class="mb-0 opacity-80">{{ $user->posisi }} • {{ $user->userGroup ? $user->userGroup->name : 'No Group' }}</p>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <div class="opacity-90">
                            <div class="h4 mb-0" id="current-time"></div>
                            <small class="opacity-70">{{ now()->format('l, d F Y') }}</small>
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
                        <div class="action-item">
                            <div class="action-content">
                                <div class="action-icon action-primary">
                                    <i class="fas fa-plus fa-lg"></i>
                                </div>
                                <div class="action-text">
                                    <h6 class="mb-1">Create Kasbon</h6>
                                    <small class="text-muted">Buat pengajuan kasbon baru</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="action-item">
                            <div class="action-content">
                                <div class="action-icon action-warning">
                                    <i class="fas fa-clock fa-lg"></i>
                                </div>
                                <div class="action-text">
                                    <h6 class="mb-1">Pending Approval</h6>
                                    <small class="text-muted">Lihat kasbon yang menunggu</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="action-item">
                            <div class="action-content">
                                <div class="action-icon action-success">
                                    <i class="fas fa-chart-bar fa-lg"></i>
                                </div>
                                <div class="action-text">
                                    <h6 class="mb-1">Monthly Report</h6>
                                    <small class="text-muted">Laporan kasbon bulanan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="action-item">
                            <div class="action-content">
                                <div class="action-icon action-info">
                                    <i class="fas fa-history fa-lg"></i>
                                </div>
                                <div class="action-text">
                                    <h6 class="mb-1">History</h6>
                                    <small class="text-muted">Riwayat semua kasbon</small>
                                </div>
                            </div>
                        </div>
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
/* Dashboard Styles - Clean & Compatible */
.dashboard-welcome {
    background: linear-gradient(135deg, #2c5282 0%, #3182ce 100%);
    border-radius: 15px;
}

.stat-card {
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-primary {
    background-color: rgba(49, 130, 206, 0.1);
    color: #3182ce;
}

.stat-warning {
    background-color: rgba(214, 158, 46, 0.1);
    color: #d69e2e;
}

.stat-success {
    background-color: rgba(56, 161, 105, 0.1);
    color: #38a169;
}

.stat-info {
    background-color: rgba(49, 130, 206, 0.1);
    color: #3182ce;
}

.stat-label {
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.action-item {
    background-color: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
    border: 1px solid transparent;
    height: 100%;
}

.action-item:hover {
    background-color: #ffffff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-color: #e2e8f0;
    transform: translateY(-1px);
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

.action-primary {
    background-color: rgba(49, 130, 206, 0.1);
    color: #3182ce;
}

.action-warning {
    background-color: rgba(214, 158, 46, 0.1);
    color: #d69e2e;
}

.action-success {
    background-color: rgba(56, 161, 105, 0.1);
    color: #38a169;
}

.action-info {
    background-color: rgba(49, 130, 206, 0.1);
    color: #3182ce;
}

.action-text h6 {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 2px;
}

.activity-placeholder {
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.section-title {
    color: #2d3748;
    font-weight: 600;
    font-size: 1.1rem;
}

.opacity-80 { opacity: 0.8; }
.opacity-90 { opacity: 0.9; }
.opacity-70 { opacity: 0.7; }

@media (max-width: 768px) {
    .stat-card:hover,
    .action-item:hover {
        transform: none;
    }
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