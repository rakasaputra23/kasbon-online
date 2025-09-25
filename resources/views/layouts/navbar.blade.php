<nav class="main-header navbar navbar-expand navbar-white navbar-light kai-navbar">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('dashboard') }}" class="nav-link">
        <i class="fas fa-home mr-1"></i>Home
      </a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    @php
      $user = Auth::user();
      $notifications = [
        ['id' => 1, 'message' => 'New user registered', 'time' => '5 mins ago', 'icon' => 'fas fa-user', 'color' => 'success'],
        ['id' => 2, 'message' => 'Server maintenance scheduled', 'time' => '2 hours ago', 'icon' => 'fas fa-tools', 'color' => 'warning'],
        ['id' => 3, 'message' => 'Backup completed successfully', 'time' => '1 day ago', 'icon' => 'fas fa-check', 'color' => 'info']
      ];
      
      $messages = [
        ['id' => 1, 'sender' => 'John Doe', 'message' => 'Hello, how are you doing?', 'time' => '2 mins ago', 'avatar' => null],
        ['id' => 2, 'sender' => 'Jane Smith', 'message' => 'Can you check the report?', 'time' => '15 mins ago', 'avatar' => null],
        ['id' => 3, 'sender' => 'Admin', 'message' => 'System update completed', 'time' => '1 hour ago', 'avatar' => null]
      ];
    @endphp

    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" role="button">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">{{ count($notifications) }}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">{{ count($notifications) }} Notifications</span>
        <div class="dropdown-divider"></div>
        
        @foreach($notifications as $notification)
        <a href="#" class="dropdown-item">
          <i class="{{ $notification['icon'] }} mr-2 text-{{ $notification['color'] }}"></i> {{ $notification['message'] }}
          <span class="float-right text-muted text-sm">{{ $notification['time'] }}</span>
        </a>
        <div class="dropdown-divider"></div>
        @endforeach
        
        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>
    </li>

    <!-- Messages Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" role="button">
        <i class="far fa-comments"></i>
        <span class="badge badge-danger navbar-badge">{{ count($messages) }}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">{{ count($messages) }} Messages</span>
        <div class="dropdown-divider"></div>
        
        @foreach($messages as $message)
        <a href="#" class="dropdown-item">
          <div class="media">
            <div class="media-object">
              @if($message['avatar'])
                <img src="{{ $message['avatar'] }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              @else
                <div class="avatar-placeholder">
                  <i class="fas fa-user-circle"></i>
                </div>
              @endif
            </div>
            <div class="media-body">
              <h3 class="dropdown-item-title">
                {{ $message['sender'] }}
                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">{{ Str::limit($message['message'], 30) }}</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> {{ $message['time'] }}</p>
            </div>
          </div>
        </a>
        <div class="dropdown-divider"></div>
        @endforeach
        
        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
      </div>
    </li>

    @if($user)
      <!-- User Dropdown -->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle user-nav-link" data-toggle="dropdown" role="button">
          <div class="user-avatar">
            <i class="fas fa-user-circle"></i>
          </div>
          <span class="user-name d-none d-md-inline-block ml-2">{{ $user->nama }}</span>
          <span class="user-role d-none d-lg-block ml-1">{{ $user->posisi ?? 'User' }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right user-dropdown">
          <!-- User Header -->
          <li class="user-header">
            <div class="user-profile-section">
              <div class="user-avatar-large">
                <i class="fas fa-user-circle"></i>
              </div>
              <div class="user-info">
                <h4 class="user-full-name">{{ $user->nama }}</h4>
                @if($user->posisi)
                  <p class="user-position">{{ $user->posisi }}</p>
                @endif
                @if($user->userGroup)
                  <p class="user-group">{{ $user->userGroup->name }}</p>
                @endif
              </div>
            </div>
          </li>

          <!-- Menu Items -->
          <li class="user-menu-section">
            <div class="user-menu-items">
              @if(Route::has('profile'))
              <a href="{{ route('profile') }}" class="user-menu-item">
                <div class="menu-item-content">
                  <div class="menu-icon">
                    <i class="fas fa-user"></i>
                  </div>
                  <div class="menu-text">
                    <span class="menu-title">Profile</span>
                    <span class="menu-subtitle">View your profile</span>
                  </div>
                </div>
              </a>
              @endif
              
              @if(Route::has('profile.edit'))
              <a href="{{ route('profile.edit') }}" class="user-menu-item">
                <div class="menu-item-content">
                  <div class="menu-icon">
                    <i class="fas fa-edit"></i>
                  </div>
                  <div class="menu-text">
                    <span class="menu-title">Edit Profile</span>
                    <span class="menu-subtitle">Update your information</span>
                  </div>
                </div>
              </a>
              @endif
              
              <a href="#" class="user-menu-item">
                <div class="menu-item-content">
                  <div class="menu-icon">
                    <i class="fas fa-cog"></i>
                  </div>
                  <div class="menu-text">
                    <span class="menu-title">Settings</span>
                    <span class="menu-subtitle">Configure preferences</span>
                  </div>
                </div>
              </a>
            </div>
          </li>

          <!-- Menu Footer -->
          <li class="user-footer">
            <div class="logout-section">
              <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                @csrf
                <button type="button" class="btn btn-danger btn-logout" onclick="confirmLogout(this)">
                  <i class="fas fa-sign-out-alt mr-2"></i>
                  <span>Logout</span>
                </button>
              </form>
            </div>
          </li>
        </ul>
      </li>
    @endif
  </ul>
</nav>

<style>
:root {
  --kai-primary: #4f46e5;
  --kai-white: #ffffff;
  --kai-gray-50: #f9fafb;
  --kai-gray-100: #f3f4f6;
  --kai-gray-200: #e5e7eb;
  --kai-gray-500: #6b7280;
  --kai-gray-600: #4b5563;
  --kai-gray-700: #374151;
  --kai-gray-800: #1f2937;
  --kai-success: #10b981;
  --kai-warning: #f59e0b;
  --kai-danger: #ef4444;
}

/* Navbar */
.main-header.navbar.kai-navbar {
  background: var(--kai-white) !important;
  border-bottom: 1px solid var(--kai-gray-200);
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  padding: 0.5rem 1rem;
}

.main-header.navbar.kai-navbar .navbar-nav .nav-link {
  color: var(--kai-gray-700) !important;
  padding: 0.5rem 0.75rem;
  border-radius: 0.375rem;
  margin: 0 0.125rem;
  transition: all 0.15s ease;
}

.main-header.navbar.kai-navbar .navbar-nav .nav-link:hover {
  color: var(--kai-primary) !important;
  background-color: rgba(79, 70, 229, 0.05);
}

/* User Navigation - Simplified like in image */
.main-header.navbar.kai-navbar .user-nav-link {
  display: flex !important;
  align-items: center !important;
  background: var(--kai-primary) !important;
  color: var(--kai-white) !important;
  border-radius: 9999px !important;
  padding: 0.5rem 1rem !important;
  margin: 0 !important;
  text-decoration: none !important;
  transition: all 0.15s ease !important;
}

.main-header.navbar.kai-navbar .user-nav-link:hover {
  background: rgba(79, 70, 229, 0.9) !important;
  color: var(--kai-white) !important;
  text-decoration: none !important;
}

.main-header.navbar.kai-navbar .user-avatar {
  width: 32px;
  height: 32px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--kai-white);
  font-size: 1.125rem;
  flex-shrink: 0;
}

