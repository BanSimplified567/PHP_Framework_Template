<?php
// app/Controllers/Auth/AuthController.php
require_once __DIR__ . '/../Path/BaseController.php';

class AuthController extends BaseController
{
  // ==============================
  // LOGIN FUNCTIONALITY
  // ==============================
  public function login()
  {
    // If user is already logged in, redirect to dashboard
    if (isset($_SESSION['user_id'])) {
      $this->redirectToDashboard();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->handleLogin();
    } else {
      $this->showLoginForm();
    }
  }

  public function showLoginForm()
  {
    require_once '../app/Views/auth/login.php';
  }

  private function handleLogin()
  {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($email) || empty($password)) {
      $_SESSION['error'] = "Please enter both email and password.";
      $this->redirect('login');
    }

    try {
      // Get user with account lock status
      $stmt = $this->pdo->prepare("
                SELECT * FROM users
                WHERE email = ? AND is_active = 1
            ");
      $stmt->execute([$email]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      // Check if user exists and is not locked
      if (!$user) {
        $this->handleFailedLogin($email, "User not found");
        return;
      }

      // Check if account is locked using database time for consistency
      if ($user['locked_until']) {
        try {
          $stmt = $this->pdo->prepare("SELECT NOW() as current_time, ? as locked_until");
          $stmt->execute([$user['locked_until']]);
          $timeCheck = $stmt->fetch(PDO::FETCH_ASSOC);

          // Check if lock has expired
          if (strtotime($timeCheck['locked_until']) <= strtotime($timeCheck['current_time'])) {
            // Lock has expired - reset failed attempts and clear lock
            $this->resetFailedAttempts($user['id']);

            // Refresh user data to get updated status
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user['id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
          } else {
            // Account is still locked
            $unlockTime = date('M j, Y H:i A', strtotime($timeCheck['locked_until']));
            $_SESSION['error'] = "Account is temporarily locked. Please try again after $unlockTime.";
            $this->redirect('login');
            return;
          }
        } catch (PDOException $e) {
          // Fallback to PHP time if database query fails
          if (strtotime($user['locked_until']) <= time()) {
            // Lock has expired - reset failed attempts and clear lock
            $this->resetFailedAttempts($user['id']);

            // Refresh user data
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user['id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
          } else {
            // Account is still locked
            $unlockTime = date('M j, Y H:i A', strtotime($user['locked_until']));
            $_SESSION['error'] = "Account is temporarily locked. Please try again after $unlockTime.";
            $this->redirect('login');
            return;
          }
        }
      }

      // Verify password
      if ($user && password_verify($password, $user['password'])) {
        // Successful login - reset failed attempts and lock
        $this->resetFailedAttempts($user['id']);

        // Security: Regenerate session ID
        session_regenerate_id(true);

        // Update last_login timestamp
        $this->updateLastLogin($user['id']);

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['first_name'] = $user['first_name'] ?? '';
        $_SESSION['last_name'] = $user['last_name'] ?? '';
        $_SESSION['phone'] = $user['phone'] ?? '';

        // Log the successful login
        $this->logAction("User logged in successfully");

        // Clear any previous error messages
        unset($_SESSION['error']);

        // Set success message
        $_SESSION['success'] = 'Welcome back, ' . htmlspecialchars($user['first_name']) . '!';

        // Redirect based on role
        $this->redirectToDashboard();
      } else {
        // Failed login - increment attempts and potentially lock account
        $this->handleFailedLogin($email, "Invalid password", $user['id'], $user['failed_attempts']);
      }
    } catch (PDOException $e) {
      error_log("Login error: " . $e->getMessage());
      $_SESSION['error'] = "System error. Please try again.";
      $this->redirect('login');
    }
  }

  // ==============================
  // REGISTRATION FUNCTIONALITY WITH SECRET QUESTIONS
  // ==============================
  public function register()
  {
    // If user is already logged in, redirect to dashboard
    if (isset($_SESSION['user_id'])) {
      $this->redirectToDashboard();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->handleRegistration();
    } else {
      $this->showRegistrationForm();
    }
  }

  private function handleRegistration()
  {
    $errors = [];

    // Collect form data
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $role       = trim($_POST['role'] ?? '');
    $password   = $_POST['password'] ?? '';
    $confirm    = $_POST['confirm_password'] ?? '';
    $terms      = isset($_POST['terms']);

    // Get security questions and answers
    $secret_questions = [];
    $secret_answers = [];
    for ($i = 1; $i <= 5; $i++) {
      $secret_questions[$i] = $_POST['secret_question_' . $i] ?? '';
      $secret_answers[$i] = trim($_POST['secret_answer_' . $i] ?? '');
    }

    // Generate username from email
    $username = $email;

    // Validation
    if (empty($first_name)) {
      $errors[] = "First name is required.";
    } elseif (strlen($first_name) < 2) {
      $errors[] = "First name must be at least 2 characters.";
    }

    if (empty($last_name)) {
      $errors[] = "Last name is required.";
    } elseif (strlen($last_name) < 2) {
      $errors[] = "Last name must be at least 2 characters.";
    }

    if (empty($email)) {
      $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Invalid email format.";
    }

    if (empty($role)) {
      $errors[] = "Role selection is required.";
    } elseif (!in_array($role, ['admin', 'staff', 'user'])) {
      $errors[] = "Invalid role selected.";
    }

    // Validate security questions
    $used_questions = [];
    for ($i = 1; $i <= 5; $i++) {
      if (empty($secret_questions[$i])) {
        $errors[] = "Security Question $i is required.";
      } elseif (empty($secret_answers[$i])) {
        $errors[] = "Answer for Question $i is required.";
      } elseif (in_array($secret_questions[$i], $used_questions)) {
        $errors[] = "Please select different security questions.";
      } else {
        $used_questions[] = $secret_questions[$i];
      }
    }

    if (!$terms) {
      $errors[] = "You must agree to the Terms and Conditions.";
    }

    // Password validation
    if (empty($password)) {
      $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
      $errors[] = "Password must be at least 8 characters.";
    }

    if ($password !== $confirm) {
      $errors[] = "Passwords do not match.";
    }

    // Check if email already exists
    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
          $errors[] = "Email is already registered.";
        }
      } catch (PDOException $e) {
        error_log("Registration email check error: " . $e->getMessage());
        $errors[] = "System error. Please try again.";
      }
    }

    // Register user if no errors
    if (empty($errors)) {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      try {
        // Start transaction
        $this->pdo->beginTransaction();

        // Insert into users table (activate immediately)
        $stmt = $this->pdo->prepare("
                    INSERT INTO users
                    (username, email, password, first_name, last_name, phone, role, is_active, created_at, secret_question_setup)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW(), 1)
                ");

        $stmt->execute([
          $username,
          $email,
          $hashed_password,
          $first_name,
          $last_name,
          $phone,
          $role
        ]);

        $userId = $this->pdo->lastInsertId();

        // Insert security questions and answers
        for ($i = 1; $i <= 5; $i++) {
          $stmt = $this->pdo->prepare("
                        INSERT INTO user_secret_questions
                        (user_id, question_id, answer, created_at)
                        VALUES (?, ?, ?, NOW())
                    ");
          $stmt->execute([
            $userId,
            $secret_questions[$i],
            $secret_answers[$i]
          ]);
        }

        // Log the registration
        $this->logAction("New staff registration with security questions: " . $email . " as " . $role);

        // Commit transaction
        $this->pdo->commit();

        // Clear any old form data
        unset($_SESSION['old']);

        // Send notification to admin
        $this->notifyAdminOfNewRegistration($first_name, $last_name, $email, $role);

        // Set success message
        $_SESSION['success'] = "Account created successfully! You can now log in with your credentials.";
        $this->redirect('login');
      } catch (PDOException $e) {
        $this->pdo->rollBack();
        error_log("Registration error: " . $e->getMessage());
        $errors[] = "Registration failed. Please try again.";
      }
    }

    // If errors, store them for display
    if (!empty($errors)) {
      $_SESSION['error'] = implode("<br>", $errors);

      // Preserve form data including security questions
      $_SESSION['old'] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone' => $phone,
        'role' => $role,
        'secret_questions' => $secret_questions,
        'secret_answers' => $secret_answers
      ];

      $this->redirect('register');
    }
  }

