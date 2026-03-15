<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
  :root {
    --bs-primary: #149004cb;
    --bs-primary-dark: #149004cb;
    --bs-secondary: #ce1126;
    --bs-warning: #fcd116;
    --bs-primary-rgb: 0, 56, 147;
    --bs-secondary-rgb: 206, 17, 38;
    --bs-warning-rgb: 252, 209, 22;
  }

  body {
    background: url('assets/bfcloansbackground.png') center center/cover no-repeat;
    font-family: 'Segoe UI', system-ui, sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
    padding: 1rem;
    font-size: 0.875rem;
  }

  /* Card Styles */
  .card-login {
    max-width: 800px;
    width: 100%;
    border: none;
    border-radius: 0.75rem;
    box-shadow: 0 0.25rem 0.75rem rgba(0, 56, 147, 0.08);
    overflow: hidden;
  }

  /* Left Column */
  .bg-primary-patriotic {
    background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
    color: white;
    padding: 1.5rem;
  }

  .logo-circle {
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    border: 3px solid var(--bs-warning);
    overflow: hidden;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
  }

  .logo-circle img {
    width: 85%;
    height: auto;
    object-fit: contain;
  }


  .feature-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0.75rem;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 0.5rem;
    margin-bottom: 0.75rem;
    font-size: 0.75rem;
  }

  .feature-icon {
    color: var(--bs-warning);
    font-size: 0.875rem;
  }

  /* Right Column */
  .p-4-custom {
    padding: 1.5rem;
  }

  /* Form Controls */
  .form-label {
    font-weight: 600;
    color: var(--bs-primary-dark);
    font-size: 0.8125rem;
    margin-bottom: 0.25rem;
  }

  .input-group-custom {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    overflow: hidden;
  }

  .input-group-custom:focus-within {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.2rem rgba(0, 56, 147, 0.1);
  }

  .input-group-text-custom {
    background: white;
    border: none;
    color: var(--bs-primary);
    min-width: 40px;
    justify-content: center;
    padding: 0.5rem;
  }

  .form-control-custom {
    border: none;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    height: 38px;
  }

  .form-control-custom:focus {
    box-shadow: none;
    outline: none;
  }

  /* Buttons */
  .btn-primary-patriotic {
    background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
  }

  .btn-primary-patriotic:hover {
    background: linear-gradient(135deg, var(--bs-primary-dark) 0%, var(--bs-primary) 100%);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 0.25rem 0.5rem rgba(0, 56, 147, 0.2);
  }

  .btn-primary-patriotic:active {
    transform: translateY(0);
  }

  .btn-outline-patriotic {
    border: 1px solid var(--bs-primary);
    color: var(--bs-primary);
    background: white;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
  }

  .btn-outline-patriotic:hover {
    background: var(--bs-primary);
    color: white;
  }

  /* Checkbox */
  .form-check-input-custom:checked {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
  }

  /* Links */
  .link-patriotic {
    color: var(--bs-primary);
    text-decoration: none;
    font-size: 0.75rem;
  }

  .link-patriotic:hover {
    color: var(--bs-primary-dark);
    text-decoration: underline;
  }

  .link-muted {
    color: #6c757d;
    text-decoration: none;
    font-size: 0.75rem;
  }

  .link-muted:hover {
    color: var(--bs-primary);
  }

  /* Alerts */
  .alert-patriotic-error {
    background: rgba(206, 17, 38, 0.05);
    color: var(--bs-secondary);
    border-left: 3px solid var(--bs-secondary);
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.8125rem;
    margin-bottom: 1rem;
  }

  .alert-patriotic-success {
    background: rgba(0, 56, 147, 0.05);
    color: var(--bs-primary);
    border-left: 3px solid var(--bs-primary);
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.8125rem;
    margin-bottom: 1rem;
  }

  /* Divider */
  .divider-custom {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 1rem 0;
    color: #6c757d;
    font-size: 0.75rem;
  }

  .divider-custom::before,
  .divider-custom::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid #dee2e6;
  }

  .divider-custom::before {
    margin-right: 0.5rem;
  }

  .divider-custom::after {
    margin-left: 0.5rem;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .card-login {
      max-width: 450px;
    }
  }
