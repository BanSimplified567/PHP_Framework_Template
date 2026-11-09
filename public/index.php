<?php
// public/index.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load config
require_once '../config/load_env.php';
require_once '../config/app.php';
require_once '../config/dbconn.php';

// Get action
$action = $_GET['action'] ?? '';

// Get current user role
$role = $_SESSION['role'] ?? null;
$isLoggedIn = isset($_SESSION['user_id']);

// Redirect empty action
if (empty($action)) {
  if ($isLoggedIn) {
    $dashboards = [
      'admin' => 'admin/dashboard',
      'staff' => 'staff/dashboard',
      'user' => 'user/dashboard'
    ];

    $dashboard = $dashboards[$role] ?? 'login';
    header("Location: index.php?action=$dashboard");
    exit;
  } else {
    header('Location: index.php?action=login');
    exit;
  }
}

// Define all routes with access control
$allRoutes = [
  // ==================== PUBLIC ROUTES ====================
  'public' => [
    'login' => 'Auth/AuthController@login',
    'forgot-password' => 'Auth/AuthController@forgotPassword',
    'reset-options' => 'Auth/AuthController@resetOptions',
    'reset-password' => 'Auth/AuthController@resetPassword',
    'reset-email' => 'Auth/AuthController@resetEmail',
    'register' => 'Auth/AuthController@register'
  ],

  // ==================== ADMIN ROUTES ====================
  'admin' => [
    // Dashboard
    'admin/dashboard' => 'Admin/AdminDashboardController@index',
  ],

  // ==================== STAFF ROUTES ====================
  'staff' => [
    // Dashboard
    'staff/dashboard' => 'Staff/StaffDashboardController@index',
  ],

  // ==================== USER ROUTES ====================
  'user' => [
    'user/dashboard' => 'User/UserDashboardController@index',
  ],


  // ==================== SHARED AUTHENTICATED ROUTES ====================
  'shared' => [
    'logout' => 'Auth/AuthController@logout'
  ]
];

// Special handling for logout
if ($action === 'logout') {
  if (isset($_SESSION['user_id'])) {
    require_once "../app/Controllers/Auth/AuthController.php";
    $controller = new AuthController($pdo);
    $controller->logout();
  } else {
    header('Location: index.php?action=login');
    exit;
  }
}

// Function to check if route is accessible
function isRouteAccessible($action, $role, $isLoggedIn)
{
  global $allRoutes;

  // Check public routes
  if (isset($allRoutes['public'][$action])) {
    return true;
  }

  // Check admin routes
  if (isset($allRoutes['admin'][$action])) {
    return $isLoggedIn && $role === 'admin';
  }

  // Check staff routes
  if (isset($allRoutes['staff'][$action])) {
    return $isLoggedIn && $role === 'staff';
  }

  // Check user routes (if enabled)
  if (isset($allRoutes['user'][$action])) {
    return $isLoggedIn && $role === 'user';
  }

  // Check shared routes
  if (isset($allRoutes['shared'][$action])) {
    return $isLoggedIn;
  }

  return false;
}

// Check if action exists in any route group
function getRoute($action)
{
  global $allRoutes;

  foreach ($allRoutes as $group) {
    if (isset($group[$action])) {
      return $group[$action];
    }
  }

  return null;
}

// Main routing logic
error_log("[Router] Action: $action, Role: $role, LoggedIn: " . ($isLoggedIn ? 'yes' : 'no'));
error_log("[Router] Route accessible: " . (isRouteAccessible($action, $role, $isLoggedIn) ? 'yes' : 'no'));

if (!isRouteAccessible($action, $role, $isLoggedIn)) {
  error_log("[Router] Route not accessible, redirecting to dashboard");
  if ($isLoggedIn) {
    // Redirect to appropriate dashboard based on role
    $dashboards = [
      'admin' => 'admin/dashboard',
      'staff' => 'staff/dashboard',
      'user' => 'user/dashboard'
    ];
    $dashboard = $dashboards[$role] ?? 'login';

    $_SESSION['error'] = " ";
    header("Location: index.php?action=$dashboard");
    exit;
  } else {
    header('Location: index.php?action=login');
    exit;
  }
}

// Get the route handler
$routeHandler = getRoute($action);

if ($routeHandler) {
  if (is_callable($routeHandler)) {
    $routeHandler();
  } else {
    list($controllerPath, $method) = explode('@', $routeHandler);
    $controllerFile = "../app/Controllers/{$controllerPath}.php";

    if (file_exists($controllerFile)) {
      require_once $controllerFile;

      // Convert controller path to proper class name
      $controllerClass = basename($controllerPath);

      // Handle ID parameter for view/edit/update routes
      if (in_array($method, ['view', 'edit', 'update', 'delete', 'approve', 'reject', 'disburse', 'printReceipt', 'receipt', 'downloadReceipt', 'void', 'getSchedule', 'viewClient', 'clientLoans', 'clientDetails', 'getStats', 'statsView', 'deleteDocument', 'viewDocument', 'downloadDocument'])) {
        $id = $_GET['id'] ?? null;
        if ($id) {
          $controller = new $controllerClass($pdo);
          if (method_exists($controller, $method)) {
            $controller->$method($id);
          } else {
            http_response_code(404);
            echo "Method $method not found in $controllerClass";
            exit;
          }
        } else {
          $_SESSION['error'] = "ID parameter is required.";
          // Fix the redirect to go to the correct base route
          $baseAction = str_replace('/' . $method, '', $action);
          header("Location: index.php?action=$baseAction");
          exit;
        }
      } else {
        $controller = new $controllerClass($pdo);

        if (method_exists($controller, $method)) {
          $controller->$method();
        } else {
          http_response_code(404);
          echo "Method $method not found in $controllerClass";
          exit;
        }
      }
    } else {
      http_response_code(404);
      echo "Controller file not found: $controllerFile";
      exit;
    }
  }
} else {
  http_response_code(404);
  require_once '../app/Views/errors/404.php';
}