.main-header.navbar.kai-navbar .user-name {
  color: var(--kai-white) !important;
  font-weight: 600;
  font-size: 0.875rem;
  white-space: nowrap;
}

.main-header.navbar.kai-navbar .user-role {
  color: rgba(255, 255, 255, 0.8) !important;
  font-weight: 400;
  font-size: 0.75rem;
}

/* User Dropdown */
.main-header.navbar.kai-navbar .user-dropdown {
  border: none;
  padding: 0;
  margin-top: 0.5rem;
  width: 300px;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  border-radius: 0.75rem;
  overflow: hidden;
  background: var(--kai-white);
}

.main-header.navbar.kai-navbar .user-header {
  background: linear-gradient(135deg, var(--kai-primary) 0%, rgba(79, 70, 229, 0.8) 100%);
  padding: 1.5rem;
  text-align: center;
  list-style: none;
}

.main-header.navbar.kai-navbar .user-avatar-large {
  width: 60px;
  height: 60px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.main-header.navbar.kai-navbar .user-avatar-large i {
  font-size: 1.75rem;
  color: white;
}

.main-header.navbar.kai-navbar .user-info {
  color: white;
}

.main-header.navbar.kai-navbar .user-full-name {
  font-size: 1rem;
  font-weight: 700;
  margin: 0 0 0.25rem 0;
}

.main-header.navbar.kai-navbar .user-position,
.main-header.navbar.kai-navbar .user-group {
  font-size: 0.8rem;
  color: rgba(255, 255, 255, 0.9);
  margin: 0.125rem 0;
}

/* Menu Items */
.main-header.navbar.kai-navbar .user-menu-section {
  padding: 1rem 0;
  list-style: none;
}

.main-header.navbar.kai-navbar .user-menu-item {
  display: block;
  color: var(--kai-gray-700);
  text-decoration: none;
  transition: all 0.15s ease;
}

.main-header.navbar.kai-navbar .user-menu-item:hover {
  color: var(--kai-primary);
  text-decoration: none;
  background: rgba(79, 70, 229, 0.05);
}

.main-header.navbar.kai-navbar .menu-item-content {
  display: flex;
  align-items: center;
  padding: 0.75rem 1.5rem;
  gap: 1rem;
}

.main-header.navbar.kai-navbar .menu-icon {
  width: 36px;
  height: 36px;
  background: var(--kai-gray-100);
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--kai-gray-600);
  transition: all 0.15s ease;
  flex-shrink: 0;
}

