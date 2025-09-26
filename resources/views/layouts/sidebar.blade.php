<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('dashboard') }}" class="brand-link">
    <div class="brand-image-container">
      <img src="{{ asset('vendor/adminlte/dist/img/logo-qinka.png') }}" 
           alt="Logo INKA" 
           class="brand-image"
           onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjQwIiB2aWV3Qm94PSIwIDAgMTIwIDQwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cmVjdCB3aWR0aD0iMTIwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjMDA0Mjk5Ii8+Cjx0ZXh0IHg9IjEwIiB5PSIyNiIgZmlsbD0id2hpdGUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZm9udC13ZWlnaHQ9ImJvbGQiPklOS0E8L3R4dD4KPHRleHQgeD0iNDUiIHk9IjE2IiBmaWxsPSJ3aGl0ZSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjgiIGZvbnQtc3R5bGU9Iml0YWxpYyI+TXVsdGkgU29sdXNpPC90ZXh0Pgo8dGV4dCB4PSI0NSIgeT0iMjYiIGZpbGw9IndoaXRlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iOCIgZm9udC1zdHlsZT0iaXRhbGljIj5Tb2x1dGlvbjwvdGV4dD4KPC9zdmc+';">
    </div>
    <span class="brand-text font-weight-light">Kasbon Online</span>
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

<!-- Custom CSS untuk logo INKA dengan warna asli -->
<style>
/* Brand link styling untuk logo INKA */
.brand-link {
    display: flex !important;
    align-items: center !important;
    padding: 12px 16px !important;
    height: 60px !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
}

.brand-image-container {
    width: 120px !important;
    height: 40px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: transparent !important;
    margin-right: 12px !important;
    border-radius: 4px !important;
    overflow: hidden !important;
}

.brand-image {
    width: 100% !important;
    height: 100% !important;
    object-fit: contain !important;
}


.brand-link:hover .brand-image {
    /* HAPUS FILTER YANG MEMBUAT WARNA PUTIH */
    transform: scale(1.05) !important;
}

.brand-text {
    font-size: 16px !important;
    font-weight: 300 !important;
    color: white !important;
    white-space: nowrap !important;
    margin-left: 0 !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3) !important;
}


/* Biarkan transparan untuk PNG */
.brand-image-container {
    background: transparent !important;
    padding: 0 !important;
    border: none !important;
}



/* Fallback styling untuk SVG placeholder */
.brand-image[src*="svg"] {
    background: #004299 !important;
    border-radius: 4px !important;
    padding: 4px !important;
}

/* Sidebar dengan gradient KAI Access yang sophisticated */
.sidebar-dark-primary {
    background: linear-gradient(180deg, var(--kai-primary-dark) 0%, var(--kai-primary) 100%) !important;
    box-shadow: 2px 0 10px rgba(30, 64, 175, 0.15) !important;
}

/* Brand link dengan styling premium */
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

/* Navigation link active state */
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

/* Navigation link hover effect */
.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover {
    background: rgba(255, 255, 255, 0.1) !important;
    color: var(--kai-white) !important;
    border-radius: 0 20px 20px 0 !important;
    margin-right: 12px !important;
    transform: translateX(4px) !important;
    transition: all 0.3s ease !important;
    backdrop-filter: blur(5px) !important;
}

/* Treeview submenu styling */
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

/* Nav header styling */
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

/* Icon styling */
.nav-icon {
    width: 20px !important;
    text-align: center !important;
    font-size: 14px !important;
    filter: drop-shadow(0 1px 1px rgba(0, 0, 0, 0.3)) !important;
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
    .brand-image-container {
        width: 100px !important;
        height: 35px !important;
    }
    
    .brand-text {
        font-size: 14px !important;
    }
    
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
</style>