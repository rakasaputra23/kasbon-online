<nav class="main-header navbar navbar-expand navbar-primary navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars text-white"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('dashboard') }}" class="nav-link text-white">
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
      <a class="nav-link text-white" data-toggle="dropdown" href="#">
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
      <a class="nav-link text-white" data-toggle="dropdown" href="#">
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
        <a href="#" class="nav-link dropdown-toggle text-white" data-toggle="dropdown">
          <i class="fas fa-user-circle user-icon"></i>
          <span class="d-none d-md-inline">{{ $user->nama }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User icon header -->
          <li class="user-header bg-primary-custom">
            <i class="fas fa-user-circle user-icon-large"></i>
            <p>
              {{ $user->nama }}
              @if($user->posisi)
                <small>{{ $user->posisi }}</small>
              @endif
              @if($user->userGroup)
                <br><small>{{ $user->userGroup->name }}</small>
              @endif
            </p>
          </li>

          <!-- Menu Footer-->
          <li class="user-footer">
            <div class="row">
              <div class="col-6">
                @if(Route::has('profile'))
                <a href="{{ route('profile') }}" class="btn btn-default btn-flat btn-block">
                  <i class="fas fa-user mr-1"></i> Profile
                </a>
                @endif
              </div>
              <div class="col-6">
                @if(Route::has('profile.edit'))
                <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat btn-block">
                  <i class="fas fa-edit mr-1"></i> Edit Profile
                </a>
                @endif
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-12">
                <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                  @csrf
                  <a href="#" class="btn btn-danger btn-flat btn-block" onclick="confirmLogout(this)">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                  </a>
                </form>
              </div>
            </div>
          </li>
        </ul>
      </li>
    @endif
  </ul>
</nav>

<!-- Enhanced CSS dengan tema biru elegan yang match dengan sidebar -->
<style>
/* Navbar styling dengan gradient biru elegan */
.main-header.navbar-primary {
    background: linear-gradient(135deg, #2c5282 0%, #3182ce 50%, #4299e1 100%) !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Navbar link styling */
.navbar-primary .navbar-nav .nav-link {
    color: rgba(255, 255, 255, 0.9) !important;
    transition: all 0.3s ease;
    border-radius: 6px;
    margin: 0 2px;
    padding: 8px 12px !important;
}

.navbar-primary .navbar-nav .nav-link:hover {
    color: #ffffff !important;
    background-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.navbar-primary .navbar-nav .nav-link:focus {
    color: #ffffff !important;
    background-color: rgba(255, 255, 255, 0.2);
}

/* Pushmenu button special styling */
.nav-link[data-widget="pushmenu"] {
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
}

.nav-link[data-widget="pushmenu"]:hover {
    background-color: rgba(255, 255, 255, 0.2) !important;
    transform: translateY(-1px);
}

/* Badge styling dengan efek subtle */
.navbar-badge {
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.25rem 0.4rem;
    position: absolute;
    right: -0.375rem;
    top: -0.375rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.9);
}

.badge-warning {
    background: linear-gradient(135deg, #f6ad55, #ed8936);
    color: #ffffff;
}

.badge-danger {
    background: linear-gradient(135deg, #fc8181, #e53e3e);
    color: #ffffff;
}

/* Dropdown menu enhancements */
.dropdown-menu {
    border: none;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    overflow: hidden;
}

.dropdown-menu-lg {
    min-width: 280px;
}

.dropdown-header {
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 1px solid #dee2e6;
    color: #495057;
}

.dropdown-footer {
    font-size: 0.875rem;
    font-weight: 600;
    text-align: center;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-top: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.dropdown-footer:hover {
    background: linear-gradient(135deg, #e9ecef, #dee2e6);
    color: #495057;
    text-decoration: none;
}

.dropdown-item {
    transition: all 0.2s ease;
    border-radius: 0;
}

.dropdown-item:hover {
    background-color: rgba(44, 82, 130, 0.08);
    transform: translateX(2px);
}

/* Message media styling enhancements */
.media {
    display: flex;
    align-items: flex-start;
    padding: 0.5rem 0;
}

.media-object {
    margin-right: 1rem;
}

.media-body {
    flex: 1;
}

.avatar-placeholder {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    margin-right: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.avatar-placeholder i {
    font-size: 2rem;
    color: white;
}

.img-size-50 {
    width: 50px;
    height: 50px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.img-circle {
    border-radius: 50%;
}

/* User menu dropdown styling dengan tema yang konsisten */
.user-menu .dropdown-menu {
    border-top: 0;
    padding: 0;
    margin-top: 0;
    width: 280px;
}

/* User icon styling dengan warna putih untuk navbar biru */
.user-icon {
    font-size: 2.1rem;
    color: rgba(255, 255, 255, 0.9);
    margin-right: 0.5rem;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.user-icon-large {
    font-size: 5rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 10px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* User header dengan gradient yang match */
.user-header.bg-primary-custom {
    background: linear-gradient(135deg, #2c5282 0%, #3182ce 50%, #4299e1 100%) !important;
    height: 175px;
    padding: 30px 25px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.user-header.bg-primary-custom::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" patternUnits="userSpaceOnUse" width="100" height="100"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.user-header p {
    z-index: 5;
    color: #fff;
    font-size: 17px;
    margin-top: 10px;
    position: relative;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.user-header small {
    display: block;
    font-size: 12px;
    opacity: 0.9;
}

/* User footer dengan styling yang lebih modern */
.user-footer {
    background-color: #f8f9fa;
    padding: 15px;
}

.user-footer .btn-flat {
    border-radius: 6px;
    border-width: 1px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.user-footer .btn-default:hover {
    background-color: #e9ecef;
    border-color: #adb5bd;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.user-footer .btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

/* Dropdown toggle styling */
.user-menu .nav-link.dropdown-toggle::after {
    border: none;
    content: "\f0d7";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    margin-left: 6px;
    transition: transform 0.3s ease;
}

.user-menu .nav-link.dropdown-toggle:hover::after {
    transform: rotate(180deg);
}

/* Text colors untuk icons dengan kontras yang baik */
.text-success { color: #28a745 !important; }
.text-warning { color: #ffc107 !important; }
.text-info { color: #17a2b8 !important; }
.text-danger { color: #dc3545 !important; }

/* Responsive adjustments */
@media (max-width: 767px) {
    .user-menu .dropdown-menu,
    .dropdown-menu-lg {
        width: 250px;
        margin-left: -180px;
    }
    
    .navbar-badge {
        right: -0.25rem;
        top: -0.25rem;
    }
    
    .navbar-primary .navbar-nav .nav-link {
        padding: 6px 8px !important;
    }
}

/* Enhanced animations */
@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.navbar-badge {
    animation: pulse 3s ease-in-out infinite;
}

/* Subtle hover animations untuk dropdown items */
.dropdown-item-title {
    font-size: 0.875rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
    transition: color 0.2s ease;
}

.dropdown-item:hover .dropdown-item-title {
    color: #2c5282;
}

/* Loading shimmer effect untuk avatar placeholder */
@keyframes shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

.avatar-placeholder {
    background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #667eea 100%);
    background-size: 1000px 100%;
    animation: shimmer 2s infinite linear;
}
</style>

<script>
// Simple logout confirmation dengan tema yang konsisten
function confirmLogout(element) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Logout',
            text: 'Are you sure you want to logout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Logout',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-3'
            },
            buttonsStyling: true
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

// Mark notification as read dengan feedback visual
function markNotificationAsRead(notificationId) {
    console.log('Marking notification ' + notificationId + ' as read');
    // Tambahkan efek visual saat notification dibaca
    const badge = document.querySelector('.badge-warning');
    if (badge) {
        badge.style.transform = 'scale(0.8)';
        setTimeout(() => {
            badge.style.transform = 'scale(1)';
        }, 150);
    }
}

// Mark message as read dengan feedback visual
function markMessageAsRead(messageId) {
    console.log('Marking message ' + messageId + ' as read');
    // Tambahkan efek visual saat message dibaca
    const badge = document.querySelector('.badge-danger');
    if (badge) {
        badge.style.transform = 'scale(0.8)';
        setTimeout(() => {
            badge.style.transform = 'scale(1)';
        }, 150);
    }
}

// Initialize navbar interactions
$(document).ready(function() {
    // Smooth animations untuk dropdown
    $('.dropdown-toggle').on('click', function() {
        $(this).next('.dropdown-menu').fadeIn(200);
    });
    
    // Auto-hide dropdowns when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').fadeOut(150);
        }
    });
});
</script>