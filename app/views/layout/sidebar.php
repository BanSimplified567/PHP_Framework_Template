<?php
// app/views/layout/sidebar.php
// Dynamic sidebar that shows content based on user role
$userRole = $_SESSION['role'] ?? '';
$firstName = htmlspecialchars($_SESSION['first_name'] ?? $_SESSION['username'] ?? 'User');
$userId = $_SESSION['user_id'] ?? 0;

// Only show for authenticated users
if (!in_array($userRole, ['admin', 'staff', 'cashier'])) {
  return;
}

// Role-specific configurations with green palette
$roleConfig = [
  'admin' => [
    'badge' => 'bg-primary',
    'badgeText' => 'Admin',
    'portalName' => 'Admin Panel',
    'dashboardPrefix' => 'admin',
    'iconBg' => 'bg-primary',
    'color' => '#10b981', // Primary green
    'darkColor' => '#065f46' // Dark green
  ],
  'staff' => [
    'badge' => 'bg-success',
    'badgeText' => 'Staff',
    'portalName' => 'Loan Officer',
    'dashboardPrefix' => 'staff',
    'iconBg' => 'bg-success',
    'color' => '#34d399', // Accent green
    'darkColor' => '#059669' // Secondary green
  ],
  'cashier' => [
    'badge' => 'bg-success',
    'badgeText' => 'Cashier',
    'portalName' => 'Cashier Portal',
    'dashboardPrefix' => 'cashier',
    'iconBg' => 'bg-success',
    'color' => '#10b981',
    'darkColor' => '#065f46'
  ]
];

$config = $roleConfig[$userRole];
?>

