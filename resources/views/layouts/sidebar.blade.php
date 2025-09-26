<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('dashboard') }}" class="brand-link">
    <img src="{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}" 
         alt="Logo Kasbon" 
         class="brand-image elevation-0"
         style="width: 32px; height: 32px; object-fit: contain; background: transparent; margin-top: -3px;"
         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGRlZnM+PGxpbmVhckdyYWRpZW50IGlkPSJncmFkIiB4MT0iMCUiIHkxPSIwJSIgeDI9IjEwMCUiIHkyPSIxMDAlIj48c3RvcCBvZmZzZXQ9IjAlIiBzdHlsZT0ic3RvcC1jb2xvcjojMUU0MEFGO3N0b3Atb3BhY2l0eToxIiAvPjxzdG9wIG9mZnNldD0iMTAwJSIgc3R5bGU9InN0b3AtY29sb3I6IzNCODJGNjtzdG9wLW9wYWNpdHk6MSIgLz48L2xpbmVhckdyYWRpZW50PjwvZGVmcz4KPGNpcmNsZSBjeD0iMTYiIGN5PSIxNiIgcj0iMTYiIGZpbGw9InVybCgjZ3JhZCkiLz4KPHR4dCB4PSIxNiIgeT0iMjIiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IndoaXRlIiBmb250LWZhbWlseT0iSW50ZXIsQXJpYWwsc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNiIgZm9udC13ZWlnaHQ9ImJvbGQiPks8L3R4dD4KPC9zdmc+';">
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

        <!-- PPK Management -->
        @php
          $hasPpkAccess = Auth::user()->canAccessRoute('ppk.index') || 
                         Auth::user()->canAccessRoute('ppk.create');
        @endphp
        
        @if($hasPpkAccess)
        <li class="nav-item {{ request()->routeIs('ppk*') ? 'menu-open' : '' }}" data-widget="treeview">
          <a href="#" class="nav-link {{ request()->routeIs('ppk*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>
              PPK Management
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if(Auth::user()->canAccessRoute('ppk.create'))
            <li class="nav-item">
              <a href="{{ route('ppk.create') }}" class="nav-link {{ request()->routeIs('ppk.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Create PPK</p>
              </a>
            </li>
            @endif
            
            @if(Auth::user()->canAccessRoute('ppk.index'))
            <li class="nav-item">
              <a href="{{ route('ppk.index') }}" class="nav-link {{ request()->routeIs('ppk.index') || (request()->routeIs('ppk.*') && !request()->routeIs('ppk.create')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>View PPK</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif

        <!-- Kasbon Management - Other Features (Coming Soon) -->
        <li class="nav-item {{ request()->routeIs('kasbon*') || request()->routeIs('sppd*') ? 'menu-open' : '' }}" data-widget="treeview">
          <a href="#" class="nav-link {{ request()->routeIs('kasbon*') || request()->routeIs('sppd*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-receipt"></i>
            <p>
              Kasbon Management
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('sppd.create') ? 'active' : '' }}" onclick="showComingSoon('Create SPPD')">
                <i class="far fa-circle nav-icon"></i>
                <p>Create SPPD</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('sppd.index') ? 'active' : '' }}" onclick="showComingSoon('View SPPD')">
                <i class="far fa-circle nav-icon"></i>
                <p>View SPPD</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Data Master -->
        <li class="nav-item {{ request()->routeIs('master*') ? 'menu-open' : '' }}" data-widget="treeview">
          <a href="#" class="nav-link {{ request()->routeIs('master*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-database"></i>
            <p>
              Data Master
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('master.divisi*') ? 'active' : '' }}" onclick="showComingSoon('Divisi')">
                <i class="far fa-circle nav-icon"></i>
                <p>Divisi</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('master.departemen*') ? 'active' : '' }}" onclick="showComingSoon('Departemen')">
                <i class="far fa-circle nav-icon"></i>
                <p>Departemen</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('master.bagian*') ? 'active' : '' }}" onclick="showComingSoon('Bagian')">
                <i class="far fa-circle nav-icon"></i>
                <p>Bagian</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('master.unit*') ? 'active' : '' }}" onclick="showComingSoon('Unit')">
                <i class="far fa-circle nav-icon"></i>
                <p>Unit</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('master.staff*') ? 'active' : '' }}" onclick="showComingSoon('Staff')">
                <i class="far fa-circle nav-icon"></i>
                <p>Staff</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Reports -->
        <li class="nav-item {{ request()->routeIs('reports*') ? 'menu-open' : '' }}" data-widget="treeview">
          <a href="#" class="nav-link {{ request()->routeIs('reports*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-pie"></i>
            <p>
              Reports
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('reports.sppd*') ? 'active' : '' }}" onclick="showComingSoon('SPPD Report')">
                <i class="far fa-circle nav-icon"></i>
                <p>SPPD</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('reports.ppk*') ? 'active' : '' }}" onclick="showComingSoon('PPK Report')">
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
        <li class="nav-item {{ request()->routeIs('settings*') ? 'menu-open' : '' }}" data-widget="treeview">
          <a href="#" class="nav-link {{ request()->routeIs('settings*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cogs"></i>
            <p>
              Settings
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('settings.general') ? 'active' : '' }}" onclick="showComingSoon('General Settings')">
                <i class="far fa-circle nav-icon"></i>
                <p>General Settings</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('settings.notifications') ? 'active' : '' }}" onclick="showComingSoon('Notifications')">
                <i class="far fa-circle nav-icon"></i>
                <p>Notifications</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="#" class="nav-link {{ request()->routeIs('settings.backup') ? 'active' : '' }}" onclick="showComingSoon('Backup & Restore')">
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

