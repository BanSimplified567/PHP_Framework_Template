<?php

declare(strict_types=1);

final class UserDashboardController
{
    public function index(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (($_SESSION['role'] ?? '') !== 'user') {
            header('Location: index.php?action=login');
            exit;
        }

        $firstName = htmlspecialchars($_SESSION['first_name'] ?? $_SESSION['username'] ?? 'User');

        header('Content-Type: text/html; charset=UTF-8');
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard - PHP Framecore</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --fc-dark: #2b2e33;
      --fc-dark-soft: #3a3e45;
      --fc-accent: #54e5c7;
      --fc-accent-hover: #3dd4b4;
      --fc-accent-soft: rgba(84, 229, 199, 0.12);
      --fc-text: #2b2e33;
      --fc-muted: #6b7280;
      --fc-border: #e5e7eb;
      --fc-bg: #f3f4f6;
      --fc-surface: #ffffff;
      --bs-body-font-family: "Inter", system-ui, -apple-system, sans-serif;
      --bs-body-color: var(--fc-text);
      --bs-border-color: var(--fc-border);
    }

    body {
      margin: 0;
      background-color: var(--fc-bg);
      font-family: var(--bs-body-font-family);
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      width: 260px;
      background: var(--fc-dark);
      color: #fff;
      z-index: 1030;
      overflow-y: auto;
      transition: transform 0.25s ease;
      box-shadow: 2px 0 12px rgba(0, 0, 0, 0.08);
    }

    .sidebar .nav-link {
      color: rgba(255, 255, 255, 0.78);
      padding: 0.75rem 1.25rem;
      border-radius: 0;
      border-left: 3px solid transparent;
      transition: all 0.15s ease;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-size: 0.9375rem;
      font-weight: 500;
      text-decoration: none;
    }

    .sidebar .nav-link:hover {
      color: #fff;
      background: var(--fc-dark-soft);
      border-left-color: var(--fc-accent);
    }

    .sidebar .nav-link.active {
      color: var(--fc-dark);
      background: var(--fc-accent-soft);
      border-left-color: var(--fc-accent);
    }

    .sidebar .nav-link.active i {
      color: var(--fc-dark);
    }

    .sidebar .nav-link i {
      color: rgba(255, 255, 255, 0.6);
      width: 1.25rem;
      text-align: center;
      font-size: 1rem;
    }

    .sidebar-brand {
      padding: 1.5rem 1.25rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .sidebar-brand i {
      font-size: 1.5rem;
      color: var(--fc-accent);
    }

    .sidebar-brand .brand-name {
      font-weight: 700;
      font-size: 1.125rem;
      letter-spacing: -0.02em;
    }

    .sidebar-brand .brand-sub {
      font-size: 0.75rem;
      color: rgba(255, 255, 255, 0.55);
    }

    .sidebar-section-title {
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: rgba(255, 255, 255, 0.45);
      padding: 1rem 1.25rem 0.5rem;
      font-weight: 600;
    }

    .main-content {
      margin-left: 260px;
      padding: 2rem 2.5rem;
      min-height: 100vh;
    }

    .topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 2rem;
    }

    .page-title {
      font-size: 1.75rem;
      font-weight: 700;
      letter-spacing: -0.02em;
      color: var(--fc-dark);
      margin: 0;
    }

    .page-sub {
      color: var(--fc-muted);
      font-size: 0.9375rem;
      margin: 0.25rem 0 0;
    }

    .btn-logout {
      background: var(--fc-dark-soft);
      color: #fff;
      border: 1px solid rgba(255, 255, 255, 0.15);
      padding: 0.5rem 1rem;
      border-radius: 0.65rem;
      font-weight: 500;
      font-size: 0.9375rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      text-decoration: none;
      transition: all 0.15s ease;
    }

    .btn-logout:hover {
      background: #43474e;
      color: #fff;
      border-color: rgba(255, 255, 255, 0.3);
    }

    .card-fc {
      border: 1px solid var(--fc-border);
      border-radius: 1rem;
      background: var(--fc-surface);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    @media (max-width: 991.98px) {
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.show {
        transform: translateX(0);
      }
      .main-content {
        margin-left: 0;
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
      <i class="bi bi-person"></i>
      <div>
        <div class="brand-name">PHP Framecore</div>
        <div class="brand-sub">User Account</div>
      </div>
    </div>

    <div class="sidebar-section-title">Navigation</div>
    <nav class="nav flex-column">
      <a class="nav-link active" href="index.php?action=user/dashboard">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
    </nav>

    <div class="sidebar-section-title" style="margin-top: 2rem;">Account</div>
    <nav class="nav flex-column">
      <a class="nav-link btn-logout" href="index.php?action=logout">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </nav>
  </aside>

  <main class="main-content">
    <div class="topbar">
      <div>
        <h1 class="page-title">Welcome, <?php echo $firstName; ?></h1>
        <p class="page-sub">User account dashboard - PHP Framecore</p>
      </div>
      <a class="btn-logout" href="index.php?action=logout">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>

    <div class="card-fc p-4">
      <h5 class="fw-semibold mb-1 text-dark">User panel</h5>
      <p class="text-muted small mb-0">You are signed in as a user.</p>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
        <?php
    }
}