  // ==============================
  // FORGOT PASSWORD FUNCTIONALITY - STEP 1: DISPLAY 5 QUESTIONS
  // ==============================
  public function forgotPassword()
  {
    // If user is already logged in, redirect to dashboard
    if (isset($_SESSION['user_id'])) {
      $this->redirectToDashboard();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->handleSecurityAnswersVerification();
    } else {
      $this->showSecurityQuestionsForm();
    }
  }

  /**
   * Show the 5 security questions form (Step 1)
   */
  private function showSecurityQuestionsForm()
  {
    try {
      // Get 5 security questions from the database
      $stmt = $this->pdo->prepare("
                SELECT id, question
                FROM secret_questions
                WHERE is_active = 1
                ORDER BY id
                LIMIT 5
            ");
      $stmt->execute();
      $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Store questions in session for the view
      $_SESSION['security_questions'] = $questions;
      $_SESSION['reset_step'] = 1; // Step 1: Answer questions

      require_once '../app/Views/auth/forgot-password.php';
    } catch (PDOException $e) {
      error_log("Show security questions error: " . $e->getMessage());
      $_SESSION['error'] = "System error. Please try again.";
      $this->redirect('login');
    }
  }

  /**
   * Handle security answers verification
   * Find user by matching all 5 answers
   */
  private function handleSecurityAnswersVerification()
  {
    // Get all posted answers
    $answers = [];
    $question_ids = [];

    // Collect answers and question IDs from form
    for ($i = 1; $i <= 5; $i++) {
      if (isset($_POST['answer_' . $i]) && !empty(trim($_POST['answer_' . $i]))) {
        $answers[$i] = trim($_POST['answer_' . $i]);
        $question_ids[$i] = (int)($_POST['question_id_' . $i] ?? 0);
      } else {
        $_SESSION['error'] = "Please answer all 5 security questions.";
        $this->redirect('forgot-password');
        return;
      }
    }

    try {
      // Find user by matching all 5 answers
      $user = $this->findUserByAllSecurityAnswers($answers, $question_ids);

      if ($user) {
        // User verified - store user info
        $_SESSION['reset_user_id'] = $user['id'];
        $_SESSION['reset_email'] = $user['email'];
        $_SESSION['reset_first_name'] = $user['first_name'];
        $_SESSION['reset_last_name'] = $user['last_name'];
        $_SESSION['questions_verified'] = true;
        $_SESSION['reset_step'] = 2; // Step 2: Choose reset option

        // Log successful verification
        $this->logAction("Security questions verified successfully for user: " . $user['email']);

        // Redirect to reset options page
        $this->redirect('reset-options');
      } else {
        $_SESSION['error'] = "Security answers do not match any user in our system. Please try again.";
        $this->redirect('forgot-password');
      }
    } catch (PDOException $e) {
      error_log("Security answers verification error: " . $e->getMessage());
      $_SESSION['error'] = "System error. Please try again.";
      $this->redirect('forgot-password');
    }
  }

  /**
   * Find user by matching all 5 security answers
   */
  private function findUserByAllSecurityAnswers($answers, $question_ids)
  {
    // Build a query to find users who have all 5 answers matching
    $sql = "
            SELECT u.id, u.email, u.first_name, u.last_name
            FROM users u
            WHERE u.is_active = 1
            AND u.secret_question_setup = 1
        ";

    $params = [];

    // Join conditions for each answer
    for ($i = 1; $i <= 5; $i++) {
      $sql .= "
                AND EXISTS (
                    SELECT 1 FROM user_secret_questions usq{$i}
                    WHERE usq{$i}.user_id = u.id
                    AND usq{$i}.question_id = :question_id_{$i}
                    AND LOWER(usq{$i}.answer) = LOWER(:answer_{$i})
                )
            ";
      $params[':question_id_' . $i] = $question_ids[$i];
      $params[':answer_' . $i] = $answers[$i];
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    // Return the first user found (if any)
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // ==============================
  // RESET OPTIONS - STEP 2: CHOOSE WHAT TO RESET
  // ==============================
  public function resetOptions()
  {
    // If user is already logged in, redirect to dashboard
    if (isset($_SESSION['user_id'])) {
      $this->redirectToDashboard();
    }

    // Check if coming from security questions verification
    if (!isset($_SESSION['questions_verified']) || !$_SESSION['questions_verified']) {
      $_SESSION['error'] = "Please verify your identity first.";
      $this->redirect('forgot-password');
      return;
    }

    $user_id = $_SESSION['reset_user_id'] ?? 0;
    if (!$user_id) {
      $_SESSION['error'] = "Session expired. Please start over.";
      $this->redirect('forgot-password');
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->handleResetOptions();
    } else {
      $_SESSION['reset_step'] = 2;
      require_once '../app/Views/auth/reset-options.php';
    }
  }

  private function handleResetOptions()
  {
    $reset_option = $_POST['reset_option'] ?? '';

    if (empty($reset_option)) {
      $_SESSION['error'] = "Please select what you want to reset.";
      $this->redirect('reset-options');
      return;
    }

    if ($reset_option === 'password') {
      $_SESSION['reset_step'] = 3; // Step 3: Reset password
      $this->redirect('reset-password');
    } elseif ($reset_option === 'email') {
      $_SESSION['reset_step'] = 4; // Step 4: Reset email
      $this->redirect('reset-email');
    } else {
      $_SESSION['error'] = "Invalid option selected.";
      $this->redirect('reset-options');
    }
  }

  // ==============================
  // RESET PASSWORD - STEP 3
  // ==============================
  public function resetPassword()
  {
    // If user is already logged in, redirect to dashboard
    if (isset($_SESSION['user_id'])) {
      $this->redirectToDashboard();
    }

    // Check if coming from reset options
    if (!isset($_SESSION['questions_verified']) || !$_SESSION['questions_verified']) {
      $_SESSION['error'] = "Please verify your identity first.";
      $this->redirect('forgot-password');
      return;
    }

    $user_id = $_SESSION['reset_user_id'] ?? 0;
    if (!$user_id) {
      $_SESSION['error'] = "Session expired. Please start over.";
      $this->redirect('forgot-password');
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->handleResetPassword();
    } else {
      $_SESSION['reset_step'] = 3;
      require_once '../app/Views/auth/forgot-password.php';
    }
  }

  private function handleResetPassword()
  {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $user_id = $_SESSION['reset_user_id'] ?? 0;

    // Validate input
    if (empty($password) || empty($confirm)) {
      $_SESSION['error'] = "Please enter both password fields.";
      $this->redirect('reset-password');
      return;
    }

    if ($password !== $confirm) {
      $_SESSION['error'] = "Passwords do not match.";
      $this->redirect('reset-password');
      return;
    }

    if (strlen($password) < 8) {
      $_SESSION['error'] = "Password must be at least 8 characters.";
      $this->redirect('reset-password');
      return;
    }

    // Additional password strength checks
    if (!preg_match('/[A-Z]/', $password)) {
      $_SESSION['error'] = "Password must contain at least one uppercase letter.";
      $this->redirect('reset-password');
      return;
    }

    if (!preg_match('/[a-z]/', $password)) {
      $_SESSION['error'] = "Password must contain at least one lowercase letter.";
      $this->redirect('reset-password');
      return;
    }

    if (!preg_match('/[0-9]/', $password)) {
      $_SESSION['error'] = "Password must contain at least one number.";
      $this->redirect('reset-password');
      return;
    }

    if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
      $_SESSION['error'] = "Password must contain at least one special character.";
      $this->redirect('reset-password');
      return;
    }

    try {
      // Update password
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      $stmt = $this->pdo->prepare("
                UPDATE users
                SET password = ?, reset_token = NULL, reset_expires = NULL
                WHERE id = ?
            ");
      $stmt->execute([$hashed_password, $user_id]);

      // Get user info for logging
      $user_email = $_SESSION['reset_email'] ?? 'unknown';

      // Log the password reset
      $this->logAction("Password reset successfully via security questions for " . $user_email);

      // Clear session
      $this->clearResetSession();

      $_SESSION['success'] = "Password has been reset successfully. You can now login with your new password.";
      $this->redirect('login');
    } catch (PDOException $e) {
      error_log("Reset password error: " . $e->getMessage());
      $_SESSION['error'] = "System error. Please try again.";
      $this->redirect('reset-password');
    }
  }

  // ==============================
  // RESET EMAIL - STEP 4
  // ==============================
  public function resetEmail()
  {
    // If user is already logged in, redirect to dashboard
    if (isset($_SESSION['user_id'])) {
      $this->redirectToDashboard();
    }

    // Check if coming from reset options
    if (!isset($_SESSION['questions_verified']) || !$_SESSION['questions_verified']) {
      $_SESSION['error'] = "Please verify your identity first.";
      $this->redirect('forgot-password');
      return;
    }

    $user_id = $_SESSION['reset_user_id'] ?? 0;
    if (!$user_id) {
      $_SESSION['error'] = "Session expired. Please start over.";
      $this->redirect('forgot-password');
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->handleResetEmail();
    } else {
      $_SESSION['reset_step'] = 4;
      require_once '../app/Views/auth/reset-email.php';
    }
  }

  private function handleResetEmail()
  {
    $new_email = trim($_POST['new_email'] ?? '');
    $confirm_email = trim($_POST['confirm_email'] ?? '');
    $user_id = $_SESSION['reset_user_id'] ?? 0;

    // Validate input
    if (empty($new_email) || empty($confirm_email)) {
      $_SESSION['error'] = "Please enter both email fields.";
      $this->redirect('reset-email');
      return;
    }

    if ($new_email !== $confirm_email) {
      $_SESSION['error'] = "Email addresses do not match.";
      $this->redirect('reset-email');
      return;
    }

    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error'] = "Please enter a valid email address.";
      $this->redirect('reset-email');
      return;
    }

    try {
      // Check if email already exists
      $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
      $stmt->execute([$new_email, $user_id]);
      if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "This email address is already in use by another account.";
        $this->redirect('reset-email');
        return;
      }

      // Update email
      $stmt = $this->pdo->prepare("
                UPDATE users
                SET email = ?, username = ?
                WHERE id = ?
            ");
      $stmt->execute([$new_email, $new_email, $user_id]);

      // Get old email for logging
      $old_email = $_SESSION['reset_email'] ?? 'unknown';

      // Log the email reset
      $this->logAction("Email reset successfully via security questions from {$old_email} to {$new_email} for user ID: {$user_id}");

      // Clear session
      $this->clearResetSession();

      $_SESSION['success'] = "Email has been updated successfully. You can now login with your new email.";
      $this->redirect('login');
    } catch (PDOException $e) {
      error_log("Reset email error: " . $e->getMessage());
      $_SESSION['error'] = "System error. Please try again.";
      $this->redirect('reset-email');
    }
  }

  private function clearResetSession()
  {
    unset($_SESSION['reset_user_id']);
    unset($_SESSION['reset_email']);
    unset($_SESSION['reset_first_name']);
    unset($_SESSION['reset_last_name']);
    unset($_SESSION['reset_method']);
    unset($_SESSION['reset_step']);
    unset($_SESSION['questions_verified']);
    unset($_SESSION['security_questions']);
    unset($_SESSION['old']);
  }

  // ==============================
  // HELPER METHODS
  // ==============================
  public function showRegistrationForm()
  {
    // Get 5 specific security questions for direct display
    try {
      $stmt = $this->pdo->prepare("
                SELECT id, question
                FROM secret_questions
                WHERE is_active = 1
                ORDER BY id
                LIMIT 5
            ");
      $stmt->execute();
      $security_questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Store in session for use in view
      $_SESSION['security_questions'] = $security_questions;
    } catch (PDOException $e) {
      error_log("Get security questions error: " . $e->getMessage());
      $_SESSION['security_questions'] = [];
    }

    require_once '../app/Views/auth/register.php';
  }

  private function notifyAdminOfNewRegistration($firstName, $lastName, $email, $role)
  {
    try {
      // Get all admin users
      $stmt = $this->pdo->prepare("SELECT email FROM users WHERE role = 'admin' AND is_active = 1");
      $stmt->execute();
      $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // In a real system, send email notifications here
      // For now, we'll just log it
      $message = "New registration: $firstName $lastName ($email) as $role";

      foreach ($admins as $admin) {
        $this->logAction("Notification sent to admin " . $admin['email'] . ": " . $message);
      }

      // Also create a system notification
      $this->createSystemNotification("New staff registration pending review: $firstName $lastName");
    } catch (PDOException $e) {
      error_log("Failed to notify admin: " . $e->getMessage());
    }
  }

  private function createSystemNotification($message)
  {
    try {
      $stmt = $this->pdo->prepare("INSERT INTO notifications (title, message, type, created_at) VALUES (?, ?, ?, NOW())");
      $stmt->execute([
        'New Registration',
        $message,
        'system'
      ]);
    } catch (PDOException $e) {
      error_log("Failed to create notification: " . $e->getMessage());
    }
  }

  private function redirectToDashboard()
  {
    // Ensure role is set in session
    if (!isset($_SESSION['role'])) {
      $this->redirect('login');
    }

    // Redirect based on role
    if ($_SESSION['role'] == 'admin') {
      $this->redirect('admin/dashboard');
    } elseif ($_SESSION['role'] == 'staff') {
      $this->redirect('staff/dashboard');
    } elseif ($_SESSION['role'] == 'user') {
      $this->redirect('user/dashboard');
    } else {
      // Fallback for any other roles
      $_SESSION['error'] = "Unauthorized access. Please contact administrator.";
      $this->redirect('login');
    }
  }

  public function logout()
  {
    // Get user info for logging before clearing session
    $user_id = $_SESSION['user_id'] ?? null;
    $user_email = $_SESSION['email'] ?? 'unknown';

    // Update last_logout timestamp if user is logged in
    if ($user_id) {
      $this->updateLastLogout($user_id);
    }

    // Log the logout
    $this->logAction("User logged out");

    // Clear all session variables
    $_SESSION = [];

    // Delete the session cookie
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
      );
    }

    // Destroy the session
    session_destroy();

    // Redirect to login using BaseController's redirect method
    $this->redirect('login');
  }

  // ==============================
  // LOGIN/LOGOUT TRACKING METHODS
  // ==============================

  /**
   * Update last_login timestamp for user
   */
  private function updateLastLogin($userId)
  {
    try {
      $stmt = $this->pdo->prepare("
                UPDATE users
                SET last_login = NOW()
                WHERE id = ?
            ");
      $stmt->execute([$userId]);
    } catch (PDOException $e) {
      error_log("Failed to update last_login for user $userId: " . $e->getMessage());
    }
  }

  /**
   * Update last_logout timestamp for user
   */
  private function updateLastLogout($userId)
  {
    try {
      $stmt = $this->pdo->prepare("
                UPDATE users
                SET last_logout = NOW()
                WHERE id = ?
            ");
      $stmt->execute([$userId]);
    } catch (PDOException $e) {
      error_log("Failed to update last_logout for user $userId: " . $e->getMessage());
    }
  }

  // ==============================
  // ACCOUNT SECURITY METHODS
  // ==============================

  /**
   * Handle failed login attempts with account locking
   */
  private function handleFailedLogin($email, $reason, $userId = null, $currentAttempts = 0)
  {
    try {
      if ($userId) {
        // User exists - increment failed attempts
        $newAttempts = $currentAttempts + 1;
        $lockedUntil = null;
        $lockMessage = '';

        // Lock account after 5 failed attempts for 5 minutes using database time
        if ($newAttempts >= 5) {
          // Use database time for consistent timezone handling
          $stmt = $this->pdo->prepare("SELECT DATE_ADD(NOW(), INTERVAL 5 MINUTE) as locked_until");
          $stmt->execute();
          $timeResult = $stmt->fetch(PDO::FETCH_ASSOC);
          $lockedUntil = $timeResult['locked_until'];
          $lockMessage = " Account locked for 5 minutes.";
        }

        $stmt = $this->pdo->prepare("
                  UPDATE users
                  SET failed_attempts = ?, locked_until = ?
                  WHERE id = ?
              ");
        $stmt->execute([$newAttempts, $lockedUntil, $userId]);

        // Log the failed attempt
        $this->logAction("Failed login attempt: $reason. Attempts: $newAttempts", 'users', $userId);

        // Set appropriate error message
        if ($newAttempts >= 5) {
          $_SESSION['error'] = "Too many failed login attempts.$lockMessage Please try again in 5 minutes.";
        } else {
          $remaining = 5 - $newAttempts;
          $_SESSION['error'] = "Invalid email or password. $remaining attempt(s) remaining.";
        }
      } else {
        // User doesn't exist - don't reveal this for security
        $this->logAction("Failed login attempt: $reason for email $email");
        $_SESSION['error'] = "Invalid email or password.";
      }
    } catch (PDOException $e) {
      error_log("Failed to handle failed login: " . $e->getMessage());
      $_SESSION['error'] = "System error. Please try again.";
    }

    $this->redirect('login');
  }

  /**
   * Reset failed attempts and unlock account on successful login
   */
  private function resetFailedAttempts($userId)
  {
    try {
      $stmt = $this->pdo->prepare("
                UPDATE users
                SET failed_attempts = 0, locked_until = NULL
                WHERE id = ?
            ");
      $stmt->execute([$userId]);
    } catch (PDOException $e) {
      error_log("Failed to reset failed attempts for user $userId: " . $e->getMessage());
    }
  }
}
