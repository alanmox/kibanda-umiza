<?php include __DIR__ . '/../partials/header.php'; ?>

<section class="section" style="padding-top: 120px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card border-0 rounded-4 p-4 shadow-sm" style="background: var(--dark-1);">
                    <div class="text-center mb-3">
                        <div class="display-1 text-success">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <h4 class="fw-bold"><?= htmlspecialchars($user['username']) ?></h4>
                        <span class="badge bg-success rounded-pill">
                            <i class="bi bi-person"></i> Member
                        </span>
                    </div>
                    <hr class="border-secondary">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Total Bookings</span>
                        <span class="fw-bold"><?= count($bookings) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="text-muted">Total Spent</span>
                        <span class="fw-bold text-warning">TSh <?= number_format($totalSpent, 0) ?></span>
                    </div>
                </div>

                <div class="card border-0 rounded-4 p-4 shadow-sm mt-4" style="background: var(--dark-1);">
                    <h5 class="fw-bold mb-3"><i class="bi bi-key text-success"></i> Change Password</h5>
                    <?php if (isset($passwordMessage)): ?>
                        <div class="alert alert-<?= strpos($passwordMessage, 'successfully') !== false ? 'success' : 'danger' ?> alert-dismissible fade show">
                            <?= $passwordMessage ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="?page=user&action=change_password">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" required minlength="6">
                        </div>
                        <button type="submit" class="btn btn-success w-100 rounded-pill">
                            <i class="bi bi-check2"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 rounded-4 p-4 shadow-sm" style="background: var(--dark-1);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-ticket-perforated text-success"></i> My Bookings</h5>
                        <a href="?page=landing#book-seat" class="btn btn-sm btn-outline-success rounded-pill">
                            <i class="bi bi-plus"></i> Book New
                        </a>
                    </div>

                    <?php if (empty($bookings)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-ticket display-1 text-muted"></i>
                            <p class="text-muted mt-2">You haven't made any bookings yet.</p>
                            <a href="?page=landing#book-seat" class="btn btn-success rounded-pill">
                                <i class="bi bi-calendar-check"></i> Book a Match
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Ticket</th>
                                        <th>Match</th>
                                        <th>Date</th>
                                        <th>Seat</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $b): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-success">#<?= htmlspecialchars($b['ticket_number']) ?></span>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($b['team_a']) ?> vs <?= htmlspecialchars($b['team_b']) ?>
                                        </td>
                                        <td><?= date('d M Y', strtotime($b['match_date'])) ?></td>
                                        <td><?= (int)$b['seat_number'] ?></td>
                                        <td>TSh <?= number_format($b['ticket_price'], 0) ?></td>
                                        <td>
                                            <?php if ($b['match_status'] === 'upcoming'): ?>
                                                <span class="badge bg-warning text-dark">Upcoming</span>
                                            <?php elseif ($b['match_status'] === 'ongoing'): ?>
                                                <span class="badge bg-success">Live</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Completed</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
