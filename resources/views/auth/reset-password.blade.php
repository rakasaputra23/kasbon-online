@extends('layouts.guest')

@section('title', 'Reset Password - Kasbon System')

@section('content')
<div class="card login-card">
    <div class="card-header">
        <div class="login-logo">
            <i class="fas fa-key"></i>
        </div>
        <h3 class="mb-0">Reset Password</h3>
        <p class="mb-0 mt-2">Masukkan password baru Anda</p>
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
        
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token ?? '' }}">
            
            <!-- NIP Field -->
            <div class="form-floating mb-3">
                <input type="text" 
                       class="form-control @error('nip') is-invalid @enderror" 
                       id="nip" 
                       name="nip" 
                       placeholder="Masukkan NIP"
                       value="{{ $nip ?? old('nip') }}" 
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
                       placeholder="Password Baru"
                       required>
                <label for="password">
                    <i class="fas fa-lock me-2"></i>Password Baru
                </label>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <!-- Confirm Password Field -->
            <div class="form-floating mb-4">
                <input type="password" 
                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       placeholder="Konfirmasi Password"
                       required>
                <label for="password_confirmation">
                    <i class="fas fa-lock me-2"></i>Konfirmasi Password
                </label>
                @error('password_confirmation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Reset Password
                </button>
            </div>
            
            <!-- Back to Login -->
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection