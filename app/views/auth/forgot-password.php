<?php
// app/views/auth/forgot-password.php
$step = isset($_SESSION['reset_step']) ? $_SESSION['reset_step'] : 1;
$questions = isset($_SESSION['security_questions']) ? $_SESSION['security_questions'] : [];
$first_name = $_SESSION['reset_first_name'] ?? '';
$last_name = $_SESSION['reset_last_name'] ?? '';
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    background: url('assets/bfcloansbackground.png') center/cover fixed;
    font-family: 'Segoe UI', sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
  }

  /* Main Container */
  .forgot-password-container {
    max-width: 1000px;
    width: 100%;
    height: 650px;
    border-radius: 1rem;
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
    background: white;
    display: flex;
    overflow: hidden;
  }

  /* Left Column - Fixed */
  .left-container {
    width: 35%;
    background: linear-gradient(135deg, #149004cb 0%, #0f7000cb 100%);
    color: white;
    padding: 2rem 1.5rem;
    overflow-y: auto;
  }

  /* Right Column - Scrollable */
  .right-container {
    width: 65%;
    background: white;
    padding: 1.5rem;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
  }

  /* Custom scrollbar */
  .right-container::-webkit-scrollbar,
  .left-container::-webkit-scrollbar {
    width: 5px;
  }

  .right-container::-webkit-scrollbar-track,
  .left-container::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  .right-container::-webkit-scrollbar-thumb {
    background: #149004cb;
    border-radius: 5px;
  }

  .left-container::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 5px;
  }

  /* Logo */
  .logo-circle {
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    border: 2px solid #fcd116;
    overflow: hidden;
  }

  .logo-circle img {
    width: 80%;
    height: auto;
  }

  /* Feature Items - Compact */
  .feature-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.6rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 0.5rem;
    margin-bottom: 0.6rem;
    font-size: 0.85rem;
  }

  .feature-icon {
    color: #fcd116;
    font-size: 1rem;
  }

  /* Progress Steps - Compact */
  .progress-steps {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
  }

  .progress-step {
    text-align: center;
    flex: 1;
  }

  .step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
    margin: 0 auto 0.2rem;
  }

  .step-number.active {
    background: #149004cb;
    color: white;
  }

  .step-number.completed {
    background: #4CAF50;
    color: white;
  }

  .step-label {
    font-size: 0.65rem;
    color: #6c757d;
  }

  .step-label.active-label {
    color: #149004cb;
    font-weight: 600;
  }

  .progress {
    height: 4px;
    margin-bottom: 1rem;
    background: #e9ecef;
  }

  .progress-bar {
    background: linear-gradient(90deg, #149004cb 0%, #4CAF50 100%);
  }

  /* Forms - Compact */
  .form-label {
    font-weight: 600;
    color: #0f7000cb;
    font-size: 0.75rem;
    margin-bottom: 0.2rem;
  }

  .input-group {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    overflow: hidden;
  }

  .input-group-text {
    background: #f8f9fa;
    border: none;
    color: #149004cb;
    padding: 0.4rem 0.75rem;
  }

  .form-control {
    border: none;
    padding: 0.4rem 0.75rem;
    font-size: 0.85rem;
  }

  /* Question Box - Compact */
  .question-box {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 0.75rem;
    margin-bottom: 0.75rem;
    border-left: 3px solid #149004cb;
  }

  .question-number {
    display: inline-flex;
    width: 20px;
    height: 20px;
    background: #149004cb;
    color: white;
    border-radius: 50%;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    margin-right: 0.4rem;
  }

  /* Buttons - Compact */
  .btn {
    padding: 0.4rem 1.2rem;
    font-size: 0.85rem;
    border-radius: 0.5rem;
    font-weight: 500;
  }

  .btn-primary {
    background: linear-gradient(135deg, #149004cb 0%, #0f7000cb 100%);
    border: none;
    color: white;
  }

  .btn-outline-primary {
    border: 1px solid #149004cb;
    color: #149004cb;
    background: white;
  }

  .btn-success {
    background: linear-gradient(135deg, #4CAF50 0%, #2e7d32 100%);
    border: none;
    color: white;
  }

  .button-group {
    display: flex;
    gap: 0.75rem;
    margin-top: 1rem;
  }

  /* Alerts - Compact */
  .alert-patriotic-error,
  .alert-patriotic-success {
    padding: 0.6rem 1rem;
    border-radius: 0.5rem;
    margin-bottom: 0.75rem;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .alert-patriotic-error {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    border-left: 3px solid #dc3545;
  }

  .alert-patriotic-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border-left: 3px solid #4CAF50;
  }

  /* Step Content */
  .step-icon {
    text-align: center;
    margin-bottom: 0.5rem;
  }

  .step-icon i {
    font-size: 2rem;
    color: #149004cb;
    background: #f0f9f0;
    padding: 0.75rem;
    border-radius: 50%;
  }

  .step-content h3 {
    font-size: 1.1rem;
    color: #0f7000cb;
    margin-bottom: 0.25rem;
    font-weight: 600;
    text-align: center;
  }

  .step-description {
    color: #6c757d;
    font-size: 0.75rem;
    margin-bottom: 1rem;
    text-align: center;
  }

  /* Password Requirements - Compact */
  .password-requirements {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 0.75rem;
    margin-top: 0.5rem;
  }

  .requirements-title {
    font-weight: 600;
    color: #0f7000cb;
    margin-bottom: 0.5rem;
    font-size: 0.75rem;
  }

  .password-requirements ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.3rem;
  }

  .requirement {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    color: #6c757d;
    font-size: 0.7rem;
  }

  .requirement.valid {
    color: #4CAF50;
  }

  .requirement i {
    font-size: 0.6rem;
  }

  /* Password Match */
  .password-match {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    margin-top: 0.4rem;
    padding: 0.4rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.7rem;
    background: #f8d7da;
    color: #dc3545;
  }

  .password-match.valid {
    background: #d4edda;
    color: #4CAF50;
  }

  /* Toggle Password */
  .toggle-password {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 0.2rem;
    z-index: 10;
  }

  .password-input {
    position: relative;
  }

  /* Footer - Compact */
  .forgot-password-footer {
    margin-top: 1rem;
    padding-top: 0.75rem;
    border-top: 1px solid #e9ecef;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
  }

  .security-notice,
  .admin-support {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    padding: 0.6rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
    border-left: 3px solid #149004cb;
  }

  .admin-support {
    border-left-color: #fcd116;
  }

  .security-notice i,
  .admin-support i {
    font-size: 1rem;
    color: #149004cb;
  }

  .admin-support i {
    color: #fcd116;
  }

  .security-notice strong,
  .admin-support strong {
    display: block;
    font-size: 0.75rem;
    margin-bottom: 0.2rem;
  }

  .security-notice p,
  .admin-support p {
    font-size: 0.65rem;
    line-height: 1.3;
    margin: 0;
    color: #6b7280;
  }

  .admin-support a {
    color: #149004cb;
    text-decoration: none;
    font-size: 0.65rem;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .forgot-password-container {
      flex-direction: column;
      height: auto;
    }

    .left-container,
    .right-container {
      width: 100%;
    }

    .right-container {
      max-height: 500px;
    }

    .forgot-password-footer {
      grid-template-columns: 1fr;
    }

    .button-group {
      flex-direction: column;
    }
  }
</style>

<div class="forgot-password-container">
  <!-- Left Column - Fixed Info -->
  <div class="left-container">
    <div class="text-center">
      <div class="logo-circle">
        <img src="assets/bfcloans.png" alt="BUENAMENTE">
      </div>
      <h5 class="text-white mb-1 fw-bold">BUENAMENTE</h5>
      <p class="text-white small mb-3 opacity-75">Faculty Recovery</p>

      <div class="mt-3">
        <div class="feature-item">
          <i class="bi bi-shield-check feature-icon"></i>
          <span class="text-white small">Secure Recovery</span>
        </div>
        <div class="feature-item">
          <i class="bi bi-key feature-icon"></i>
          <span class="text-white small">Identity Verification</span>
        </div>
        <div class="feature-item">
          <i class="bi bi-lock feature-icon"></i>
          <span class="text-white small">SSL Encrypted</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Column - Scrollable Content -->
  <div class="right-container">
    <!-- Progress -->
    <div class="mb-2">
      <div class="progress">
        <div class="progress-bar" style="width: <?php echo ($step == 1) ? '33%' : (($step == 2) ? '66%' : '100%'); ?>;"></div>
      </div>
      <div class="progress-steps">
        <div class="progress-step">
          <div class="step-number <?php echo $step >= 1 ? 'active' : ''; ?> <?php echo $step > 1 ? 'completed' : ''; ?>">1</div>
          <small class="step-label <?php echo $step == 1 ? 'active-label' : ''; ?>">Security</small>
        </div>
        <div class="progress-step">
          <div class="step-number <?php echo $step >= 2 ? 'active' : ''; ?> <?php echo $step > 2 ? 'completed' : ''; ?>">2</div>
          <small class="step-label <?php echo $step == 2 ? 'active-label' : ''; ?>">Options</small>
        </div>
        <div class="progress-step">
          <div class="step-number <?php echo $step >= 3 ? 'active' : ''; ?>">3</div>
          <small class="step-label <?php echo $step == 3 ? 'active-label' : ''; ?>">Reset</small>
        </div>
      </div>
    </div>

    <!-- Alerts -->
    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert-patriotic-error">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span><?php echo htmlspecialchars($_SESSION['error']);
              unset($_SESSION['error']); ?></span>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert-patriotic-success">
        <i class="bi bi-check-circle-fill"></i>
        <span><?php echo htmlspecialchars($_SESSION['success']);
              unset($_SESSION['success']); ?></span>
      </div>
    <?php endif; ?>

    <!-- Step 1: Security Questions -->
    <?php if ($step == 1): ?>
      <div class="step-content">
        <div class="step-icon">
          <i class="bi bi-shield-lock"></i>
        </div>
        <h3>Security Verification</h3>
        <p class="step-description">Answer 5 questions to verify identity</p>

        <form method="POST" action="index.php?action=forgot-password">
          <?php if (empty($questions)): ?>
            <div class="alert-patriotic-error">
              <i class="bi bi-exclamation-triangle-fill"></i> System error: Unable to load questions
            </div>
            <div class="button-group">
              <a href="index.php?action=login" class="btn btn-primary w-100">Back to Login</a>
            </div>
          <?php else: ?>
            <?php foreach ($questions as $index => $question): ?>
              <?php $qNum = $index + 1; ?>
              <div class="question-box">
                <div class="d-flex align-items-center mb-2">
                  <span class="question-number"><?php echo $qNum; ?></span>
                  <span class="small fw-semibold" style="color: #0f7000cb;">Q<?php echo $qNum; ?></span>
                </div>
                <input type="hidden" name="question_id_<?php echo $qNum; ?>" value="<?php echo $question['id']; ?>">
                <div class="mb-2">
                  <div class="p-2 bg-white rounded border small">
                    <i class="bi bi-question-circle-fill me-1" style="color: #149004cb;"></i>
                    <?php echo htmlspecialchars($question['question']); ?>
                  </div>
                </div>
                <div>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                    <input type="text" class="form-control" name="answer_<?php echo $qNum; ?>" placeholder="Your answer" required <?php echo $index == 0 ? 'autofocus' : ''; ?>>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>

            <div class="button-group">
              <a href="index.php?action=login" class="btn btn-outline-primary">Cancel</a>
              <button type="submit" class="btn btn-primary">Verify</button>
            </div>
          <?php endif; ?>
        </form>
      </div>

      <!-- Step 3: Set New Password -->
    <?php elseif ($step == 3): ?>
      <div class="step-content">
        <div class="step-icon">
          <i class="bi bi-lock"></i>
        </div>
        <h3>Create New Password</h3>
        <p class="step-description">Choose a strong password for your account</p>

        <form method="POST" action="index.php?action=reset-password" id="passwordResetForm">
          <div class="mb-2">
            <label class="form-label">New Password</label>
            <div class="password-input">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                <input type="password" class="form-control" id="new_password" name="password" placeholder="New password" required minlength="8">
                <button type="button" class="toggle-password" data-target="new_password">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <div class="password-requirements">
              <div class="requirements-title">Requirements:</div>
              <ul>
                <li class="requirement" data-rule="length"><i class="bi bi-circle"></i> 8+ chars</li>
                <li class="requirement" data-rule="uppercase"><i class="bi bi-circle"></i> Uppercase</li>
                <li class="requirement" data-rule="lowercase"><i class="bi bi-circle"></i> Lowercase</li>
                <li class="requirement" data-rule="number"><i class="bi bi-circle"></i> Number</li>
                <li class="requirement" data-rule="special"><i class="bi bi-circle"></i> Special</li>
              </ul>
            </div>
          </div>

          <div class="mb-2">
            <label class="form-label">Confirm Password</label>
            <div class="password-input">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                <button type="button" class="toggle-password" data-target="confirm_password">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
            <div class="password-match" id="passwordMatchIndicator" style="display: none;">
              <i class="bi bi-x-circle"></i> <span>Passwords do not match</span>
            </div>
          </div>

          <div class="button-group">
            <a href="index.php?action=reset-options" class="btn btn-outline-primary">Back</a>
            <button type="submit" class="btn btn-success" id="resetPasswordBtn" disabled>Reset</button>
          </div>
        </form>
      </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="forgot-password-footer">
      <div class="security-notice">
        <i class="bi bi-shield-check"></i>
        <div>
          <strong>Security Notice</strong>
          <p>All attempts are logged for security</p>
        </div>
      </div>
      <div class="admin-support">
        <i class="bi bi-headset"></i>
        <div>
          <strong>Need Help?</strong>
          <p><a href="mailto:support@institution.edu">Contact Support</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
      button.addEventListener('click', function() {
        const input = document.getElementById(this.dataset.target);
        const icon = this.querySelector('i');
        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
          input.type = 'password';
          icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
      });
    });

    // Password validation for step 3
    const passwordForm = document.getElementById('passwordResetForm');
    if (passwordForm) {
      const newPassword = document.getElementById('new_password');
      const confirmPassword = document.getElementById('confirm_password');
      const matchIndicator = document.getElementById('passwordMatchIndicator');
      const submitBtn = document.getElementById('resetPasswordBtn');
      const requirements = document.querySelectorAll('.requirement');

      function validatePassword() {
        const password = newPassword?.value || '';
        let allChecksPassed = true;

        if (password.length > 0) {
          const checks = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
          };

          requirements.forEach(req => {
            const rule = req.dataset.rule;
            const icon = req.querySelector('i');
            if (checks[rule]) {
              req.classList.add('valid');
              icon.className = 'bi bi-check-circle-fill';
            } else {
              req.classList.remove('valid');
              icon.className = 'bi bi-circle';
              allChecksPassed = false;
            }
          });
        } else {
          allChecksPassed = false;
        }

        if (confirmPassword?.value) {
          matchIndicator.style.display = 'flex';
          const match = password === confirmPassword.value && password.length >= 8 && allChecksPassed;
          if (match) {
            matchIndicator.className = 'password-match valid';
            matchIndicator.innerHTML = '<i class="bi bi-check-circle-fill"></i> <span>Passwords match</span>';
          } else {
            matchIndicator.className = 'password-match';
            matchIndicator.innerHTML = '<i class="bi bi-exclamation-circle"></i> <span>Passwords do not match</span>';
          }
          if (submitBtn) submitBtn.disabled = !match;
        } else {
          matchIndicator.style.display = 'none';
          if (submitBtn) submitBtn.disabled = true;
        }
      }

      newPassword?.addEventListener('input', validatePassword);
      confirmPassword?.addEventListener('input', validatePassword);
      validatePassword();
    }

    // Loading state on form submit
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        if (btn && !btn.disabled) {
          btn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Processing...';
          btn.disabled = true;
        }
      });
    });
  });
</script>
