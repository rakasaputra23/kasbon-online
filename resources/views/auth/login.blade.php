<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Kasbon Online System</title>
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
      max-width: 450px;
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
      background: linear-gradient(135deg, #007bff, #0056b3);
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
      margin-bottom: 0;
    }

    .login-logo img {
      height: 55px;
      margin-bottom: 0.75rem;
      filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
      max-width: 100%;
    }

    .brand-text {
      color: white;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
      display: block;
    }

    .brand-bom {
      font-family: 'Source Sans Pro', sans-serif;
      font-size: clamp(1.2rem, 3vw, 1.6rem);
      font-weight: 700;
      letter-spacing: 2px;
    }

    .brand-system {
      font-family: 'Pacifico', cursive;
      font-size: clamp(0.75rem, 2vw, 0.9rem);
      font-weight: 400;
      opacity: 0.9;
    }

    .card-body {
      padding: 1.8rem 1.5rem 1.2rem;
      background: rgba(255, 255, 255, 0.95);
    }

    .login-box-msg {
      margin: 0 0 1.5rem 0;
      text-align: center;
      color: #6c757d;
      font-weight: 400;
      font-size: 0.9rem;
    }

    .input-group {
      margin-bottom: 1rem;
      position: relative;
    }

    .input-group:last-of-type {
      margin-bottom: 1.5rem;
    }

    .form-control {
      border: 1px solid #ced4da;
      border-radius: 0.25rem;
      background-color: #fff;
      transition: all 0.15s ease-in-out;
      height: calc(2.5rem + 2px);
      font-size: clamp(0.85rem, 2vw, 0.9rem);
    }

    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .input-group-text {
      background-color: #e9ecef;
      border-color: #ced4da;
      color: #495057;
      border-radius: 0.25rem;
      width: 45px;
      justify-content: center;
      font-size: clamp(0.8rem, 1.5vw, 0.9rem);
    }

    .password-toggle-btn {
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .password-toggle-btn:hover {
      background-color: #007bff;
      border-color: #007bff;
      color: white;
    }

    .input-group:focus-within .input-group-text {
      background-color: #007bff;
      border-color: #007bff;
      color: white;
    }

    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
      border-radius: 0.25rem;
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
      background-color: #0056b3;
      border-color: #0056b3;
      transform: translateY(-1px);
      box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    }

    .btn-primary:focus {
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .icheck-primary {
      margin-right: 0.5rem;
    }

    .forgot-password {
      color: #007bff;
      text-decoration: none;
      font-size: clamp(0.75rem, 1.5vw, 0.85rem);
      transition: color 0.3s ease;
      font-weight: 500;
    }

    .forgot-password:hover {
      color: #0056b3;
      text-decoration: underline;
    }

    /* Alert Styles */
    .alert {
      border-radius: 8px;
      border: none;
      margin-bottom: 1.5rem;
      font-size: 0.85rem;
    }

    .alert-danger {
      background: linear-gradient(135deg, #dc3545, #bd2130);
      color: white;
    }

    .alert-success {
      background: linear-gradient(135deg, #28a745, #1e7e34);
      color: white;
    }

    .alert .close {
      color: white;
      text-shadow: none;
      opacity: 0.8;
    }

    .alert .close:hover {
      opacity: 1;
    }

    .invalid-feedback {
      color: #dc3545;
      font-size: 0.8rem;
      font-weight: 500;
    }

    .is-invalid {
      border-color: #dc3545;
    }

    .is-invalid:focus {
      border-color: #dc3545;
      box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    /* Loading Animation */
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

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .card-footer {
      background-color: #f8f9fa;
      border-top: 1px solid #dee2e6;
      padding: 1rem 1.5rem;
      text-align: center;
      font-size: 0.75rem;
      color: #6c757d;
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
        height: calc(2.75rem + 2px);
        font-size: 16px; /* Prevents zoom on iOS */
      }
      
      .input-group-text {
        width: 50px;
      }
    }

    @media (max-width: 576px) {
      .login-page {
        padding: 1rem;
      }
      
      .login-container {
        max-width: 320px;
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
        padding: 1rem 0.8rem;
      }
      
      .card-header {
        padding: 1.2rem 0.8rem 1rem;
      }
    }

    /* Animation */
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

    .login-card {
      animation: fadeInUp 0.8s ease-out;
    }

    /* Ripple effect */
    .ripple {
      position: absolute;
      border-radius: 50%;
      background-color: rgba(255, 255, 255, 0.3);
      transform: scale(0);
      animation: ripple 0.6s linear;
      pointer-events: none;
    }

    @keyframes ripple {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }

    /* Additional AdminLTE-like enhancements */
    .form-control::placeholder {
      color: #adb5bd;
      opacity: 1;
    }

    /* Form focus styles */
    .input-group:focus-within .form-control {
      border-color: #007bff;
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
    <!-- Main Login Card -->
    <div class="login-card">
      <div class="card-header">
        <div class="login-logo">
          <a href="{{ url('/') }}" style="text-decoration: none;">
            <img src="{{ asset('vendor/adminlte/dist/img/logo-qinka.png') }}" alt="Logo Kasbon">
            <div class="brand-text">
              <div class="brand-bom">KASBON</div>
              <div class="brand-system">Online System</div>
            </div>
          </a>
        </div>
      </div>
      
      <div class="card-body">
        <p class="login-box-msg">Silakan masuk untuk melanjutkan</p>

        <!-- Error/Success Messages -->
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

        @if(session('error'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h6><i class="icon fas fa-ban"></i> Login Gagal!</h6>
            {{ session('error') }}
          </div>
        @endif

        @if(session('success'))
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h6><i class="icon fas fa-check"></i> Berhasil!</h6>
            {{ session('success') }}
          </div>
        @endif

        @if(session('status'))
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h6><i class="icon fas fa-check"></i> Berhasil!</h6>
            {{ session('status') }}
          </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST" id="loginForm">
          @csrf
          
          <!-- NIP Field -->
          <div class="input-group mb-3">
            <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
                   placeholder="NIP" value="{{ old('nip') }}" required autofocus>
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

          <!-- Password Field -->
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                   placeholder="Password" required id="passwordField">
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

          <div class="row mb-3">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">
                  Ingat Saya
                </label>
              </div>
            </div>
            <div class="col-4">
              <a href="{{ route('password.request') }}" class="forgot-password text-right d-block">
                Lupa?
              </a>
            </div>
          </div>

          <!-- Login Button -->
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block" id="loginBtn">
                MASUK
              </button>
            </div>
          </div>
        </form>

      </div>
      
      <!-- Card Footer -->
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

<!-- Custom JS -->
<script>
$(document).ready(function() {
  // Focus on NIP field when page loads
  setTimeout(function() {
    $('input[name="nip"]').focus();
  }, 500);
  
  // Password toggle functionality
  $('#togglePassword').on('click', function() {
    const passwordField = $('#passwordField');
    const icon = $(this).find('span');
    const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
    passwordField.attr('type', type);
    icon.toggleClass('fa-eye fa-eye-slash');
  });
  
  // Form submission handling with validation
  $('#loginForm').on('submit', function(e) {
    // Reset previous errors
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    let hasError = false;
    
    // Validate NIP
    const nipValue = $('input[name="nip"]').val().trim();
    if (nipValue === '') {
      showFieldError('nip', 'NIP tidak boleh kosong');
      hasError = true;
    } else if (nipValue.length < 5) {
      showFieldError('nip', 'NIP minimal 5 karakter');
      hasError = true;
    }
    
    // Validate Password
    const passwordValue = $('input[name="password"]').val().trim();
    if (passwordValue === '') {
      showFieldError('password', 'Password tidak boleh kosong');
      hasError = true;
    } else if (passwordValue.length < 6) {
      showFieldError('password', 'Password minimal 6 karakter');
      hasError = true;
    }
    
    if (hasError) {
      e.preventDefault();
      return false;
    }
    
    // Show loading state
    const loginBtn = $('#loginBtn');
    loginBtn.addClass('btn-loading').prop('disabled', true);
    loginBtn.text('Processing...');
  });
  
  // Function to show field error
  function showFieldError(fieldName, message) {
    const field = $(`input[name="${fieldName}"]`);
    field.addClass('is-invalid');
    field.closest('.input-group').after(`<div class="invalid-feedback d-block"><strong>${message}</strong></div>`);
  }
  
  // Clear error on input
  $('input[name="nip"], input[name="password"]').on('input', function() {
    $(this).removeClass('is-invalid');
    $(this).closest('.input-group').next('.invalid-feedback').remove();
    
    // Reset login button if it was in loading state
    const loginBtn = $('#loginBtn');
    if (loginBtn.hasClass('btn-loading')) {
      loginBtn.removeClass('btn-loading').prop('disabled', false);
      loginBtn.text('MASUK');
    }
  });
  
  // Auto-hide alerts after 5 seconds
  setTimeout(function() {
    $('.alert').fadeOut('slow');
  }, 5000);
  
  // Add ripple effect to button
  $('.btn-primary').on('click', function(e) {
    if (!$(this).hasClass('btn-loading')) {
      const ripple = $('<span class="ripple"></span>');
      const rect = this.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      const x = e.clientX - rect.left - size / 2;
      const y = e.clientY - rect.top - size / 2;
      
      ripple.css({
        width: size + 'px',
        height: size + 'px',
        left: x + 'px',
        top: y + 'px'
      });
      
      $(this).append(ripple);
      
      setTimeout(() => {
        ripple.remove();
      }, 600);
    }
  });
  
  // Smooth form interactions
  $('.form-control').on('focus', function() {
    $(this).closest('.input-group').addClass('focused');
  });
  
  $('.form-control').on('blur', function() {
    $(this).closest('.input-group').removeClass('focused');
  });
  
  // Enhanced validation feedback
  $('.form-control').on('keyup', function() {
    if ($(this).hasClass('is-invalid') && $(this).val().length > 0) {
      $(this).removeClass('is-invalid');
      $(this).closest('.input-group').next('.invalid-feedback').fadeOut();
    }
  });
  
  // Enhanced keyboard navigation
  $('input').on('keypress', function(e) {
    if (e.which === 13) { // Enter key
      const inputs = $('input:visible:not([type="checkbox"])');
      const currentIndex = inputs.index(this);
      
      if (currentIndex < inputs.length - 1) {
        inputs.eq(currentIndex + 1).focus();
      } else {
        $('#loginForm').submit();
      }
    }
  });
  
  // Prevent multiple form submissions
  $('#loginForm').on('submit', function() {
    setTimeout(() => {
      $('#loginBtn').prop('disabled', true);
    }, 100);
  });
  
  // Form field animations
  $('.form-control').on('focus', function() {
    $(this).parent().addClass('input-focus');
  }).on('blur', function() {
    $(this).parent().removeClass('input-focus');
  });
  
  // Close alerts manually
  $('.alert .close').on('click', function() {
    $(this).closest('.alert').fadeOut();
  });
});

// Additional utility functions
function resetLoginForm() {
  $('#loginForm')[0].reset();
  $('.form-control').removeClass('is-invalid');
  $('.invalid-feedback').remove();
  $('#loginBtn').removeClass('btn-loading').prop('disabled', false);
  $('#loginBtn').text('MASUK');
  $('input[name="nip"]').focus();
}

// Handle browser back button
window.addEventListener('pageshow', function(event) {
  if (event.persisted) {
    resetLoginForm();
  }
});

// Prevent form resubmission on page refresh
if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}
</script>

</body>
</html>