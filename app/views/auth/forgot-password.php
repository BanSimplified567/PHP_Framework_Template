<?php
// app/Views/auth/forgot-password.php
$step = isset($_SESSION['reset_step']) ? (int) $_SESSION['reset_step'] : 1;
$codeSent = !empty($_SESSION['reset_code_sent']);
$verified = !empty($_SESSION['questions_verified']);
$resetEmail = (string) ($_SESSION['reset_email'] ?? ($_POST['email'] ?? ''));
$first_name = $_SESSION['reset_first_name'] ?? '';
$last_name = $_SESSION['reset_last_name'] ?? '';
$progress = $verified ? '100%' : ($codeSent || $step >= 2 ? '66%' : '33%');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account recovery - PHP Framecore</title>
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
      max-width: 1040px;
      display: grid;
      grid-template-columns: minmax(0, 360px) minmax(0, 1fr);
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
      width: 300px;
      height: 300px;
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
      margin-bottom: 1.75rem;
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
      font-size: 1.5rem;
      font-weight: 700;
      letter-spacing: -0.03em;
      line-height: 1.2;
      margin: 0 0 0.5rem;
      color: #fff;
    }

    .brand-copy {
      margin: 0;
      color: rgba(255, 255, 255, 0.72);
      font-size: 0.9rem;
    }

    .recovery-steps {
      display: grid;
      gap: 0.625rem;
      margin-top: 1.75rem;
    }

    .recovery-step {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.65rem 0.75rem;
      border-radius: 0.75rem;
      border: 1px solid rgba(255, 255, 255, 0.08);
      background: rgba(255, 255, 255, 0.03);
      color: rgba(255, 255, 255, 0.65);
      font-size: 0.875rem;
    }

    .recovery-step.is-active {
      border-color: rgba(84, 229, 199, 0.3);
      background: rgba(84, 229, 199, 0.1);
      color: #fff;
    }

    .recovery-step.is-done {
      color: rgba(255, 255, 255, 0.85);
    }

    .step-dot {
      width: 1.75rem;
      height: 1.75rem;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      font-weight: 600;
      background: rgba(255, 255, 255, 0.1);
      color: rgba(255, 255, 255, 0.75);
      flex-shrink: 0;
    }

    .recovery-step.is-active .step-dot {
      background: var(--fc-accent);
      color: var(--fc-dark);
    }

    .recovery-step.is-done .step-dot {
      background: rgba(84, 229, 199, 0.2);
      color: var(--fc-accent);
    }

    .auth-form {
      padding: 2rem;
      display: flex;
      flex-direction: column;
      max-height: calc(100vh - 4rem);
      overflow-y: auto;
    }

    @media (min-width: 576px) {
      .auth-form {
        padding: 2.25rem 2.5rem;
      }
    }

    .auth-header {
      margin-bottom: 1.25rem;
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

    .progress-track {
      height: 6px;
      background: #eef2f7;
      border-radius: 999px;
      overflow: hidden;
      margin-bottom: 1.25rem;
    }

    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--fc-dark) 0%, var(--fc-accent) 100%);
      border-radius: 999px;
      transition: width 0.2s ease;
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

    .question-card {
      border: 1px solid var(--fc-border);
      border-radius: 0.9rem;
      padding: 0.9rem 1rem;
      margin-bottom: 0.75rem;
      background: #fcfcfd;
    }

    .question-meta {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 0.5rem;
      font-size: 0.75rem;
      font-weight: 600;
      color: var(--fc-muted);
      text-transform: uppercase;
      letter-spacing: 0.03em;
    }

    .question-badge {
      width: 1.35rem;
      height: 1.35rem;
      border-radius: 999px;
      background: var(--fc-dark);
      color: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 0.7rem;
    }

    .question-text {
      font-size: 0.9375rem;
      color: var(--fc-text);
      margin: 0 0 0.65rem;
      line-height: 1.4;
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
    }

    .btn-primary-fc:hover {
      background: var(--fc-dark-soft);
      color: #fff;
    }

    .btn-primary-fc:focus {
      box-shadow: 0 0 0 4px rgba(43, 46, 51, 0.2);
      color: #fff;
    }

    .btn-primary-fc:disabled {
      opacity: 0.55;
      cursor: not-allowed;
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
      margin-top: 1rem;
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

    .requirements {
      border: 1px solid var(--fc-border);
      border-radius: 0.85rem;
      padding: 0.85rem 1rem;
      margin-top: 0.75rem;
      background: #fafbfc;
    }

    .requirements-title {
      font-size: 0.8125rem;
      font-weight: 600;
      color: var(--fc-text);
      margin-bottom: 0.5rem;
    }

    .requirements-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.35rem 0.75rem;
    }

    .requirement {
      display: flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.8125rem;
      color: var(--fc-muted);
    }

    .requirement.valid {
      color: #0f766e;
    }

    .requirement i {
      font-size: 0.75rem;
      color: #cbd5e1;
    }

    .requirement.valid i {
      color: var(--fc-accent-hover);
    }

    .match-status {
      margin-top: 0.6rem;
      font-size: 0.8125rem;
      border-radius: 0.65rem;
      padding: 0.55rem 0.75rem;
      display: none;
      align-items: center;
      gap: 0.4rem;
    }

    .match-status.is-error {
      display: flex;
      background: #fef2f2;
      color: #991b1b;
    }

    .match-status.is-valid {
      display: flex;
      background: rgba(84, 229, 199, 0.12);
      color: #1f6f5e;
    }

    .auth-footer {
      margin-top: 1.25rem;
      padding-top: 1rem;
      border-top: 1px solid var(--fc-border);
      font-size: 0.8125rem;
      color: var(--fc-muted);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    .auth-footer a {
      color: var(--fc-dark);
      font-weight: 500;
      text-decoration: none;
    }

    .auth-footer a:hover {
      text-decoration: underline;
      text-underline-offset: 2px;
    }

    @media (max-width: 991.98px) {
      .auth-wrapper {
        grid-template-columns: 1fr;
        max-width: 560px;
      }

      .auth-brand {
        padding: 1.75rem;
      }

      .auth-form {
        max-height: none;
      }

      .requirements-grid {
        grid-template-columns: 1fr;
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
            <div class="brand-mark" aria-hidden="true"><i class="bi bi-shield-lock"></i></div>
            <div>
              <div class="brand-name">PHP Framecore</div>
              <div class="brand-tag">Account recovery</div>
            </div>
          </div>

          <h2 class="brand-headline">Verify your identity and regain access.</h2>
          <p class="brand-copy">Recovery now uses an email verification code sent via PHPMailer (Gmail). All attempts are logged for account protection.</p>
        </div>

        <div class="brand-bottom">
          <div class="recovery-steps" aria-label="Recovery progress">
            <div class="recovery-step <?php echo (!$codeSent && !$verified) ? 'is-active' : (($codeSent || $verified) ? 'is-done' : ''); ?>">
              <span class="step-dot"><?php echo ($codeSent || $verified) ? '<i class="bi bi-check"></i>' : '1'; ?></span>
              <span>Enter email</span>
            </div>
            <div class="recovery-step <?php echo ($codeSent && !$verified) ? 'is-active' : ($verified ? 'is-done' : ''); ?>">
              <span class="step-dot"><?php echo $verified ? '<i class="bi bi-check"></i>' : '2'; ?></span>
              <span>Verify code</span>
            </div>
            <div class="recovery-step <?php echo $verified ? 'is-active' : ''; ?>">
              <span class="step-dot">3</span>
              <span>Reset account</span>
            </div>
          </div>
        </div>
      </section>

      <main class="auth-panel auth-form" aria-labelledby="auth-title">
        <div class="progress-track" aria-hidden="true">
          <div class="progress-fill" style="width: <?php echo htmlspecialchars($progress); ?>;"></div>
        </div>

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

        <?php if ($step == 1 || !$codeSent): ?>
          <header class="auth-header">
            <h1 id="auth-title">Forgot password</h1>
            <p>Enter your account email and we’ll send a verification code using Gmail/PHPMailer.</p>
          </header>

          <form method="POST" action="index.php?action=forgot-password" id="emailCodeRequestForm" novalidate>
            <input type="hidden" name="intent" value="request_code">
            <div class="question-card" style="margin-bottom:0;">
              <div class="question-meta">
                <span class="question-badge"><i class="bi bi-envelope"></i></span>
                <span>Email address</span>
              </div>
              <div class="input-icon-wrap">
                <i class="bi bi-envelope input-icon" aria-hidden="true"></i>
                <input type="email" class="form-control" id="reset_email" name="email"
                  value="<?php echo htmlspecialchars($resetEmail, ENT_QUOTES, 'UTF-8'); ?>"
                  placeholder="name@example.com" autocomplete="email" required autofocus>
              </div>
            </div>

            <div class="actions">
              <a href="index.php?action=login" class="btn-outline-fc">Back to sign in</a>
              <button type="submit" class="btn-primary-fc">Send verification code</button>
            </div>
          </form>

        <?php elseif ($step == 2 && $codeSent): ?>
          <header class="auth-header">
            <h1 id="auth-title">Enter verification code</h1>
            <p>We sent a 6-digit code to <strong><?php echo htmlspecialchars($resetEmail, ENT_QUOTES, 'UTF-8'); ?></strong>. The code expires in 15 minutes.</p>
          </header>

          <form method="POST" action="index.php?action=forgot-password" id="codeVerifyForm" novalidate>
            <input type="hidden" name="intent" value="verify_code">
            <div class="question-card" style="margin-bottom:0;">
              <div class="question-meta">
                <span class="question-badge"><i class="bi bi-shield-check"></i></span>
                <span>Verification code</span>
              </div>
              <div class="input-icon-wrap">
                <i class="bi bi-123 input-icon" aria-hidden="true"></i>
                <input type="text" class="form-control" id="verification_code" name="verification_code"
                  inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="Enter 6-digit code"
                  autocomplete="one-time-code" required autofocus>
              </div>
            </div>

            <div class="actions">
              <a href="index.php?action=forgot-password" class="btn-outline-fc" onclick="return confirmResend(event);">Send new code</a>
              <button type="submit" class="btn-primary-fc">Verify code</button>
            </div>
          </form>
          <form method="POST" action="index.php?action=forgot-password" id="resendCodeForm" style="display:none;">
            <input type="hidden" name="intent" value="resend_code">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($resetEmail, ENT_QUOTES, 'UTF-8'); ?>">
          </form>

        <?php elseif ($step == 3): ?>
          <header class="auth-header">
            <h1 id="auth-title">Create a new password</h1>
            <p>Choose a strong password for your account<?php echo ($first_name || $last_name) ? ', ' . htmlspecialchars(trim($first_name . ' ' . $last_name)) : ''; ?>.</p>
          </header>

          <form method="POST" action="index.php?action=reset-password" id="passwordResetForm" novalidate>
            <div class="mb-3">
              <label for="new_password" class="form-label">New password</label>
              <div class="input-icon-wrap">
                <i class="bi bi-lock input-icon" aria-hidden="true"></i>
                <input type="password" class="form-control has-toggle" id="new_password" name="password"
                  placeholder="Enter a new password" autocomplete="new-password" required minlength="8">
                <button type="button" class="btn-toggle-password" data-target="new_password" aria-label="Show password">
                  <i class="bi bi-eye" aria-hidden="true"></i>
                </button>
              </div>

              <div class="requirements" aria-label="Password requirements">
                <div class="requirements-title">Password requirements</div>
                <div class="requirements-grid">
                  <div class="requirement" data-rule="length"><i class="bi bi-circle-fill"></i><span>8+ characters</span></div>
                  <div class="requirement" data-rule="uppercase"><i class="bi bi-circle-fill"></i><span>Uppercase letter</span></div>
                  <div class="requirement" data-rule="lowercase"><i class="bi bi-circle-fill"></i><span>Lowercase letter</span></div>
                  <div class="requirement" data-rule="number"><i class="bi bi-circle-fill"></i><span>Number</span></div>
                  <div class="requirement" data-rule="special"><i class="bi bi-circle-fill"></i><span>Special character</span></div>
                </div>
              </div>
            </div>

            <div class="mb-1">
              <label for="confirm_password" class="form-label">Confirm password</label>
              <div class="input-icon-wrap">
                <i class="bi bi-shield-check input-icon" aria-hidden="true"></i>
                <input type="password" class="form-control has-toggle" id="confirm_password" name="confirm_password"
                  placeholder="Re-enter your new password" autocomplete="new-password" required>
                <button type="button" class="btn-toggle-password" data-target="confirm_password" aria-label="Show password">
                  <i class="bi bi-eye" aria-hidden="true"></i>
                </button>
              </div>
              <div class="match-status" id="passwordMatchIndicator" aria-live="polite"></div>
            </div>

            <div class="actions">
              <a href="index.php?action=reset-options" class="btn-outline-fc">Back</a>
              <button type="submit" class="btn-primary-fc" id="resetPasswordBtn" disabled>Reset password</button>
            </div>
          </form>

        <?php else: ?>
          <header class="auth-header">
            <h1 id="auth-title">Continue recovery</h1>
            <p>Your email has been verified. Continue to choose a recovery option.</p>
          </header>
          <div class="actions">
            <a href="index.php?action=reset-options" class="btn-primary-fc" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">Continue</a>
            <a href="index.php?action=login" class="btn-outline-fc">Cancel</a>
          </div>
        <?php endif; ?>

        <div class="auth-footer">
          <span>Remembered your password?</span>
          <a href="index.php?action=login">Back to sign in</a>
        </div>
      </main>
    </div>
  </div>

  <script>
    (function () {
      document.querySelectorAll('.btn-toggle-password').forEach(function (button) {
        button.addEventListener('click', function () {
          var targetId = button.getAttribute('data-target');
          var input = targetId ? document.getElementById(targetId) : null;
          var icon = button.querySelector('i');
          if (!input) return;
          var show = input.type === 'password';
          input.type = show ? 'text' : 'password';
          button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
          if (icon) icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
      });

      var passwordForm = document.getElementById('passwordResetForm');
      if (passwordForm) {
        var newPassword = document.getElementById('new_password');
        var confirmPassword = document.getElementById('confirm_password');
        var matchIndicator = document.getElementById('passwordMatchIndicator');
        var submitBtn = document.getElementById('resetPasswordBtn');
        var requirements = document.querySelectorAll('.requirement');

        function validatePassword() {
          var password = newPassword ? newPassword.value : '';
          var allChecksPassed = password.length > 0;
          var checks = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
          };

          requirements.forEach(function (req) {
            var rule = req.getAttribute('data-rule');
            var ok = !!checks[rule];
            req.classList.toggle('valid', ok);
            if (!ok) allChecksPassed = false;
          });
          if (password.length === 0) allChecksPassed = false;

          if (confirmPassword && confirmPassword.value) {
            var match = password === confirmPassword.value && allChecksPassed;
            matchIndicator.className = 'match-status ' + (match ? 'is-valid' : 'is-error');
            matchIndicator.innerHTML = match
              ? '<i class="bi bi-check-circle" aria-hidden="true"></i><span>Passwords match</span>'
              : '<i class="bi bi-exclamation-circle" aria-hidden="true"></i><span>Passwords do not match</span>';
            if (submitBtn) submitBtn.disabled = !match;
          } else if (matchIndicator) {
            matchIndicator.className = 'match-status';
            matchIndicator.innerHTML = '';
            if (submitBtn) submitBtn.disabled = true;
          }
        }

        if (newPassword) newPassword.addEventListener('input', validatePassword);
        if (confirmPassword) confirmPassword.addEventListener('input', validatePassword);
        validatePassword();
      }

      function confirmResend(e) {
        e.preventDefault();
        var form = document.getElementById('resendCodeForm');
        if (form) form.submit();
        return false;
      }
      window.confirmResend = confirmResend;

      document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
          var btn = form.querySelector('button[type="submit"]');
          if (btn && btn.disabled) {
            e.preventDefault();
            return;
          }
          if (btn) {
            btn.disabled = true;
            btn.textContent = 'Processing...';
          }
        });
      });
    }());
  </script>
</body>

</html>