<!-- Custom CSS untuk penyesuaian dengan tema KAI Access yang authentic dan terpadu -->
<style>
/* Sidebar dengan gradient KAI Access yang sophisticated - Override semua style sebelumnya */
.sidebar-dark-primary {
    background: linear-gradient(180deg, var(--kai-primary-dark) 0%, var(--kai-primary) 100%) !important;
    box-shadow: 2px 0 10px rgba(30, 64, 175, 0.15) !important;
}

/* Brand link dengan styling premium yang konsisten dengan app.blade.php */
.sidebar-dark-primary .brand-link {
    background: linear-gradient(135deg, var(--kai-primary-dark), var(--kai-primary)) !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    transition: all 0.3s ease !important;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1) !important;
}

.sidebar-dark-primary .brand-link:hover {
    background: linear-gradient(135deg, #1E3A8A, #2563EB) !important;
    transform: translateY(-1px) !important;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 2px 8px rgba(30, 64, 175, 0.3) !important;
}

/* Navigation link active state dengan efek modern yang konsisten */
.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active, 
.sidebar-dark-primary .nav-sidebar > .nav-item.menu-open > .nav-link {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.08)) !important;
    color: var(--kai-white) !important;
    border-left: 4px solid var(--kai-secondary) !important;
    border-radius: 0 25px 25px 0 !important;
    margin-right: 8px !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
    backdrop-filter: blur(10px) !important;
}

/* Navigation link hover effect yang smooth */
.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover {
    background: rgba(255, 255, 255, 0.1) !important;
    color: var(--kai-white) !important;
    border-radius: 0 20px 20px 0 !important;
    margin-right: 12px !important;
    transform: translateX(4px) !important;
    transition: all 0.3s ease !important;
    backdrop-filter: blur(5px) !important;
}

/* Treeview submenu styling yang lebih refined dan konsisten */
.sidebar-dark-primary .nav-sidebar > .nav-item .nav-treeview {
    background: rgba(0, 0, 0, 0.15) !important;
    border-radius: 8px !important;
    margin: 4px 8px 8px 8px !important;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2) !important;
}

