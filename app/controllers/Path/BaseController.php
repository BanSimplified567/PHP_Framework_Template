<?php
// app/Controllers/BaseController.php
class BaseController
{
  protected $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  protected function render($view, $data = [])
  {
    extract($data);

    // Get user role
    $role = $_SESSION['role'] ?? null;

    // Verify this is actually an admin, staff, or user user
    if (!$this->isAuthorizedUser()) {
      $this->redirect('login');
      return;
    }

    $isAdmin = ($role === 'admin');
    $isStaff = ($role === 'staff');
    $isUser = ($role === 'user');
    $isAuthorized = true; // We already checked above

    // Set page title based on role
    $page_title = $data['page_title'] ?? match ($role) {
      'admin' => 'Admin Panel - PHP_Framecore',
      'staff' => 'Staff Panel - PHP_Framecore',
      'user' => 'User Panel - PHP_Framecore',
      default => 'PHP_Framecore'
    };

    // Make controller available to views
    $controller = $this;

    include BASE_PATH . '/app/Views/layout/sidebar.php';


    // View
    $viewPath = BASE_PATH . '/app/Views/' . $view . '.php';
    if (file_exists($viewPath)) {
      include $viewPath;
    } else {
      echo "<div class='alert alert-danger mt-3'>View not found: $view</div>";
    }

    // Global footer scripts
    include BASE_PATH . '/app/Views/layout/base-footer.php';
  }

  protected function redirect($action, $params = [])
  {
    $queryString = http_build_query(array_merge(['action' => $action], $params));
    header("Location: index.php?$queryString");
    exit;
  }

  protected function requireAdminOrStaff()
  {
    $this->requireLogin();

    if (!$this->isAdminOrStaff()) {
      $_SESSION['error'] = "Access denied. Admin or staff privileges required.";
      $this->redirect('dashboard');
    }
  }

  protected function requireAdmin()
  {
    $this->requireLogin();

    $userRole = $_SESSION['role'] ?? null;
    if ($userRole !== 'admin') {
      $_SESSION['error'] = "Access denied. Administrator privileges required.";
      $this->redirect('dashboard');
    }
  }

  protected function requireStaff()
  {
    $this->requireLogin();

    $userRole = $_SESSION['role'] ?? null;
    if ($userRole !== 'staff') {
      $_SESSION['error'] = "Access denied. Staff privileges required.";
      $this->redirect('dashboard');
    }
  }

  protected function requireUser()
  {
    $this->requireLogin();

    $userRole = $_SESSION['role'] ?? null;
    if ($userRole !== 'user') {
      $_SESSION['error'] = "Access denied. User privileges required.";
      $this->redirect('dashboard');
    }
  }

  protected function isAuthorizedUser()
  {
    if (!$this->isLoggedIn()) {
      return false;
    }

    $role = $_SESSION['role'] ?? null;
    return in_array($role, ['admin', 'staff', 'user']);
  }

  protected function isAdminOrStaff()
  {
    if (!$this->isLoggedIn()) {
      return false;
    }

    $role = $_SESSION['role'] ?? null;
    return in_array($role, ['admin', 'staff']);
  }

  protected function isAdmin()
  {
    return ($this->isLoggedIn() && ($_SESSION['role'] ?? null) === 'admin');
  }

  protected function isStaff()
  {
    return ($this->isLoggedIn() && ($_SESSION['role'] ?? null) === 'staff');
  }

  protected function isUser()
  {
    return ($this->isLoggedIn() && ($_SESSION['role'] ?? null) === 'user');
  }

  protected function isLoggedIn()
  {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
  }

  protected function requireLogin()
  {
    if (!$this->isLoggedIn()) {
      $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'] ?? '';
      $this->redirect('login');
    }
  }

  public function timeAgo($datetime)
  {
    if (empty($datetime)) {
      return 'Never';
    }

    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;

    if ($diff < 60) {
      return 'Just now';
    } elseif ($diff < 3600) {
      return round($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
      return round($diff / 3600) . ' hours ago';
    } elseif ($diff < 604800) {
      return round($diff / 86400) . ' days ago';
    } elseif ($diff < 2592000) {
      return round($diff / 604800) . ' weeks ago';
    } elseif ($diff < 31536000) {
      return round($diff / 2592000) . ' months ago';
    } else {
      return round($diff / 31536000) . ' years ago';
    }
  }

  protected function logAction($action, $tableName = null, $recordId = null, $oldValues = null, $newValues = null)
  {
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
        $action,
        $tableName,
        $recordId,
        $oldValues ? json_encode($oldValues) : null,
        $newValues ? json_encode($newValues) : null,
        $ipAddress,
        $userAgent
      ]);
    } catch (Exception $e) {
      error_log("Failed to log action: " . $e->getMessage());
    }
  }

  protected function jsonResponse($data, $statusCode = 200)
  {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
  }
}
