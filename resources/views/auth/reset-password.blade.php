@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', 'Reset Password')

@section('auth_body')
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="input-group mb-3">
            <input id="email" type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email', $request->email) }}" 
                   placeholder="Email" required autofocus autocomplete="username">
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

        <!-- Password -->
        <div class="input-group mb-3">
            <input id="password" type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   name="password" placeholder="Password Baru" required autocomplete="new-password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="input-group mb-3">
            <input id="password_confirmation" type="password" 
                   class="form-control" 
                   name="password_confirmation" placeholder="Konfirmasi Password" required autocomplete="new-password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Reset Password
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