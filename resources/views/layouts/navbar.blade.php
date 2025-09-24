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
      // Sample data - replace with your actual data source
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
      <a class="nav-link" data-toggle="dropdown" href="#">
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
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-comments"></i>
        <span class="badge badge-danger navbar-badge">{{ count($messages) }}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">{{ count($messages) }} Messages</span>
        <div class="dropdown-divider"></div>
        
        @foreach($messages as $message)
        <a href="#" class="dropdown-item">
          <!-- Message Start -->
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
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        @endforeach
        
        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
      </div>
    </li>

    @if($user)
      <!-- User Dropdown -->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle user-nav-link" data-toggle="dropdown">
          <div class="user-nav-content">
            <div class="user-avatar">
              <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-details d-none d-md-flex">
              <div class="user-text">
                <span class="user-name">{{ $user->nama }}</span>
                @if($user->posisi)
                  <span class="user-role">{{ $user->posisi }}</span>
                @endif
              </div>
            </div>
            <div class="dropdown-arrow">
              <i class="fas fa-chevron-down"></i>
            </div>
          </div>
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
              
              <a href="#" class="user-menu-item" onclick="showComingSoon('Settings')">
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
/* Override dengan !important hanya untuk mencegah konflik tanpa merusak style existing */

/* Navbar utama - hanya override yang diperlukan */
.main-header.navbar.kai-navbar {
    background: var(--kai-white) !important;
    border-bottom: 1px solid var(--kai-gray-200) !important;
    box-shadow: 0 1px 3px rgba(30, 64, 175, 0.1), 0 1px 2px rgba(30, 64, 175, 0.06) !important;
}

/* Navbar links - dengan spesifisitas untuk override AdminLTE */
.main-header.navbar.kai-navbar .navbar-nav .nav-link {
    color: var(--kai-gray-700) !important;
    transition: all 0.3s ease !important;
    border-radius: 8px !important;
    margin: 0 4px !important;
    font-weight: 500 !important;
}

.main-header.navbar.kai-navbar .navbar-nav .nav-link:hover {
    color: var(--kai-primary) !important;
    background: rgba(30, 64, 175, 0.05) !important;
    transform: translateY(-1px) !important;
}

/* Pushmenu button */
.main-header.navbar.kai-navbar .nav-link[data-widget="pushmenu"] {
    background: rgba(30, 64, 175, 0.05) !important;
    color: var(--kai-primary) !important;
}

.main-header.navbar.kai-navbar .nav-link[data-widget="pushmenu"]:hover {
    background: rgba(30, 64, 175, 0.1) !important;
    color: var(--kai-primary-dark) !important;
}

/* User nav link - dengan prioritas tinggi */
.main-header.navbar.kai-navbar .user-menu .user-nav-link {
    background: linear-gradient(135deg, rgba(30, 64, 175, 0.03), rgba(59, 130, 246, 0.03)) !important;
    border: 1px solid rgba(30, 64, 175, 0.1) !important;
    border-radius: 50px !important;
    padding: 8px 16px !important;
    margin-right: 8px !important;
    min-width: 200px !important;
    display: flex !important;
    align-items: center !important;
}

.main-header.navbar.kai-navbar .user-menu .user-nav-link:hover {
    background: linear-gradient(135deg, rgba(30, 64, 175, 0.08), rgba(59, 130, 246, 0.08)) !important;
    border-color: rgba(30, 64, 175, 0.2) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.15) !important;
}

/* User nav content */
.main-header.navbar.kai-navbar .user-nav-content {
    display: flex;
    align-items: center;
    width: 100%;
    gap: 12px;
}

.main-header.navbar.kai-navbar .user-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, var(--kai-primary), var(--kai-primary-light));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.main-header.navbar.kai-navbar .user-details {
    flex: 1;
    min-width: 0;
}

