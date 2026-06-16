<?php include __DIR__ . '/../partials/admin_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-graph-up text-success"></i> Reports</h2>
</div>

<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-search"></i> Search Reports by Date</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2">
            <input type="hidden" name="page" value="admin">
            <input type="hidden" name="action" value="reports">
            <div class="col-md-4">
                <label class="form-label">Select Date</label>
                <input type="date" class="form-control" name="date" value="<?= htmlspecialchars($reportDate) ?>" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-search"></i> View Report
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card stats-card bg-success text-white">
            <div class="card-body text-center">
                <i class="bi bi-people display-5"></i>
                <h3 class="mt-2"><?= $customerCount ?></h3>
                <p class="mb-0">Customers on <?= date('d M Y', strtotime($reportDate)) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stats-card bg-warning text-dark">
            <div class="card-body text-center">
                <i class="bi bi-cash-coin display-5"></i>
                <h3 class="mt-2">TSh <?= number_format($totalRevenue, 2) ?></h3>
                <p class="mb-0">Revenue on <?= date('d M Y', strtotime($reportDate)) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-people"></i> Daily Customers Report</h5>
            </div>
            <div class="card-body">
                <?php if (empty($dailyCustomers)): ?>
                    <p class="text-muted text-center mb-0">No customers registered on this date.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Ticket #</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Seat</th>
                                    <th>Match</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dailyCustomers as $c): ?>
                                    <tr>
                                        <td><span class="badge bg-dark"><?= htmlspecialchars($c['ticket_number']) ?></span></td>
                                        <td><?= htmlspecialchars($c['full_name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($c['phone'] ?? '') ?></td>
                                        <td><?= $c['seat_number'] ?></td>
                                        <td><small><?= htmlspecialchars($c['team_a']) ?> vs <?= htmlspecialchars($c['team_b']) ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-cash-coin"></i> Daily Revenue Report</h5>
            </div>
            <div class="card-body">
                <?php if (empty($dailyRevenue)): ?>
                    <p class="text-muted text-center mb-0">No payments recorded on this date.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Customer</th>
                                    <th>Ticket #</th>
                                    <th>Match</th>
                                    <th>Amount (TSh)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dailyRevenue as $r): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($r['customer_name'] ?? 'N/A') ?></td>
                                        <td><span class="badge bg-dark"><?= htmlspecialchars($r['ticket_number']) ?></span></td>
                                        <td><small><?= htmlspecialchars($r['team_a']) ?> vs <?= htmlspecialchars($r['team_b']) ?></small></td>
                                        <td class="fw-bold text-success"><?= number_format($r['amount_paid'], 2) ?></td>
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

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>
