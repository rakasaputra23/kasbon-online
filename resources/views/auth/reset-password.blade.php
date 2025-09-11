<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reset Password - Kasbon Online System</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('vendor/adminlte/dist/img/logo-inka.png') }}" type="image/x-icon">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
  
  <!-- AdminLTE 3 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&family=Pacifico&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <style>
    * {
      box-sizing: border-box;
    }

    body, html {
      height: 100vh;
      width: 100vw;
      margin: 0;
      padding: 0;
      font-family: 'Source Sans Pro', sans-serif;
      overflow-x: hidden;
    }

    .login-page {
      min-height: 100vh;
      width: 100vw;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      background-image: url('{{ asset("vendor/adminlte/dist/img/login-bg.png") }}');
      background-size: cover;
      background-position: center center;
      background-repeat: no-repeat;
      position: relative;
    }

    /* Background overlay */
    .login-page::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.3);
      z-index: 1;
    }

    /* Main Container */
    .login-container {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 500px;
      animation: fadeInUp 0.8s ease-out;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 15px;
      box-shadow: 
        0 14px 28px rgba(0,0,0,0.25),
        0 10px 10px rgba(0,0,0,0.22);
      overflow: hidden;
      width: 100%;
    }

    .card-header {
      background: linear-gradient(135deg, #28a745, #1e7e34);
      border: none;
      text-align: center;
      padding: 2rem 1.5rem 1.8rem;
      position: relative;
      border-radius: 15px 15px 0 0;
    }

    .card-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="%23ffffff" opacity="0.1"><path d="M0,0 C150,100 350,0 500,50 C650,100 850,0 1000,50 L1000,100 L0,100 Z"/></svg>');
      background-size: cover;
    }

    .login-logo {
      position: relative;
      z-index: 1;
      margin-bottom: 0.5rem;
    }

    .login-logo i {
      font-size: 3rem;
      color: white;
      margin-bottom: 0.5rem;
      filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
      animation: keyFloat 2s ease-in-out infinite;
    }

    .reset-title {
      color: white;
      font-size: clamp(1.4rem, 3vw, 1.8rem);
      font-weight: 700;
      margin: 0.5rem 0;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .reset-subtitle {
      color: rgba(255, 255, 255, 0.9);
      font-size: clamp(0.8rem, 2vw, 0.9rem);
      margin: 0;
      text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }

    .card-body {
      padding: 2rem 1.5rem 1.5rem;
      background: rgba(255, 255, 255, 0.95);
    }

    .login-box-msg {
      margin: 0 0 1.5rem 0;
      text-align: center;
      color: #6c757d;
      font-weight: 400;
      font-size: 0.9rem;
    }

    .user-info-box {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      border-left: 4px solid #28a745;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1.5rem;
    }

    .user-info-box h6 {
      color: #28a745;
      font-weight: 600;
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
    }

    .user-info-box p {
      margin: 0;
      font-size: 0.85rem;
      color: #495057;
    }

    .input-group {
      margin-bottom: 1rem;
      position: relative;
    }

    .input-group:last-of-type {
      margin-bottom: 1.5rem;
    }

    .form-control {
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      background-color: #fff;
      transition: all 0.3s ease;
      height: calc(2.5rem + 4px);
      font-size: clamp(0.85rem, 2vw, 0.9rem);
    }

    .form-control:focus {
      border-color: #28a745;
      box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
    }

    .input-group-text {
      background-color: #e9ecef;
      border-color: #e0e0e0;
      color: #495057;
      border-radius: 8px;
      width: 45px;
      justify-content: center;
      font-size: clamp(0.8rem, 1.5vw, 0.9rem);
      transition: all 0.3s ease;
    }

    .password-toggle-btn {
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .password-toggle-btn:hover {
      background-color: #28a745;
      border-color: #28a745;
      color: white;
    }

    .input-group:focus-within .input-group-text:not(.password-toggle-btn) {
      background-color: #28a745;
      border-color: #28a745;
      color: white;
    }

    .input-group:focus-within .form-control {
      border-color: #28a745;
    }

    .btn-primary {
      background: linear-gradient(135deg, #28a745, #1e7e34);
      border: none;
      border-radius: 8px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 0.8rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      font-size: clamp(0.85rem, 2vw, 0.9rem);
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #1e7e34, #155724);
      transform: translateY(-1px);
      box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-primary:focus {
      box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
    }

    .btn-secondary {
      background-color: #6c757d;
      border-color: #6c757d;
      border-radius: 8px;
      font-weight: 500;
      padding: 0.6rem 1rem;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      text-align: center;
      font-size: clamp(0.8rem, 1.8vw, 0.85rem);
    }

    .btn-secondary:hover {
      background-color: #545b62;
      border-color: #545b62;
      color: white;
      text-decoration: none;
      transform: translateY(-1px);
    }

    .alert {
      border-radius: 8px;
      border: none;
      margin-bottom: 1.5rem;
      font-size: 0.85rem;
      position: relative;
      overflow: hidden;
    }

    .alert-danger {
      background: linear-gradient(135deg, #dc3545, #bd2130);
      color: white;
    }

    .alert-success {
      background: linear-gradient(135deg, #28a745, #1e7e34);
      color: white;
    }

    .alert-warning {
      background: linear-gradient(135deg, #ffc107, #e0a800);
      color: #212529;
    }

    .alert::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      animation: alertShine 2s infinite;
    }

    .alert .close {
      color: white;
      text-shadow: none;
      opacity: 0.8;
    }

    .alert .close:hover {
      opacity: 1;
    }

    .password-requirements {
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1.5rem;
    }

    .password-requirements h6 {
      color: #495057;
      font-size: 0.85rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .password-requirements ul {
      margin: 0;
      padding-left: 1.5rem;
    }

    .password-requirements li {
      font-size: 0.8rem;
      color: #6c757d;
      margin-bottom: 0.25rem;
    }

    .invalid-feedback {
      color: #dc3545;
      font-size: 0.8rem;
      font-weight: 500;
      margin-top: 0.5rem;
    }

    .is-invalid {
      border-color: #dc3545 !important;
    }

    .is-invalid:focus {
      border-color: #dc3545 !important;
      box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }

    .btn-loading {
      position: relative;
      color: transparent;
    }

    .btn-loading::after {
      content: '';
      position: absolute;
      width: 16px;
      height: 16px;
      top: 50%;
      left: 50%;
      margin-left: -8px;
      margin-top: -8px;
      border: 2px solid #ffffff;
      border-radius: 50%;
      border-top-color: transparent;
      animation: spin 1s linear infinite;
    }

    .card-footer {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      border-top: 1px solid #dee2e6;
      padding: 1rem 1.5rem;
      text-align: center;
      font-size: 0.75rem;
      color: #6c757d;
    }

    /* Password Match Indicator */
    .password-match-indicator {
      margin-bottom: 1rem;
      padding: 0.5rem;
      border-radius: 6px;
      font-size: 0.8rem;
      font-weight: 500;
    }

    .password-match-indicator.match {
      background-color: rgba(40, 167, 69, 0.1);
      color: #28a745;
      border: 1px solid rgba(40, 167, 69, 0.3);
    }

    .password-match-indicator.no-match {
      background-color: rgba(220, 53, 69, 0.1);
      color: #dc3545;
      border: 1px solid rgba(220, 53, 69, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .login-page {
        padding: 1rem;
      }
      
      .login-container {
        max-width: 100%;
      }
      
      .card-body, .card-header {
        padding-left: 1.3rem;
        padding-right: 1.3rem;
      }
      
      .form-control {
        height: calc(3rem + 4px);
        font-size: 16px; /* Prevents zoom on iOS */
      }
    }

    @media (max-width: 576px) {
      .login-page {
        padding: 1rem;
      }
      
      .login-container {
        max-width: 100%;
      }
      
      .card-body, .card-header {
        padding-left: 1rem;
        padding-right: 1rem;
      }

      .card-header {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
      }
      
      .btn-primary {
        padding: 1rem 0.7rem;
      }
    }

    @media (max-width: 360px) {
      .card-body {
        padding: 1.5rem 0.8rem;
      }
      
      .card-header {
        padding: 1.2rem 0.8rem 1rem;
      }
    }

    /* Animations */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes keyFloat {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    @keyframes alertShine {
      0% { left: -100%; }
      100% { left: 100%; }
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Print styles */
    @media print {
      .login-page {
        display: none;
      }
    }
  </style>
</head>
<body class="hold-transition login-page">

<div class="login-page">
  <div class="login-container">
    <!-- Main Card -->
    <div class="login-card">
      <div class="card-header">
        <div class="login-logo">
          <i class="fas fa-key"></i>
          <h3 class="reset-title">Reset Password</h3>
          <p class="reset-subtitle">Buat password baru untuk akun Anda</p>
        </div>
      </div>
      
      <div class="card-body">
        @if(isset($is_manual) && $is_manual)
          <!-- Manual Reset Mode -->
          <p class="login-box-msg">Manual Password Reset (Admin Access)</p>
          
          <div class="alert alert-warning">
            <h6><i class="icon fas fa-exclamation-triangle"></i> Manual Reset</h6>
            <small>This is a manual password reset. Enter the NIP of the user whose password you want to reset.</small>
          </div>
        @else
          <!-- Email Token Mode -->
          <p class="login-box-msg">Masukkan password baru Anda di bawah ini</p>
          
          @if(isset($user))
          <div class="user-info-box">
            <h6><i class="fas fa-user-check mr-2"></i>Akun Terverifikasi</h6>
            <p><strong>NIP:</strong> {{ $user->nip }}</p>
            <p><strong>Nama:</strong> {{ $user->nama }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
          </div>
          @endif
        @endif

        <!-- Messages -->
        @if($errors->any())
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h6><i class="icon fas fa-ban"></i> Error!</h6>
            <ul class="mb-0 pl-3">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if(session('status'))
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h6><i class="icon fas fa-check"></i> Berhasil!</h6>
            {{ session('status') }}
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h6><i class="icon fas fa-exclamation-triangle"></i> Gagal!</h6>
            {{ session('error') }}
          </div>
        @endif

        <!-- Reset Password Form -->
        <form action="{{ route('password.update') }}" method="POST" id="resetPasswordForm">
          @csrf
          
          <!-- Hidden fields for token mode -->
          @if(!isset($is_manual) || !$is_manual)
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="nip" value="{{ $nip }}">
          @else
            <!-- NIP Field for manual mode -->
            <div class="input-group mb-3">
              <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
                     placeholder="Masukkan NIP untuk reset" value="{{ old('nip') }}" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            @error('nip')
              <div class="invalid-feedback d-block mb-2">
                <strong>{{ $message }}</strong>
              </div>
            @enderror
          @endif

          <!-- Password Requirements Box -->
          <div class="password-requirements">
            <h6><i class="fas fa-shield-alt mr-2"></i>Persyaratan Password</h6>
            <ul>
              <li>Minimal 6 karakter</li>
              <li>Konfirmasi password harus sama</li>
              <li>Gunakan password yang kuat dan unik</li>
              <li>Hindari kata-kata umum atau informasi pribadi</li>
            </ul>
          </div>

          <!-- New Password Field -->
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                   placeholder="Password Baru" required id="passwordField">
            <div class="input-group-append">
              <div class="input-group-text password-toggle-btn" id="togglePassword">
                <span class="fas fa-eye"></span>
              </div>
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          @error('password')
            <div class="invalid-feedback d-block mb-2">
              <strong>{{ $message }}</strong>
            </div>
          @enderror

          <!-- Confirm Password Field -->
          <div class="input-group mb-3">
            <input type="password" name="password_confirmation" class="form-control" 
                   placeholder="Konfirmasi Password Baru" required id="passwordConfirmField">
            <div class="input-group-append">
              <div class="input-group-text password-toggle-btn" id="togglePasswordConfirm">
                <span class="fas fa-eye"></span>
              </div>
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>

          <!-- Password Match Indicator -->
          <div id="passwordMatchIndicator" class="password-match-indicator" style="display: none;">
            <span id="matchText"></span>
          </div>

          <!-- Submit Button -->
          <div class="row mb-3">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block" id="resetBtn">
                <i class="fas fa-key mr-2"></i>RESET PASSWORD
              </button>
            </div>
          </div>

          <!-- Back to Login -->
          <div class="row">
            <div class="col-12 text-center">
              <a href="{{ route('login') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Login
              </a>
            </div>
          </div>
        </form>

      </div>
      
      <div class="card-footer text-muted">
        <small>&copy; {{ date('Y') }} Kasbon Online System. All rights reserved.</small>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
  // Focus on appropriate field
  setTimeout(function() {
    @if(isset($is_manual) && $is_manual)
      $('input[name="nip"]').focus();
    @else
      $('input[name="password"]').focus();
    @endif
  }, 500);
  
  // Password toggle functionality
  $('#togglePassword').on('click', function() {
    const passwordField = $('#passwordField');
    const icon = $(this).find('span');
    const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
    passwordField.attr('type', type);
    icon.toggleClass('fa-eye fa-eye-slash');
  });

  $('#togglePasswordConfirm').on('click', function() {
    const passwordField = $('#passwordConfirmField');
    const icon = $(this).find('span');
    const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
    passwordField.attr('type', type);
    icon.toggleClass('fa-eye fa-eye-slash');
  });
  
  // Password matching validation
  function checkPasswordMatch() {
    const password = $('#passwordField').val();
    const confirmPassword = $('#passwordConfirmField').val();
    const indicator = $('#passwordMatchIndicator');
    const matchText = $('#matchText');
    
    if (password && confirmPassword) {
      indicator.show();
      if (password === confirmPassword) {
        indicator.removeClass('no-match').addClass('match');
        matchText.html('<i class="fas fa-check mr-1"></i>Password cocok');
      } else {
        indicator.removeClass('match').addClass('no-match');
        matchText.html('<i class="fas fa-times mr-1"></i>Password tidak cocok');
      }
    } else {
      indicator.hide();
    }
  }
  
  // Real-time password matching
  $('#passwordField, #passwordConfirmField').on('input', checkPasswordMatch);
  
  // Form submission handling
  $('#resetPasswordForm').on('submit', function(e) {
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    let hasError = false;
    
    @if(isset($is_manual) && $is_manual)
    // Validate NIP for manual mode
    const nipValue = $('input[name="nip"]').val().trim();
    if (nipValue === '') {
      showFieldError('nip', 'NIP tidak boleh kosong');
      hasError = true;
    } else if (nipValue.length < 5) {
      showFieldError('nip', 'NIP minimal 5 karakter');
      hasError = true;
    }
    @endif
    
    // Validate passwords
    const passwordValue = $('input[name="password"]').val();
    const confirmPasswordValue = $('input[name="password_confirmation"]').val();
    
    if (passwordValue === '') {
      showFieldError('password', 'Password baru tidak boleh kosong');
      hasError = true;
    } else if (passwordValue.length < 6) {
      showFieldError('password', 'Password minimal 6 karakter');
      hasError = true;
    }
    
    if (confirmPasswordValue === '') {
      showFieldError('password_confirmation', 'Konfirmasi password tidak boleh kosong');
      hasError = true;
    } else if (passwordValue !== confirmPasswordValue) {
      showFieldError('password_confirmation', 'Konfirmasi password tidak sesuai');
      hasError = true;
    }
    
    if (hasError) {
      e.preventDefault();
      return false;
    }
    
    // Show loading state
    const resetBtn = $('#resetBtn');
    resetBtn.addClass('btn-loading').prop('disabled', true);
    resetBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>MEMPROSES...');
  });
  
  function showFieldError(fieldName, message) {
    const field = $(`input[name="${fieldName}"]`);
    field.addClass('is-invalid');
    field.closest('.input-group').after(`<div class="invalid-feedback d-block"><strong>${message}</strong></div>`);
  }
  
  // Clear error on input
  $('.form-control').on('input', function() {
    $(this).removeClass('is-invalid');
    $(this).closest('.input-group').next('.invalid-feedback').remove();
    
    const resetBtn = $('#resetBtn');
    if (resetBtn.hasClass('btn-loading')) {
      resetBtn.removeClass('btn-loading').prop('disabled', false);
      resetBtn.html('<i class="fas fa-key mr-2"></i>RESET PASSWORD');
    }
  });
  
  // Auto-hide alerts
  setTimeout(function() {
    $('.alert').fadeOut('slow');
  }, 8000);
});
</script>

</body>
</html>