<style>
  :root {
    /* Green color palette */
    --primary-green: #10b981;
    --secondary-green: #059669;
    --light-green: #d1fae5;
    --dark-green: #065f46;
    --accent-green: #34d399;
    --background-green: #f0fdf4;

    /* Role colors */
    --sidebar-width: 290px;
    --sidebar-collapsed-width: 100px;
    --sidebar-bg: <?php echo $config['color']; ?>;
    --sidebar-gradient: linear-gradient(180deg, <?php echo $config['color']; ?> 0%, <?php echo $config['darkColor']; ?> 100%);
    --sidebar-text: #fff;
    --header-height: 56px;
  }

  /* Base body styles */
  body {
    margin: 0;
    padding: 0;
    overflow-x: hidden;
    min-height: 100vh;
    background-color: var(--background-green);
  }

  /* Desktop Sidebar Styles */
  #sidebar {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: var(--sidebar-width);
    transition: width 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    z-index: 1040;
    background: var(--sidebar-gradient);
    box-shadow: 4px 0 10px rgba(16, 185, 129, 0.1);
    overflow: visible;
    display: flex;
    flex-direction: column;
  }

  .sidebar-inner {
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow-x: hidden;
    overflow-y: auto;
  }

  .main-content {
    margin-left: var(--sidebar-width);
    transition: margin-left 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), width 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    min-height: 100vh;
    width: calc(100% - var(--sidebar-width));
    display: block;
    position: relative;
    background: var(--background-green);
  }

  /* Collapsed State */
  body.sidebar-toggled #sidebar {
    width: var(--sidebar-collapsed-width);
  }

  body.sidebar-toggled .main-content {
    margin-left: var(--sidebar-collapsed-width);
    width: calc(100% - var(--sidebar-collapsed-width));
  }

  body.sidebar-toggled #sidebar .sidebar-text,
  body.sidebar-toggled #sidebar .sidebar-brand-text,
  body.sidebar-toggled #sidebar .section-header,
  body.sidebar-toggled #sidebar .role-badge,
  body.sidebar-toggled #sidebar .user-info-text,
  body.sidebar-toggled #sidebar .dropdown-arrow,
  body.sidebar-toggled #sidebar .dropdown-menu-sidebar {
    display: none !important;
  }

  body.sidebar-toggled #sidebar .sidebar-brand {
    justify-content: center;
    padding: 1rem 0.5rem !important;
  }

  body.sidebar-toggled #sidebar .sidebar-brand-icon {
    margin: 0 auto;
  }

  body.sidebar-toggled #sidebar .nav-link {
    text-align: center;
    padding: 1rem 0.5rem !important;
    flex-direction: column;
    justify-content: center;
  }

  body.sidebar-toggled #sidebar .nav-link i {
    margin-right: 0 !important;
    font-size: 1.25rem;
    margin-bottom: 0;
    color: var(--light-green);
  }

  body.sidebar-toggled #sidebar .nav-link-dropdown {
    flex-direction: column;
    padding: 1rem 0.5rem !important;
  }

  body.sidebar-toggled #sidebar .nav-link-dropdown i:first-child {
    margin-right: 0 !important;
    font-size: 1.25rem;
    margin-bottom: 0.25rem;
    color: var(--light-green);
  }

  body.sidebar-toggled #sidebar .user-quick-info {
    justify-content: center;
    padding: 1rem 0.5rem !important;
  }

  body.sidebar-toggled #sidebar .user-avatar {
    margin: 0 auto;
  }

  body.sidebar-toggled #sidebar hr {
    margin: 0.5rem 1rem;
  }

  /* Floating Toggle Button */
  #sidebarToggle {
    position: absolute;
    top: 20px;
    right: -15px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #fff;
    color: var(--primary-green);
    border: 1px solid var(--light-green);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    z-index: 1050;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(16, 185, 129, 0.15);
    font-size: 0.75rem;
  }

  #sidebarToggle:hover {
    background: var(--light-green);
    color: var(--dark-green);
    transform: scale(1.1);
    box-shadow: 0 0.25rem 2rem 0 rgba(16, 185, 129, 0.25);
    border-color: var(--primary-green);
  }

  body.sidebar-toggled #sidebarToggle i {
    transform: rotate(180deg);
  }

  #sidebarToggle i {
    transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  }

  /* Dropdown Styles */
  .nav-item-dropdown {
    position: relative;
  }

  .nav-link-dropdown {
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.9);
    padding: 0.6rem 0.75rem;
    border-radius: 6px;
    transition: all 0.2s;
  }

  .nav-link-dropdown:hover {
    background: rgba(209, 250, 229, 0.15);
    color: white;
  }

  .nav-link-dropdown:hover i {
    color: var(--accent-green);
  }

  .dropdown-arrow {
    transition: transform 0.3s ease;
    font-size: 0.75rem;
    opacity: 0.7;
    margin-left: auto;
    color: var(--light-green);
  }

  .nav-item-dropdown.open .dropdown-arrow {
    transform: rotate(90deg);
    opacity: 1;
    color: var(--accent-green);
  }

  .dropdown-menu-sidebar {
    background: rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    margin-top: 0.25rem;
    margin-bottom: 0.5rem;
    padding: 0.25rem 0;
    list-style: none;
    transition: all 0.3s ease;
    display: none;
    position: relative;
    z-index: 1030;
    border-left: 2px solid var(--accent-green);
  }

  .dropdown-menu-sidebar .nav-link {
    padding-left: 2.5rem !important;
    font-size: 0.9rem;
  }

  .dropdown-menu-sidebar .nav-link i {
    font-size: 0.8rem;
    opacity: 0.7;
    color: var(--light-green);
  }

  .dropdown-menu-sidebar .nav-link:hover i {
    color: var(--accent-green);
  }

  .nav-item-dropdown.open .dropdown-menu-sidebar {
    display: block;
    opacity: 1;
    visibility: visible;
  }

  /* Nav Link Styles */
  .nav-link {
    transition: all 0.2s;
    color: rgba(255, 255, 255, 0.9) !important;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 0.6rem 0.75rem;
    border-radius: 6px;
    white-space: nowrap;
  }

  .nav-link:hover:not(.active) {
    background: rgba(209, 250, 229, 0.15);
    color: white !important;
  }

  .nav-link:hover:not(.active) i {
    color: var(--accent-green);
  }

  .nav-link.active {
    background: var(--primary-green) !important;
    color: white !important;
    font-weight: bold;
    box-shadow: 0 2px 5px rgba(6, 150, 105, 0.3);
    border-left: 3px solid var(--accent-green);
  }

  .nav-link.active i {
    color: white !important;
  }

  .nav-link.text-danger {
    color: #fecaca !important;
  }

  .nav-link.text-danger:hover {
    background: rgba(239, 68, 68, 0.15) !important;
    color: #fee2e2 !important;
  }

  .nav-link.text-danger i {
    color: #fca5a5;
  }

  /* Icon container */
  .icon-container {
    width: 24px;
    display: flex;
    justify-content: center;
    flex-shrink: 0;
  }

  .icon-container i {
    font-size: 0.9rem;
    color: var(--light-green);
    transition: color 0.2s;
  }

  .sidebar-text {
    margin-left: 0.75rem;
    font-size: 0.9rem;
  }

  /* Role badge */
  .role-badge {
    background: rgba(209, 250, 229, 0.2);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: var(--light-green);
    border: 1px solid rgba(209, 250, 229, 0.3);
  }

  /* Brand */
  .sidebar-brand {
    height: 70px;
    padding: 0.5rem 1rem;
    border-bottom: 1px solid rgba(209, 250, 229, 0.1);
    display: flex;
    align-items: center;
  }

  .sidebar-brand-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(16, 185, 129, 0.2);
    border: 2px solid var(--light-green);
  }

  .sidebar-brand-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .sidebar-brand-text {
    margin-top: 1rem;
    margin-left: 0.75rem;
    line-height: 1.2;
    white-space: nowrap;
  }

  .sidebar-brand-text .fw-bold {
    color: white;
    font-size: 0.9rem;
  }

  /* User Info */
  .user-quick-info {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid rgba(209, 250, 229, 0.1);
    display: flex;
    align-items: center;
  }

  .user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: var(--accent-green);
  }

  .user-avatar i {
    font-size: 1.2rem;
    color: var(--dark-green);
  }

  .user-info-text {
    margin-left: 0.75rem;
    min-width: 0;
    flex: 1;
  }

  .user-info-text .name {
    font-weight: 600;
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: white;
  }

  .user-info-text .email {
    font-size: 0.7rem;
    opacity: 0.7;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: var(--light-green);
  }

  /* Section Headers */
  .section-header {
    padding: 0.5rem 0.75rem;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.8;
  }

  /* Divider */
  hr {
    border-color: var(--light-green) !important;
    opacity: 0.1;
    margin: 0.5rem 0;
  }

  /* Mobile Top Bar */
  .mobile-top-bar {
    background: white;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.1);
    position: sticky;
    top: 0;
    z-index: 1030;
    border-bottom: 1px solid var(--light-green);
  }

  .mobile-sidebar-toggle {
    background: none;
    border: none;
    color: var(--primary-green);
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.2s;
  }

  .mobile-sidebar-toggle:hover {
    background: var(--light-green);
    color: var(--dark-green);
  }

  .mobile-top-bar .badge.bg-primary {
    background: var(--primary-green) !important;
    color: white !important;
  }

  .mobile-top-bar .badge.bg-success {
    background: var(--accent-green) !important;
    color: var(--dark-green) !important;
  }

  .mobile-top-bar .badge.bg-warning {
    background: var(--warning, #fbbf24) !important;
    color: #78350f !important;
  }

  .mobile-top-bar .rounded-circle.bg-primary {
    background: var(--primary-green) !important;
  }

  .mobile-top-bar .rounded-circle.bg-success {
    background: var(--accent-green) !important;
  }

  .mobile-top-bar .rounded-circle.bg-warning {
    background: var(--warning, #fbbf24) !important;
  }

  /* Mobile handling */
  @media (max-width: 767.98px) {
    #sidebar {
      position: fixed;
      transform: translateX(-100%);
      transition: transform 0.3s ease;
      z-index: 1050;
      display: flex !important;
      width: var(--sidebar-width) !important;
    }

    body.sidebar-mobile-open #sidebar {
      transform: translateX(0);
    }

    .main-content {
      margin-left: 0 !important;
      width: 100% !important;
      transition: none !important;
    }

    #sidebarToggle {
      display: none !important;
    }

    body.sidebar-mobile-open::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(6, 95, 70, 0.5);
      z-index: 1049;
    }

    #sidebar .sidebar-brand-text,
    #sidebar .sidebar-text,
    #sidebar .section-header,
    #sidebar .role-badge,
    #sidebar .user-info-text,
    #sidebar .dropdown-arrow,
    #sidebar .dropdown-menu-sidebar {
      display: block !important;
    }

    #sidebar .nav-link,
    #sidebar .nav-link-dropdown {
      text-align: left;
      flex-direction: row;
      justify-content: flex-start;
      padding: 0.75rem 1rem !important;
    }

    #sidebar .nav-link i,
    #sidebar .nav-link-dropdown i:first-child {
      margin-right: 1rem !important;
      font-size: 1rem !important;
    }

    #sidebar .sidebar-brand {
      justify-content: flex-start !important;
      padding: 0.75rem 1rem !important;
    }

    #sidebar .user-quick-info {
      justify-content: flex-start;
      padding: 0.75rem 1rem !important;
    }

    .dropdown-menu-sidebar .nav-link {
      padding-left: 3.5rem !important;
    }
  }

  @media (min-width: 768px) {
    #sidebar {
      display: flex !important;
      transform: none !important;
    }

    body.sidebar-mobile-open #sidebar {
      transform: none !important;
    }

    body.sidebar-mobile-open::before {
      display: none;
    }

    .mobile-top-bar {
      display: none !important;
    }

    .main-content {
      min-width: 0;
    }
  }

  /* Custom Scrollbar */
  .custom-scrollbar::-webkit-scrollbar {
    width: 6px;
  }

  .custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(209, 250, 229, 0.1);
  }

  .custom-scrollbar::-webkit-scrollbar-thumb {
    background: var(--accent-green);
    border-radius: 3px;
  }

  .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: var(--primary-green);
  }
