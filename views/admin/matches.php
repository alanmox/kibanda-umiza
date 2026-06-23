<?php include __DIR__ . '/../partials/admin_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-trophy text-success"></i> Match Management</h2>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMatchModal">
        <i class="bi bi-plus-circle"></i> Add Match
    </button>
</div>

<?php if ($successMessage): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> <?= $successMessage ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($errorMessage): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle"></i> <?= $errorMessage ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="POST" class="row g-2 mb-4">
    <div class="col-md-10">
        <input type="text" class="form-control" name="keyword" placeholder="Search by team name or competition..." value="<?= htmlspecialchars($_POST['keyword'] ?? '') ?>">
    </div>
    <div class="col-md-2">
        <button type="submit" name="search" class="btn btn-dark w-100">
            <i class="bi bi-search"></i> Search
        </button>
    </div>
</form>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Team A</th>
                        <th>Team B</th>
                        <th>Competition</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Price (TSh)</th>
                        <th>Seats</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($footballMatches)): ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted">No matches found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($footballMatches as $idx => $m): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td><strong><?= htmlspecialchars($m['team_a']) ?></strong></td>
                                <td><strong><?= htmlspecialchars($m['team_b']) ?></strong></td>
                                <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($m['competition']) ?></span></td>
                                <td><?= date('d M Y', strtotime($m['match_date'])) ?></td>
                                <td><?= date('H:i', strtotime($m['match_time'])) ?></td>
                                <td><?= number_format($m['ticket_price'], 2) ?></td>
                                <td><?= $m['total_seats'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $m['status'] == 'upcoming' ? 'primary' : ($m['status'] == 'ongoing' ? 'warning' : 'secondary') ?>">
                                        <?= ucfirst($m['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#editMatchModal<?= $m['id'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteMatchModal<?= $m['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php foreach ($footballMatches as $m): ?>
<div class="modal fade" id="editMatchModal<?= $m['id'] ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Match</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Team A</label>
                            <input type="text" class="form-control" name="team_a" value="<?= htmlspecialchars($m['team_a']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Team B</label>
                            <input type="text" class="form-control" name="team_b" value="<?= htmlspecialchars($m['team_b']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Competition</label>
                            <input type="text" class="form-control" name="competition" value="<?= htmlspecialchars($m['competition']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="upcoming" <?= $m['status'] == 'upcoming' ? 'selected' : '' ?>>Upcoming</option>
                                <option value="ongoing" <?= $m['status'] == 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                                <option value="completed" <?= $m['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Match Date</label>
                            <input type="date" class="form-control" name="match_date" value="<?= $m['match_date'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Match Time</label>
                            <input type="time" class="form-control" name="match_time" value="<?= date('H:i', strtotime($m['match_time'])) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Total Seats</label>
                            <input type="number" class="form-control" name="total_seats" value="<?= $m['total_seats'] ?>" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ticket Price (TSh)</label>
                            <input type="number" step="0.01" class="form-control" name="ticket_price" value="<?= $m['ticket_price'] ?>" min="0.01" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit_match" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Match
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteMatchModal<?= $m['id'] ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this match?</p>
                <p class="fw-bold"><?= htmlspecialchars($m['team_a']) ?> vs <?= htmlspecialchars($m['team_b']) ?></p>
                <p class="text-danger"><i class="bi bi-exclamation-triangle"></i> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="delete_match" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<div class="modal fade" id="addMatchModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Add New Match</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Team A <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="team_a" required placeholder="e.g. Liverpool">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Team B <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="team_b" required placeholder="e.g. Manchester City">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Competition <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="competition" required placeholder="e.g. Premier League">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Match Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="match_date" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Match Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="match_time" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ticket Price (TSh) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="ticket_price" min="0.01" required placeholder="e.g. 15000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total Seats <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="total_seats" value="50" min="1" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_match" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Add Match
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>