.main-header.navbar.kai-navbar .user-text {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.main-header.navbar.kai-navbar .user-name {
    color: var(--kai-gray-800) !important;
    font-weight: 600 !important;
    font-size: 0.9rem;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 120px;
}

.main-header.navbar.kai-navbar .user-role {
    color: var(--kai-gray-600) !important;
    font-weight: 400 !important;
    font-size: 0.75rem;
    line-height: 1;
    margin-top: 1px;
}

.main-header.navbar.kai-navbar .dropdown-arrow {
    color: var(--kai-gray-500);
    font-size: 0.8rem;
    transition: transform 0.3s ease;
    flex-shrink: 0;
}

.main-header.navbar.kai-navbar .user-nav-link:hover .dropdown-arrow {
    color: var(--kai-primary);
    transform: rotate(180deg);
}

/* User Dropdown - dengan spesifisitas tinggi untuk mencegah konflik */
.main-header.navbar.kai-navbar .user-menu .user-dropdown {
    border: none !important;
    padding: 0 !important;
    margin-top: 12px !important;
    width: 320px !important;
    box-shadow: 0 10px 40px rgba(30, 64, 175, 0.15) !important;
    border-radius: 16px !important;
    overflow: hidden !important;
    background: var(--kai-white) !important;
}

/* User header */
.main-header.navbar.kai-navbar .user-header {
    background: linear-gradient(135deg, var(--kai-primary), var(--kai-primary-light)) !important;
    padding: 24px 20px !important;
    text-align: center !important;
    position: relative !important;
    overflow: hidden !important;
    border: none !important;
    list-style: none !important;
}

.main-header.navbar.kai-navbar .user-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
    animation: rotate 20s linear infinite;
    pointer-events: none;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.main-header.navbar.kai-navbar .user-profile-section {
    position: relative;
    z-index: 2;
}

.main-header.navbar.kai-navbar .user-avatar-large {
    width: 64px;
    height: 64px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.main-header.navbar.kai-navbar .user-avatar-large i {
    font-size: 2rem;
    color: white;
}

.main-header.navbar.kai-navbar .user-info {
    color: white;
}

.main-header.navbar.kai-navbar .user-full-name {
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0 0 4px 0;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.main-header.navbar.kai-navbar .user-position,
.main-header.navbar.kai-navbar .user-group {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.9);
    margin: 2px 0;
    font-weight: 400;
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
}

/* Menu Items */
.main-header.navbar.kai-navbar .user-menu-section {
    padding: 16px 0;
    background: var(--kai-white);
    list-style: none;
}

.main-header.navbar.kai-navbar .user-menu-items {
    display: flex;
    flex-direction: column;
}

.main-header.navbar.kai-navbar .user-menu-item {
    display: block;
    padding: 0;
    color: var(--kai-gray-700);
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    background: none;
}

.main-header.navbar.kai-navbar .user-menu-item:hover {
    color: var(--kai-primary);
    text-decoration: none;
    background: rgba(30, 64, 175, 0.04);
}

.main-header.navbar.kai-navbar .menu-item-content {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    gap: 16px;
}

.main-header.navbar.kai-navbar .menu-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--kai-gray-100), var(--kai-gray-50));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--kai-gray-600);
    font-size: 1rem;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.main-header.navbar.kai-navbar .user-menu-item:hover .menu-icon {
    background: linear-gradient(135deg, var(--kai-primary-light), var(--kai-primary));
    color: white;
    transform: scale(1.05);
}

.main-header.navbar.kai-navbar .menu-text {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.main-header.navbar.kai-navbar .menu-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--kai-gray-800);
    line-height: 1.2;
}

.main-header.navbar.kai-navbar .menu-subtitle {
    font-size: 0.8rem;
    color: var(--kai-gray-500);
    margin-top: 2px;
    line-height: 1.2;
}

.main-header.navbar.kai-navbar .user-menu-item:hover .menu-title {
    color: var(--kai-primary);
}

.main-header.navbar.kai-navbar .user-menu-item:hover .menu-subtitle {
    color: var(--kai-primary-light);
}

/* Logout Section */
.main-header.navbar.kai-navbar .user-footer {
    background: var(--kai-gray-50);
    padding: 16px 20px;
    border-top: 1px solid var(--kai-gray-100);
    list-style: none;
}

.main-header.navbar.kai-navbar .logout-section {
    display: flex;
    justify-content: center;
}

.main-header.navbar.kai-navbar .btn-logout {
    background: linear-gradient(135deg, var(--kai-danger), #f56565) !important;
    border: none !important;
    border-radius: 10px !important;
    padding: 12px 24px !important;
    font-weight: 600 !important;
    font-size: 0.9rem !important;
    color: white !important;
    transition: all 0.3s ease !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    min-width: 120px !important;
    justify-content: center !important;
}

.main-header.navbar.kai-navbar .btn-logout:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4) !important;
    background: linear-gradient(135deg, #dc2626, var(--kai-danger)) !important;
    color: white !important;
}

/* Badge styling */
.main-header.navbar.kai-navbar .navbar-badge {
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.25rem 0.4rem;
    position: absolute;
    right: -0.375rem;
    top: -0.375rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--kai-white);
    animation: pulse 3s ease-in-out infinite;
}

.main-header.navbar.kai-navbar .badge-warning {
    background: linear-gradient(135deg, var(--kai-warning), var(--kai-secondary-light)) !important;
    color: var(--kai-white) !important;
}

