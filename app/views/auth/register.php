<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
  :root {
    --bs-primary: #149004cb;
    --bs-primary-dark: #0f7000cb;
    --bs-secondary: #ce1126;
    --bs-warning: #fcd116;
    --light-green: #f0f9f0;
    --accent-green: #4CAF50;
    --dark-green: #2e7d32;
    --text-light: #ffffff;
  }

  * {
    box-sizing: border-box;
  }

  body {
    background: url('assets/bfcloansbackground.png') center center/cover no-repeat fixed;
    font-family: 'Segoe UI', system-ui, sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
    padding: 1rem;
  }

  /* Card Styles */
  .register-container {
    max-width: 1200px;
    width: 100%;
    height: 90vh;
    max-height: 750px;
    border: none;
    border-radius: 1rem;
    box-shadow: 0 0.5rem 1.5rem rgba(0, 56, 147, 0.15);
    overflow: hidden;
    background: white;
  }

  .row-custom {
    display: flex;
    width: 100%;
    height: 100%;
  }

  /* Left Column */
  .left-container {
    width: 40%;
    background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
    color: white;
    padding: 2rem 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  /* Right Column */
  .right-container {
    width: 60%;
    background: white;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
  }

  /* Custom scrollbar */
  .right-container::-webkit-scrollbar {
    width: 6px;
  }

  .right-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
  }

  .right-container::-webkit-scrollbar-thumb {
    background: var(--bs-primary);
    border-radius: 10px;
  }

  .right-container::-webkit-scrollbar-thumb:hover {
    background: var(--bs-primary-dark);
  }

  .logo-circle {
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    border: 3px solid var(--bs-warning);
    overflow: hidden;
    box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.2);
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
    padding: 0.75rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 0.5rem;
    margin-bottom: 0.75rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
  }

  .feature-item:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateX(5px);
  }

  .feature-icon {
    color: var(--bs-warning);
    font-size: 1.1rem;
  }

  /* Progress Steps */
  .progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    position: relative;
  }

  .progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
    z-index: 1;
  }

  .step-number {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-bottom: 0.25rem;
    border: 2px solid transparent;
    transition: all 0.3s ease;
  }

  .step-number.active {
    background-color: var(--bs-primary);
    color: white;
    border-color: var(--bs-primary-dark);
    transform: scale(1.05);
  }

  .step-number.completed {
    background-color: var(--accent-green);
    color: white;
  }

  .step-label {
    font-size: 0.7rem;
    color: #6c757d;
    font-weight: 500;
  }

  .step-label.active-label {
    color: var(--bs-primary);
    font-weight: 600;
  }

  .progress {
    height: 4px;
    border-radius: 2px;
    margin-bottom: 1.5rem;
    background-color: #e9ecef;
  }

  .progress-bar {
    background: linear-gradient(90deg, var(--bs-primary) 0%, var(--accent-green) 100%);
    transition: width 0.3s ease;
  }

  /* Form Controls */
  .form-label {
    font-weight: 600;
    color: var(--bs-primary-dark);
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
  }

  .input-group {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .input-group:focus-within {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.2rem rgba(20, 144, 4, 0.1);
  }

  .input-group-text {
    background: #f8f9fa;
    border: none;
    color: var(--bs-primary);
    min-width: 40px;
    justify-content: center;
  }

  .form-control,
  .form-select {
    border: none;
    padding: 0.5rem 0.75rem;
    font-size: 0.9rem;
    background: white;
  }

  .form-control:focus,
  .form-select:focus {
    box-shadow: none;
    outline: none;
    background: white;
  }

  /* Buttons */
  .btn-primary {
    background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
    color: white;
    border: none;
    padding: 0.5rem 1.5rem;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
  }

  .btn-primary:hover {
    background: linear-gradient(135deg, var(--bs-primary-dark) 0%, var(--bs-primary) 100%);
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 0.75rem rgba(20, 144, 4, 0.3);
  }

  .btn-outline-primary {
    border: 1px solid var(--bs-primary);
    color: var(--bs-primary);
    background: white;
    padding: 0.5rem 1.5rem;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 0.5rem;
  }

  .btn-outline-primary:hover {
    background: var(--bs-primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 0.75rem rgba(20, 144, 4, 0.2);
  }

  /* Question Box */
  .question-box {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
    border-left: 4px solid var(--bs-primary);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
  }

  .question-box:hover {
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
  }

  .question-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    background: var(--bs-primary);
    color: white;
    border-radius: 50%;
    margin-right: 0.5rem;
    font-weight: 600;
    font-size: 0.75rem;
  }

  /* Terms Box */
  .terms-box {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem;
    margin: 1rem 0;
    border: 1px solid #e9ecef;
  }

  /* Password Strength */
  .password-strength {
    margin-top: 0.5rem;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
    border-left: 3px solid var(--bs-primary);
  }

  .strength-bar {
    height: 4px;
    background: #dee2e6;
    border-radius: 2px;
    overflow: hidden;
    margin-top: 0.25rem;
  }

  .strength-fill {
    height: 100%;
    width: 0;
    border-radius: 2px;
    transition: width 0.3s ease;
  }

  /* Alerts */
  .alert-patriotic-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    border-left: 4px solid var(--accent-green);
    margin-bottom: 1rem;
  }

  .alert-patriotic-error {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    border-left: 4px solid #dc3545;
    margin-bottom: 1rem;
  }

  /* Form Sections */
  .form-section {
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  .form-content {
    flex: 1;
    overflow-y: auto;
    padding-right: 0.5rem;
    margin-bottom: 1rem;
  }

  .form-content::-webkit-scrollbar {
    width: 4px;
  }

  .form-content::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  .form-content::-webkit-scrollbar-thumb {
    background: var(--bs-primary);
    border-radius: 4px;
  }

  .button-group {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
  }

  /* Review Info */
  .review-info {
    background: white;
    border-radius: 0.5rem;
    padding: 0.75rem;
    border: 1px solid #e9ecef;
  }

  /* Links */
  .link-patriotic {
    color: var(--bs-primary);
    text-decoration: none;
    font-weight: 600;
  }

  .link-patriotic:hover {
    color: var(--bs-primary-dark);
    text-decoration: underline;
  }

  /* Animation */
  .form-section {
    animation: fadeIn 0.3s ease;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Responsive */
  @media (max-width: 768px) {
    body {
      padding: 0.5rem;
    }

    .register-container {
      height: 95vh;
    }

    .row-custom {
      flex-direction: column;
    }

    .left-container,
    .right-container {
      width: 100%;
    }

    .left-container {
      padding: 1.5rem;
    }
  }
</style>

<div class="register-container">
  <div class="row-custom">
    <!-- Left Container -->
    <div class="left-container">
      <div class="text-center">
        <div class="logo-circle">
          <img src="assets/bfcloans.png" alt="BUENAMENTE Lending Firm Logo" class="img-fluid">
        </div>

        <h4 class="text-white mb-1 fw-bold">BUENAMENTE</h4>
        <p class="text-white small mb-3 opacity-75">Staff Registration Portal</p>

        <div class="mt-4">
          <div class="feature-item">
            <i class="bi bi-shield-check feature-icon"></i>
            <span class="text-white small">Secure Registration Process</span>
          </div>
          <div class="feature-item">
            <i class="bi bi-person-check feature-icon"></i>
            <span class="text-white small">Staff Account Creation</span>
          </div>
          <div class="feature-item">
            <i class="bi bi-lock feature-icon"></i>
            <span class="text-white small">256-bit SSL Encryption</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Container -->
    <div class="right-container">
      <div class="text-center mb-3">
        <h4 class="fw-bold mb-1" style="color: var(--bs-primary-dark);">Create Account</h4>
        <p class="text-muted small">Register for staff access</p>
      </div>

      <!-- Progress Indicator -->
      <div class="mb-3">
        <div class="progress">
          <div class="progress-bar" id="progressBar" style="width: 33%;"></div>
        </div>
        <div class="progress-steps">
          <div class="progress-step">
            <div class="step-number active" id="step1">1</div>
            <small class="step-label active-label">Personal</small>
          </div>
          <div class="progress-step">
            <div class="step-number" id="step2">2</div>
            <small class="step-label">Security</small>
          </div>
          <div class="progress-step">
            <div class="step-number" id="step3">3</div>
            <small class="step-label">Review</small>
          </div>
        </div>
      </div>

      <!-- Alerts -->
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-patriotic-success d-flex align-items-center gap-2">
          <i class="bi bi-check-circle-fill"></i>
          <span class="small"><?php echo htmlspecialchars($_SESSION['success']);
                              unset($_SESSION['success']); ?></span>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-patriotic-error d-flex align-items-center gap-2">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <span class="small"><?php echo htmlspecialchars($_SESSION['error']);
                              unset($_SESSION['error']); ?></span>
        </div>
      <?php endif; ?>

      <!-- Registration Form -->
      <form action="index.php?action=register" method="POST" id="registerForm" style="height: calc(100% - 120px);">
        <!-- Step 1: Personal Details -->
        <div class="form-section active" id="step1Section">
          <div class="form-content">
            <div class="mb-3">
              <h5 class="fw-semibold mb-1" style="color: var(--bs-primary-dark);">
                <i class="bi bi-person-badge me-1"></i>Personal Information
              </h5>
              <p class="text-muted small">Provide your personal details for staff account creation</p>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">First Name <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                  <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($old['first_name'] ?? ''); ?>" placeholder="Enter first name" required>
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                  <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($old['last_name'] ?? ''); ?>" placeholder="Enter last name" required>
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label">Company Email <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                  <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" placeholder="example@gmail.com" required>
                </div>
                <small class="text-muted d-block mt-1"><i class="bi bi-info-circle me-1"></i>Must use company email</small>
              </div>

              <div class="col-md-6">
                <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                  <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>" placeholder="Enter contact number" required>
                </div>
              </div>

              <div class="col-12">
                <label class="form-label">Staff Role <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-briefcase-fill"></i></span>
                  <select class="form-select" name="role" required>
                    <option value="" disabled selected>Select your role</option>
                    <option value="admin" <?php echo (($old['role'] ?? '') == 'admin') ? 'selected' : ''; ?>>Administrator</option>
                    <option value="staff" <?php echo (($old['role'] ?? '') == 'staff') ? 'selected' : ''; ?>>Staff Member</option>
                    <option value="cashier" <?php echo (($old['role'] ?? '') == 'cashier') ? 'selected' : ''; ?>>Cashier</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="button-group d-flex justify-content-end">
            <a href="index.php?action=login" class="btn btn-outline-primary me-2">
              <i class="bi bi-arrow-left me-1"></i> Back to Login
            </a>
            <button type="button" class="btn btn-primary" onclick="nextStep()">
              Next Step <i class="bi bi-arrow-right ms-1"></i>
            </button>
          </div>
        </div>

        <!-- Step 2: Security Questions -->
        <div class="form-section" id="step2Section" style="display: none;">
          <div class="form-content">
            <div class="mb-3">
              <h5 class="fw-semibold mb-1" style="color: var(--bs-primary-dark);">
                <i class="bi bi-shield-lock me-1"></i>Security Questions
              </h5>
              <p class="text-muted small">Set up security questions for account recovery</p>
            </div>

            <?php
            $security_questions = $_SESSION['security_questions'] ?? [];
            $old_questions = $old['secret_questions'] ?? [];
            $old_answers = $old['secret_answers'] ?? [];
            ?>

            <?php for ($i = 1; $i <= 5; $i++): ?>
              <?php
              $question = $security_questions[$i - 1] ?? null;
              if ($question):
              ?>
                <div class="question-box">
                  <div class="d-flex align-items-center mb-2">
                    <span class="question-number"><?php echo $i; ?></span>
                    <span class="small fw-semibold" style="color: var(--bs-primary-dark);">Question <?php echo $i; ?></span>
                  </div>

                  <div class="mb-2">
                    <label class="form-label small">Security Question</label>
                    <div class="p-2 bg-white rounded border small">
                      <i class="bi bi-question-circle-fill me-1" style="color: var(--bs-primary);"></i>
                      <span><?php echo htmlspecialchars($question['question']); ?></span>
                    </div>
                    <input type="hidden" name="secret_question_<?php echo $i; ?>" value="<?php echo $question['id']; ?>">
                  </div>

                  <div>
                    <label class="form-label small">Your Answer <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                      <input type="text" class="form-control" name="secret_answer_<?php echo $i; ?>" value="<?php echo htmlspecialchars($old_answers[$i] ?? ''); ?>" placeholder="Enter your answer" required>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            <?php endfor; ?>

            <div class="terms-box">
              <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-triangle-fill me-2" style="color: var(--bs-primary);"></i>
                <span class="fw-semibold small" style="color: var(--bs-primary-dark);">Security Notes:</span>
              </div>
              <ul class="text-muted small mb-0 ps-3">
                <li class="mb-1">Answers are NOT case-sensitive</li>
                <li class="mb-1">Choose questions only you can answer</li>
                <li>Used for password recovery only</li>
              </ul>
            </div>
          </div>

          <div class="button-group d-flex justify-content-between">
            <button type="button" class="btn btn-outline-primary" onclick="prevStep()">
              <i class="bi bi-arrow-left me-1"></i> Previous
            </button>
            <button type="button" class="btn btn-primary" onclick="nextStep()">
              Next Step <i class="bi bi-arrow-right ms-1"></i>
            </button>
          </div>
        </div>

        <!-- Step 3: Review & Password -->
        <div class="form-section" id="step3Section" style="display: none;">
          <div class="form-content">
            <div class="mb-3">
              <h5 class="fw-semibold mb-1" style="color: var(--bs-primary-dark);">
                <i class="bi bi-clipboard-check me-1"></i>Review & Account Setup
              </h5>
              <p class="text-muted small">Review your information and create a secure password</p>
            </div>

            <div class="question-box">
              <h6 class="fw-semibold mb-2" style="color: var(--bs-primary-dark);">
                <i class="bi bi-person-check me-1"></i>Information Review
              </h6>
              <div class="review-info">
                <div class="row g-2">
                  <div class="col-6">
                    <small class="text-muted d-block">Full Name</small>
                    <strong><span id="reviewName"></span></strong>
                  </div>
                  <div class="col-6">
                    <small class="text-muted d-block">Email Address</small>
                    <strong><span id="reviewEmail"></span></strong>
                  </div>
                  <div class="col-6">
                    <small class="text-muted d-block">Contact Number</small>
                    <strong><span id="reviewPhone"></span></strong>
                  </div>
                  <div class="col-6">
                    <small class="text-muted d-block">Staff Role</small>
                    <strong><span id="reviewRole"></span></strong>
                  </div>
                </div>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Create Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                  <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" placeholder="Enter password" required minlength="8">
                  <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)" style="border: none; background: #f8f9fa;">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>

                <div class="password-strength">
                  <div class="d-flex justify-content-between small">
                    <span>Password Strength:</span>
                    <span id="strengthText" class="fw-semibold">Weak</span>
                  </div>
                  <div class="strength-bar">
                    <div class="strength-fill" id="strengthFill"></div>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" autocomplete="new-password" placeholder="Confirm password" required minlength="8">
                  <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password', this)" style="border: none; background: #f8f9fa;">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
                <div id="passwordMatch" class="mt-1 small"></div>
              </div>
            </div>

            <div class="terms-box">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                <label class="form-check-label small" for="terms">
                  I agree to the <a href="#" class="link-patriotic">Terms of Service</a> and
                  <a href="#" class="link-patriotic">Privacy Policy</a>
                </label>
              </div>
            </div>
          </div>

          <div class="button-group d-flex justify-content-between">
            <button type="button" class="btn btn-outline-primary" onclick="prevStep()">
              <i class="bi bi-arrow-left me-1"></i> Previous
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-person-plus me-1"></i> Create Account
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  let currentStep = 1;

  function showStep(step) {
    // Hide all sections
    document.querySelectorAll('.form-section').forEach(section => {
      section.style.display = 'none';
    });

    // Show current section
    document.getElementById(`step${step}Section`).style.display = 'flex';

    // Update progress steps
    document.querySelectorAll('.step-number').forEach((el, idx) => {
      const stepNum = idx + 1;
      el.classList.remove('active', 'completed');

      if (stepNum === step) {
        el.classList.add('active');
      } else if (stepNum < step) {
        el.classList.add('completed');
      }
    });

    // Update progress bar
    const progressPercentage = ((step - 1) / 2) * 100;
    document.getElementById('progressBar').style.width = `${progressPercentage}%`;

    // Update step labels
    document.querySelectorAll('.progress-step small').forEach((el, idx) => {
      const stepNum = idx + 1;
      if (stepNum === step) {
        el.classList.add('active-label');
        el.classList.remove('text-muted');
      } else {
        el.classList.remove('active-label');
        el.classList.add('text-muted');
      }
    });

    if (step === 3) updateReview();
  }

  function nextStep() {
    if (validateStep(currentStep)) {
      currentStep++;
      showStep(currentStep);
    }
  }

  function prevStep() {
    currentStep--;
    showStep(currentStep);
  }

  function validateStep(step) {
    if (step === 1) {
      const required = ['first_name', 'last_name', 'email', 'phone', 'role'];
      for (let field of required) {
        const element = document.querySelector(`[name="${field}"]`);
        if (!element || !element.value.trim()) {
          alert(`Please fill in ${field.replace('_', ' ')}`);
          element?.focus();
          return false;
        }
      }

      const email = document.querySelector('[name="email"]').value;
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        alert('Please enter a valid email address');
        return false;
      }
    }

    if (step === 2) {
      for (let i = 1; i <= 5; i++) {
        const answer = document.querySelector(`[name="secret_answer_${i}"]`);
        if (answer && !answer.value.trim()) {
          alert(`Please provide an answer for security question ${i}`);
          answer.focus();
          return false;
        }
      }
    }

    return true;
  }

  function updateReview() {
    const firstName = document.querySelector('[name="first_name"]').value;
    const lastName = document.querySelector('[name="last_name"]').value;
    const email = document.querySelector('[name="email"]').value;
    const phone = document.querySelector('[name="phone"]').value;
    const roleSelect = document.querySelector('[name="role"]');
    const role = roleSelect.options[roleSelect.selectedIndex]?.text || '';

    document.getElementById('reviewName').textContent = `${firstName} ${lastName}`;
    document.getElementById('reviewEmail').textContent = email;
    document.getElementById('reviewPhone').textContent = phone;
    document.getElementById('reviewRole').textContent = role;
  }

  function togglePassword(fieldId, button) {
    const field = document.getElementById(fieldId);
    const icon = button.querySelector('i');

    if (field.type === 'password') {
      field.type = 'text';
      icon.className = 'bi bi-eye-slash';
    } else {
      field.type = 'password';
      icon.className = 'bi bi-eye';
    }
  }

  // Password strength indicator
  document.getElementById('password')?.addEventListener('input', function() {
    const password = this.value;
    let strength = 0;

    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;

    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');

    strengthFill.style.width = `${strength * 20}%`;

    const colors = ['#dc3545', '#ffc107', '#4CAF50', '#28a745', '#149004cb'];
    const texts = ['Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];

    strengthFill.style.backgroundColor = colors[Math.min(strength, 4)];
    strengthText.textContent = texts[Math.min(strength, 4)];
    strengthText.style.color = colors[Math.min(strength, 4)];
  });

  // Password match validation
  document.getElementById('confirm_password')?.addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const matchDiv = document.getElementById('passwordMatch');

    if (this.value === password) {
      matchDiv.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Passwords match</span>';
    } else {
      matchDiv.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>Passwords do not match</span>';
    }
  });

  // Initialize
  showStep(1);
</script>
