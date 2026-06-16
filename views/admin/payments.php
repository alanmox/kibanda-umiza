<?php include __DIR__ . '/../partials/admin_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-cash-coin text-success"></i> Payment Recording</h2>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">
        <i class="bi bi-plus-circle"></i> Record Payment
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

<div class="card">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-receipt"></i> Payment History</h5>
        <span class="badge bg-success">Total: <?= count($payments) ?></span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Ticket #</th>
                        <th>Match</th>
                        <th>Amount (TSh)</th>
                        <th>Payment Date</th>
                        <th>Recorded At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($payments)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No payments recorded yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($payments as $idx => $p): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td><?= htmlspecialchars($p['customer_name'] ?? 'N/A') ?></td>
                                <td><span class="badge bg-dark"><?= htmlspecialchars($p['ticket_number']) ?></span></td>
                                <td>
                                    <strong><?= htmlspecialchars($p['team_a']) ?></strong> vs
                                    <strong><?= htmlspecialchars($p['team_b']) ?></strong>
                                    <br><small class="text-muted"><?= date('d M Y', strtotime($p['match_date'])) ?></small>
                                </td>
                                <td class="fw-bold text-success">TSh <?= number_format($p['amount_paid'], 2) ?></td>
                                <td><?= date('d M Y', strtotime($p['payment_date'])) ?></td>
                                <td><small class="text-muted"><?= date('d M Y H:i', strtotime($p['created_at'])) ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-cash"></i> Record Cash Payment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Customer <span class="text-danger">*</span></label>
                        <select class="form-select" name="customer_id" required>
                            <option value="">Select Customer</option>
                            <?php foreach ($allCustomers as $c): ?>
                                <option value="<?= $c['id'] ?>">
                                    #<?= htmlspecialchars($c['ticket_number']) ?> -
                                    <?= htmlspecialchars($c['full_name'] ?? 'N/A') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Match <span class="text-danger">*</span></label>
                        <select class="form-select" name="match_id" required>
                            <option value="">Select Match</option>
                            <?php foreach ($allMatches as $m): ?>
                                <option value="<?= $m['id'] ?>">
                                    <?= htmlspecialchars($m['team_a']) ?> vs <?= htmlspecialchars($m['team_b']) ?>
                                    (<?= date('d M Y', strtotime($m['match_date'])) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount Paid (TSh) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">TSh</span>
                            <input type="number" step="0.01" class="form-control" name="amount_paid"
                                   min="0.01" required placeholder="e.g. 15000">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="payment_date"
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="record_payment" class="btn btn-success">
                        <i class="bi bi-check-lg"></i> Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>
