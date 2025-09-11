<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('dashboard') }}" class="brand-link">
    <img src="{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}" 
         alt="Logo Kasbon" 
         class="brand-image elevation-0"
         style="width: 32px; height: 32px; object-fit: contain; background: transparent; margin-top: -3px;">
    <span class="brand-text font-weight-light" style="font-size: 15px; margin-left: 6px;">Kasbon Online</span>
</a>



  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" 
             class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Kasbon Management -->
        <li class="nav-item {{ request()->routeIs('kasbon.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('kasbon.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>
              Kasbon Management
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('kasbon.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>View Kasbon</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('kasbon.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Create Kasbon</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('kasbon.pending') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Pending Approval</p>
                <span class="badge badge-warning right">5</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('kasbon.history') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>History</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Reports -->
        <li class="nav-item {{ request()->routeIs('reports.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-pie"></i>
            <p>
              Reports
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('reports.monthly') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Monthly Report</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('reports.yearly') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Yearly Report</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('reports.employee') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Employee Report</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- User Management -->
        <li class="nav-header">USER MANAGEMENT</li>
        <li class="nav-item {{ request()->routeIs('user*') || request()->routeIs('permissions.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('user*') || request()->routeIs('permissions.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>
              User Management
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('user') }}" 
                 class="nav-link {{ request()->routeIs('user') && !request()->routeIs('user.group*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Users</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('user.group') }}" 
                 class="nav-link {{ request()->routeIs('user.group*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>User Groups</p>
              </a>
            </li>
            @if(Route::has('permissions.index'))
            <li class="nav-item">
              <a href="{{ route('permissions.index') }}" 
                 class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Permissions</p>
              </a>
            </li>
            @endif
          </ul>
        </li>

        <!-- Settings -->
        <li class="nav-header">SETTINGS</li>
        <li class="nav-item {{ request()->routeIs('settings.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cogs"></i>
            <p>
              Settings
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('settings.general') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>General Settings</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('settings.notifications') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Notifications</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('settings.backup') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Backup & Restore</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- System Info -->
        @if(Auth::check())
        <li class="nav-header">SYSTEM</li>
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="showSystemInfo()">
            <i class="nav-icon fas fa-info-circle"></i>
            <p>System Info</p>
          </a>
        </li>
        @endif

      </ul>
    </nav>
  </div>
</aside>

<!-- Custom CSS untuk penyesuaian warna -->
<style>
/* Sidebar dengan warna biru yang lebih natural */
.sidebar-dark-primary {
    background-color: #2c5282;
}

.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active, 
.sidebar-dark-primary .nav-sidebar > .nav-item.menu-open > .nav-link {
    background-color: #3182ce;
    color: #fff;
}

.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
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
}

.sidebar-dark-primary .brand-link:hover {
    background-color: #2d4f75;
}

/* Badge styling yang lebih sederhana */
.badge-warning {
    background-color: #f6ad55;
    color: #744210;
}

/* Smooth transitions */
.nav-link {
    transition: all 0.2s ease-in-out;
}

.nav-treeview {
    background-color: rgba(0, 0, 0, 0.1);
}

.brand-link .brand-image {
    width: 26px;
    height: 26px;
    object-fit: contain;
    background: transparent;
    box-shadow: none !important;
}
.brand-link .brand-text {
    font-size: 15px;
    margin-left: 6px;
}

</style>

<script>
// System info dengan styling yang lebih sederhana
function showSystemInfo() {
    @if(Auth::check())
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
                        <tr><td><strong>User:</strong></td><td>{{ Auth::user()->nama ?? 'Unknown' }}</td></tr>
                        @if(Auth::user()->userGroup)
                        <tr><td><strong>Role:</strong></td><td>{{ Auth::user()->userGroup->name ?? 'No Group' }}</td></tr>
                        @endif
                    </table>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Close',
            width: 450
        });
    } else {
        alert('System Information:\n\nApplication: Kasbon Online System\nUser: {{ Auth::user()->nama ?? 'Unknown' }}');
    }
    @else
    alert('Please login first');
    @endif
}
</script>