<?php include __DIR__ . '/partials/header.php'; ?>

<section class="hero-section">
    <div class="hero-pattern"></div>
    <div class="hero-glow"></div>
    <div class="hero-glow-2"></div>

    <div class="container position-relative">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-7">
                <div class="hero-badge">
                    <i class="bi bi-broadcast"></i> Live Football Experience
                </div>
                <h1 class="hero-title">Feel Every<br>Goal Like Never Before</h1>
                <p class="hero-subtitle">Welcome to Kibanda Umiza — Tanzania's premier football viewing center. Watch live matches on massive screens with crystal-clear sound and electrifying atmosphere.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#today-matches" class="btn btn-success btn-lg px-4 rounded-pill">
                        <i class="bi bi-calendar-check"></i> Today's Matches
                    </a>
                    <a href="#tickets" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                        <i class="bi bi-ticket"></i> View Prices
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat-item">
                        <span class="hero-stat-number">50+</span>
                        <span class="hero-stat-label">Seats</span>
                    </div>
                    <div class="hero-stat-item">
                        <span class="hero-stat-number">4K</span>
                        <span class="hero-stat-label">Projection</span>
                    </div>
                    <div class="hero-stat-item">
                        <span class="hero-stat-number">24/7</span>
                        <span class="hero-stat-label">Service</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <div class="hero-image-wrapper">
                    <div class="hero-image-glow"></div>
                    <div class="hero-trophy">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div class="floating-ball"><i class="bi bi-circle-fill text-success"></i></div>
                    <div class="floating-ball"><i class="bi bi-circle-fill text-warning"></i></div>
                    <div class="floating-ball"><i class="bi bi-circle-fill text-danger"></i></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="today-matches" class="section">
    <div class="container">
        <div class="text-center">
            <span class="section-tag"><i class="bi bi-calendar-check"></i> Live Now</span>
            <h2 class="section-title">Today's Matches</h2>
            <p class="section-subtitle">Catch all the action live on our big screens. Grab your seat and enjoy the game!</p>
        </div>

        <?php if (empty($todayMatches)): ?>
            <div class="text-center py-5">
                <i class="bi bi-calendar-x display-1 text-muted"></i>
                <p class="text-muted mt-3 fs-5">No matches scheduled for today. Check upcoming matches!</p>
                <a href="#upcoming-matches" class="btn btn-success rounded-pill px-4 mt-2">
                    <i class="bi bi-calendar-event"></i> View Upcoming
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($todayMatches as $m):
                    $matchObj = new FootballMatch();
                    $avail = $matchObj->getAvailableSeats($m['id']);
                    $seatClass = $avail > 10 ? 'seat-available' : ($avail > 0 ? 'seat-limited' : 'seat-full');
                    $seatText = $avail > 0 ? $avail . ' / ' . $m['total_seats'] . ' seats left' : 'Fully booked';
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="match-card h-100">
                            <span class="match-status match-status-upcoming">
                                <i class="bi bi-dot"></i> Today
                            </span>
                            <div class="match-teams">
                                <div class="team-block">
                                    <div class="team-icon">
                                        <i class="bi bi-shield-fill-check"></i>
                                    </div>
                                    <div class="team-name"><?= htmlspecialchars($m['team_a']) ?></div>
                                </div>
                                <div class="vs-divider">VS</div>
                                <div class="team-block">
                                    <div class="team-icon">
                                        <i class="bi bi-shield-fill-exclamation"></i>
                                    </div>
                                    <div class="team-name"><?= htmlspecialchars($m['team_b']) ?></div>
                                </div>
                            </div>
                            <div class="match-details">
                                <div class="match-detail-item">
                                    <i class="bi bi-clock"></i> <?= date('H:i', strtotime($m['match_time'])) ?>
                                </div>
                                <div class="match-detail-item">
                                    <i class="bi bi-cash"></i> TSh <?= number_format($m['ticket_price'], 0) ?>
                                </div>
                                <div class="match-competition">
                                    <i class="bi bi-trophy"></i> <?= htmlspecialchars($m['competition']) ?>
                                </div>
                                <div class="mt-3">
                                    <span class="seat-info <?= $seatClass ?>">
                                        <i class="bi bi-seat"></i> <?= $seatText ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section id="upcoming-matches" class="section" style="background: var(--dark-2);">
    <div class="container">
        <div class="text-center">
            <span class="section-tag"><i class="bi bi-calendar-event"></i> Don't Miss</span>
            <h2 class="section-title">Upcoming Matches</h2>
            <p class="section-subtitle">Plan your visit and book your seat for the biggest football matches.</p>
        </div>

        <?php if (empty($upcomingMatches)): ?>
            <div class="text-center py-5">
                <i class="bi bi-calendar-week display-1 text-muted"></i>
                <p class="text-muted mt-3 fs-5">No upcoming matches scheduled yet. Check back soon!</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($upcomingMatches as $m):
                    $matchObj = new FootballMatch();
                    $avail = $matchObj->getAvailableSeats($m['id']);
                    $seatClass = $avail > 10 ? 'seat-available' : ($avail > 0 ? 'seat-limited' : 'seat-full');
                    $seatText = $avail > 0 ? $avail . ' / ' . $m['total_seats'] . ' seats' : 'Full';
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="match-card h-100">
                            <span class="match-status match-status-upcoming">
                                <i class="bi bi-calendar3"></i> <?= date('d M', strtotime($m['match_date'])) ?>
                            </span>
                            <div class="match-teams">
                                <div class="team-block">
                                    <div class="team-icon">
                                        <i class="bi bi-shield-fill-check"></i>
                                    </div>
                                    <div class="team-name"><?= htmlspecialchars($m['team_a']) ?></div>
                                </div>
                                <div class="vs-divider">VS</div>
                                <div class="team-block">
                                    <div class="team-icon">
                                        <i class="bi bi-shield-fill-exclamation"></i>
                                    </div>
                                    <div class="team-name"><?= htmlspecialchars($m['team_b']) ?></div>
                                </div>
                            </div>
                            <div class="match-details">
                                <div class="match-detail-item">
                                    <i class="bi bi-calendar"></i> <?= date('l, d M Y', strtotime($m['match_date'])) ?>
                                </div>
                                <div class="match-detail-item">
                                    <i class="bi bi-clock"></i> <?= date('H:i', strtotime($m['match_time'])) ?>
                                </div>
                                <div class="match-detail-item">
                                    <i class="bi bi-cash"></i> TSh <?= number_format($m['ticket_price'], 0) ?>
                                </div>
                                <div class="match-competition">
                                    <i class="bi bi-trophy"></i> <?= htmlspecialchars($m['competition']) ?>
                                </div>
                                <div class="mt-3">
                                    <span class="seat-info <?= $seatClass ?>">
                                        <i class="bi bi-seat"></i> <?= $seatText ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section id="book-seat" class="section" style="background: var(--dark-2);">
    <div class="container">
        <div class="text-center">
            <span class="section-tag"><i class="bi bi-ticket-perforated"></i> Book Your Seat</span>
            <h2 class="section-title">Reserve a spot for the next match</h2>
            <p class="section-subtitle">Choose a match, select your seat, and get a ticket number instantly.</p>
        </div>

        <?php if (!empty($bookingMessage)): ?>
            <div class="alert alert-success rounded-4">
                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($bookingMessage) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($bookingError)): ?>
            <div class="alert alert-danger rounded-4">
                <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($bookingError) ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 rounded-4 p-4 shadow-sm" style="background: var(--dark-1);">
                    <h4 class="mb-3 text-success"><i class="bi bi-calendar2-check"></i> New Booking</h4>
                    <form method="post">
                        <input type="hidden" name="register_booking" value="1">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="full_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="gender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Seat Number</label>
                                <input type="number" class="form-control" name="seat_number" min="1" max="100" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Match</label>
                                <select class="form-select" name="match_id" required>
                                    <?php foreach ($upcomingMatches as $m): ?>
                                        <option value="<?= (int)$m['id'] ?>">
                                            <?= htmlspecialchars($m['team_a']) ?> vs <?= htmlspecialchars($m['team_b']) ?> (<?= date('d M', strtotime($m['match_date'])) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Payment Option</label>
                                <select class="form-select" name="payment_option">
                                    <option value="reserve">Reserve only</option>
                                    <option value="pay_now">Pay now</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-4 rounded-pill px-4">
                            <i class="bi bi-check2-circle"></i> Confirm Booking
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 rounded-4 p-4 shadow-sm" style="background: var(--dark-1);">
                    <h4 class="mb-3 text-warning"><i class="bi bi-search"></i> Ticket Lookup</h4>
                    <form method="post">
                        <input type="hidden" name="lookup_ticket" value="1">
                        <label class="form-label">Ticket Number</label>
                        <input type="text" class="form-control" name="ticket_number" placeholder="Enter ticket number" required>
                        <button type="submit" class="btn btn-outline-warning mt-3 rounded-pill px-4">
                            <i class="bi bi-arrow-right-circle"></i> Check Booking
                        </button>
                    </form>

                    <?php if (!empty($bookingLookup)): ?>
                        <div class="alert alert-info rounded-4 mt-3 mb-0">
                            <strong>Ticket found</strong><br>
                            Name: <?= htmlspecialchars($bookingLookup['full_name']) ?><br>
                            Phone: <?= htmlspecialchars($bookingLookup['phone']) ?><br>
                            Seat: <?= (int)$bookingLookup['seat_number'] ?><br>
                            Match ID: <?= (int)$bookingLookup['match_id'] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="tickets" class="section">
    <div class="container">
        <div class="text-center">
            <span class="section-tag"><i class="bi bi-ticket-perforated"></i> Pricing</span>
            <h2 class="section-title">Ticket Prices</h2>
            <p class="section-subtitle">Affordable prices for every fan. Experience world-class football viewing.</p>
        </div>
        <div class="row justify-content-center g-4">
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <div class="pricing-icon regular">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>
                    <h4 class="pricing-name">Regular Match</h4>
                    <div class="pricing-price text-success">TSh 12,000</div>
                    <ul class="pricing-features">
                        <li><i class="bi bi-check-lg"></i> Standard seating</li>
                        <li><i class="bi bi-check-lg"></i> Big screen view</li>
                        <li><i class="bi bi-check-lg"></i> Free water</li>
                        <li><i class="bi bi-check-lg"></i> League matches</li>
                    </ul>
                    <a href="#upcoming-matches" class="btn btn-outline-success w-100 rounded-pill">
                        <i class="bi bi-calendar"></i> Book Now
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card featured">
                    <div class="pricing-icon premium">
                        <i class="bi bi-ticket-detailed"></i>
                    </div>
                    <h4 class="pricing-name">Premium Match</h4>
                    <div class="pricing-price text-warning">TSh 18,000</div>
                    <ul class="pricing-features">
                        <li><i class="bi bi-check-lg"></i> Premium seating</li>
                        <li><i class="bi bi-check-lg"></i> Center screen view</li>
                        <li><i class="bi bi-check-lg"></i> Free snacks & drink</li>
                        <li><i class="bi bi-check-lg"></i> All matches included</li>
                    </ul>
                    <a href="#upcoming-matches" class="btn btn-success w-100 rounded-pill">
                        <i class="bi bi-calendar"></i> Book Now
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <div class="pricing-icon vip">
                        <i class="bi bi-ticket-fill"></i>
                    </div>
                    <h4 class="pricing-name">VIP Section</h4>
                    <div class="pricing-price text-danger">TSh 25,000</div>
                    <ul class="pricing-features">
                        <li><i class="bi bi-check-lg"></i> VIP lounge access</li>
                        <li><i class="bi bi-check-lg"></i> Best screen position</li>
                        <li><i class="bi bi-check-lg"></i> Full meal & drinks</li>
                        <li><i class="bi bi-check-lg"></i> Dedicated service</li>
                    </ul>
                    <a href="#upcoming-matches" class="btn btn-outline-danger w-100 rounded-pill">
                        <i class="bi bi-calendar"></i> Book Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="section" style="background: var(--dark-2);">
    <div class="container">
        <div class="text-center">
            <span class="section-tag"><i class="bi bi-star"></i> Why Choose Us</span>
            <h2 class="section-title">The Ultimate Viewing Experience</h2>
            <p class="section-subtitle">Everything you need for an unforgettable match day experience.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-tv"></i></div>
                    <h5>4K Projection</h5>
                    <p>Crystal clear ultra-HD screens for every seat</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-speaker"></i></div>
                    <h5>Surround Sound</h5>
                    <p>Immersive audio that puts you in the stadium</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-cup-hot"></i></div>
                    <h5>Snacks & Drinks</h5>
                    <p>Full menu of refreshments and local snacks</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                    <h5>Secure & Safe</h5>
                    <p>24/7 security and comfortable environment</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
