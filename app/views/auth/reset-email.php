<?php
// app/Views/auth/reset-email.php
$first_name = $_SESSION['reset_first_name'] ?? '';
$last_name = $_SESSION['reset_last_name'] ?? '';
$email = $_SESSION['reset_email'] ?? '';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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

  .reset-email-container {
    width: 100%;
    max-width: 600px;
    height: 650px;
    margin: 0 auto;
  }

  .reset-email-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  /* Fixed Header */
  .reset-email-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    padding: 1.25rem;
    text-align: center;
    flex-shrink: 0;
  }

  .logo {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 1rem;
    font-weight: 600;
  }

  .logo i {
    font-size: 1.3rem;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem;
    border-radius: 8px;
  }

  .logo-text {
    font-size: 1.1rem;
  }

  h1 {
    font-size: 1.4rem;
    margin-bottom: 0.2rem;
    font-weight: 700;
    color: white;
  }

  .subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.8rem;
    margin-bottom: 0.75rem;
  }

  /* Progress Steps - Compact */
  .progress-steps {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
  }

  .step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.2rem;
  }

  .step-number {
    width: 30px;
    height: 30px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.8rem;
    border: 2px solid transparent;
  }

  .step.active .step-number {
    background: white;
    color: #7c3aed;
    border-color: white;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
  }

  .step.completed .step-number {
    background: #10b981;
    color: white;
    border-color: #10b981;
  }

  .step-label {
    font-size: 0.6rem;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
  }

  .step-divider {
    width: 30px;
    height: 2px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 1px;
  }

  /* Scrollable Content */
  .scrollable-content {
    flex: 1;
    overflow-y: auto;
    padding: 1.25rem;
  }

  /* Custom scrollbar */
  .scrollable-content::-webkit-scrollbar {
    width: 5px;
  }

  .scrollable-content::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  .scrollable-content::-webkit-scrollbar-thumb {
    background: #7c3aed;
    border-radius: 5px;
  }

  /* Alerts - Compact */
  .alert {
    padding: 0.6rem 1rem;
    margin-bottom: 1rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    border-left: 3px solid;
  }

  .alert-error {
    background: #fee;
    color: #dc2626;
    border-left-color: #dc2626;
  }

  .alert-success {
    background: #f0fdf4;
    color: #16a34a;
    border-left-color: #16a34a;
  }

  /* Step Content */
  .step-content {
    text-align: center;
  }

  .step-icon {
    font-size: 2rem;
    color: #7c3aed;
    margin-bottom: 0.5rem;
    background: #f5f3ff;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin: 0 auto 0.75rem;
  }

  .step-content h3 {
    font-size: 1.2rem;
    color: #1f2937;
    margin-bottom: 0.25rem;
    font-weight: 600;
  }

  .step-description {
    color: #6b7280;
    font-size: 0.8rem;
    margin-bottom: 1rem;
    line-height: 1.4;
  }

  /* Current Info - Compact */
  .current-info {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 0.75rem;
    margin-bottom: 1rem;
    text-align: left;
  }

  .info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
  }

  .info-item label {
    font-weight: 600;
    color: #374151;
  }

  .info-item span {
    color: #6b7280;
  }

  /* Form - Compact */
  .step-form {
    text-align: left;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    margin-bottom: 1rem;
  }

  label {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    color: #374151;
    font-weight: 500;
    font-size: 0.8rem;
  }

  label i {
    color: #7c3aed;
    font-size: 0.9rem;
  }

  label.required::after {
    content: '*';
    color: #dc2626;
    margin-left: 2px;
  }

  input[type="email"] {
    width: 100%;
    padding: 0.6rem 0.8rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    background: white;
  }

  input[type="email"]:focus {
    outline: none;
    border-color: #7c3aed;
    box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.1);
  }

  input::placeholder {
    color: #9ca3af;
    font-size: 0.8rem;
  }

  .field-help {
    font-size: 0.7rem;
    color: #6b7280;
    margin-top: 2px;
  }

  /* Email Match Indicator - Compact */
  .email-match {
    display: none;
    align-items: center;
    gap: 0.4rem;
    margin-top: 0.4rem;
    padding: 0.5rem 0.75rem;
    border-radius: 0.4rem;
    font-size: 0.75rem;
    background: #fee;
    color: #dc2626;
  }

  .email-match.valid {
    background: #f0fdf4;
    color: #16a34a;
  }

  .email-match i {
    font-size: 0.8rem;
  }

  /* Security Notice Form - Compact */
  .security-notice-form {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 0.5rem;
    padding: 0.75rem;
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    margin-bottom: 1rem;
  }

  .security-notice-form i {
    color: #3b82f6;
    font-size: 1rem;
    margin-top: 2px;
  }

  .security-notice-form strong {
    color: #1e40af;
    display: block;
    margin-bottom: 2px;
    font-size: 0.75rem;
  }

  .security-notice-form p {
    color: #1e40af;
    font-size: 0.7rem;
    line-height: 1.3;
    margin: 0;
  }

  /* Buttons - Compact */
  .form-actions {
    margin-top: 1rem;
  }

  .button-group {
    display: flex;
    gap: 0.75rem;
  }

  .btn {
    padding: 0.6rem 1rem;
    border: none;
    border-radius: 0.5rem;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    text-decoration: none;
    flex: 1;
  }

  .btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
  }

  .btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
  }

  .btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #e5e7eb;
  }

  .btn-secondary:hover {
    background: #e5e7eb;
    transform: translateY(-2px);
  }

  .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  /* Fixed Footer */
  .reset-email-footer {
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
    padding: 0.75rem 1rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    flex-shrink: 0;
  }

  .security-notice,
  .admin-support {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    padding: 0.5rem;
    background: white;
    border-radius: 0.5rem;
    border-left: 3px solid #7c3aed;
  }

  .admin-support {
    border-left-color: #d97706;
  }

  .security-notice i,
  .admin-support i {
    font-size: 0.9rem;
    margin-top: 2px;
  }

  .security-notice i {
    color: #7c3aed;
  }

  .admin-support i {
    color: #d97706;
  }

  .security-notice strong,
  .admin-support strong {
    display: block;
    color: #1f2937;
    margin-bottom: 2px;
    font-size: 0.7rem;
  }

  .security-notice p,
  .admin-support p {
    color: #6b7280;
    font-size: 0.65rem;
    line-height: 1.3;
    margin: 0;
  }

  .admin-support a {
    color: #7c3aed;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.65rem;
  }

  /* Responsive */
  @media (max-width: 640px) {
    .reset-email-container {
      height: auto;
      max-height: 90vh;
    }

    .button-group {
      flex-direction: column;
    }

    .btn {
      width: 100%;
    }

    .progress-steps {
      gap: 0.2rem;
    }

    .step-divider {
      width: 15px;
    }
  }

  @media (max-width: 480px) {
    .reset-email-header {
      padding: 1rem;
    }

    h1 {
      font-size: 1.2rem;
    }

    .progress-steps {
      flex-direction: column;
      gap: 0.3rem;
    }

    .step-divider {
      width: 2px;
      height: 15px;
    }

    .step-icon {
      width: 50px;
      height: 50px;
      font-size: 1.5rem;
    }

    .reset-email-footer {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="reset-email-container">
  <div class="reset-email-card">
    <!-- Fixed Header -->
    <div class="reset-email-header">
      <div class="logo">
        <i class="fas fa-chalkboard-admin"></i>
        <span class="logo-text">Faculty Recovery</span>
      </div>
      <h1>Reset Email</h1>
      <p class="subtitle">Update your account email</p>

      <div class="progress-steps">
        <div class="step completed">
          <div class="step-number"><i class="fas fa-check"></i></div>
          <div class="step-label">Security</div>
        </div>
        <div class="step-divider"></div>
        <div class="step completed">
          <div class="step-number"><i class="fas fa-check"></i></div>
          <div class="step-label">Choose</div>
        </div>
        <div class="step-divider"></div>
        <div class="step active">
          <div class="step-number">3</div>
          <div class="step-label">Reset</div>
        </div>
      </div>
    </div>

    <!-- Scrollable Content -->
    <div class="scrollable-content">
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
          <i class="fas fa-exclamation-circle"></i>
          <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i>
          <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>

      <div class="step-content">
        <div class="step-icon">
          <i class="fas fa-envelope"></i>
        </div>
        <h3>Update Email</h3>
        <p class="step-description">Enter your new email address for future logins</p>

        <div class="current-info">
          <div class="info-item">
            <label>Current:</label>
            <span><?php echo htmlspecialchars($email); ?></span>
          </div>
        </div>

        <form method="POST" action="index.php?action=reset-email" class="step-form" id="emailResetForm">
          <div class="form-group">
            <label for="new_email" class="required">
              <i class="fas fa-envelope"></i> New Email
            </label>
            <input
              type="email"
              id="new_email"
              name="new_email"
              placeholder="Enter new email"
              required
              autocomplete="email"
            >
            <div class="field-help">This will be your new login email</div>
          </div>

          <div class="form-group">
            <label for="confirm_email" class="required">
              <i class="fas fa-envelope"></i> Confirm Email
            </label>
            <input
              type="email"
              id="confirm_email"
              name="confirm_email"
              placeholder="Confirm new email"
              required
              autocomplete="email"
            >
            <div class="email-match" id="emailMatchIndicator">
              <i class="fas fa-times"></i> <span>Emails do not match</span>
            </div>
          </div>

          <div class="security-notice-form">
            <i class="fas fa-info-circle"></i>
            <div>
              <strong>Important:</strong>
              <p>You'll need to login with the new email address after update</p>
            </div>
          </div>

          <div class="form-actions">
            <div class="button-group">
              <a href="index.php?action=reset-options" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
              </a>
              <button type="submit" class="btn btn-primary" id="resetEmailBtn" disabled>
                <i class="fas fa-save"></i> Update
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Fixed Footer -->
    <div class="reset-email-footer">
      <div class="security-notice">
        <i class="fas fa-shield-alt"></i>
        <div>
          <strong>Security</strong>
          <p>Changes are logged</p>
        </div>
      </div>
      <div class="admin-support">
        <i class="fas fa-headset"></i>
        <div>
          <strong>Support</strong>
          <p><a href="mailto:support@institution.edu">Email</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Email validation
  const emailForm = document.getElementById('emailResetForm');
  if (emailForm) {
    const newEmail = document.getElementById('new_email');
    const confirmEmail = document.getElementById('confirm_email');
    const matchIndicator = document.getElementById('emailMatchIndicator');
    const submitBtn = document.getElementById('resetEmailBtn');

    function validateEmailForm() {
      const newEmailValue = newEmail?.value.trim() || '';
      const confirmEmailValue = confirmEmail?.value.trim() || '';
      let isValid = false;

      if (newEmailValue && confirmEmailValue) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const validFormat = emailRegex.test(newEmailValue);
        const match = newEmailValue === confirmEmailValue && validFormat;

        if (match) {
          matchIndicator.className = 'email-match valid';
          matchIndicator.innerHTML = '<i class="fas fa-check-circle"></i> <span>Emails match</span>';
          isValid = true;
        } else {
          matchIndicator.className = 'email-match';
          matchIndicator.innerHTML = '<i class="fas fa-exclamation-circle"></i> <span>Emails do not match</span>';
        }
        matchIndicator.style.display = 'flex';
      } else {
        matchIndicator.style.display = 'none';
      }

      if (submitBtn) submitBtn.disabled = !isValid;
    }

    newEmail?.addEventListener('input', validateEmailForm);
    confirmEmail?.addEventListener('input', validateEmailForm);
    validateEmailForm();
  }

  // Form submission loading state
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
      const btn = this.querySelector('button[type="submit"]');
      if (btn && !btn.disabled) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        btn.disabled = true;
      }
    });
  });
});
</script>
