<?php
// app/Views/auth/reset-options.php
$first_name = $_SESSION['reset_first_name'] ?? '';
$last_name = $_SESSION['reset_last_name'] ?? '';
$email = $_SESSION['reset_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Choose reset option - PHP Framecore</title>
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
      margin-bottom: 2rem;
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
      margin-bottom: 1rem;
      flex-shrink: 0;
    }

    .auth-header h1 {
      font-size: 1.5rem;
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

    .input-icon {
      position: absolute;
      left: 0.875rem;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 1rem;
      pointer-events: none;
      transition: color 0.15s ease;
      z-index: 2;
    }

    .input-icon-wrap:focus-within .input-icon {
      color: var(--fc-dark);
    }

    .btn-primary-fc {
      height: 2.75rem;
      border: 0;
      border-radius: 0.75rem;
      background: var(--fc-dark);
      color: #fff;
      font-size: 0.9375rem;
      font-weight: 600;
      padding: 0 1.1rem;
      transition: background-color 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-primary-fc:hover {
      background: var(--fc-dark-soft);
      color: #fff;
    }

    .btn-primary-fc:focus {
      box-shadow: 0 0 0 4px rgba(43, 46, 51, 0.2);
      color: #fff;
    }

    .btn-outline-fc {
      height: 2.75rem;
      border: 1px solid var(--fc-border);
      border-radius: 0.75rem;
      background: #fff;
      color: var(--fc-text);
      font-size: 0.9375rem;
      font-weight: 500;
      padding: 0 1.1rem;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-outline-fc:hover {
      border-color: #cfd6e0;
      background: #f8fafc;
      color: var(--fc-text);
    }

    .actions {
      display: flex;
      gap: 0.75rem;
      margin-top: auto;
      padding-top: 1rem;
      border-top: 1px solid var(--fc-border);
      flex-shrink: 0;
    }

    .actions > * {
      flex: 1;
    }

    .alert-fc {
      border: 0;
      border-radius: 0.75rem;
      padding: 0.75rem 0.875rem;
      font-size: 0.875rem;
      margin-bottom: 1rem;
      flex-shrink: 0;
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

    .option-card {
      border: 1px solid var(--fc-border);
      border-radius: 0.9rem;
      padding: 1rem 1.1rem;
      margin-bottom: 0.75rem;
      background: #fcfcfd;
      cursor: pointer;
      transition: border-color 0.15s ease, background 0.15s ease;
      display: block;
      text-decoration: none;
      color: inherit;
    }

    .option-card:hover {
      border-color: var(--fc-accent);
      background: rgba(84, 229, 199, 0.05);
    }

    .option-card input[type="radio"] {
      display: none;
    }

    .option-header {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 0.5rem;
    }

    .option-icon {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 0.7rem;
      background: var(--fc-dark);
      color: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      flex-shrink: 0;
    }

    .option-title {
      font-size: 1rem;
      font-weight: 600;
      color: var(--fc-dark);
      margin: 0;
    }

    .option-desc {
      margin: 0;
      font-size: 0.875rem;
      color: var(--fc-muted);
      line-height: 1.4;
    }

    .option-footer {
      margin-top: 0.75rem;
      padding-top: 0.75rem;
      border-top: 1px solid var(--fc-border);
      font-size: 0.75rem;
      color: var(--fc-muted);
    }

    .option-footer li {
      list-style: none;
      margin-bottom: 0.2rem;
      color: var(--fc-muted);
    }

    .option-footer li::before {
      content: "\2022";
      color: var(--fc-accent);
      font-weight: bold;
      display: inline-block;
      width: 0.75rem;
      margin-left: -0.75rem;
    }

    .auth-footer {
      margin-top: 1rem;
      padding-top: 0.85rem;
      border-top: 1px solid var(--fc-border);
      font-size: 0.8125rem;
      color: var(--fc-muted);
      text-align: center;
      flex-shrink: 0;
    }

    .link-subtle {
      color: var(--fc-dark);
      font-weight: 500;
      text-decoration: none;
    }

    .link-subtle:hover {
      text-decoration: underline;
      text-underline-offset: 2px;
      color: #1a1c20;
    }

    @media (max-width: 991.98px) {
      .auth-wrapper {
        grid-template-columns: 1fr;
        max-width: 620px;
      }

      .auth-brand {
        padding: 1.75rem;
      }
    }
  </style>
</head>

<body>
  <div class="auth-shell">
    <div class="auth-wrapper">
      <section class="auth-panel auth-brand" aria-label="Account recovery">
        <div class="brand-top">
          <div class="brand">
            <div class="brand-mark" aria-hidden="true"><i class="bi bi-key"></i></div>
            <div>
              <div class="brand-name">PHP Framecore</div>
              <div class="brand-tag">Account recovery</div>
            </div>
          </div>

          <h2 class="brand-headline">Choose how to recover your account.</h2>
          <p class="brand-copy">Select what you would like to reset. A verification step will follow.</p>

          <div class="brand-points">
            <div class="brand-point">
              <i class="bi bi-check2"></i>
              <span>Your identity has been verified.</span>
            </div>
            <div class="brand-point">
              <i class="bi bi-check2"></i>
              <span>Choose password or email reset.</span>
            </div>
          </div>
        </div>

        <div class="brand-bottom">
          <div class="auth-footer">
            <a class="link-subtle" href="index.php?action=forgot-password">Start over</a>
          </div>
        </div>
      </section>

      <main class="auth-panel auth-form" aria-labelledby="reset-title">
        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert-fc alert-fc-danger d-flex align-items-start gap-2" role="alert">
            <i class="bi bi-exclamation-circle mt-1" aria-hidden="true"></i>
            <div><?php echo $_SESSION['error'];
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

        <header class="auth-header">
          <h1 id="reset-title">Reset option</h1>
          <p>Identity verified for <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?> (<?php echo htmlspecialchars($email); ?>).</p>
        </header>

        <form method="POST" action="index.php?action=reset-options" class="options-form" novalidate>
          <label class="option-card">
            <input type="radio" name="reset_option" value="password" required>
            <div class="option-header">
              <div class="option-icon"><i class="bi bi-lock"></i></div>
              <h3 class="option-title">Reset password</h3>
            </div>
            <p class="option-desc">Create a new strong password for your account.</p>
            <ul class="option-footer">
              <li>Meets security requirements (8+ characters)</li>
              <li>Confirmed with a second entry</li>
            </ul>
          </label>

          <label class="option-card">
            <input type="radio" name="reset_option" value="email" required>
            <div class="option-header">
              <div class="option-icon"><i class="bi bi-envelope"></i></div>
              <h3 class="option-title">Reset email</h3>
            </div>
            <p class="option-desc">Update your account email address.</p>
            <ul class="option-footer">
              <li>Enter and confirm new email</li>
              <li>Login with new email after update</li>
            </ul>
          </label>

          <div class="actions">
            <a href="index.php?action=forgot-password" class="btn-outline-fc">Start over</a>
            <button type="submit" class="btn-primary-fc">Continue</button>
          </div>
        </form>
      </main>
    </div>
  </div>
</body>

</html>
