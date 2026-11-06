<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in - PHP Framecore</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --fc-accent: #54e5c7;
      --fc-accent-hover: #3dd4b4;
      --fc-accent-soft: rgba(84, 229, 199, 0.14);
      --fc-accent-ring: rgba(84, 229, 199, 0.35);
      --fc-dark: #2b2e33;
      --fc-dark-soft: #3a3e45;
      --fc-text: #2b2e33;
      --fc-muted: #6b7280;
      --fc-border: #e5e7eb;
      --fc-bg: #f3f4f6;
      --fc-surface: #ffffff;
      --bs-primary: var(--fc-accent);
      --bs-primary-rgb: 84, 229, 199;
      --bs-body-font-family: "Inter", system-ui, -apple-system, sans-serif;
      --bs-body-color: var(--fc-text);
      --bs-border-color: var(--fc-border);
    }

    body {
      min-height: 100vh;
      margin: 0;
      background-color: var(--fc-bg);
      background-image:
        radial-gradient(ellipse 70% 45% at 15% 10%, rgba(84, 229, 199, 0.1), transparent),
        linear-gradient(180deg, #f3f4f6 0%, #e9ecef 100%);
    }

    .auth-shell {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    .auth-wrapper {
      width: 100%;
      max-width: 980px;
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 420px);
      gap: 1.25rem;
      align-items: stretch;
    }

    .auth-panel {
      background: var(--fc-surface);
      border: 1px solid rgba(229, 231, 235, 0.95);
      border-radius: 1.25rem;
      box-shadow:
        0 1px 2px rgba(43, 46, 51, 0.04),
        0 12px 36px -14px rgba(43, 46, 51, 0.14);
    }

    .auth-brand {
      background: linear-gradient(160deg, var(--fc-dark) 0%, #23262b 55%, #1d2024 100%);
      color: #fff;
      padding: 2.25rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 100%;
      position: relative;
      overflow: hidden;
    }

    .auth-brand::after {
      content: "";
      position: absolute;
      inset: auto -20% -35% auto;
      width: 320px;
      height: 320px;
      background: radial-gradient(circle, rgba(84, 229, 199, 0.18), transparent 65%);
      pointer-events: none;
    }

    .brand-top,
    .brand-bottom {
      position: relative;
      z-index: 1;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 2.5rem;
    }

    .brand-mark {
      width: 2.75rem;
      height: 2.75rem;
      border-radius: 0.85rem;
      background: rgba(84, 229, 199, 0.14);
      color: var(--fc-accent);
      border: 1px solid rgba(84, 229, 199, 0.22);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      flex-shrink: 0;
    }

    .brand-name {
      font-size: 1rem;
      font-weight: 600;
      color: #fff;
      letter-spacing: -0.01em;
      line-height: 1.2;
    }

    .brand-tag {
      font-size: 0.8125rem;
      color: rgba(255, 255, 255, 0.62);
    }

    .brand-headline {
      font-size: clamp(1.6rem, 2.2vw, 2rem);
      font-weight: 700;
      letter-spacing: -0.03em;
      line-height: 1.15;
      margin: 0 0 0.75rem;
      color: #fff;
    }

    .brand-copy {
      margin: 0;
      color: rgba(255, 255, 255, 0.72);
      font-size: 0.9375rem;
      max-width: 34ch;
    }

    .brand-points {
      display: grid;
      gap: 0.75rem;
      margin-top: 2rem;
    }

    .brand-point {
      display: flex;
      align-items: flex-start;
      gap: 0.75rem;
      font-size: 0.875rem;
      color: rgba(255, 255, 255, 0.84);
    }

    .brand-point i {
      color: var(--fc-accent);
      margin-top: 0.1rem;
      flex-shrink: 0;
    }

    .auth-form {
      padding: 2rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    @media (min-width: 576px) {
      .auth-form {
        padding: 2.25rem 2.5rem;
      }
    }

    .auth-header {
      margin-bottom: 1.5rem;
    }

    .auth-header h1 {
      font-size: 1.625rem;
      font-weight: 700;
      letter-spacing: -0.025em;
      margin: 0 0 0.375rem;
      color: var(--fc-dark);
    }

    .auth-header p {
      margin: 0;
      font-size: 0.9375rem;
      color: var(--fc-muted);
    }

    .form-label {
      font-size: 0.8125rem;
      font-weight: 500;
      color: var(--fc-text);
      margin-bottom: 0.375rem;
    }

    .form-control {
      height: 2.75rem;
      padding: 0.625rem 0.875rem;
      font-size: 0.9375rem;
      border-radius: 0.75rem;
      border-color: var(--fc-border);
      background-color: #fff;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-control::placeholder {
      color: #94a3b8;
    }

    .form-control:focus {
      border-color: var(--fc-accent);
      box-shadow: 0 0 0 4px var(--fc-accent-ring);
    }

    .input-icon-wrap {
      position: relative;
    }

    .input-icon-wrap .form-control {
      padding-left: 2.625rem;
    }

    .input-icon-wrap .form-control.has-toggle {
      padding-right: 2.75rem;
    }

    .input-icon {
      position: absolute;
      left: 0.875rem;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 1rem;
      pointer-events: none;
      transition: color 0.15s ease;
    }

    .input-icon-wrap:focus-within .input-icon {
      color: var(--fc-dark);
    }

    .btn-toggle-password {
      position: absolute;
      right: 0.375rem;
      top: 50%;
      transform: translateY(-50%);
      border: 0;
      background: transparent;
      color: #94a3b8;
      width: 2rem;
      height: 2rem;
      border-radius: 0.5rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0;
    }

    .btn-toggle-password:hover {
      color: var(--fc-muted);
      background: #f1f5f9;
    }

    .form-check-input {
      width: 1rem;
      height: 1rem;
      margin-top: 0.125rem;
      border-color: #cbd5e1;
      border-radius: 0.3rem;
    }

    .form-check-input:checked {
      background-color: var(--fc-accent);
      border-color: var(--fc-accent);
    }

    .form-check-input:focus {
      border-color: var(--fc-accent);
      box-shadow: 0 0 0 4px var(--fc-accent-ring);
    }

    .form-check-label {
      font-size: 0.875rem;
      color: var(--fc-muted);
    }

    .link-subtle {
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--fc-dark);
      text-decoration: none;
    }

    .link-subtle:hover {
      color: #1a1c20;
      text-decoration: underline;
      text-underline-offset: 2px;
    }

    .btn-primary-fc {
      height: 2.75rem;
      border: 0;
      border-radius: 0.75rem;
      background: var(--fc-dark);
      color: #fff;
      font-size: 0.9375rem;
      font-weight: 600;
      transition: background-color 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease;
    }

    .btn-primary-fc:hover {
      background: var(--fc-dark-soft);
      color: #fff;
    }

    .btn-primary-fc:focus {
      box-shadow: 0 0 0 4px rgba(43, 46, 51, 0.2);
    }

    .btn-primary-fc:active {
      transform: translateY(1px);
    }

    .alert-fc {
      border: 0;
      border-radius: 0.75rem;
      padding: 0.75rem 0.875rem;
      font-size: 0.875rem;
      margin-bottom: 1.25rem;
    }

    .alert-fc-danger {
      background: #fef2f2;
      color: #991b1b;
    }

    .alert-fc-success {
      background: rgba(84, 229, 199, 0.12);
      color: #1f6f5e;
      border: 1px solid rgba(84, 229, 199, 0.35);
    }

    .alert-fc i {
      opacity: 0.9;
    }

    .auth-footer {
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 1px solid var(--fc-border);
      text-align: center;
      font-size: 0.875rem;
      color: var(--fc-muted);
    }

    @media (max-width: 991.98px) {
      .auth-wrapper {
        grid-template-columns: 1fr;
        max-width: 460px;
      }

      .auth-brand {
        padding: 1.75rem;
      }

      .brand {
        margin-bottom: 1.25rem;
      }

      .brand-points {
        margin-top: 1.25rem;
      }
    }
  </style>
</head>

<body>
  <div class="auth-shell">
    <div class="auth-wrapper">
      <section class="auth-panel auth-brand" aria-label="PHP Framecore">
        <div class="brand-top">
          <div class="brand">
            <div class="brand-mark" aria-hidden="true"><i class="bi bi-layers-half"></i></div>
            <div>
              <div class="brand-name">PHP Framecore</div>
              <div class="brand-tag">MVC starter template</div>
            </div>
          </div>

          <h2 class="brand-headline">Secure access for your application workspace.</h2>
          <p class="brand-copy">A clean Bootstrap 5 foundation for authentication, dashboards, and role-based modules.</p>
        </div>

        <div class="brand-bottom brand-points">
          <div class="brand-point">
            <i class="bi bi-shield-check" aria-hidden="true"></i>
            <span>Session authentication with role-aware routing.</span>
          </div>
          <div class="brand-point">
            <i class="bi bi-diagram-3" aria-hidden="true"></i>
            <span>Controllers, views, and PDO-backed data access.</span>
          </div>
          <div class="brand-point">
            <i class="bi bi-layout-text-window-reverse" aria-hidden="true"></i>
            <span>Modern, responsive UI ready for extension.</span>
          </div>
        </div>
      </section>

      <main class="auth-panel auth-form" aria-labelledby="auth-title">
        <header class="auth-header">
          <h1 id="auth-title">Welcome back</h1>
          <p>Sign in to continue to your dashboard.</p>
        </header>

        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert-fc alert-fc-danger d-flex align-items-start gap-2" role="alert">
            <i class="bi bi-exclamation-circle mt-1" aria-hidden="true"></i>
            <div><?php echo htmlspecialchars($_SESSION['error']);
                  unset($_SESSION['error']); ?></div>
          </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert-fc alert-fc-success d-flex align-items-start gap-2" role="alert">
            <i class="bi bi-check-circle mt-1" aria-hidden="true"></i>
            <div><?php echo htmlspecialchars($_SESSION['success']);
                  unset($_SESSION['success']); ?></div>
          </div>
        <?php endif; ?>

        <form action="index.php?action=login" method="POST" novalidate>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <div class="input-icon-wrap">
              <i class="bi bi-envelope input-icon" aria-hidden="true"></i>
              <input type="email" class="form-control" id="email" name="email" autocomplete="username"
                placeholder="name@example.com" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-icon-wrap">
              <i class="bi bi-lock input-icon" aria-hidden="true"></i>
              <input type="password" class="form-control has-toggle" id="password" name="password"
                autocomplete="current-password" placeholder="Enter your password" required>
              <button class="btn-toggle-password" type="button" id="togglePassword" aria-label="Show password">
                <i class="bi bi-eye" aria-hidden="true"></i>
              </button>
            </div>
          </div>

          <div class="d-flex align-items-center justify-content-between gap-2 mb-4">
            <div class="form-check mb-0">
              <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
              <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <a class="link-subtle" href="index.php?action=forgot-password">Forgot password?</a>
          </div>

          <button type="submit" class="btn btn-primary-fc w-100">Sign in</button>
        </form>

        <div class="auth-footer">
          Don&rsquo;t have an account?
          <a class="link-subtle" href="index.php?action=register">Create one</a>
        </div>
      </main>
    </div>
  </div>

  <script>
    (function () {
      var toggle = document.getElementById('togglePassword');
      var password = document.getElementById('password');
      var email = document.getElementById('email');

      if (toggle && password) {
        toggle.addEventListener('click', function () {
          var show = password.type === 'password';
          password.type = show ? 'text' : 'password';
          toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
          var icon = toggle.querySelector('i');
          if (icon) icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
      }

      if (email) email.focus();
    }());
  </script>
</body>

</html>