.main-header.navbar.kai-navbar .user-menu-item:hover .menu-icon {
  background: var(--kai-primary);
  color: white;
}

.main-header.navbar.kai-navbar .menu-text {
  display: flex;
  flex-direction: column;
}

.main-header.navbar.kai-navbar .menu-title {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--kai-gray-800);
  line-height: 1.25;
}

.main-header.navbar.kai-navbar .menu-subtitle {
  font-size: 0.75rem;
  color: var(--kai-gray-500);
  line-height: 1.25;
}

/* Logout Section */
.main-header.navbar.kai-navbar .user-footer {
  background: var(--kai-gray-50);
  padding: 1rem 1.5rem;
  border-top: 1px solid var(--kai-gray-100);
  list-style: none;
}

.main-header.navbar.kai-navbar .btn-logout {
  background: var(--kai-danger) !important;
  border: none !important;
  border-radius: 0.5rem !important;
  padding: 0.75rem 1.5rem !important;
  font-weight: 600 !important;
  color: white !important;
  width: 100%;
  transition: all 0.15s ease !important;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.main-header.navbar.kai-navbar .btn-logout:hover {
  background: #dc2626 !important;
  transform: translateY(-1px);
}

/* Badge styling */
.main-header.navbar.kai-navbar .navbar-badge {
  font-size: 0.625rem;
  font-weight: 700;
  padding: 0.125rem 0.375rem;
  position: absolute;
  right: -0.5rem;
  top: -0.25rem;
  border: 2px solid var(--kai-white);
  border-radius: 9999px;
}

.main-header.navbar.kai-navbar .badge-warning {
  background: var(--kai-warning) !important;
  color: white !important;
}

.main-header.navbar.kai-navbar .badge-danger {
  background: var(--kai-danger) !important;
  color: white !important;
}

/* Dropdown menus */
.main-header.navbar.kai-navbar .dropdown-menu {
  border: 1px solid var(--kai-gray-200);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  border-radius: 0.5rem;
  overflow: hidden;
  background: var(--kai-white);
}

.main-header.navbar.kai-navbar .dropdown-menu-lg {
  min-width: 280px;
}

.main-header.navbar.kai-navbar .dropdown-header {
  font-size: 0.875rem;
  font-weight: 600;
  padding: 0.75rem 1rem;
  background: var(--kai-gray-50);
  color: var(--kai-gray-700);
  border-bottom: 1px solid var(--kai-gray-200);
}

.main-header.navbar.kai-navbar .dropdown-footer {
  font-size: 0.875rem;
  font-weight: 600;
  text-align: center;
  background: var(--kai-gray-50);
  color: var(--kai-primary);
  padding: 0.75rem 1rem;
  border-top: 1px solid var(--kai-gray-200);
  transition: all 0.15s ease;
}

.main-header.navbar.kai-navbar .dropdown-footer:hover {
  background: var(--kai-primary);
  color: white;
  text-decoration: none;
}

.main-header.navbar.kai-navbar .dropdown-item {
  transition: all 0.15s ease;
  color: var(--kai-gray-700);
  padding: 0.5rem 1rem;
}

.main-header.navbar.kai-navbar .dropdown-item:hover {
  background: rgba(79, 70, 229, 0.05);
  color: var(--kai-primary);
  text-decoration: none;
}

/* Avatar placeholder */
.main-header.navbar.kai-navbar .avatar-placeholder {
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, var(--kai-primary), rgba(79, 70, 229, 0.8));
  border-radius: 50%;
  margin-right: 0.75rem;
  flex-shrink: 0;
}

