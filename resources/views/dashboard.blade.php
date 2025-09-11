@extends('layouts.app')

@section('title', 'Dashboard - Kasbon System')
@section('page_title', 'Dashboard')

@section('content')
<!-- Content Row - Statistics -->
<div class="row">
    <!-- Total Users Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalUsers }}</h3>
                <p>Total Users</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('user') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Total User Groups Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalUserGroups }}</h3>
                <p>User Groups</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tag"></i>
            </div>
            <a href="{{ route('user.group') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Active Users Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $activeUsers }}</h3>
                <p>Active Users</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <!-- System Status Card -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><i class="fas fa-server"></i></h3>
                <p>System Status: <span class="badge badge-success">Online</span></p>
            </div>
            <div class="icon">
                <i class="fas fa-server"></i>
            </div>
            <a href="#" class="small-box-footer" onclick="showSystemInfo()">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Welcome Card -->
    <div class="col-md-6">
        <div class="card card-widget widget-user">
            <div class="widget-user-header bg-info">
                <h3 class="widget-user-username">{{ $user->nama }}</h3>
                <h5 class="widget-user-desc">{{ $user->posisi }}</h5>
                <div class="widget-user-image">
                    <img class="img-circle elevation-2" src="{{ asset('dist/img/user2-160x160.jpg') }}" alt="User Avatar">
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-6 border-right">
                        <div class="description-block">
                            <h5 class="description-header">{{ $user->nip }}</h5>
                            <span class="description-text">NIP</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="description-block">
                            <h5 class="description-header">
                                <span class="badge badge-primary">
                                    {{ $user->userGroup ? $user->userGroup->name : 'No Group' }}
                                </span>
                            </h5>
                            <span class="description-text">GROUP</span>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <div class="text-center">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Users -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-1"></i>
                    Recent Users
                </h3>
                <div class="card-tools">
                    <a href="{{ route('user') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-arrow-right"></i> View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentUsers->count() > 0)
                    <ul class="users-list clearfix">
                        @foreach($recentUsers->take(8) as $recentUser)
                        <li>
                            <img src="{{ asset('dist/img/user2-160x160.jpg') }}" alt="User Image">
                            <a class="users-list-name" href="#">{{ $recentUser->nama }}</a>
                            <span class="users-list-date">{{ $recentUser->posisi }}</span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center">
                        <i class="fas fa-users fa-3x text-gray mb-3"></i>
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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-1"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Manage Users</span>
                                <span class="info-box-number">
                                    <a href="{{ route('user') }}" class="btn btn-primary btn-sm">
                                        Go to Users
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-user-tag"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">User Groups</span>
                                <span class="info-box-number">
                                    <a href="{{ route('user.group') }}" class="btn btn-success btn-sm">
                                        Go to Groups
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-user-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">My Profile</span>
                                <span class="info-box-number">
                                    <a href="{{ route('profile') }}" class="btn btn-info btn-sm">
                                        View Profile
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-info-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">System Info</span>
                                <span class="info-box-number">
                                    <button class="btn btn-warning btn-sm" onclick="showSystemInfo()">
                                        View Info
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Time Display Card -->
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock mr-1"></i>
                    Current Time
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h4 id="current-time" class="text-primary"></h4>
                </div>
            </div>
        </div>
    </div>
</div>
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
            <div class="text-left">
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