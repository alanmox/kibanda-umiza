<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="min-vh-100 d-flex align-items-center justify-content-center bg-login">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-circle display-3 text-success"></i>
                            <h2 class="mt-2 fw-bold">Sign In</h2>
                            <p class="text-muted">Kibanda Umiza Football Viewing Center</p>
                        </div>

                        <?php if (isset($loginError)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="bi bi-exclamation-triangle"></i> <?= $loginError ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($registerSuccess)): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="bi bi-check-circle"></i> <?= $registerSuccess ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="?page=auth&action=do_login" novalidate>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" id="username" name="username"
                                           required placeholder="Enter your username">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control" id="password" name="password"
                                           required placeholder="Enter your password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100 btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Sign In
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="?page=auth&action=register" class="text-decoration-none text-success">
                                <i class="bi bi-person-plus"></i> Don't have an account? Register
                            </a>
                        </div>
                        <div class="text-center mt-2">
                            <a href="?page=landing" class="text-decoration-none text-muted">
                                <i class="bi bi-arrow-left"></i> Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
