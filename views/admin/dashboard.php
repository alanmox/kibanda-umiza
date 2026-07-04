<?php include __DIR__ . '/../partials/admin_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-speedometer2 text-success"></i> Dashboard</h2>
    <span class="text-muted">
        <i class="bi bi-person-circle"></i> Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>
    </span>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stats-card bg-success text-white h-100">
            <div class="card-body text-center">
                <i class="bi bi-trophy display-5"></i>
                <h3 class="mt-2"><?= $totalMatches ?></h3>
                <p class="mb-0">Total Matches</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-primary text-white h-100">
            <div class="card-body text-center">
                <i class="bi bi-people display-5"></i>
                <h3 class="mt-2"><?= $todayCustomers ?></h3>
                <p class="mb-0">Customers Today</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-warning text-dark h-100">
            <div class="card-body text-center">
                <i class="bi bi-cash-coin display-5"></i>
                <h3 class="mt-2">TSh <?= number_format($todayRevenue, 2) ?></h3>
                <p class="mb-0">Revenue Today</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-info text-white h-100">
            <div class="card-body text-center">
                <i class="bi bi-seat display-5"></i>
                <h3 class="mt-2"><?php
                    $totalAvail = 0;
                    foreach ($allMatches as $m) {
                        $totalAvail += $footballMatch->getAvailableSeats($m['id']);
                    }
                    echo $totalAvail;
                ?></h3>
                <p class="mb-0">Available Seats</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Upcoming Matches</h5>
            </div>
            <div class="card-body">
                <?php if (empty($upcomingMatches)): ?>
                    <p class="text-muted mb-0">No upcoming matches.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Match</th>
                                    <th>Competition</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Price</th>
                                    <th>Available Seats</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($upcomingMatches as $m): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($m['team_a']) ?></strong> vs <strong><?= htmlspecialchars($m['team_b']) ?></strong></td>
                                        <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($m['competition']) ?></span></td>
                                        <td><?= date('d M Y', strtotime($m['match_date'])) ?></td>
                                        <td><?= date('H:i', strtotime($m['match_time'])) ?></td>
                                        <td>TSh <?= number_format($m['ticket_price'], 2) ?></td>
                                        <td>
                                            <?php
                                            $avail = $footballMatch->getAvailableSeats($m['id']);
                                            ?>
                                            <span class="<?= $avail > 0 ? 'text-success' : 'text-danger' ?>">
                                                <?= $avail ?> / <?= $m['total_seats'] ?>
                                            </span>
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

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>
