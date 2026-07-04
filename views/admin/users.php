<?php include __DIR__ . '/../partials/admin_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-badge text-success"></i> Registered Users</h2>
    <span class="text-muted">Total: <?= count($users) ?> users</span>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($users)): ?>
            <p class="text-muted mb-0">No registered users yet.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Bookings</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $i => $u): ?>
                        <tr>
                            <td><?= (int)$u['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($u['username']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-success">User</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark"><?= (int)$u['booking_count'] ?></span>
                            </td>
                            <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>
