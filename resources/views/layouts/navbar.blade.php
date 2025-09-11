<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="text-decoration-none">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                @if(isset($breadcrumbs))
                    @foreach($breadcrumbs as $breadcrumb)
                        @if($loop->last)
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $breadcrumb['title'] }}
                            </li>
                        @else
                            <li class="breadcrumb-item">
                                <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">
                                    {{ $breadcrumb['title'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @else
                    @if(request()->routeIs('user'))
                        <li class="breadcrumb-item active" aria-current="page">User Management</li>
                    @elseif(request()->routeIs('user.group'))
                        <li class="breadcrumb-item active" aria-current="page">User Groups</li>
                    @elseif(request()->routeIs('dashboard'))
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    @endif
                @endif
            </ol>
        </nav>
        
        <!-- User Dropdown -->
        <div class="ms-auto">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user me-1"></i>
                    {{ Auth::user()->nama }}
                    <span class="badge bg-primary ms-1">{{ Auth::user()->userGroup ? Auth::user()->userGroup->name : 'No Group' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <h6 class="dropdown-header">
                            <div class="text-primary">{{ Auth::user()->nama }}</div>
                            <small class="text-muted">{{ Auth::user()->posisi }}</small>
                        </h6>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile') }}">
                            <i class="fas fa-user me-2"></i>Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>