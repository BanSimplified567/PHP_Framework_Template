<?php
// app/Controllers/DashboardController.php
require_once __DIR__ . '/Path/BaseController.php';

class DashboardController extends BaseController
{
  // app/Controllers/DashboardController.php
  public function index()
  {
    $this->requireLogin('admin');

    $userRole = $_SESSION['role'];
    // Render the view
    $this->render('admin/dashboard', ['userRole' => $userRole]);
  }

}