</style>

<div class="container-fluid p-0 d-flex justify-content-center align-items-center min-vh-100">
  <div class="row g-0 card-login w-100">
    <!-- Left Container -->
    <div class="col-md-6 bg-primary-patriotic d-flex flex-column justify-content-center p-4">
      <div class="text-center">
        <div class="logo-circle">
          <img src="assets/bfcloans.png" alt="BUENAMENTE Lending Firm Logo" class="img-fluid">
        </div>

        <h5 class="text-white mb-2 fw-semibold">BUENAMENTE Lending Firm</h5>
        <p class="text-white small mb-4">Professional Loan Management System</p>

        <div class="mt-3">
          <div class="feature-item">
            <i class="bi bi-shield-check feature-icon"></i>
            <span class="text-white">Secure & Reliable Platform</span>
          </div>
          <div class="feature-item">
            <i class="bi bi-speedometer2 feature-icon"></i>
            <span class="text-white">Efficient Loan Management</span>
          </div>
          <div class="feature-item">
            <i class="bi bi-people-fill feature-icon"></i>
            <span class="text-white">Client-Focused Services</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Container -->
    <div class="col-md-6 bg-white d-flex flex-column justify-content-center p-4">
      <div class="text-center mb-3">
        <h5 class="text-primary-dark fw-semibold mb-1">Welcome Back</h5>
        <p class="text-muted small">Sign in to your dashboard</p>
      </div>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-patriotic-error d-flex align-items-center gap-2">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <span><?php echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); ?></span>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-patriotic-success d-flex align-items-center gap-2">
          <i class="bi bi-check-circle-fill"></i>
          <span><?php echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']); ?></span>
        </div>
      <?php endif; ?>

      <form action="index.php?action=login" method="POST">
        <!-- Email Field -->
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <div class="input-group-custom d-flex align-items-stretch">
            <span class="input-group-text-custom d-flex align-items-center">
              <i class="bi bi-envelope-fill"></i>
            </span>
            <input type="email" class="form-control-custom flex-grow-1"
              name="email" autocomplete="username"
              placeholder="example@gmail.com" required>
          </div>
        </div>

        <!-- Password Field -->
        <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group-custom d-flex align-items-stretch">
            <span class="input-group-text-custom d-flex align-items-center">
              <i class="bi bi-lock-fill"></i>
            </span>
            <input type="password" class="form-control-custom flex-grow-1"
              id="password" name="password"
              autocomplete="current-password" required>
            <button class="btn input-group-text-custom border-start-0"
              type="button" onclick="togglePassword()">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-3">
          <input class="form-check-input form-check-input-custom"
            type="checkbox" id="remember" name="remember">
          <label class="form-check-label small" for="remember">
            Remember me
          </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary-patriotic w-100 mb-3">
          <i class="bi bi-box-arrow-in-right me-1"></i>Login
        </button>

        <!-- Links -->
        <div class="text-center">
          <div class="mb-2">
            <a href="index.php?action=register" class="link-patriotic">
              <i class="bi bi-person-plus me-1"></i>Create Account
            </a>
          </div>

          <div class="divider-custom">or</div>

          <div class="mt-2">
            <a href="index.php?action=forgot-password" class="link-muted">
              <i class="bi bi-key me-1"></i>Forgot Password?
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function togglePassword() {
    const password = document.getElementById('password');
    const button = event.currentTarget;
    const icon = button.querySelector('i');

    if (password.type === 'password') {
      password.type = 'text';
      icon.className = 'bi bi-eye-slash';
    } else {
      password.type = 'password';
      icon.className = 'bi bi-eye';
    }
  }

  // Auto-focus on email field
  document.addEventListener('DOMContentLoaded', function() {
    const emailField = document.querySelector('[name="email"]');
    if (emailField) {
      emailField.focus();
    }
  });
</script>
