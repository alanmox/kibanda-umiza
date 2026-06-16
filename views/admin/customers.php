<?php include __DIR__ . '/../partials/admin_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people text-success"></i> Customer Registration</h2>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registerModal">
        <i class="bi bi-person-plus"></i> Register Customer
    </button>
</div>

<?php if (isset($successMessage) && $successMessage): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> <?= $successMessage ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($errorMessage) && $errorMessage): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle"></i> <?= $errorMessage ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter by Match</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2">
            <input type="hidden" name="page" value="admin">
            <input type="hidden" name="action" value="customers">
            <div class="col-md-10">
                <select class="form-select" name="match_id">
                    <option value="">-- All Customers --</option>
                    <?php foreach ($allMatches as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= (isset($_GET['match_id']) && $_GET['match_id'] == $m['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['team_a']) ?> vs <?= htmlspecialchars($m['team_b']) ?> - <?= date('d M Y', strtotime($m['match_date'])) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark w-100">
                    <i class="bi bi-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-table"></i> Registered Customers</h5>
        <span class="badge bg-success">Total: <?= count($customers) ?></span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <?php
            $displayCustomers = $customerMatch ?? $customers;
            ?>
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Ticket #</th>
                        <th>Full Name</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Seat</th>
                        <th>Match</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($displayCustomers)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No customers registered yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($displayCustomers as $idx => $c): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td><span class="badge bg-dark"><?= htmlspecialchars($c['ticket_number']) ?></span></td>
                                <td><?= htmlspecialchars($c['full_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['phone'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['gender']) ?></td>
                                <td><?= $c['seat_number'] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($c['team_a'] ?? '') ?></strong> vs
                                    <strong><?= htmlspecialchars($c['team_b'] ?? '') ?></strong>
                                </td>
                                <td><?= isset($c['match_date']) ? date('d M Y', strtotime($c['match_date'])) : date('d M Y', strtotime($c['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="registerModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-person-plus"></i> Register New Customer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?page=admin&action=customers" novalidate>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="full_name" required
                                   placeholder="Enter full name" maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="phone" required
                                   placeholder="e.g. 0712345678" maxlength="15">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-select" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Seat Number <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="seat_number" required
                                   placeholder="e.g. 1" min="1" max="999">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Match <span class="text-danger">*</span></label>
                            <select class="form-select" name="match_id" required>
                                <option value="">Select Match</option>
                                <?php foreach ($allMatches as $m): ?>
                                    <option value="<?= $m['id'] ?>" data-seats="<?= $footballMatch->getAvailableSeats($m['id']) ?>">
                                        <?= htmlspecialchars($m['team_a']) ?> vs <?= htmlspecialchars($m['team_b']) ?>
                                        (<?= date('d M', strtotime($m['match_date'])) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="register_customer" class="btn btn-success">
                        <i class="bi bi-person-check"></i> Register & Generate Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>
