<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kibanda Umiza - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <a class="admin-brand" href="?page=admin&action=dashboard">
                <i class="bi bi-shield-fill-check"></i>
                <span>Kibanda Umiza</span>
            </a>

            <div class="admin-user-card">
                <i class="bi bi-person-circle"></i>
                <div>
                    <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong>
                    <small>Administrator</small>
                </div>
            </div>

            <nav class="admin-nav">
                <a class="admin-nav-link <?= (($action ?? '') === 'dashboard') ? 'active' : '' ?>" href="?page=admin&action=dashboard">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="admin-nav-link <?= (($action ?? '') === 'matches') ? 'active' : '' ?>" href="?page=admin&action=matches">
                    <i class="bi bi-trophy"></i> Matches
                </a>
                <a class="admin-nav-link <?= (($action ?? '') === 'customers') ? 'active' : '' ?>" href="?page=admin&action=customers">
                    <i class="bi bi-people"></i> Customers
                </a>
                <a class="admin-nav-link <?= (($action ?? '') === 'payments') ? 'active' : '' ?>" href="?page=admin&action=payments">
                    <i class="bi bi-cash-coin"></i> Payments
                </a>
                <a class="admin-nav-link <?= (($action ?? '') === 'reports') ? 'active' : '' ?>" href="?page=admin&action=reports">
                    <i class="bi bi-graph-up"></i> Reports
                </a>
                <a class="admin-nav-link <?= (($action ?? '') === 'change_password') ? 'active' : '' ?>" href="?page=admin&action=change_password">
                    <i class="bi bi-key"></i> Change Password
                </a>
            </nav>

            <a class="admin-nav-link danger" href="?page=admin&action=logout">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <div>
                    <p class="admin-topbar-label">Admin Panel</p>
                    <h4 class="mb-0">Manage your football viewing center</h4>
                </div>
                <div class="admin-topbar-badge">
                    <i class="bi bi-broadcast"></i> Live Operations
                </div>
            </header>

            <main class="admin-content">
                <div class="container-fluid py-3">
