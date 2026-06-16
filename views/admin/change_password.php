<?php include __DIR__ . '/../partials/admin_header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-key"></i> Change Password</h5>
            </div>
            <div class="card-body">
                <?php if (isset($passwordMessage) && $passwordMessage): ?>
                    <?php if ($passwordMessage === 'Password changed successfully.'): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> <?= $passwordMessage ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> <?= $passwordMessage ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Current Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" name="current_password" required
                                   placeholder="Enter current password">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" name="new_password" required
                                   placeholder="Enter new password" minlength="6">
                        </div>
                        <small class="text-muted">Minimum 6 characters</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" name="confirm_password" required
                                   placeholder="Confirm new password" minlength="6"
                                   oninput="if(this.value !== this.form.new_password.value) this.setCustomValidity('Passwords do not match.'); else this.setCustomValidity('');">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-lg"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>
