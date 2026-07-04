<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kibanda Umiza - Football Viewing Center</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand fw-bold" href="?page=landing">
            <i class="bi bi-trophy-fill text-warning"></i> Kibanda Umiza
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="?page=landing#today-matches">Today's Matches</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=landing#upcoming-matches">Upcoming</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=landing#tickets">Tickets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=landing#features">Features</a>
                </li>
                <?php if ($auth->isLoggedIn()): ?>
                    <?php if ($auth->isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=admin&action=dashboard">
                                <i class="bi bi-shield-lock"></i> Admin Panel
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <span class="nav-link text-success">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                        </span>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link btn btn-outline-danger btn-sm px-3 rounded-pill" href="?page=auth&action=logout">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link btn btn-outline-light btn-sm px-3 rounded-pill" href="?page=auth&action=login">
                            <i class="bi bi-person"></i> Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
