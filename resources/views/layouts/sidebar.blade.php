<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('dashboard') }}" class="brand-link">
    <img src="{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}" 
         alt="Logo Kasbon" 
         class="brand-image elevation-0"
         style="width: 32px; height: 32px; object-fit: contain; background: transparent; margin-top: -3px;"
         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTYiIGN5PSIxNiIgcj0iMTYiIGZpbGw9IiMzMTgyQ0UiLz4KPHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeD0iNyIgeT0iNyI+CjxwYXRoIGZpbGw9IndoaXRlIiBkPSJNMTIgMmMtNC40MSAwLTggMy41OS04IDhzMy41OSA4IDggOCA4LTMuNTkgOC04LTMuNTktOC04LTh6TTEyIDE3Yy0zLjg3IDAtNy0zLjEzLTctN3MzLjEzLTcgNy03IDcgMy4xMyA3IDctMy4xMyA3LTcgN3oiLz4KPHA+dGggZmlsbD0id2hpdGUiIGQ9Ik0xMSA1aDJ2N2gtMnpNMTEgMTRoMnYyaC0yeiIvPgo8L3N2Zz4KPC9zdmc+';">
    <span class="brand-text font-weight-light" style="font-size: 15px; margin-left: 6px;">Kasbon Online</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
        <!-- Dashboard -->
        @if(Auth::user()->canAccessRoute('dashboard'))
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" 
             class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        @endif

        <!-- Kasbon Management -->
        <li class="nav-item" data-widget="treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>
              Kasbon Management
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link" onclick="showComingSoon('Create SPPD')">
                <i class="far fa-circle nav-icon"></i>
                <p>Create SPPD</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link" onclick="showComingSoon('View SPPD')">
                <i class="far fa-circle nav-icon"></i>
                <p>View SPPD</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link" onclick="showComingSoon('Create PPK')">
                <i class="far fa-circle nav-icon"></i>
                <p>Create PPK</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link" onclick="showComingSoon('View PPK')">
                <i class="far fa-circle nav-icon"></i>
                <p>View PPK</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Data Master -->
        <li class="nav-item" data-widget="treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-database"></i>
            <p>
              Data Master
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link" onclick="showComingSoon('Divisi')">
                <i class="far fa-circle nav-icon"></i>
                <p>Divisi</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link" onclick="showComingSoon('Departemen')">
                <i class="far fa-circle nav-icon"></i>
                <p>Departemen</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link" onclick="showComingSoon('Bagian')">
                <i class="far fa-circle nav-icon"></i>
                <p>Bagian</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link" onclick="showComingSoon('Unit')">
                <i class="far fa-circle nav-icon"></i>
                <p>Unit</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link" onclick="showComingSoon('Staff')">
                <i class="far fa-circle nav-icon"></i>
                <p>Staff</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Reports -->
        <li class="nav-item" data-widget="treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-pie"></i>
            <p>
              Reports
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link" onclick="showComingSoon('SPPD Report')">
                <i class="far fa-circle nav-icon"></i>
                <p>SPPD</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link" onclick="showComingSoon('PPK Report')">
                <i class="far fa-circle nav-icon"></i>
                <p>PPK</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- User Management Section -->
        @php
          $hasUserMgmtAccess = Auth::user()->canAccessRoute('user') || 
                              Auth::user()->canAccessRoute('user.group') || 
                              Auth::user()->canAccessRoute('permissions.index');
        @endphp
        
        @if($hasUserMgmtAccess)
        <li class="nav-header">USER MANAGEMENT</li>
        <li class="nav-item {{ request()->routeIs('user*') || request()->routeIs('permissions.*') ? 'menu-open' : '' }}" data-widget="treeview">
          <a href="#" class="nav-link {{ request()->routeIs('user*') || request()->routeIs('permissions.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>
              User Management
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if(Auth::user()->canAccessRoute('user'))
            <li class="nav-item">
              <a href="{{ route('user') }}" 
                 class="nav-link {{ request()->routeIs('user') && !request()->routeIs('user.group*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Users</p>
              </a>
            </li>
            @endif
            
            @if(Auth::user()->canAccessRoute('user.group'))
            <li class="nav-item">
              <a href="{{ route('user.group') }}" 
                 class="nav-link {{ request()->routeIs('user.group*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>User Groups</p>
              </a>
            </li>
            @endif
            
            @if(Auth::user()->canAccessRoute('permissions.index') && Route::has('permissions.index'))
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
        @endif

        <!-- Settings Section -->
        <li class="nav-header">SETTINGS</li>
        <li class="nav-item" data-widget="treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cogs"></i>
            <p>
              Settings
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link" onclick="showComingSoon('General Settings')">
                <i class="far fa-circle nav-icon"></i>
                <p>General Settings</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link" onclick="showComingSoon('Notifications')">
                <i class="far fa-circle nav-icon"></i>
                <p>Notifications</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link" onclick="showComingSoon('Backup & Restore')">
                <i class="far fa-circle nav-icon"></i>
                <p>Backup & Restore</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- System Info - Always visible for authenticated users -->
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

<!-- Custom CSS untuk penyesuaian warna dan spacing -->
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

/* Override AdminLTE sidebar spacing untuk balance kiri-kanan */
.main-sidebar .sidebar .nav-sidebar > .nav-item > .nav-link {
    padding: 0.5rem 1.5rem 0.5rem 1rem !important; /* top right bottom left */
}

.main-sidebar .sidebar .nav-sidebar .nav-treeview > .nav-item > .nav-link {
    padding: 0.5rem 1.5rem 0.5rem 2.25rem !important; /* submenu dengan padding kanan sama */
}

/* Override default AdminLTE nav-link styling */
.main-sidebar .nav-sidebar .nav-link {
    border-radius: 0.25rem;
    margin-bottom: 0.125rem;
    margin-left: 0.5rem;
    margin-right: 0.5rem !important; /* margin kanan untuk balance */
}

/* Posisi icon panah dropdown AdminLTE */
.main-sidebar .nav-link i.right {
    right: 1rem !important; /* jarak dari tepi kanan */
    position: absolute;
}

/* Ensure text doesn't overlap with arrow icon */
.main-sidebar .nav-treeview .nav-link,
.main-sidebar .nav-sidebar > .nav-item[data-widget="treeview"] > .nav-link {
    position: relative;
}

.main-sidebar .nav-sidebar > .nav-item[data-widget="treeview"] > .nav-link p {
    padding-right: 2rem; /* space untuk arrow icon */
}
</style>