<nav class="main-header navbar navbar-expand navbar-white navbar-light">
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
    <li class="nav-item d-none d-md-inline-block">
      <a href="{{ route('dashboard') }}" class="nav-link">
        <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
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
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <i class="fas fa-user-circle user-icon"></i>
          <span class="d-none d-md-inline">{{ $user->nama }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User icon header -->
          <li class="user-header bg-primary">
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

<!-- AdminLTE Style CSS -->
<style>
/* Navbar badge styling */
.navbar-badge {
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.25rem 0.4rem;
    position: absolute;
    right: -0.375rem;
    top: -0.375rem;
}

/* Notification and message dropdown styling */
.dropdown-menu-lg {
    min-width: 280px;
}

.dropdown-item-title {
    font-size: 0.875rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.dropdown-header {
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.5rem 1.5rem;
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.dropdown-footer {
    font-size: 0.875rem;
    font-weight: 600;
    text-align: center;
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.dropdown-footer:hover {
    background-color: #e9ecef;
    color: #495057;
    text-decoration: none;
}

/* Message media styling */
.media {
    display: flex;
    align-items: flex-start;
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
    background-color: #6c757d;
    border-radius: 50%;
    margin-right: 1rem;
}

.avatar-placeholder i {
    font-size: 2rem;
    color: white;
}

.img-size-50 {
    width: 50px;
    height: 50px;
}

.img-circle {
    border-radius: 50%;
}

/* User menu dropdown styling */
.user-menu .dropdown-menu {
    border-top: 0;
    padding: 0;
    margin-top: 0;
    width: 280px;
}

/* User icon styling */
.user-icon {
    font-size: 2.1rem;
    color: #6c757d;
    margin-right: 0.5rem;
}

.user-icon-large {
    font-size: 5rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 10px;
}

/* User header */
.user-header {
    height: 175px;
    padding: 30px 25px;
    text-align: center;
}

.user-header p {
    z-index: 5;
    color: #fff;
    color: rgba(255, 255, 255, 0.8);
    font-size: 17px;
    margin-top: 10px;
}

.user-header small {
    display: block;
    font-size: 12px;
}

/* User footer */
.user-footer {
    background-color: #f4f4f4;
    padding: 15px;
}

.user-footer .btn-flat {
    border-radius: 0;
    border-width: 1px;
    font-size: 14px;
}

/* Dropdown toggle */
.user-menu .nav-link.dropdown-toggle::after {
    border: none;
    content: "\f0d7";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    margin-left: 6px;
}

.user-menu .nav-link:hover,
.nav-item .nav-link:hover {
    background-color: rgba(0,0,0,0.1);
    border-radius: 4px;
}

/* Badge colors */
.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

.badge-danger {
    background-color: #dc3545;
    color: #fff;
}

.badge-success {
    background-color: #28a745;
    color: #fff;
}

.badge-info {
    background-color: #17a2b8;
    color: #fff;
}

/* Text colors for icons */
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
}

/* Notification and message dropdown hover effects */
.dropdown-item:hover {
    background-color: #f8f9fa;
}

/* Animation for badges */
.navbar-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}
</style>

<script>
// Simple logout confirmation
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

// Mark notification as read (optional)
function markNotificationAsRead(notificationId) {
    // Add your AJAX call here to mark notification as read
    console.log('Marking notification ' + notificationId + ' as read');
}

// Mark message as read (optional)
function markMessageAsRead(messageId) {
    // Add your AJAX call here to mark message as read
    console.log('Marking message ' + messageId + ' as read');
}
</script>