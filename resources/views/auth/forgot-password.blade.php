<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lupa Password - Kasbon Online System</title>
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
      background: linear-gradient(135deg, #17a2b8, #138496);
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

    .forgot-title {
      color: white;
      font-size: clamp(1.4rem, 3vw, 1.8rem);
      font-weight: 700;
      margin: 0.5rem 0;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .forgot-subtitle {
      color: rgba(255, 255, 255, 0.9);
      font-size: clamp(0.8rem, 2vw, 0.9rem);
      margin: 0;
      text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }

    .card-body {
      padding: 1.8rem 1.5rem 1.2rem;
      background: rgba(255, 255, 255, 0.95);
    }

    .info-box {
      background: linear-gradient(135deg, #e3f2fd, #bbdefb);
      border-left: 4px solid #2196f3;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1.5rem;
      position: relative;
      overflow: hidden;
    }

    .info-box::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, transparent 49%, rgba(255,255,255,0.1) 50%, transparent 51%);
      animation: shimmer 2s infinite;
    }

    .info-box i {
      color: #2196f3;
      margin-right: 0.5rem;
    }

    .info-box-text {
      color: #1976d2;
      font-size: 0.85rem;
      margin: 0;
      position: relative;
      z-index: 1;
    }

    .input-group {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .form-control {
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      background-color: #fff;
      transition: all 0.3s ease;
      height: calc(2.75rem + 4px);
      font-size: clamp(0.9rem, 2vw, 1rem);
      padding-left: 3rem;
    }

    .form-control:focus {
      border-color: #17a2b8;
      box-shadow: 0 0 0 0.25rem rgba(23, 162, 184, 0.25);
      transform: translateY(-2px);
    }

    .input-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
      font-size: 1rem;
      z-index: 3;
      transition: all 0.3s ease;
    }

    .input-group:focus-within .input-icon {
      color: #17a2b8;
      transform: translateY(-50%) scale(1.1);
    }

    .btn-primary {
      background: linear-gradient(135deg, #17a2b8, #138496);
      border: none;
      border-radius: 8px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 0.9rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      font-size: clamp(0.9rem, 2vw, 1rem);
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #138496, #117a8b);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
    }

    .btn-primary:focus {
      box-shadow: 0 0 0 0.25rem rgba(23, 162, 184, 0.25);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .back-link {
      color: #17a2b8;
      text-decoration: none;
      font-size: clamp(0.8rem, 1.5vw, 0.9rem);
      transition: all 0.3s ease;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .back-link:hover {
      color: #138496;
      text-decoration: none;
      transform: translateX(-5px);
    }

    .back-link i {
      transition: transform 0.3s ease;
    }

    .back-link:hover i {
      transform: translateX(-3px);
    }

    /* Alert Styles */
    .alert {
      border-radius: 8px;
      border: none;
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
      position: relative;
      overflow: hidden;
    }

    .alert-success {
      background: linear-gradient(135deg, #28a745, #1e7e34);
      color: white;
    }

    .alert-danger {
      background: linear-gradient(135deg, #dc3545, #bd2130);
      color: white;
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
      font-size: 1.2rem;
      transition: all 0.3s ease;
    }

    .alert .close:hover {
      opacity: 1;
      transform: rotate(90deg);
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

    /* Loading Animation */
    .btn-loading {
      position: relative;
      color: transparent;
    }

    .btn-loading::after {
      content: '';
      position: absolute;
      width: 20px;
      height: 20px;
      top: 50%;
      left: 50%;
      margin-left: -10px;
      margin-top: -10px;
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

    /* Progress bar for form completion */
    .progress-bar {
      height: 3px;
      background: linear-gradient(90deg, #17a2b8, #138496);
      border-radius: 2px;
      transition: width 0.3s ease;
      margin-bottom: 1rem;
    }

    /* Floating labels effect */
    .floating-label {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .floating-label input {
      width: 100%;
      padding: 1rem 1rem 1rem 3rem;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: transparent;
    }

    .floating-label label {
      position: absolute;
      left: 3rem;
      top: 1rem;
      color: #6c757d;
      font-size: 1rem;
      transition: all 0.3s ease;
      pointer-events: none;
      background: white;
      padding: 0 0.5rem;
    }

    .floating-label input:focus + label,
    .floating-label input:not(:placeholder-shown) + label {
      top: -0.5rem;
      left: 2.5rem;
      font-size: 0.8rem;
      color: #17a2b8;
    }

    .floating-label input:focus {
      border-color: #17a2b8;
      box-shadow: 0 0 0 0.25rem rgba(23, 162, 184, 0.25);
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

    @keyframes shimmer {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }

    @keyframes alertShine {
      0% { left: -100%; }
      100% { left: 100%; }
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Form validation success */
    .is-valid {
      border-color: #28a745 !important;
    }

    .is-valid:focus {
      border-color: #28a745 !important;
      box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
    }

    .valid-feedback {
      color: #28a745;
      font-size: 0.8rem;
      font-weight: 500;
      margin-top: 0.5rem;
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
          <h3 class="forgot-title">Lupa Password</h3>
          <p class="forgot-subtitle">Masukkan NIP untuk reset password</p>
        </div>
      </div>
      
      <div class="card-body">
        <!-- Progress Bar -->
        <div class="progress-bar" id="progressBar" style="width: 0%"></div>

        <!-- Info Box -->
        <div class="info-box">
          <i class="fas fa-info-circle"></i>
          <p class="info-box-text">
            Masukkan NIP Anda dan kami akan mengirimkan link reset password ke email terdaftar.
          </p>
        </div>

        <!-- Error/Success Messages -->
        @if(session('status'))
          <div class="alert alert-success alert-dismissible" id="successAlert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h6><i class="icon fas fa-check"></i> Berhasil!</h6>
            {{ session('status') }}
          </div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger alert-dismissible" id="errorAlert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h6><i class="icon fas fa-ban"></i> Error!</h6>
            <ul class="mb-0 pl-3">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- Forgot Password Form -->
        <form action="{{ route('password.email') }}" method="POST" id="forgotPasswordForm" novalidate>
          @csrf
          
          <!-- NIP Field with Floating Label -->
          <div class="floating-label">
            <i class="input-icon fas fa-user"></i>
            <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
                   id="nipField" placeholder=" " value="{{ old('nip') }}" required autofocus 
                   autocomplete="username" maxlength="20">
            <label for="nipField">Nomor Induk Pegawai (NIP)</label>
          </div>
          @error('nip')
            <div class="invalid-feedback d-block mb-2">
              <strong><i class="fas fa-exclamation-triangle"></i> {{ $message }}</strong>
            </div>
          @enderror

          <!-- Submit Button -->
          <div class="row mb-3">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block" id="submitBtn">
                <i class="fas fa-paper-plane me-2"></i>
                KIRIM LINK RESET PASSWORD
              </button>
            </div>
          </div>

          <!-- Back to Login -->
          <div class="text-center">
            <a href="{{ route('login') }}" class="back-link">
              <i class="fas fa-arrow-left"></i>
              Kembali ke Login
            </a>
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

<!-- Custom JavaScript -->
<script>
$(document).ready(function() {
  // Focus on NIP field when page loads
  setTimeout(function() {
    $('#nipField').focus();
  }, 500);
  
  // Form submission handling
  $('#forgotPasswordForm').on('submit', function(e) {
    $('.form-control').removeClass('is-invalid is-valid');
    $('.invalid-feedback').remove();
    
    let hasError = false;
    
    const nipValue = $('#nipField').val().trim();
    if (nipValue === '') {
      showFieldError('nip', 'NIP tidak boleh kosong');
      hasError = true;
    } else {
      showFieldSuccess('nip', 'NIP valid');
      $('#progressBar').css('width', '100%');
    }
    
    if (hasError) {
      e.preventDefault();
      return false;
    }
    
    // Show loading state
    const submitBtn = $('#submitBtn');
    submitBtn.addClass('btn-loading').prop('disabled', true);
    submitBtn.html('Mengirim...');
  });
  
  function showFieldError(fieldName, message) {
    const field = $(`input[name="${fieldName}"]`);
    field.addClass('is-invalid');
    field.closest('.floating-label').after(`<div class="invalid-feedback d-block"><strong><i class="fas fa-exclamation-triangle"></i> ${message}</strong></div>`);
  }
  
  function showFieldSuccess(fieldName, message) {
    const field = $(`input[name="${fieldName}"]`);
    field.addClass('is-valid');
    field.closest('.floating-label').after(`<div class="valid-feedback d-block"><strong><i class="fas fa-check"></i> ${message}</strong></div>`);
  }
  
  // Real-time validation
  $('#nipField').on('input', function() {
    $(this).removeClass('is-invalid is-valid');
    $('.invalid-feedback, .valid-feedback').remove();
    
    const nipValue = $(this).val().trim();
    let progress = 0;
    
    if (nipValue !== '') {
      if (nipValue.length >= 8 && nipValue.length <= 20 && /^[0-9]+$/.test(nipValue)) {
        showFieldSuccess('nip', 'NIP valid');
        progress = 100;
      } else if (nipValue.length > 0) {
        progress = 50;
      }
    }
    
    $('#progressBar').css('width', progress + '%');
  });
  
  
  // Clear error on input
  $('#nipField').on('input', function() {
    const submitBtn = $('#submitBtn');
    if (submitBtn.hasClass('btn-loading')) {
      submitBtn.removeClass('btn-loading').prop('disabled', false);
      submitBtn.html('<i class="fas fa-paper-plane me-2"></i>KIRIM LINK RESET PASSWORD');
    }
  });
  
  // Auto-hide alerts
  setTimeout(function() {
    $('.alert').fadeOut('slow');
  }, 8000);
  
  // Enhanced alert close functionality
  $(document).on('click', '.alert .close', function() {
    $(this).closest('.alert').fadeOut();
  });
});
</script>

</body>
</html>