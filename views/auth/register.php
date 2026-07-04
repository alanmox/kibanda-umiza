<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="min-vh-100 d-flex align-items-center justify-content-center bg-login">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-plus-fill display-3 text-success"></i>
                            <h2 class="mt-2 fw-bold">Create Account</h2>
                            <p class="text-muted">Join Kibanda Umiza for easy bookings</p>
                        </div>

                        <?php if (isset($registerError)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="bi bi-exclamation-triangle"></i> <?= $registerError ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="?page=auth&action=do_register" novalidate>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" id="username" name="username"
                                           required placeholder="Choose a username" minlength="3">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email"
                                           required placeholder="Enter your email">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control" id="password" name="password"
                                           required placeholder="Minimum 6 characters" minlength="6">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                    <input type="password" class="form-control" id="confirm_password"
                                           name="confirm_password" required placeholder="Repeat your password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100 btn-lg">
                                <i class="bi bi-person-plus"></i> Register
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="?page=auth&action=login" class="text-decoration-none text-success">
                                <i class="bi bi-box-arrow-in-right"></i> Already have an account? Sign In
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