.sidebar-dark-primary .nav-sidebar > .nav-item .nav-treeview > .nav-item > .nav-link.active {
    background: linear-gradient(135deg, var(--kai-secondary), #FB923C) !important;
    color: var(--kai-white) !important;
    border-radius: 6px !important;
    margin: 2px 8px !important;
    box-shadow: 0 2px 4px rgba(249, 115, 22, 0.4) !important;
}

.sidebar-dark-primary .nav-sidebar > .nav-item .nav-treeview > .nav-item > .nav-link:hover {
    background: rgba(255, 255, 255, 0.08) !important;
    color: var(--kai-white) !important;
    border-radius: 6px !important;
    margin: 2px 8px !important;
    transform: translateX(2px) !important;
}

/* Nav header styling yang konsisten dengan app.blade.php */
.sidebar-dark-primary .nav-header {
    background: rgba(0, 0, 0, 0.1) !important;
    color: rgba(255, 255, 255, 0.8) !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
    margin: 12px 0 8px 0 !important;
    padding: 8px 16px !important;
    font-weight: 600 !important;
    font-size: 11px !important;
    letter-spacing: 1px !important;
    text-transform: uppercase !important;
    border-radius: 4px !important;
    margin-left: 8px !important;
    margin-right: 8px !important;
}

/* Brand image styling yang konsisten */
.brand-link .brand-image {
    width: 28px !important;
    height: 28px !important;
    object-fit: contain !important;
    background: transparent !important;
    box-shadow: none !important;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3)) !important;
    transition: transform 0.3s ease !important;
}

.brand-link:hover .brand-image {
    transform: scale(1.1) !important;
}

.brand-link .brand-text {
    font-size: 16px !important;
    margin-left: 8px !important;
    font-weight: 600 !important;
    letter-spacing: 0.5px !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3) !important;
}

/* Icon styling yang konsisten */
.nav-icon {
    width: 20px !important;
    text-align: center !important;
    font-size: 14px !important;
    filter: drop-shadow(0 1px 1px rgba(0, 0, 0, 0.3)) !important;
}

/* Arrow icon positioning yang konsisten dengan app.blade.php */
.main-sidebar .nav-link i.right {
    right: 1rem !important;
    position: absolute !important;
    font-size: 12px !important;
    transition: transform 0.3s ease !important;
}

.main-sidebar .nav-item.menu-open > .nav-link i.right {
    transform: rotate(-90deg) !important;
}

.main-sidebar .nav-sidebar > .nav-item[data-widget="treeview"] > .nav-link {
    position: relative !important;
}

.main-sidebar .nav-sidebar > .nav-item[data-widget="treeview"] > .nav-link p {
    padding-right: 2.5rem !important;
}

/* Scrollbar styling untuk sidebar yang konsisten */
.sidebar::-webkit-scrollbar {
    width: 6px !important;
}

.sidebar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1) !important;
    border-radius: 3px !important;
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3) !important;
    border-radius: 3px !important;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5) !important;
}

/* Responsive adjustments yang konsisten */
@media (max-width: 991.98px) {
    .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active,
    .sidebar-dark-primary .nav-sidebar > .nav-item.menu-open > .nav-link {
        border-radius: 0 !important;
        margin-right: 0 !important;
    }
    
    .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover {
        margin-right: 0 !important;
        transform: none !important;
        border-radius: 0 !important;
    }
}

/* Elevation untuk depth yang konsisten dengan app.blade.php */
.main-sidebar {
    z-index: 1038 !important;
}

.elevation-4 {
    box-shadow: 0 4px 6px rgba(30, 64, 175, 0.1), 0 1px 3px rgba(30, 64, 175, 0.08) !important;
}

/* Override style lama yang mungkin bertabrakan */
.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
}

.sidebar-dark-primary .nav-sidebar > .nav-item .nav-treeview > .nav-item > .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.08) !important;
}

/* Pastikan brand-link tetap konsisten */
.sidebar-dark-primary .brand-link {
    background-color: var(--kai-primary-dark) !important;
}

.sidebar-dark-primary .brand-link:hover {
    background-color: #2d4f75 !important;
}

/* Pastikan nav-header tetap terbaca */
.sidebar-dark-primary .nav-header {
    background-color: inherit !important;
    color: rgba(255, 255, 255, 0.8) !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    margin-bottom: 8px !important;
    padding-bottom: 8px !important;
}

/* Fix untuk badge warning yang mungkin tertimpa */
.badge-warning {
    background-color: var(--kai-secondary) !important;
    color: var(--kai-white) !important;
}
</style>