.main-header.navbar.kai-navbar .badge-danger {
    background: linear-gradient(135deg, var(--kai-danger), #f56565) !important;
    color: var(--kai-white) !important;
}

/* Dropdown menus */
.main-header.navbar.kai-navbar .dropdown-menu {
    border: 1px solid var(--kai-gray-200);
    box-shadow: 0 4px 6px rgba(30, 64, 175, 0.15), 0 2px 4px rgba(30, 64, 175, 0.06);
    border-radius: 12px;
    overflow: hidden;
    background: var(--kai-white);
}

.main-header.navbar.kai-navbar .dropdown-menu-lg {
    min-width: 280px;
}

.main-header.navbar.kai-navbar .dropdown-header {
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, var(--kai-gray-50), var(--kai-gray-100));
    border-bottom: 1px solid var(--kai-gray-200);
    color: var(--kai-gray-700);
}

.main-header.navbar.kai-navbar .dropdown-footer {
    font-size: 0.875rem;
    font-weight: 600;
    text-align: center;
    background: linear-gradient(135deg, var(--kai-gray-50), var(--kai-gray-100));
    border-top: 1px solid var(--kai-gray-200);
    transition: all 0.3s ease;
    color: var(--kai-primary);
}

.main-header.navbar.kai-navbar .dropdown-footer:hover {
    background: linear-gradient(135deg, var(--kai-primary-light), var(--kai-primary));
    color: var(--kai-white);
    text-decoration: none;
}

.main-header.navbar.kai-navbar .dropdown-item {
    transition: all 0.2s ease;
    color: var(--kai-gray-700);
}

.main-header.navbar.kai-navbar .dropdown-item:hover {
    background: rgba(30, 64, 175, 0.05);
    color: var(--kai-primary);
    transform: translateX(2px);
    text-decoration: none;
}

/* Avatar placeholder */
.main-header.navbar.kai-navbar .avatar-placeholder {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--kai-primary), var(--kai-primary-light));
    border-radius: 50%;
    margin-right: 1rem;
    box-shadow: 0 2px 8px rgba(30, 64, 175, 0.2);
}

.main-header.navbar.kai-navbar .avatar-placeholder i {
    font-size: 2rem;
    color: var(--kai-white);
}

/* Media styling */
.main-header.navbar.kai-navbar .media {
    display: flex;
    align-items: flex-start;
    padding: 0.5rem 0;
}

.main-header.navbar.kai-navbar .media-object {
    margin-right: 1rem;
}

.main-header.navbar.kai-navbar .media-body {
    flex: 1;
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
.main-header.navbar.kai-navbar .text-info { color: var(--kai-primary-light) !important; }
.main-header.navbar.kai-navbar .text-danger { color: var(--kai-danger) !important; }
.main-header.navbar.kai-navbar .text-muted { color: var(--kai-gray-600) !important; }

/* Responsive */
@media (max-width: 767px) {
    .main-header.navbar.kai-navbar .user-nav-link {
        min-width: 50px !important;
        padding: 8px 12px !important;
    }
    
    .main-header.navbar.kai-navbar .user-details {
        display: none !important;
    }
    
    .main-header.navbar.kai-navbar .user-dropdown {
        width: 280px !important;
        margin-left: -200px !important;
    }
    
    .main-header.navbar.kai-navbar .dropdown-menu-lg {
        min-width: 250px !important;
        margin-left: -180px !important;
    }
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>

<script>
// Logout confirmation
function confirmLogout(element) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '<span style="color: var(--kai-primary);">Logout</span>',
            text: 'Are you sure you want to logout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'var(--kai-danger)',
            cancelButtonColor: 'var(--kai-gray-600)',
            confirmButtonText: 'Yes, Logout',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-3'
            }
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

// Initialize dropdown functionality
$(document).ready(function() {
    // Enhanced dropdown handling
    $('.main-header.navbar.kai-navbar .dropdown-toggle').on('click', function(e) {
        e.preventDefault();
        const $dropdown = $(this).next('.dropdown-menu');
        $('.main-header.navbar.kai-navbar .dropdown-menu').not($dropdown).removeClass('show');
        $dropdown.toggleClass('show');
    });

    // Close dropdowns when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.main-header.navbar.kai-navbar .dropdown').length) {
            $('.main-header.navbar.kai-navbar .dropdown-menu').removeClass('show');
        }
    });

    // Prevent dropdown from closing when clicking inside
    $('.main-header.navbar.kai-navbar .dropdown-menu').on('click', function(e) {
        e.stopPropagation();
    });
});
</script>