</style>

<!-- Mobile Top Bar -->
<div class="mobile-top-bar d-md-none">
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center py-2">
      <button class="mobile-sidebar-toggle" type="button" aria-label="Toggle sidebar">
        <i class="fas fa-bars fs-5"></i>
      </button>
      <div class="d-flex align-items-center">
        <span class="me-2 text-muted small">
          <span class="badge <?php echo $config['badge']; ?> me-1"><?php echo $config['badgeText']; ?></span>
          <?php echo $firstName; ?>
        </span>
        <div class="rounded-circle <?php echo $config['iconBg']; ?> d-flex align-items-center justify-content-center"
          style="width: 35px; height: 35px;">
          <i class="fas fa-user text-white"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Sidebar -->
<aside id="sidebar" class="flex-column position-fixed start-0 top-0 bottom-0 text-white" role="navigation" aria-label="Main navigation">
  <div class="sidebar-inner custom-scrollbar">
    <!-- Brand -->
    <div class="sidebar-brand">
      <div class="sidebar-brand-icon">
        <i class="fas fa-user-circle"></i>
      </div>
      <div class="sidebar-brand-text">
        <div class="fw-bold">BUENAMENTE</div>
        <div class="small opacity-75 d-flex align-items-center">
          <span style="font-size: 0.7rem;"><?php echo $config['portalName']; ?></span>
          <span class="role-badge ms-2"><?php echo $config['badgeText']; ?></span>
        </div>
      </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-grow-1 p-2">
      <div class="nav flex-column gap-1">

        <!-- Dashboard -->
        <a href="index.php?action=<?php echo $config['dashboardPrefix']; ?>/dashboard"
          class="nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', '/dashboard') !== false) ? 'active' : ''; ?>"
          title="Dashboard">
          <div class="icon-container"><i class="fas fa-tachometer-alt"></i></div>
          <span class="sidebar-text">Dashboard</span>
        </a>

        <hr class="my-2">

        <?php if ($userRole === 'admin'): ?>
          <!-- User Management -->
          <div class="section-header">User Management</div>

          <div class="nav-item-dropdown <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', '/users') !== false) ? 'open' : ''; ?>">
            <button class="nav-link-dropdown" data-dropdown="userManagement">
              <span class="d-flex align-items-center">
                <div class="icon-container"><i class="fas fa-user-cog"></i></div>
                <span class="sidebar-text">User Management</span>
              </span>
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </button>
            <ul class="dropdown-menu-sidebar">
              <li><a href="index.php?action=admin/users" class="nav-link"><i class="fas fa-users me-2"></i>All Users</a></li>
              <li><a href="index.php?action=admin/users/add" class="nav-link"><i class="fas fa-user-plus me-2"></i>Add User</a></li>
            </ul>
          </div>

          <!-- Client Management -->
          <div class="section-header">Client Management</div>

          <div class="nav-item-dropdown <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', '/clients') !== false) ? 'open' : ''; ?>">
            <button class="nav-link-dropdown" data-dropdown="clientManagement">
              <span class="d-flex align-items-center">
                <div class="icon-container"><i class="fas fa-users"></i></div>
                <span class="sidebar-text">Client Management</span>
              </span>
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </button>
            <ul class="dropdown-menu-sidebar">
              <li><a href="index.php?action=admin/clients" class="nav-link"><i class="fas fa-list me-2"></i>All Clients</a></li>
              <li><a href="index.php?action=admin/clients/store" class="nav-link"><i class="fas fa-user-plus me-2"></i>Add Client</a></li>
            </ul>
          </div>

          <!-- Loan Products -->
          <div class="section-header">Loan Products</div>

          <div class="nav-item-dropdown <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', '/loan-products') !== false) ? 'open' : ''; ?>">
            <button class="nav-link-dropdown" data-dropdown="loanProducts">
              <span class="d-flex align-items-center">
                <div class="icon-container"><i class="fas fa-box"></i></div>
                <span class="sidebar-text">Loan Products</span>
              </span>
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </button>
            <ul class="dropdown-menu-sidebar">
              <li><a href="index.php?action=admin/products" class="nav-link"><i class="fas fa-box me-2"></i>All Products</a></li>
              <li><a href="index.php?action=admin/products/add" class="nav-link"><i class="fas fa-plus me-2"></i>Add Product</a></li>
            </ul>
          </div>

          <!-- Loan Oversight -->
          <div class="section-header">Loan Oversight</div>

          <div class="nav-item-dropdown <?php echo (isset($_GET['action']) && (strpos($_GET['action'] ?? '', '/loans') !== false || strpos($_GET['action'] ?? '', 'disbursement-queue') !== false)) ? 'open' : ''; ?>">
            <button class="nav-link-dropdown" data-dropdown="loanOversight">
              <span class="d-flex align-items-center">
                <div class="icon-container"><i class="fas fa-hand-holding-usd"></i></div>
                <span class="sidebar-text">Loan Oversight</span>
              </span>
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </button>
            <ul class="dropdown-menu-sidebar">
              <li><a href="index.php?action=admin/loans/create" class="nav-link"><i class="fas fa-plus me-2"></i>Create New Loan</a></li>
              <li><a href="index.php?action=admin/loans" class="nav-link"><i class="fas fa-list me-2"></i>All Loans</a></li>
              <li><a href="index.php?action=admin/loans/pending" class="nav-link"><i class="fas fa-clock me-2"></i>Pending Approvals</a></li>
              <li><a href="index.php?action=admin/loans/approve" class="nav-link"><i class="fas fa-check-circle me-2"></i>Approved Loans</a></li>
              <li><a href="index.php?action=admin/disbursementqueue/disbursement-queue" class="nav-link"><i class="fas fa-money-check-alt me-2"></i>Disbursement Queue</a></li>
            </ul>
          </div>

          <!-- Payment Processing -->
          <div class="section-header">Payment Processing</div>

          <div class="nav-item-dropdown <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', 'payments') !== false) ? 'open' : ''; ?>">
            <button class="nav-link-dropdown" data-dropdown="paymentProcessing">
              <span class="d-flex align-items-center">
                <div class="icon-container"><i class="fas fa-cash-register"></i></div>
                <span class="sidebar-text">Payment Processing</span>
              </span>
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </button>
            <ul class="dropdown-menu-sidebar">
              <li><a href="index.php?action=admin/payments/record" class="nav-link"><i class="fas fa-cash-register me-2"></i>Record Payment</a></li>
              <li><a href="index.php?action=admin/payment-history" class="nav-link"><i class="fas fa-history me-2"></i>Payment History</a></li>
            </ul>
          </div>

          <!-- Reports -->
          <div class="section-header">Reports</div>

          <div class="nav-item-dropdown <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', '/reports') !== false) ? 'open' : ''; ?>">
            <button class="nav-link-dropdown" data-dropdown="reports">
              <span class="d-flex align-items-center">
                <div class="icon-container"><i class="fas fa-chart-line"></i></div>
                <span class="sidebar-text">Reports</span>
              </span>
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </button>
            <ul class="dropdown-menu-sidebar">
              <li><a href="index.php?action=admin/reports/financial" class="nav-link"><i class="fas fa-chart-pie me-2"></i>Financial Reports</a></li>
              <li><a href="index.php?action=admin/reports/loan-performance" class="nav-link"><i class="fas fa-chart-bar me-2"></i>Loan Performance</a></li>
            </ul>
          </div>

        <?php elseif ($userRole === 'staff'): ?>
          <!-- Staff Menus -->
          <div class="section-header">Client Management</div>

          <div class="nav-item-dropdown <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', '/clients') !== false) ? 'open' : ''; ?>">
            <button class="nav-link-dropdown" data-dropdown="clientManagementStaff">
              <span class="d-flex align-items-center">
                <div class="icon-container"><i class="fas fa-users"></i></div>
                <span class="sidebar-text">Client Management</span>
              </span>
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </button>
            <ul class="dropdown-menu-sidebar">
              <li><a href="index.php?action=staff/clients" class="nav-link"><i class="fas fa-list me-2"></i>All Clients</a></li>
              <li><a href="index.php?action=staff/clients/create" class="nav-link"><i class="fas fa-user-plus me-2"></i>Add Client</a></li>
            </ul>
          </div>

          <div class="section-header">Loan Management</div>

          <div class="nav-item-dropdown <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', '/loans') !== false) ? 'open' : ''; ?>">
            <button class="nav-link-dropdown" data-dropdown="loanManagement">
              <span class="d-flex align-items-center">
                <div class="icon-container"><i class="fas fa-hand-holding-usd"></i></div>
                <span class="sidebar-text">Loan Management</span>
              </span>
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </button>
            <ul class="dropdown-menu-sidebar">
              <li><a href="index.php?action=staff/loans" class="nav-link"><i class="fas fa-list me-2"></i>All Loans</a></li>
              <li><a href="index.php?action=staff/loans/pending" class="nav-link"><i class="fas fa-clock me-2"></i>Pending Approval</a></li>
              <li><a href="index.php?action=staff/loans/active" class="nav-link"><i class="fas fa-check-circle me-2"></i>Active Loans</a></li>
              <li><a href="index.php?action=staff/loans/create" class="nav-link"><i class="fas fa-plus me-2"></i>New Loan Application</a></li>
            </ul>
          </div>

          <div class="section-header">Collections</div>

          <div class="nav-item-dropdown <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', 'staff/payments') !== false) ? 'open' : ''; ?>">
            <button class="nav-link-dropdown" data-dropdown="collections">
              <span class="d-flex align-items-center">
                <div class="icon-container"><i class="fas fa-hand-holding-heart"></i></div>
                <span class="sidebar-text">Collections</span>
              </span>
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </button>
            <ul class="dropdown-menu-sidebar">
              <li><a href="index.php?action=staff/payments/record" class="nav-link"><i class="fas fa-cash-register me-2"></i>Record Payment</a></li>
              <li><a href="index.php?action=staff/payments" class="nav-link"><i class="fas fa-history me-2"></i>Payment History</a></li>
            </ul>
          </div>

          <div class="section-header">Disbursements</div>

          <div class="nav-item-dropdown <?php echo (isset($_GET['action'])) && (strpos($_GET['action'] ?? '', 'staff/disbursement') !== false || strpos($_GET['action'] ?? '', 'staff/disburse') !== false || strpos($_GET['action'] ?? '', 'staff/disbursementqueue') !== false) ? 'open' : ''; ?>">
            <button class="nav-link-dropdown" data-dropdown="staffDisbursements">
              <span class="d-flex align-items-center">
                <div class="icon-container"><i class="fas fa-money-bill-wave"></i></div>
                <span class="sidebar-text">Disbursements</span>
              </span>
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </button>
            <ul class="dropdown-menu-sidebar">
              <li><a href="index.php?action=staff/disbursement-queue" class="nav-link <?php echo (isset($_GET['action']) && $_GET['action'] === 'staff/disbursement-queue') ? 'active' : ''; ?>"><i class="fas fa-clock me-2"></i>Disbursement Queue</a></li>
            </ul>
          </div>

        <?php elseif ($userRole === 'cashier'): ?>
          <!-- Cashier Menus -->
          <div class="section-header">Clients</div>

          <a href="index.php?action=cashier/clients" class="nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', 'cashier/clients') !== false && strpos($_GET['action'] ?? '', 'cashier/clients/') === false) ? 'active' : ''; ?>">
            <div class="icon-container"><i class="fas fa-users"></i></div>
            <span class="sidebar-text">Client List</span>
          </a>

          <div class="nav-item-dropdown <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', 'cashier/payments') !== false) ? 'open' : ''; ?>">
            <button class="nav-link-dropdown" data-dropdown="cashierPayments">
              <span class="d-flex align-items-center">
                <div class="icon-container"><i class="fas fa-cash-register"></i></div>
                <span class="sidebar-text">Payments</span>
              </span>
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </button>
            <ul class="dropdown-menu-sidebar">
              <li><a href="index.php?action=cashier/payments/record" class="nav-link <?php echo (isset($_GET['action']) && (strpos($_GET['action'] ?? '', 'cashier/payments/record') !== false || strpos($_GET['action'] ?? '', 'cashier/payments/store') !== false)) ? 'active' : ''; ?>"><i class="fas fa-cash-register me-2"></i>Record Payment</a></li>
              <li><a href="index.php?action=cashier/payments" class="nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', 'cashier/payments') !== false && strpos($_GET['action'] ?? '', 'cashier/payments/') === false) ? 'active' : ''; ?>"><i class="fas fa-history me-2"></i>Payment History</a></li>
            </ul>
          </div>

          <div class="nav-item-dropdown <?php echo (isset($_GET['action']) && (strpos($_GET['action'] ?? '', 'cashier/disbursement-queue') !== false)) ? 'open' : ''; ?>">
            <button class="nav-link-dropdown" data-dropdown="cashierLoans">
              <span class="d-flex align-items-center">
                <div class="icon-container"><i class="fas fa-hand-holding-usd"></i></div>
                <span class="sidebar-text">Loans</span>
              </span>
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </button>
            <ul class="dropdown-menu-sidebar">
              <li><a href="index.php?action=cashier/disbursement-queue" class="nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', 'cashier/disbursement-queue') !== false) ? 'active' : ''; ?>"><i class="fas fa-clock me-2"></i>Disbursement Queue</a></li>
            </ul>
          </div>

          <div class="section-header">Reports</div>

          <div class="nav-item-dropdown <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', 'cashier/reports') !== false) ? 'open' : ''; ?>">
            <button class="nav-link-dropdown" data-dropdown="cashierReports">
              <span class="d-flex align-items-center">
                <div class="icon-container"><i class="fas fa-chart-line"></i></div>
                <span class="sidebar-text">Reports</span>
              </span>
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </button>
            <ul class="dropdown-menu-sidebar">
              <li><a href="index.php?action=cashier/reports" class="nav-link <?php echo (isset($_GET['action']) && $_GET['action'] === 'cashier/reports') ? 'active' : ''; ?>"><i class="fas fa-list me-2"></i>All Reports</a></li>
            </ul>
          </div>
        <?php endif; ?>

        <hr class="my-2">

        <!-- Account Section -->
        <div class="section-header">Account</div>
        <?php if ($userRole === 'admin'): ?>
          <a href="index.php?action=admin/profile" class="nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', '/profile') !== false) ? 'active' : ''; ?>">
            <div class="icon-container"><i class="fas fa-user-circle"></i></div>
            <span class="sidebar-text">My Profile</span>
          </a>
        <?php endif; ?>
        <?php if ($userRole === 'staff'): ?>
          <a href="index.php?action=staff/profile" class="nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', '/profile') !== false) ? 'active' : ''; ?>">
            <div class="icon-container"><i class="fas fa-user-circle"></i></div>
            <span class="sidebar-text">My Profile</span>
          </a>
        <?php endif; ?>
        <?php if ($userRole === 'cashier'): ?>
          <a href="index.php?action=cashier/profile" class="nav-link <?php echo (isset($_GET['action']) && strpos($_GET['action'] ?? '', '/profile') !== false) ? 'active' : ''; ?>">
            <div class="icon-container"><i class="fas fa-user-circle"></i></div>
            <span class="sidebar-text">My Profile</span>
          </a>
        <?php endif; ?>
        <a href="index.php?action=logout" class="nav-link text-danger">
          <div class="icon-container"><i class="fas fa-sign-out-alt"></i></div>
          <span class="sidebar-text">Logout</span>
        </a>
      </div>
    </nav>
  </div>

  <!-- Toggle Button (Desktop) -->
  <button id="sidebarToggle" title="Toggle Sidebar">
    <i class="fas fa-chevron-left"></i>
  </button>
