@extends('layouts.guest')

@section('title', 'Lupa Password - Kasbon System')

@section('content')
<div class="card login-card">
    <div class="card-header">
        <div class="login-logo">
            <i class="fas fa-key"></i>
        </div>
        <h3 class="mb-0">Lupa Password</h3>
        <p class="mb-0 mt-2">Masukkan NIP untuk reset password</p>
    </div>
    
    <div class="card-body">
        <!-- Display Status Messages -->
        @if (session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
            </div>
        @endif
        
        <!-- Display Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Masukkan NIP Anda dan kami akan mengirimkan link reset password ke email terdaftar.
        </div>
        
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <!-- NIP Field -->
            <div class="form-floating mb-4">
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
            
            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>Kirim Link Reset Password
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