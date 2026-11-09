<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Error - PHP Framecore</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --fc-accent: #54e5c7;
      --fc-dark: #2b2e33;
      --fc-dark-soft: #3a3e45;
      --fc-text: #2b2e33;
      --fc-muted: #6b7280;
      --fc-border: #e5e7eb;
      --fc-bg: #f3f4f6;
      --fc-surface: #ffffff;
      --bs-body-font-family: "Inter", system-ui, -apple-system, sans-serif;
      --bs-body-color: var(--fc-text);
    }

    body {
      min-height: 100vh;
      margin: 0;
      background-color: var(--fc-bg);
      background-image:
        radial-gradient(ellipse 70% 45% at 15% 10%, rgba(84, 229, 199, 0.1), transparent),
        linear-gradient(180deg, #f3f4f6 0%, #e9ecef 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    .error-panel {
      background: var(--fc-surface);
      border: 1px solid rgba(229, 231, 235, 0.95);
      border-radius: 1.25rem;
      box-shadow:
        0 1px 2px rgba(43, 46, 51, 0.04),
        0 12px 36px -14px rgba(43, 46, 51, 0.14);
      width: 100%;
      max-width: 560px;
      padding: 2.5rem;
      text-align: center;
    }

    .error-icon {
      width: 3.5rem;
      height: 3.5rem;
      border-radius: 1rem;
      background: #fef2f2;
      color: #b91c1c;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 1.25rem;
    }

    .error-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--fc-dark);
      margin: 0 0 0.5rem;
    }

    .error-message {
      font-size: 0.9375rem;
      color: var(--fc-muted);
      margin: 0 0 1.75rem;
      line-height: 1.5;
    }

    .error-actions {
      display: flex;
      gap: 0.75rem;
      justify-content: center;
      flex-wrap: wrap;
    }

    .btn-primary-fc {
      height: 2.75rem;
      border: 0;
      border-radius: 0.75rem;
      background: var(--fc-dark);
      color: #fff;
      font-size: 0.9375rem;
      font-weight: 600;
      padding: 0 1.5rem;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: background-color 0.15s ease;
    }

    .btn-primary-fc:hover {
      background: var(--fc-dark-soft);
      color: #fff;
    }

    .btn-outline-fc {
      height: 2.75rem;
      border: 1px solid var(--fc-border);
      border-radius: 0.75rem;
      background: #fff;
      color: var(--fc-text);
      font-size: 0.9375rem;
      font-weight: 500;
      padding: 0 1.5rem;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.15s ease;
    }

    .btn-outline-fc:hover {
      border-color: #cfd6e0;
      background: #f8fafc;
      color: var(--fc-text);
    }
  </style>
</head>

<body>
  <div class="error-panel">
    <div class="error-icon" aria-hidden="true"><i class="bi bi-exclamation-triangle"></i></div>
    <h2 class="error-title">Oops! Something went wrong</h2>
    <p class="error-message">
      We encountered an error while processing your request.
      Please try again later or contact the system administrator.
    </p>
    <div class="error-actions">
      <a href="index.php?action=dashboard" class="btn-primary-fc">Go to dashboard</a>
      <a href="index.php?action=login" class="btn-outline-fc">Sign in</a>
    </div>
  </div>
</body>

</html>