.main-header.navbar.kai-navbar .avatar-placeholder i {
  font-size: 1.25rem;
  color: white;
}

/* Media styling */
.main-header.navbar.kai-navbar .media {
  display: flex;
  align-items: flex-start;
}

.main-header.navbar.kai-navbar .media-body {
  flex: 1;
  padding-left: 0.75rem;
}

.main-header.navbar.kai-navbar .dropdown-item-title {
  font-size: 0.875rem;
  font-weight: 600;
  margin: 0 0 0.25rem 0;
  color: var(--kai-gray-800);
}

/* Text colors */
.main-header.navbar.kai-navbar .text-success { color: var(--kai-success) !important; }
.main-header.navbar.kai-navbar .text-warning { color: var(--kai-warning) !important; }
.main-header.navbar.kai-navbar .text-info { color: var(--kai-primary) !important; }
.main-header.navbar.kai-navbar .text-danger { color: var(--kai-danger) !important; }
.main-header.navbar.kai-navbar .text-muted { color: var(--kai-gray-600) !important; }

/* MOBILE RESPONSIVE - Fixed dropdown positioning */
@media (max-width: 767px) {
  .main-header.navbar.kai-navbar {
    padding: 0.5rem;
  }
  
  /* User button menjadi bulat di mobile, hanya icon */
  .main-header.navbar.kai-navbar .user-nav-link {
    width: 40px !important;
    height: 40px !important;
    padding: 0 !important;
    border-radius: 50% !important;
    justify-content: center !important;
  }
  
  .main-header.navbar.kai-navbar .user-avatar {
    width: 28px !important;
    height: 28px !important;
    background: rgba(255, 255, 255, 0.3) !important;
  }
  
  /* User dropdown positioning - fixed untuk mobile */
  .main-header.navbar.kai-navbar .user-dropdown {
    width: 280px !important;
    position: absolute !important;
    right: 0 !important;
    left: auto !important;
    transform: translateX(0) !important;
  }
  
  /* Notification & message dropdowns positioning */
  .main-header.navbar.kai-navbar .dropdown-menu-lg {
    width: 260px !important;
    min-width: 260px !important;
  }
  
  /* Adjust for very small screens */
  .main-header.navbar.kai-navbar .dropdown-menu {
    max-width: calc(100vw - 2rem);
  }
}

@media (max-width: 480px) {
  .main-header.navbar.kai-navbar .user-nav-link {
    width: 36px !important;
    height: 36px !important;
  }
  
  .main-header.navbar.kai-navbar .user-avatar {
    width: 24px !important;
    height: 24px !important;
    font-size: 0.875rem !important;
  }
  
  .main-header.navbar.kai-navbar .user-dropdown,
  .main-header.navbar.kai-navbar .dropdown-menu-lg {
    width: 250px !important;
    min-width: 250px !important;
  }
  
  .main-header.navbar.kai-navbar .menu-item-content {
    padding: 0.5rem 1rem;
    gap: 0.75rem;
  }
  
  .main-header.navbar.kai-navbar .menu-icon {
    width: 32px;
    height: 32px;
  }
}

/* Fix untuk dropdown show/hide */
.main-header.navbar.kai-navbar .dropdown-menu {
  display: none;
}

.main-header.navbar.kai-navbar .dropdown-menu.show {
  display: block;
}
</style>

<script>
function confirmLogout(element) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Logout',
            text: 'Are you sure you want to logout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Logout',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                element.closest('form').submit();
            }
        });
    } else {
        if (confirm('Are you sure you want to logout?')) {
            element.closest('form').submit();
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Simple dropdown toggle
    const dropdownToggles = document.querySelectorAll('[data-toggle="dropdown"]');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                if (menu !== this.nextElementSibling) {
                    menu.classList.remove('show');
                }
            });
            
            // Toggle current dropdown
            const menu = this.nextElementSibling;
            if (menu) {
                menu.classList.toggle('show');
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
    
    // Prevent dropdown from closing when clicking inside
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
});
</script>