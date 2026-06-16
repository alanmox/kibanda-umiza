<?php include __DIR__ . '/partials/header.php'; ?>

<section class="hero-section d-flex align-items-center">
    <div class="container text-center text-white">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="hero-icon mb-4">
                    <i class="bi bi-trophy-fill display-1 text-warning"></i>
                </div>
                <h1 class="display-3 fw-bold text-uppercase">Kibanda Umiza</h1>
                <p class="lead fs-3 mb-1">Football Viewing Center</p>
                <p class="fs-5 mb-4 text-success-light">Experience the thrill of live football on the big screen!</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="#today-matches" class="btn btn-success btn-lg px-4">
                        <i class="bi bi-calendar-check"></i> Today's Matches
                    </a>
                    <a href="#upcoming-matches" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-calendar-event"></i> Upcoming Matches
                    </a>
                </div>
                <div class="mt-4">
                    <a href="?page=admin&action=login" class="text-white-50 text-decoration-none small">
                        <i class="bi bi-lock"></i> Admin Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="today-matches" class="py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            <i class="bi bi-calendar-check text-success"></i> Today's Matches
        </h2>
        <?php if (empty($todayMatches)): ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> No matches scheduled for today. Check upcoming matches!
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($todayMatches as $m): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card match-card h-100">
                            <div class="card-body text-center">
                                <div class="match-teams">
                                    <div class="team-name"><?= htmlspecialchars($m['team_a']) ?></div>
                                    <div class="vs-badge">VS</div>
                                    <div class="team-name"><?= htmlspecialchars($m['team_b']) ?></div>
                                </div>
                                <div class="match-info mt-3">
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-trophy"></i> <?= htmlspecialchars($m['competition']) ?>
                                    </span>
                                    <p class="mt-2 mb-1">
                                        <i class="bi bi-clock"></i> <?= date('H:i', strtotime($m['match_time'])) ?>
                                    </p>
                                    <p class="mb-1">
                                        <i class="bi bi-currency-dollar"></i> TSh <?= number_format($m['ticket_price'], 2) ?>
                                    </p>
                                    <p class="mb-0">
                                        <i class="bi bi-seat"></i>
                                        <?php
                                        $matchObj = new Match();
                                        $avail = $matchObj->getAvailableSeats($m['id']);
                                        ?>
                                        <span class="<?= $avail > 0 ? 'text-success' : 'text-danger' ?>">
                                            <?= $avail ?> / <?= $m['total_seats'] ?> seats available
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section id="upcoming-matches" class="py-5 bg-dark">
    <div class="container">
        <h2 class="section-title text-center mb-5 text-white">
            <i class="bi bi-calendar-event text-success"></i> Upcoming Matches
        </h2>
        <?php if (empty($upcomingMatches)): ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> No upcoming matches scheduled.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($upcomingMatches as $m): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card match-card h-100">
                            <div class="card-body text-center">
                                <div class="match-teams">
                                    <div class="team-name"><?= htmlspecialchars($m['team_a']) ?></div>
                                    <div class="vs-badge">VS</div>
                                    <div class="team-name"><?= htmlspecialchars($m['team_b']) ?></div>
                                </div>
                                <div class="match-info mt-3">
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-trophy"></i> <?= htmlspecialchars($m['competition']) ?>
                                    </span>
                                    <p class="mt-2 mb-1">
                                        <i class="bi bi-calendar"></i> <?= date('d M Y', strtotime($m['match_date'])) ?>
                                    </p>
                                    <p class="mb-1">
                                        <i class="bi bi-clock"></i> <?= date('H:i', strtotime($m['match_time'])) ?>
                                    </p>
                                    <p class="mb-1">
                                        <i class="bi bi-currency-dollar"></i> TSh <?= number_format($m['ticket_price'], 2) ?>
                                    </p>
                                    <p class="mb-0">
                                        <i class="bi bi-seat"></i>
                                        <?php
                                        $matchObj = new Match();
                                        $avail = $matchObj->getAvailableSeats($m['id']);
                                        ?>
                                        <span class="<?= $avail > 0 ? 'text-success' : 'text-danger' ?>">
                                            <?= $avail ?> / <?= $m['total_seats'] ?> seats
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="py-5">
    <div class="container text-center">
        <h2 class="section-title mb-5">Ticket Prices</h2>
        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="card pricing-card h-100">
                    <div class="card-body">
                        <div class="pricing-icon mb-3">
                            <i class="bi bi-ticket-perforated display-4 text-success"></i>
                        </div>
                        <h4>Regular Match</h4>
                        <h3 class="text-success fw-bold">TSh 12,000 - 15,000</h3>
                        <p class="text-muted">Standard league matches</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card pricing-card h-100">
                    <div class="card-body">
                        <div class="pricing-icon mb-3">
                            <i class="bi bi-ticket-detailed display-4 text-warning"></i>
                        </div>
                        <h4>Premium Match</h4>
                        <h3 class="text-warning fw-bold">TSh 16,000 - 20,000</h3>
                        <p class="text-muted">Top-tier & derby matches</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card pricing-card h-100">
                    <div class="card-body">
                        <div class="pricing-icon mb-3">
                            <i class="bi bi-ticket-fill display-4 text-danger"></i>
                        </div>
                        <h4>VIP Section</h4>
                        <h3 class="text-danger fw-bold">TSh 25,000</h3>
                        <p class="text-muted">Premium seating & service</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
