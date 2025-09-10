@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', 'Reset Password')

@section('auth_body')
    <div class="mb-4 text-sm text-muted">
        Lupa password Anda? Tidak masalah. Beritahu kami alamat email Anda dan kami akan mengirimkan tautan reset password melalui email yang akan memungkinkan Anda memilih yang baru.
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-3" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="input-group mb-3">
            <input id="email" type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" 
                   placeholder="Email" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Kirim Link Reset Password
                </button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    <p class="mb-1">
        <a href="{{ route('login') }}">Kembali ke Login</a>
    </p>
@stop