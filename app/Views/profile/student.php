<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <div class="avatar-circle mx-auto mb-3" style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; font-weight: bold;">
                            <?= strtoupper(substr($student['name'], 0, 1)) ?>
                        </div>
                    </div>
                    
                    <h4 class="card-title mb-2"><?= esc($student['name']) ?></h4>
                    <p class="text-muted mb-3">
                        <i class="fas fa-id-card me-2"></i>
                        NIS: <?= esc($student['nis']) ?>
                    </p>
                    <p class="text-muted mb-3">
                        <i class="fas fa-graduation-cap me-2"></i>
                        <?= esc($student['class_name']) ?>
                    </p>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </button>
                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Current Election Status -->
            <?php if ($activePeriod): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-vote-yea me-2"></i>
                        Election Status - <?= esc($activePeriod['name']) ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Period:</strong> <?= esc($activePeriod['name']) ?>
                            </p>
                            <p class="mb-2">
                                <strong>Start Date:</strong> <?= date('d M Y', strtotime($activePeriod['start_date'])) ?>
                            </p>
                            <p class="mb-2">
                                <strong>End Date:</strong> <?= date('d M Y', strtotime($activePeriod['end_date'])) ?>
                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <?php if ($hasVoted): ?>
                                <div class="alert alert-success mb-0">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>You have voted!</strong>
                                    <br>
                                    <small>Thank you for participating in the election.</small>
                                </div>
                                <?php if ($voteReceipt): ?>
                                <a href="<?= base_url('profile/download-receipt/' . $activePeriod['id']) ?>" 
                                   class="btn btn-sm btn-outline-success mt-2">
                                    <i class="fas fa-download me-1"></i>Download Receipt
                                </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>You haven't voted yet!</strong>
                                    <br>
                                    <small>Please cast your vote before the election ends.</small>
                                </div>
                                <a href="<?= base_url('vote') ?>" class="btn btn-sm btn-primary mt-2">
                                    <i class="fas fa-vote-yea me-1"></i>Vote Now
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center py-4">
                    <i class="fas fa-calendar-times text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No Active Election</h5>
                    <p class="text-muted">There is currently no active election period.</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Voting History -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Voting History
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($votingHistory)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Election Period</th>
                                        <th>Vote Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($votingHistory as $vote): ?>
                                    <tr>
                                        <td><?= esc($vote['period_name']) ?></td>
                                        <td><?= date('d M Y H:i', strtotime($vote['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Voted
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('profile/download-receipt/' . $vote['period_id']) ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download me-1"></i>Receipt
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                            <h6 class="mt-3">No Voting History</h6>
                            <p class="text-muted">You haven't participated in any elections yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProfileForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= esc($student['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= esc($student['email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="<?= esc($student['phone'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?= esc($student['address'] ?? '') ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changePasswordForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Edit Profile Form
    $('#editProfileForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?= base_url('profile/update') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while updating profile.'
                });
            }
        });
    });

    // Change Password Form
    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?= base_url('profile/change-password') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#changePasswordModal').modal('hide');
                        $('#changePasswordForm')[0].reset();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while changing password.'
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?> 