</aside>

<script>
  (function() {
    'use strict';

    function init() {
      const body = document.body;
      const sidebar = document.getElementById('sidebar');
      const toggleBtn = document.getElementById('sidebarToggle');
      const mobileToggle = document.querySelector('.mobile-sidebar-toggle');
      const toggleIcon = toggleBtn?.querySelector('i');

      // Desktop toggle
      if (toggleBtn) {
        // Load saved state
        const saved = localStorage.getItem('buenamente-sidebar-toggled');
        if (saved === 'true') {
          body.classList.add('sidebar-toggled');
          if (toggleIcon) {
            toggleIcon.style.transform = 'rotate(180deg)';
          }
        }

        toggleBtn.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();

          body.classList.toggle('sidebar-toggled');

          if (toggleIcon) {
            if (body.classList.contains('sidebar-toggled')) {
              toggleIcon.style.transform = 'rotate(180deg)';
              localStorage.setItem('buenamente-sidebar-toggled', 'true');
            } else {
              toggleIcon.style.transform = 'rotate(0deg)';
              localStorage.setItem('buenamente-sidebar-toggled', 'false');
            }
          }

          // Close all dropdowns when collapsed
          if (body.classList.contains('sidebar-toggled')) {
            document.querySelectorAll('.nav-item-dropdown.open').forEach(d => d.classList.remove('open'));
          }

          window.dispatchEvent(new CustomEvent('sidebarToggle', {
            detail: {
              isToggled: body.classList.contains('sidebar-toggled')
            }
          }));
        });
      }

      // Mobile toggle
      if (mobileToggle) {
        mobileToggle.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();

          body.classList.toggle('sidebar-mobile-open');
          document.body.style.overflow = body.classList.contains('sidebar-mobile-open') ? 'hidden' : '';
        });
      }

      // Dropdowns
      document.querySelectorAll('.nav-link-dropdown').forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();

          const parent = this.closest('.nav-item-dropdown');
          if (!parent) return;

          // Don't open dropdowns when sidebar is collapsed on desktop
          if (window.innerWidth > 767.98 && body.classList.contains('sidebar-toggled')) {
            return;
          }

          // Close others if not collapsed
          if (!body.classList.contains('sidebar-toggled') || window.innerWidth <= 767.98) {
            document.querySelectorAll('.nav-item-dropdown.open').forEach(d => {
              if (d !== parent) d.classList.remove('open');
            });
          }

          parent.classList.toggle('open');

          // Save dropdown states
          const openDropdowns = [];
          document.querySelectorAll('.nav-item-dropdown.open').forEach(item => {
            const id = item.querySelector('.nav-link-dropdown').getAttribute('data-dropdown');
            openDropdowns.push(id);
          });
          localStorage.setItem('buenamente-open-dropdowns', JSON.stringify(openDropdowns));
        });
      });

      // Restore dropdown states
      const savedDropdowns = localStorage.getItem('buenamente-open-dropdowns');
      if (savedDropdowns && window.innerWidth > 767.98 && !body.classList.contains('sidebar-toggled')) {
        try {
          const openDropdowns = JSON.parse(savedDropdowns);
          openDropdowns.forEach(id => {
            const dropdown = document.querySelector(`[data-dropdown="${id}"]`);
            if (dropdown) {
              dropdown.closest('.nav-item-dropdown').classList.add('open');
            }
          });
        } catch (e) {
          console.error('Error restoring dropdowns:', e);
        }
      }

      function initializeMainContent() {
        const bodyChildren = Array.from(document.body.children);
        const sidebar = document.getElementById('sidebar');
        const mobileTopBar = document.querySelector('.mobile-top-bar');

        let mainContent = document.querySelector('.main-content');
        if (!mainContent) {
          mainContent = document.createElement('div');
          mainContent.className = 'main-content';
          document.body.appendChild(mainContent);
        }

        bodyChildren.forEach(child => {
          if (child !== sidebar && child !== mobileTopBar && !child.classList.contains('main-content')) {
            mainContent.appendChild(child);
          }
        });

        if (body.classList.contains('sidebar-toggled')) {
          mainContent.style.marginLeft = 'var(--sidebar-collapsed-width)';
          mainContent.style.width = 'calc(100% - var(--sidebar-collapsed-width))';
        } else {
          mainContent.style.marginLeft = 'var(--sidebar-width)';
          mainContent.style.width = 'calc(100% - var(--sidebar-width))';
        }

        return mainContent;
      }

      const mainContent = initializeMainContent();

      window.addEventListener('sidebarToggle', function(e) {
        if (mainContent) {
          if (e.detail.isToggled) {
            mainContent.style.marginLeft = 'var(--sidebar-collapsed-width)';
            mainContent.style.width = 'calc(100% - var(--sidebar-collapsed-width))';
          } else {
            mainContent.style.marginLeft = 'var(--sidebar-width)';
            mainContent.style.width = 'calc(100% - var(--sidebar-width))';
          }
        }
      });

      window.addEventListener('resize', function() {
        if (mainContent && window.innerWidth > 767.98) {
          if (body.classList.contains('sidebar-toggled')) {
            mainContent.style.marginLeft = 'var(--sidebar-collapsed-width)';
            mainContent.style.width = 'calc(100% - var(--sidebar-collapsed-width))';
          } else {
            mainContent.style.marginLeft = 'var(--sidebar-width)';
            mainContent.style.width = 'calc(100% - var(--sidebar-width))';
          }
        }
      });

      let resizeTimer;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
          if (window.innerWidth > 767.98) {
            body.classList.remove('sidebar-mobile-open');
            document.body.style.overflow = '';
          }
        }, 150);
      });

      // Close mobile sidebar on outside click
      document.addEventListener('click', function(e) {
        if (window.innerWidth <= 767.98 &&
          body.classList.contains('sidebar-mobile-open') &&
          !sidebar?.contains(e.target) &&
          !mobileToggle?.contains(e.target)) {
          body.classList.remove('sidebar-mobile-open');
          document.body.style.overflow = '';
        }
      });

      // ESC key
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          if (body.classList.contains('sidebar-mobile-open')) {
            body.classList.remove('sidebar-mobile-open');
            document.body.style.overflow = '';
          }

          if (!body.classList.contains('sidebar-toggled') || window.innerWidth <= 767.98) {
            document.querySelectorAll('.nav-item-dropdown.open').forEach(d => d.classList.remove('open'));
          }
        }
      });

      // Close mobile sidebar when clicking on navigation links
      document.querySelectorAll('#sidebar .nav-link:not(.nav-link-dropdown)').forEach(link => {
        link.addEventListener('click', function() {
          if (window.innerWidth <= 767.98) {
            body.classList.remove('sidebar-mobile-open');
            document.body.style.overflow = '';
          }
        });
      });
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', init);
    } else {
      init();
    }
  })();
</script>
