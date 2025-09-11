@extends('layouts.guest')

@section('title', 'Login - Kasbon System')

@section('content')
<div class="card login-card">
    <div class="card-header">
        <div class="login-logo">
            <i class="fas fa-receipt"></i>
        </div>
        <h3 class="mb-0">Kasbon Online System</h3>
        <p class="mb-0 mt-2">Silakan masuk untuk melanjutkan</p>
    </div>
    
    <div class="card-body">
        <!-- Display Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        
        <!-- Display Status Messages -->
        @if (session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <!-- NIP Field -->
            <div class="form-floating mb-3">
                <input type="text" 
                       class="form-control @error('nip') is-invalid @enderror" 
                       id="nip" 
                       name="nip" 
                       placeholder="Masukkan NIP"
                       value="{{ old('nip') }}" 
                       required 
                       autofocus>
                <label for="nip">
                    <i class="fas fa-user me-2"></i>NIP
                </label>
                @error('nip')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <!-- Password Field -->
            <div class="form-floating mb-3">
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       placeholder="Masukkan Password"
                       required>
                <label for="password">
                    <i class="fas fa-lock me-2"></i>Password
                </label>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <!-- Remember Me -->
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Ingat Saya
                </label>
            </div>
            
            <!-- Login Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                </button>
            </div>
            
            <!-- Forgot Password Link -->
            <div class="text-center mt-3">
                <a href="{{ route('password.request') }}" class="text-decoration-none">
                    <i class="fas fa-key me-1"></i>Lupa Password?
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Demo Accounts Info -->
<div class="card mt-3">
    <div class="card-body">
        <h6 class="card-title text-center mb-3">
            <i class="fas fa-info-circle me-2"></i>Demo Accounts
        </h6>
        <div class="row text-center">
            <div class="col-md-6">
                <div class="mb-2">
                    <strong>Admin:</strong><br>
                    <small class="text-muted">NIP: ADMIN001</small><br>
                    <small class="text-muted">Password: password</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-2">
                    <strong>User:</strong><br>
                    <small class="text-muted">NIP: 00001</small><br>
                    <small class="text-muted">Password: password</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection