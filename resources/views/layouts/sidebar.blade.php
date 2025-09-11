<nav class="sidebar">
    <!-- Sidebar Brand -->
    <div class="sidebar-brand">
        <div class="sidebar-brand-text">
            <i class="fas fa-receipt"></i> Kasbon System
        </div>
    </div>
    
    <!-- Sidebar Menu -->
    <div class="sidebar-menu">
        <ul class="nav nav-pills flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            
            <!-- Divider -->
            <hr class="sidebar-divider my-2">
            
            <!-- Heading -->
            <div class="sidebar-heading">User Management</div>
            
            <!-- User Management -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user*') && !request()->routeIs('user.group*') ? 'active' : '' }}" 
                   href="{{ route('user') }}">
                    <i class="fas fa-users me-2"></i> Users
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.group*') ? 'active' : '' }}" 
                   href="{{ route('user.group') }}">
                    <i class="fas fa-user-tag me-2"></i> User Groups
                </a>
            </li>
            
            <!-- Divider -->
            <hr class="sidebar-divider my-2">
            
            <!-- Heading -->
            <div class="sidebar-heading">System</div>
            
            <!-- System Info -->
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="showSystemInfo()">
                    <i class="fas fa-info-circle me-2"></i> System Info
                </a>
            </li>
        </ul>
    </div>
</nav>

<script>
function showSystemInfo() {
    Swal.fire({
        title: 'System Information',
        html: `
            <div class="text-start">
                <p><strong>Application:</strong> Kasbon Online System</p>
                <p><strong>Version:</strong> 1.0.0</p>
                <p><strong>Framework:</strong> Laravel 10</p>
                <p><strong>PHP Version:</strong> ${navigator.userAgent.includes('Chrome') ? '8.2+' : 'N/A'}</p>
                <p><strong>Database:</strong> MySQL</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Close'
    });
}
</script>