@extends('layouts.app')

@section('title', 'Dashboard - Kasbon System')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div class="text-muted">
            <i class="fas fa-clock me-1"></i>
            <span id="current-time"></span>
        </div>
    </div>
    
    <!-- Content Row - Statistics -->
    <div class="row">
        <!-- Total Users Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total User Groups Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                User Groups
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUserGroups }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Active Users Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Active Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- System Status Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                System Status
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span class="badge bg-success">Online</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-server fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Row -->
    <div class="row">
        <!-- Welcome Card -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Welcome, {{ $user->nama }}!</h6>
                    <div class="dropdown no-arrow">
                        <i class="fas fa-user fa-sm text-gray-400"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="mb-2"><strong>NIP:</strong> {{ $user->nip }}</p>
                            <p class="mb-2"><strong>Posisi:</strong> {{ $user->posisi }}</p>
                            <p class="mb-2"><strong>Email:</strong> {{ $user->email }}</p>
                            <p class="mb-2"><strong>Group:</strong> 
                                <span class="badge bg-primary">{{ $user->userGroup ? $user->userGroup->name : 'No Group' }}</span>
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="avatar-circle">
                                <i class="fas fa-user fa-3x text-gray-400"></i>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit me-1"></i>Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Users -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Users</h6>
                    <a href="{{ route('user') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-right me-1"></i>View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentUsers->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentUsers as $recentUser)
                            <div class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="avatar-sm">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h6 class="mb-0">{{ $recentUser->nama }}</h6>
                                        <small class="text-muted">{{ $recentUser->posisi }}</small>
                                    </div>
                                    <div class="col-auto">
                                        <span class="badge bg-secondary">{{ $recentUser->userGroup ? $recentUser->userGroup->name : 'No Group' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">Belum ada user yang terdaftar.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('user') }}" class="btn btn-outline-primary btn-block h-100">
                                <i class="fas fa-users fa-2x mb-2"></i><br>
                                Manage Users
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('user.group') }}" class="btn btn-outline-success btn-block h-100">
                                <i class="fas fa-user-tag fa-2x mb-2"></i><br>
                                User Groups
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('profile') }}" class="btn btn-outline-info btn-block h-100">
                                <i class="fas fa-user-cog fa-2x mb-2"></i><br>
                                My Profile
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-warning btn-block h-100" onclick="showSystemInfo()">
                                <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                                System Info
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #007bff !important;
}
.border-left-success {
    border-left: 0.25rem solid #28a745 !important;
}
.border-left-info {
    border-left: 0.25rem solid #17a2b8 !important;
}
.border-left-warning {
    border-left: 0.25rem solid #ffc107 !important;
}
.avatar-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.avatar-sm {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}
.btn-block {
    width: 100%;
}
</style>
@endsection

@push('scripts')
<script>
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    document.getElementById('current-time').textContent = timeString;
}

// Update time every second
setInterval(updateTime, 1000);
updateTime(); // Initial call

function showSystemInfo() {
    Swal.fire({
        title: 'System Information',
        html: `
            <div class="text-start">
                <p><strong>Application:</strong> Kasbon Online System</p>
                <p><strong>Version:</strong> 1.0.0</p>
                <p><strong>Framework:</strong> Laravel 10</p>
                <p><strong>Database:</strong> MySQL</p>
                <p><strong>Current User:</strong> {{ $user->nama }}</p>
                <p><strong>User Role:</strong> {{ $user->userGroup ? $user->userGroup->name : 'No Group' }}</p>
                <p><strong>Login Time:</strong> ${new Date().toLocaleString('id-ID')}</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Close'
    });
}
</script>
@endpush