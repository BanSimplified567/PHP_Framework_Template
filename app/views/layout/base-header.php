<?php
// This header is for admin and staff only
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title ?? 'PHP_Framecore - Admin Panel'; ?></title>
  <!-- jQuery (required for DataTables) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'primary-green': '#10b981',
            'secondary-green': '#059669',
            'light-green': '#d1fae5',
            'dark-green': '#065f46',
            'accent-green': '#34d399',
            'background-green': '#f0fdf4',
            'sidebar-bg': '<?php echo $config['color'] ?? '#1f2937'; ?>',
            'sidebar-dark': '<?php echo $config['darkColor'] ?? '#374151'; ?>',
            'success': '#10b981',
            'warning': '#fbbf24',
            'danger': '#ef4444',
            'info': '#3b82f6',
          },
          spacing: {
            'xs': '0.25rem',
            'sm': '0.5rem',
            'md': '0.75rem',
            'lg': '1rem',
          },
          fontSize: {
            'xs': '0.65rem',
            'sm': '0.7rem',
            'md': '0.8rem',
            'lg': '0.9rem',
          },
        }
      }
    }
  </script>

  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Bootstrap Icons (optional, can be replaced with Tailwind alternatives if needed) -->
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- DataTables CSS (plain version for Tailwind compatibility) -->
  <link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap"
    rel="stylesheet">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- jsPDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

</head>

<body class="bg-gray-50 font-sans">
