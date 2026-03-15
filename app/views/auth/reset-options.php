<?php
// app/views/auth/reset-options.php
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

  .reset-options-container {
    width: 100%;
    max-width: 900px;
    height: 650px;
    margin: 0 auto;
  }

  .reset-options-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  /* Fixed Header */
  .reset-options-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    padding: 1.5rem;
    text-align: center;
    flex-shrink: 0;
  }

  .logo {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
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
    font-size: 1.5rem;
    margin-bottom: 0.25rem;
    font-weight: 700;
    color: white;
  }

  .subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.85rem;
    margin-bottom: 1rem;
  }

  /* Progress Steps - Compact */
  .progress-steps {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
  }

  .step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
  }

  .step-number {
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.85rem;
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
    font-size: 0.65rem;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
  }

  .step-divider {
    width: 40px;
    height: 2px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 1px;
  }

  /* Scrollable Content Area */
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

  /* Alert - Compact */
  .alert {
    padding: 0.6rem 1rem;
    margin-bottom: 1rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    border-left: 3px solid #dc2626;
    background: #fee;
    color: #dc2626;
  }

  .alert i {
    font-size: 0.9rem;
  }

  /* User Info - Compact */
  .user-info {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
  }

  .user-info i {
    font-size: 2rem;
    color: #7c3aed;
  }

  .user-info strong {
    font-size: 0.95rem;
    color: #1f2937;
    display: block;
    margin-bottom: 0.1rem;
  }

  .user-info p {
    color: #6b7280;
    font-size: 0.75rem;
  }

  .instruction {
    text-align: center;
    color: #374151;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    font-weight: 500;
  }

  /* Options Grid - Compact */
  .options-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
  }

  .option-card {
    cursor: pointer;
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.2s ease;
    display: block;
  }

  .option-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border-color: #7c3aed;
  }

  .option-card input[type="radio"] {
    display: none;
  }

  .option-card input[type="radio"]:checked + .option-content {
    background: #f5f3ff;
  }

  .option-card input[type="radio"]:checked + .option-content .option-icon {
    background: #7c3aed;
    color: white;
  }

  .option-content {
    padding: 1rem;
    text-align: center;
    background: white;
    transition: all 0.2s ease;
  }

  .option-icon {
    width: 50px;
    height: 50px;
    background: #f3f4f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-size: 1.3rem;
    color: #6b7280;
    transition: all 0.2s ease;
  }

  .option-card h3 {
    font-size: 1.1rem;
    color: #1f2937;
    margin-bottom: 0.25rem;
    font-weight: 600;
  }

  .option-card p {
    color: #6b7280;
    font-size: 0.7rem;
    line-height: 1.3;
    margin-bottom: 0.5rem;
  }

  .option-card ul {
    list-style: none;
    text-align: left;
    padding: 0 0.5rem;
  }

  .option-card ul li {
    color: #4b5563;
    font-size: 0.65rem;
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
  }

  .option-card ul li i {
    color: #10b981;
    font-size: 0.6rem;
  }

  /* Button Group - Compact */
  .button-group {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
  }

  .btn {
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 0.5rem;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-decoration: none;
    min-width: 140px;
  }

  .btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
  }

  .btn-primary:hover {
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

  /* Footer - Compact */
  .reset-options-footer {
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
    padding: 0.6rem;
    background: white;
    border-radius: 0.5rem;
    border-left: 3px solid #7c3aed;
  }

  .admin-support {
    border-left-color: #d97706;
  }

  .security-notice i,
  .admin-support i {
    font-size: 1rem;
    margin-top: 1px;
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
    margin-bottom: 0.1rem;
    font-size: 0.75rem;
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
  @media (max-width: 768px) {
    .reset-options-container {
      height: auto;
      max-height: 90vh;
    }

    .options-grid {
      grid-template-columns: 1fr;
    }

    .button-group {
      flex-direction: column;
    }

    .btn {
      width: 100%;
    }

    .reset-options-footer {
      grid-template-columns: 1fr;
    }

    .progress-steps {
      gap: 0.25rem;
    }

    .step-divider {
      width: 20px;
    }
  }

  @media (max-width: 480px) {
    .reset-options-header {
      padding: 1rem;
    }

    h1 {
      font-size: 1.2rem;
    }

    .progress-steps {
      flex-direction: column;
      gap: 0.5rem;
    }

    .step-divider {
      width: 2px;
      height: 20px;
    }

    .user-info {
      flex-direction: column;
      text-align: center;
    }
  }
</style>

<div class="reset-options-container">
  <div class="reset-options-card">
    <!-- Fixed Header -->
    <div class="reset-options-header">
      <div class="logo">
        <i class="fas fa-chalkboard-admin"></i>
        <span class="logo-text">Faculty Recovery</span>
      </div>
      <h1>Choose Reset Option</h1>
      <p class="subtitle">Welcome back, <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?>!</p>

      <div class="progress-steps">
        <div class="step completed">
          <div class="step-number"><i class="fas fa-check"></i></div>
          <div class="step-label">Security</div>
        </div>
        <div class="step-divider"></div>
        <div class="step active">
          <div class="step-number">2</div>
          <div class="step-label">Choose</div>
        </div>
        <div class="step-divider"></div>
        <div class="step">
          <div class="step-number">3</div>
          <div class="step-label">Reset</div>
        </div>
      </div>
    </div>

    <!-- Scrollable Content -->
    <div class="scrollable-content">
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert">
          <i class="fas fa-exclamation-circle"></i>
          <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <div class="user-info">
        <i class="fas fa-user-circle"></i>
        <div>
          <strong><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></strong>
          <p><?php echo htmlspecialchars($email); ?></p>
        </div>
      </div>

      <p class="instruction">Identity verified. What would you like to reset?</p>

      <form method="POST" action="index.php?action=reset-options" class="options-form">
        <div class="options-grid">
          <label class="option-card">
            <input type="radio" name="reset_option" value="password" required>
            <div class="option-content">
              <div class="option-icon">
                <i class="fas fa-lock"></i>
              </div>
              <h3>Reset Password</h3>
              <p>Change your account password</p>
              <ul>
                <li><i class="fas fa-check"></i> Create new strong password</li>
                <li><i class="fas fa-check"></i> Meets security requirements</li>
              </ul>
            </div>
          </label>

          <label class="option-card">
            <input type="radio" name="reset_option" value="email" required>
            <div class="option-content">
              <div class="option-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <h3>Reset Email</h3>
              <p>Change your account email</p>
              <ul>
                <li><i class="fas fa-check"></i> Update to new email</li>
                <li><i class="fas fa-check"></i> Must verify new email</li>
              </ul>
            </div>
          </label>
        </div>

        <div class="form-actions">
          <div class="button-group">
            <a href="index.php?action=forgot-password" class="btn btn-secondary">
              <i class="fas fa-redo"></i> Start Over
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-arrow-right"></i> Continue
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- Fixed Footer -->
    <div class="reset-options-footer">
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

  // Add active class to selected option
  document.querySelectorAll('.option-card input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
      document.querySelectorAll('.option-card').forEach(card => {
        card.style.borderColor = '#e5e7eb';
      });
      if (this.checked) {
        this.closest('.option-card').style.borderColor = '#7c3aed';
      }
    });
  });
});
</script>
