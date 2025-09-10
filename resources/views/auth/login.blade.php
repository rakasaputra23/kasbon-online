@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('auth_header', 'Sistem Kasbon Online')
@section('auth_body')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success mb-3" role="alert">
                {{ session('status') }}
            </div>
        @endif

        {{-- Email/NIP field --}}
        <div class="input-group mb-3">
            <input id="email" type="text" 
                   class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" 
                   placeholder="Email atau NIP" required autofocus autocomplete="username">
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

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input id="password" type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   name="password" placeholder="Password" required autocomplete="current-password">
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

        {{-- Remember me & Login --}}
        <div class="row">
            <div class="col-7">
                <div class="icheck-primary">
                    <input type="checkbox" id="remember_me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember_me">Ingat Saya</label>
                </div>
            </div>
            <div class="col-5">
                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    @if (Route::has('password.request'))
        <p class="mb-1">
            <a href="{{ route('password.request') }}">Lupa Password?</a>
        </p>
    @endif
@stop