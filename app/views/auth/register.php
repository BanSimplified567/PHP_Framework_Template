<?php
// app/Views/auth/register.php
$old = isset($_SESSION['old']) && is_array($_SESSION['old']) ? $_SESSION['old'] : [];
$security_questions = isset($_SESSION['security_questions']) && is_array($_SESSION['security_questions']) ? $_SESSION['security_questions'] : [];
$old_questions = isset($old['secret_questions']) && is_array($old['secret_questions']) ? $old['secret_questions'] : [];
$old_answers = isset($old['secret_answers']) && is_array($old['secret_answers']) ? $old['secret_answers'] : [];
$prefillEmail = (string)($old['email'] ?? ($_GET['email'] ?? ''));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create account - PHP Framecore</title>
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
      max-width: 1100px;
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
      transition: border-color 0.15s ease, background 0.15s ease, color 0.15s ease;
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
      overflow: hidden;
    }

    @media (min-width: 576px) {
      .auth-form {
        padding: 2.25rem 2.5rem;
      }
    }

    .progress-track {
      height: 6px;
      background: #eef2f7;
      border-radius: 999px;
      overflow: hidden;
      margin-bottom: 1.25rem;
      flex-shrink: 0;
    }

    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--fc-dark) 0%, var(--fc-accent) 100%);
      border-radius: 999px;
      transition: width 0.2s ease;
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

    .form-control,
    .form-select {
      height: 2.75rem;
      padding: 0.625rem 0.875rem;
      font-size: 0.9375rem;
      border-radius: 0.75rem;
      border-color: var(--fc-border);
      background-color: #fff;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-select {
      padding-right: 2.25rem;
    }

    .form-control::placeholder {
      color: #94a3b8;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--fc-accent);
      box-shadow: 0 0 0 4px var(--fc-accent-ring);
    }

    .input-icon-wrap {
      position: relative;
    }

    .input-icon-wrap .form-control,
    .input-icon-wrap .form-select {
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
      z-index: 2;
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
      z-index: 2;
    }

    .btn-toggle-password:hover {
      color: var(--fc-muted);
      background: #f1f5f9;
    }

    .question-card,
    .review-card,
    .notice-card {
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

    .form-step {
      display: none;
      flex-direction: column;
      flex: 1;
      min-height: 0;
    }

    .form-step.is-active {
      display: flex;
    }

    #registerForm {
      display: flex;
      flex-direction: column;
      flex: 1;
      min-height: 0;
    }

    .step-scroll {
      flex: 1;
      min-height: 0;
      padding-right: 0.25rem;
      margin-bottom: 0.25rem;
      overflow-y: auto;
      padding-bottom: 0.5rem;
    }
    .review-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.75rem;
    }

    .review-item small {
      display: block;
      color: var(--fc-muted);
      font-size: 0.75rem;
      margin-bottom: 0.1rem;
    }

    .review-item strong {
      font-size: 0.875rem;
      color: var(--fc-text);
      word-break: break-word;
    }

    .strength-wrap {
      margin-top: 0.6rem;
    }

    .strength-meta {
      display: flex;
      justify-content: space-between;
      font-size: 0.75rem;
      color: var(--fc-muted);
      margin-bottom: 0.25rem;
    }

    .strength-track {
      height: 6px;
      background: #eef2f7;
      border-radius: 999px;
      overflow: hidden;
    }

    .strength-fill {
      height: 100%;
      width: 0;
      border-radius: 999px;
      transition: width 0.2s ease, background-color 0.2s ease;
      background: #cbd5e1;
    }

    .match-status {
      margin-top: 0.5rem;
      font-size: 0.8125rem;
      min-height: 1.25rem;
    }

    .match-status.is-valid {
      color: #0f766e;
    }

    .match-status.is-error {
      color: #b91c1c;
    }

    .form-check-input {
      border-color: #cbd5e1;
    }

    .form-check-input:checked {
      background-color: var(--fc-accent);
      border-color: var(--fc-accent);
    }

    .form-check-input:focus {
      border-color: var(--fc-accent);
      box-shadow: 0 0 0 4px var(--fc-accent-ring);
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

    .auth-footer {
      margin-top: 1rem;
      padding-top: 0.85rem;
      border-top: 1px solid var(--fc-border);
      font-size: 0.8125rem;
      color: var(--fc-muted);
      text-align: center;
      flex-shrink: 0;
    }

    .step-error {
      color: #b91c1c;
      font-size: 0.8125rem;
      margin-top: 0.5rem;
      display: none;
    }

    .step-error.is-visible {
      display: block;
    }

    @media (max-width: 991.98px) {
      .auth-wrapper {
        grid-template-columns: 1fr;
        max-width: 620px;
      }

      .auth-brand {
        padding: 1.75rem;
      }

      .auth-form {
        max-height: calc(100vh - 1rem);
        overflow: hidden;
      }

      .form-step.is-active {
        display: flex;
      }

      .step-scroll {
        overflow: scroll;
        -webkit-overflow-scrolling: touch;
      }

      .review-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <div class="auth-shell">
    <div class="auth-wrapper">
      <section class="auth-panel auth-brand" aria-label="Create account">
        <div class="brand-top">
          <div class="brand">
            <div class="brand-mark" aria-hidden="true"><i class="bi bi-person-plus"></i></div>
            <div>
              <div class="brand-name">PHP Framecore</div>
              <div class="brand-tag">Account registration</div>
            </div>
          </div>

          <h2 class="brand-headline">Set up a secure account in a few steps.</h2>
          <p class="brand-copy">Provide your details, choose security questions, and create a strong password.</p>
        </div>

        <div class="brand-bottom">
          <div class="recovery-steps" id="sideSteps" aria-label="Registration progress">
            <div class="recovery-step is-active" data-step="1">
              <span class="step-dot">1</span>
              <span>Personal details</span>
            </div>
            <div class="recovery-step" data-step="2">
              <span class="step-dot">2</span>
              <span>Security questions</span>
            </div>
            <div class="recovery-step" data-step="3">
              <span class="step-dot">3</span>
              <span>Review & password</span>
            </div>
          </div>
        </div>
      </section>

      <main class="auth-panel auth-form" aria-labelledby="auth-title">
        <div class="progress-track" aria-hidden="true">
          <div class="progress-fill" id="progressFill" style="width: 33.333%;"></div>
        </div>

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

        <form action="index.php?action=register" method="POST" id="registerForm" novalidate>
          <section class="form-step is-active" id="step1" data-step="1">
            <header class="auth-header">
              <h1 id="auth-title">Create account</h1>
              <p>Enter your personal details to get started.</p>
            </header>

            <div class="step-scroll">
              <div class="row g-3">
                <div class="col-sm-6">
                  <label for="first_name" class="form-label">First name <span class="text-danger">*</span></label>
                  <div class="input-icon-wrap">
                    <i class="bi bi-person input-icon" aria-hidden="true"></i>
                    <input type="text" class="form-control" id="first_name" name="first_name"
                      value="<?php echo htmlspecialchars($old['first_name'] ?? ''); ?>"
                      placeholder="First name" autocomplete="given-name" required>
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="last_name" class="form-label">Last name <span class="text-danger">*</span></label>
                  <div class="input-icon-wrap">
                    <i class="bi bi-person input-icon" aria-hidden="true"></i>
                    <input type="text" class="form-control" id="last_name" name="last_name"
                      value="<?php echo htmlspecialchars($old['last_name'] ?? ''); ?>"
                      placeholder="Last name" autocomplete="family-name" required>
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                  <div class="input-icon-wrap">
                    <i class="bi bi-envelope input-icon" aria-hidden="true"></i>
                    <input type="email" class="form-control" id="email" name="email"
                      value="<?php echo htmlspecialchars($prefillEmail, ENT_QUOTES, 'UTF-8'); ?>"
                      placeholder="name@gmail.com" autocomplete="email" required>
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="phone" class="form-label">Contact number <span class="text-danger">*</span></label>
                  <div class="input-icon-wrap">
                    <i class="bi bi-telephone input-icon" aria-hidden="true"></i>
                    <input type="tel" class="form-control" id="phone" name="phone"
                      value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>"
                      placeholder="Contact number" autocomplete="tel" required>
                  </div>
                </div>
                <div class="col-12">
                  <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                  <div class="input-icon-wrap">
                    <i class="bi bi-briefcase input-icon" aria-hidden="true"></i>
                    <select class="form-select" id="role" name="role" required>
                      <option value="" disabled <?php echo empty($old['role'] ?? '') ? 'selected' : ''; ?>>Select a role</option>
                      <option value="admin" <?php echo (($old['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>Administrator</option>
                      <option value="staff" <?php echo (($old['role'] ?? '') === 'staff') ? 'selected' : ''; ?>>Staff member</option>
                      <option value="user" <?php echo (($old['role'] ?? '') === 'user') ? 'selected' : ''; ?>>User</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="step-error" id="step1Error" role="alert"></div>
            </div>

            <div class="actions">
              <a href="index.php?action=login" class="btn-outline-fc">Back to sign in</a>
              <button type="button" class="btn-primary-fc" data-next="2">Continue</button>
            </div>
          </section>

          <section class="form-step" id="step2" data-step="2">
            <header class="auth-header">
              <h1>Security questions</h1>
              <p>Set answers you can use to recover your account later.</p>
            </header>

            <div class="step-scroll">
              <?php if (empty($security_questions)): ?>
                <div class="alert-fc alert-fc-danger d-flex align-items-start gap-2" role="alert">
                  <i class="bi bi-exclamation-circle mt-1" aria-hidden="true"></i>
                  <div>Security questions could not be loaded. Please refresh and try again.</div>
                </div>
              <?php else: ?>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                  <?php $question = $security_questions[$i - 1] ?? null; ?>
                  <?php if ($question): ?>
                    <div class="question-card">
                      <div class="question-meta">
                        <span class="question-badge"><?php echo $i; ?></span>
                        <span>Question <?php echo $i; ?></span>
                      </div>
                      <input type="hidden" name="secret_question_<?php echo $i; ?>" value="<?php echo htmlspecialchars((string) ($question['id'] ?? '')); ?>">
                      <p class="question-text"><?php echo htmlspecialchars($question['question'] ?? ''); ?></p>
                      <div class="input-icon-wrap">
                        <i class="bi bi-chat-left-text input-icon" aria-hidden="true"></i>
                        <input type="text" class="form-control" id="secret_answer_<?php echo $i; ?>"
                          name="secret_answer_<?php echo $i; ?>"
                          value="<?php echo htmlspecialchars((string) ($old_answers[$i] ?? '')); ?>"
                          placeholder="Your answer" autocomplete="off" required>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endfor; ?>

                <div class="notice-card">
                  <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-info-circle mt-1" style="color: var(--fc-dark);" aria-hidden="true"></i>
                    <div class="small" style="color: var(--fc-muted);">
                      Answers are not case-sensitive. Choose answers only you would know; they are used for account recovery.
                    </div>
                  </div>
                </div>
              <?php endif; ?>
              <div class="step-error" id="step2Error" role="alert"></div>
            </div>

            <div class="actions">
              <button type="button" class="btn-outline-fc" data-prev="1">Previous</button>
              <button type="button" class="btn-primary-fc" data-next="3">Continue</button>
            </div>
          </section>

          <section class="form-step" id="step3" data-step="3">
            <header class="auth-header">
              <h1>Review & password</h1>
              <p>Confirm your details and set a secure password.</p>
            </header>

            <div class="step-scroll">
              <div class="review-card">
                <div class="question-meta mb-3" style="margin-bottom:.75rem;">
                  <i class="bi bi-person-check" aria-hidden="true"></i>
                  <span>Account details</span>
                </div>
                <div class="review-grid">
                  <div class="review-item">
                    <small>Full name</small>
                    <strong id="reviewName">-</strong>
                  </div>
                  <div class="review-item">
                    <small>Email</small>
                    <strong id="reviewEmail">-</strong>
                  </div>
                  <div class="review-item">
                    <small>Contact number</small>
                    <strong id="reviewPhone">-</strong>
                  </div>
                  <div class="review-item">
                    <small>Role</small>
                    <strong id="reviewRole">-</strong>
                  </div>
                </div>
              </div>

              <div class="row g-3">
                <div class="col-sm-6">
                  <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                  <div class="input-icon-wrap">
                    <i class="bi bi-lock input-icon" aria-hidden="true"></i>
                    <input type="password" class="form-control has-toggle" id="password" name="password"
                      placeholder="Create password" autocomplete="new-password" required minlength="8">
                    <button type="button" class="btn-toggle-password" data-target="password" aria-label="Show password">
                      <i class="bi bi-eye" aria-hidden="true"></i>
                    </button>
                  </div>
                  <div class="strength-wrap" aria-live="polite">
                    <div class="strength-meta">
                      <span>Password strength</span>
                      <span id="strengthText">Weak</span>
                    </div>
                    <div class="strength-track">
                      <div class="strength-fill" id="strengthFill"></div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="confirm_password" class="form-label">Confirm password <span class="text-danger">*</span></label>
                  <div class="input-icon-wrap">
                    <i class="bi bi-shield-check input-icon" aria-hidden="true"></i>
                    <input type="password" class="form-control has-toggle" id="confirm_password" name="confirm_password"
                      placeholder="Re-enter password" autocomplete="new-password" required minlength="8">
                    <button type="button" class="btn-toggle-password" data-target="confirm_password" aria-label="Show password">
                      <i class="bi bi-eye" aria-hidden="true"></i>
                    </button>
                  </div>
                  <div class="match-status" id="passwordMatch" aria-live="polite"></div>
                </div>
              </div>

              <div class="notice-card mt-3">
                <div class="form-check mb-0">
                  <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                  <label class="form-check-label small" for="terms" style="color: var(--fc-muted);">
                    I agree to the <a href="#" class="link-subtle">Terms of Service</a> and
                    <a href="#" class="link-subtle">Privacy Policy</a>.
                  </label>
                </div>
              </div>
              <div class="step-error" id="step3Error" role="alert"></div>
            </div>

            <div class="actions">
              <button type="button" class="btn-outline-fc" data-prev="2">Previous</button>
              <button type="submit" class="btn-primary-fc">Create account</button>
            </div>
          </section>
        </form>

        <div class="auth-footer">
          Already have an account? <a class="link-subtle" href="index.php?action=login">Sign in</a>
        </div>
      </main>
    </div>
  </div>

  <script>
    (function () {
      var current = 1;
      var steps = Array.prototype.slice.call(document.querySelectorAll('.form-step'));
      var sideSteps = Array.prototype.slice.call(document.querySelectorAll('#sideSteps .recovery-step'));
      var progressFill = document.getElementById('progressFill');

      function showError(id, message) {
        var el = document.getElementById(id);
        if (!el) return;
        el.textContent = message || '';
        el.classList.toggle('is-visible', !!message);
      }

      function setStep(step) {
        current = step;
        steps.forEach(function (s) {
          s.classList.toggle('is-active', Number(s.getAttribute('data-step')) === step);
        });
        sideSteps.forEach(function (s) {
          var n = Number(s.getAttribute('data-step'));
          s.classList.toggle('is-active', n === step);
          s.classList.toggle('is-done', n < step);
          var dot = s.querySelector('.step-dot');
          if (dot) dot.innerHTML = n < step ? '<i class="bi bi-check"></i>' : String(n);
        });
        if (progressFill) progressFill.style.width = ((step / 3) * 100) + '%';
        showError('step1Error', '');
        showError('step2Error', '');
        showError('step3Error', '');
        if (step === 3) updateReview();
        var active = document.querySelector('.form-step.is-active');
        if (active) {
          var first = active.querySelector('input, select');
          if (first && step !== 3) first.focus();
        }
      }

      function validateStep(step) {
        if (step === 1) {
          var fields = [
            { name: 'first_name', label: 'First name' },
            { name: 'last_name', label: 'Last name' },
            { name: 'email', label: 'Email' },
            { name: 'phone', label: 'Contact number' },
            { name: 'role', label: 'Role' }
          ];
          for (var i = 0; i < fields.length; i++) {
            var el = document.querySelector('[name="' + fields[i].name + '"]');
            if (!el || !String(el.value || '').trim()) {
              showError('step1Error', fields[i].label + ' is required.');
              if (el) el.focus();
              return false;
            }
          }
          var email = document.querySelector('[name="email"]');
          if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            showError('step1Error', 'Please enter a valid email address.');
            email.focus();
            return false;
          }
        }

        if (step === 2) {
          for (var q = 1; q <= 5; q++) {
            var answer = document.querySelector('[name="secret_answer_' + q + '"]');
            if (answer && !String(answer.value || '').trim()) {
              showError('step2Error', 'Please provide an answer for security question ' + q + '.');
              answer.focus();
              return false;
            }
          }
        }

        return true;
      }

      function updateReview() {
        var first = document.querySelector('[name="first_name"]');
        var last = document.querySelector('[name="last_name"]');
        var email = document.querySelector('[name="email"]');
        var phone = document.querySelector('[name="phone"]');
        var role = document.querySelector('[name="role"]');
        var set = function (id, value) {
          var el = document.getElementById(id);
          if (el) el.textContent = value || '-';
        };
        set('reviewName', ((first ? first.value : '') + ' ' + (last ? last.value : '')).trim() || '-');
        set('reviewEmail', email ? email.value : '-');
        set('reviewPhone', phone ? phone.value : '-');
        set('reviewRole', role && role.options[role.selectedIndex] ? role.options[role.selectedIndex].text : '-');
      }

      document.querySelectorAll('[data-next]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var next = Number(btn.getAttribute('data-next'));
          if (validateStep(current)) setStep(next);
        });
      });

      document.querySelectorAll('[data-prev]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          setStep(Number(btn.getAttribute('data-prev')));
        });
      });

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

      var password = document.getElementById('password');
      var confirm = document.getElementById('confirm_password');
      var strengthFill = document.getElementById('strengthFill');
      var strengthText = document.getElementById('strengthText');
      var match = document.getElementById('passwordMatch');

      function updateStrength() {
        var value = password ? password.value : '';
        var score = 0;
        if (value.length >= 8) score++;
        if (/[A-Z]/.test(value)) score++;
        if (/[a-z]/.test(value)) score++;
        if (/[0-9]/.test(value)) score++;
        if (/[^A-Za-z0-9]/.test(value)) score++;

        var labels = ['Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very strong'];
        var colors = ['#cbd5e1', '#ef4444', '#f59e0b', '#54e5c7', '#3dd4b4', '#2b2e33'];
        if (strengthFill) {
          strengthFill.style.width = (score * 20) + '%';
          strengthFill.style.backgroundColor = colors[score];
        }
        if (strengthText) strengthText.textContent = labels[score];
      }

      function updateMatch() {
        if (!confirm || !match) return;
        if (!confirm.value) {
          match.textContent = '';
          match.className = 'match-status';
          return;
        }
        var ok = password && password.value === confirm.value;
        match.textContent = ok ? 'Passwords match' : 'Passwords do not match';
        match.className = 'match-status ' + (ok ? 'is-valid' : 'is-error');
      }

      if (password) password.addEventListener('input', function () { updateStrength(); updateMatch(); });
      if (confirm) confirm.addEventListener('input', updateMatch);

      var form = document.getElementById('registerForm');
      if (form) {
        form.addEventListener('submit', function (e) {
          showError('step3Error', '');
          if (!validateStep(1)) { e.preventDefault(); setStep(1); return; }
          if (!validateStep(2)) { e.preventDefault(); setStep(2); return; }

          if (!password || password.value.length < 8) {
            e.preventDefault();
            showError('step3Error', 'Password must be at least 8 characters.');
            setStep(3);
            if (password) password.focus();
            return;
          }
          if (!confirm || password.value !== confirm.value) {
            e.preventDefault();
            showError('step3Error', 'Passwords do not match.');
            setStep(3);
            if (confirm) confirm.focus();
            return;
          }
          var terms = document.getElementById('terms');
          if (!terms || !terms.checked) {
            e.preventDefault();
            showError('step3Error', 'You must agree to the Terms of Service and Privacy Policy.');
            setStep(3);
            return;
          }

          var submitBtn = form.querySelector('button[type="submit"]');
          if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating account...';
          }
        });
      }

      setStep(1);
      updateStrength();
    }());
  </script>
</body>

</html>
