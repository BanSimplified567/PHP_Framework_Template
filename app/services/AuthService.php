<?php
// app/Services/AuthService.php
if (!class_exists('User', false) && file_exists(__DIR__ . '/../models/User.php')) {
  require_once __DIR__ . '/../models/User.php';
}
if (!class_exists('MailService', false) && file_exists(__DIR__ . '/MailService.php')) {
  require_once __DIR__ . '/MailService.php';
}

class AuthService
{
  protected $pdo;
  protected $userModel;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
    $this->userModel = new User($pdo);
  }


  public function login($email, $password)
  {
    if (empty($email) || empty($password)) {
      return ['success' => false, 'error' => 'Please enter both email and password.'];
    }

    try {
      $user = $this->userModel->findByEmail($email);

      if (!$user) {
        return ['success' => false, 'error' => 'Invalid email or password.'];
      }

      if ($user['locked_until']) {
        $lockCheck = $this->checkLockStatus($user);
        if ($lockCheck['locked']) {
          return ['success' => false, 'error' => $lockCheck['message']];
        }
        $user = $this->userModel->findById($user['id']);
      }

      if (password_verify($password, $user['password'])) {
        $this->userModel->resetFailedAttempts($user['id']);
        $this->userModel->updateLastLogin($user['id']);

        return [
          'success' => true,
          'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
            'first_name' => $user['first_name'] ?? '',
            'last_name' => $user['last_name'] ?? '',
            'phone' => $user['phone'] ?? ''
          ]
        ];
      }

      $this->handleFailedLogin($user['id'], (int)($user['failed_attempts'] ?? 0));
      return ['success' => false, 'error' => 'Invalid email or password.'];

    } catch (Exception $e) {
      error_log("Login error: " . $e->getMessage());
      return ['success' => false, 'error' => 'System error. Please try again.'];
    }
  }

  private function checkLockStatus($user)
  {
    $lockedUntil = $user['locked_until'];
    if (strtotime($lockedUntil) <= time()) {
      $this->userModel->resetFailedAttempts($user['id']);
      return ['locked' => false];
    }
    $unlockTime = date('M j, Y H:i A', strtotime($lockedUntil));
    return [
      'locked' => true,
      'message' => "Account is temporarily locked. Please try again after $unlockTime."
    ];
  }

  private function handleFailedLogin($userId, $currentAttempts)
  {
    $result = $this->userModel->handleFailedLogin($userId, $currentAttempts);
    if ($result['locked']) {
      $_SESSION['error'] = "Too many failed login attempts. Account locked for 5 minutes. Please try again in 5 minutes.";
    } else {
      $remaining = 5 - $result['attempts'];
      $_SESSION['error'] = "Invalid email or password. $remaining attempt(s) remaining.";
    }
  }

  public function register($data, $secretQuestions, $secretAnswers)
  {
    $errors = $this->validateRegistration($data, $secretQuestions, $secretAnswers);
    if (!empty($errors)) {
      return ['success' => false, 'errors' => $errors, 'old' => $this->getOldInput($data, $secretQuestions, $secretAnswers)];
    }

    $email = trim($data['email'] ?? '');
    $data['email'] = $email;

    if ($this->userModel->emailExists($email)) {
      $errors[] = 'Email is already registered.';
      return ['success' => false, 'errors' => $errors, 'old' => $this->getOldInput($data, $secretQuestions, $secretAnswers)];
    }

    try {
      $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
      $data['password'] = $hashedPassword;
      $data['username'] = $email;

      $userId = $this->userModel->create($data, $secretQuestions, $secretAnswers);

      $this->logAction('New registration: ' . $email . ' as ' . ($data['role'] ?? 'user'));
      $this->notifyAdminOfNewRegistration($data['first_name'] ?? '', $data['last_name'] ?? '', $email, $data['role'] ?? 'user');
      $this->sendRegistrationEmail($email, trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')), $data['role'] ?? 'user');

      return [
        'success' => true,
        'message' => 'Account created successfully! A confirmation email was sent if mail is configured. You can now log in with your credentials.'
      ];
    } catch (Exception $e) {
      error_log("Registration error: " . $e->getMessage());
      return ['success' => false, 'errors' => ['Registration failed. Please try again.']];
    }
  }

  private function validateRegistration($data, $secretQuestions, $secretAnswers)
  {
    $errors = [];

    if (empty($data['first_name'])) {
      $errors[] = 'First name is required.';
    } elseif (strlen($data['first_name']) < 2) {
      $errors[] = 'First name must be at least 2 characters.';
    }

    if (empty($data['last_name'])) {
      $errors[] = 'Last name is required.';
    } elseif (strlen($data['last_name']) < 2) {
      $errors[] = 'Last name must be at least 2 characters.';
    }

    if (empty($data['email'])) {
      $errors[] = 'Email is required.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      $errors[] = 'Invalid email format.';
    }

    if (empty($data['role'])) {
      $errors[] = 'Role selection is required.';
    } elseif (!in_array($data['role'], ['admin', 'staff', 'user'])) {
      $errors[] = 'Invalid role selected.';
    }

    $usedQuestions = [];
    for ($i = 1; $i <= 5; $i++) {
      if (empty($secretQuestions[$i] ?? '')) {
        $errors[] = 'Security Question ' . $i . ' is required.';
      } elseif (empty($secretAnswers[$i] ?? '')) {
        $errors[] = 'Answer for Question ' . $i . ' is required.';
      } elseif (in_array($secretQuestions[$i], $usedQuestions)) {
        $errors[] = 'Please select different security questions.';
      } else {
        $usedQuestions[] = $secretQuestions[$i];
      }
    }

    if (!isset($data['terms']) || !$data['terms']) {
      $errors[] = 'You must agree to the Terms and Conditions.';
    }

    if (empty($data['password'])) {
      $errors[] = 'Password is required.';
    } elseif (strlen($data['password']) < 8) {
      $errors[] = 'Password must be at least 8 characters.';
    }

    if ($data['password'] ?? '' !== ($data['confirm_password'] ?? '')) {
      $errors[] = 'Passwords do not match.';
    }

    return $errors;
  }

  private function getOldInput($data, $secretQuestions, $secretAnswers)
  {
    return [
      'first_name' => $data['first_name'] ?? '',
      'last_name' => $data['last_name'] ?? '',
      'email' => $data['email'] ?? '',
      'phone' => $data['phone'] ?? '',
      'role' => $data['role'] ?? '',
      'secret_questions' => $secretQuestions,
      'secret_answers' => $secretAnswers
    ];
  }

  public function verifySecurityAnswers($answers, $questionIds)
  {
    try {
      $user = $this->userModel->findByAllSecurityAnswers($answers, $questionIds);

      if ($user) {
        $_SESSION['reset_user_id'] = $user['id'];
        $_SESSION['reset_email'] = $user['email'];
        $_SESSION['reset_first_name'] = $user['first_name'];
        $_SESSION['reset_last_name'] = $user['last_name'];
        $_SESSION['questions_verified'] = true;
        $_SESSION['reset_step'] = 2;

        $this->logAction('Security questions verified successfully for user: ' . $user['email']);

        return ['success' => true, 'user' => $user];
      }

      return ['success' => false, 'error' => 'Security answers do not match any user in our system. Please try again.'];

    } catch (Exception $e) {
      error_log("Security answers verification error: " . $e->getMessage());
      return ['success' => false, 'error' => 'System error. Please try again.'];
    }
  }

  public function resetPassword($userId, $password, $confirm)
  {
    if (empty($password) || empty($confirm)) {
      return ['success' => false, 'error' => 'Please enter both password fields.'];
    }

    if ($password !== $confirm) {
      return ['success' => false, 'error' => 'Passwords do not match.'];
    }

    if (strlen($password) < 8) {
      return ['success' => false, 'error' => 'Password must be at least 8 characters.'];
    }

    if (!preg_match('/[A-Z]/', $password)) {
      return ['success' => false, 'error' => 'Password must contain at least one uppercase letter.'];
    }

    if (!preg_match('/[a-z]/', $password)) {
      return ['success' => false, 'error' => 'Password must contain at least one lowercase letter.'];
    }

    if (!preg_match('/[0-9]/', $password)) {
      return ['success' => false, 'error' => 'Password must contain at least one number.'];
    }

    if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
      return ['success' => false, 'error' => 'Password must contain at least one special character.'];
    }

    try {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $this->userModel->updatePassword($userId, $hashedPassword);

      $this->clearResetSession();
      $this->logAction('Password reset successfully via security questions');

      return [
        'success' => true,
        'message' => 'Password has been reset successfully. You can now login with your new password.'
      ];
    } catch (Exception $e) {
      error_log("Reset password error: " . $e->getMessage());
      return ['success' => false, 'error' => 'System error. Please try again.'];
    }
  }

  public function resetEmail($userId, $newEmail, $confirmEmail)
  {
    if (empty($newEmail) || empty($confirmEmail)) {
      return ['success' => false, 'error' => 'Please enter both email fields.'];
    }

    if ($newEmail !== $confirmEmail) {
      return ['success' => false, 'error' => 'Email addresses do not match.'];
    }

    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
      return ['success' => false, 'error' => 'Please enter a valid email address.'];
    }

    try {
      if ($this->userModel->emailExists($newEmail, $userId)) {
        return ['success' => false, 'error' => 'This email address is already in use by another account.'];
      }

      $oldEmail = $_SESSION['reset_email'] ?? 'unknown';
      $this->userModel->updateEmail($userId, $newEmail);

      $this->clearResetSession();
      $this->logAction("Email reset successfully via security questions from {$oldEmail} to {$newEmail} for user ID: {$userId}");

      return [
        'success' => true,
        'message' => 'Email has been updated successfully. You can now login with your new email.'
      ];
    } catch (Exception $e) {
      error_log("Reset email error: " . $e->getMessage());
      return ['success' => false, 'error' => 'System error. Please try again.'];
    }
  }

  public function logout($userId = null, $userEmail = null)
  {
    if ($userId) {
      $this->userModel->updateLastLogout($userId);
    }
    $this->logAction('User logged out');
  }

  public function clearResetSession()
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

  public function getSecurityQuestions($limit = 5)
  {
    return $this->userModel->getSecurityQuestions($limit);
  }

  protected function logAction($message)
  {
    if (method_exists($this, 'logActionFromBase')) {
      return;
    }
    try {
      $userId = $_SESSION['user_id'] ?? null;
      $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
      $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

      $stmt = $this->pdo->prepare("
        INSERT INTO logs (user_id, action, table_name, record_id, old_values, new_values, ip_address, user_agent)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
      ");
      $stmt->execute([
        $userId,
        $message,
        null,
        null,
        null,
        null,
        $ipAddress,
        $userAgent
      ]);
    } catch (Exception $e) {
      error_log("Failed to log action: " . $e->getMessage());
    }
  }

  protected function notifyAdminOfNewRegistration($firstName, $lastName, $email, $role)
  {
    try {
      $admins = $this->userModel->getActiveAdmins();
      $message = "New registration: $firstName $lastName ($email) as $role";

      foreach ($admins as $admin) {
        $this->logAction("Notification sent to admin " . $admin['email'] . ": " . $message);
      }

      $stmt = $this->pdo->prepare("INSERT INTO notifications (title, message, type, created_at) VALUES (?, ?, ?, NOW())");
      $stmt->execute(['New Registration', $message, 'system']);
    } catch (Exception $e) {
      error_log("Failed to notify admin: " . $e->getMessage());
    }
  }

  protected function sendRegistrationEmail(string $email, string $name, string $role): void
  {
    if (!class_exists('MailService', false) && file_exists(__DIR__ . '/MailService.php')) {
      require_once __DIR__ . '/MailService.php';
    }
    if (!class_exists('MailService')) {
      $this->logAction('Registration email skipped: MailService unavailable for ' . $email);
      return;
    }

    try {
      $mailer = new MailService();
      $sent = $mailer->sendRegistrationWelcome($email, $name !== '' ? $name : $email, $role);
      $this->logAction(($sent ? 'Registration confirmation email sent to ' : 'Registration confirmation email not sent to ') . $email);
    } catch (Exception $e) {
      error_log('Registration email error: ' . $e->getMessage());
      $this->logAction('Registration confirmation email failed for ' . $email);
    }
  }
}
