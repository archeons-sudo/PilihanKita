<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-primary">
                    <i class="fas fa-users me-3"></i>
                    Daftar Kandidat
                </h1>
                <?php if (isset($current_period) && $current_period): ?>
                    <p class="lead text-muted">
                        Periode: <strong><?= esc($current_period['name']) ?></strong>
                    </p>
                    <p class="text-muted">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <?= date('d F Y, H:i', strtotime($current_period['start_date'])) ?> - 
                        <?= date('d F Y, H:i', strtotime($current_period['end_date'])) ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (isset($candidates) && !empty($candidates)): ?>
        <div class="row">
            <?php foreach ($candidates as $index => $candidate): ?>
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="candidate-card h-100">
                        <div class="position-relative">
                            <?php if (!empty($candidate['photo']) && file_exists(FCPATH . 'uploads/candidates/' . $candidate['photo'])): ?>
                                <img src="<?= base_url('uploads/candidates/' . $candidate['photo']) ?>" 
                                     alt="<?= esc($candidate['name']) ?>" 
                                     class="candidate-photo">
                            <?php else: ?>
                                <div class="candidate-photo d-flex align-items-center justify-content-center">
                                    <i class="fas fa-user fa-5x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-primary fs-6">
                                    Kandidat #<?= $candidate['id'] ?>
                                </span>
                            </div>
                            
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-success fs-6">
                                    <?= number_format($candidate['vote_count']) ?> Suara
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="card-title text-center mb-4"><?= esc($candidate['name']) ?></h3>
                            
                            <?php if (!empty($candidate['vision'])): ?>
                                <div class="mb-4">
                                    <h6 class="text-primary mb-2">
                                        <i class="fas fa-eye me-2"></i>Visi
                                    </h6>
                                    <p class="text-muted">
                                        <?= nl2br(esc($candidate['vision'])) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($candidate['mission'])): ?>
                                <div class="mb-4">
                                    <h6 class="text-primary mb-2">
                                        <i class="fas fa-bullseye me-2"></i>Misi
                                    </h6>
                                    <p class="text-muted">
                                        <?= nl2br(esc($candidate['mission'])) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="d-grid gap-2">
                                <a href="<?= base_url('candidates/' . $candidate['id']) ?>" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Lihat Detail
                                </a>
                                
                                <?php if (session()->get('student_logged_in') && !session()->get('student_has_voted')): ?>
                                    <a href="<?= base_url('voting?candidate=' . $candidate['id']) ?>" 
                                       class="btn btn-primary">
                                        <i class="fas fa-vote-yea me-2"></i>
                                        Pilih Kandidat Ini
                                    </a>
                                <?php elseif (!session()->get('student_logged_in')): ?>
                                    <a href="<?= base_url('auth/google') ?>" 
                                       class="btn btn-primary">
                                        <i class="fab fa-google me-2"></i>
                                        Login untuk Voting
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Voting Summary -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">
                            <i class="fas fa-chart-bar me-2 text-primary"></i>
                            Ringkasan Voting
                        </h4>
                        
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="h4 text-primary"><?= count($candidates) ?></div>
                                <div class="text-muted">Total Kandidat</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="h4 text-success"><?= number_format(array_sum(array_column($candidates, 'vote_count'))) ?></div>
                                <div class="text-muted">Total Suara</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <?php 
                                $topCandidate = !empty($candidates) ? $candidates[0] : null;
                                ?>
                                <div class="h4 text-warning">
                                    <?= $topCandidate ? number_format($topCandidate['vote_count']) : '0' ?>
                                </div>
                                <div class="text-muted">Suara Tertinggi</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="h4 text-info">
                                    <?php
                                    $totalVotes = array_sum(array_column($candidates, 'vote_count'));
                                    if ($totalVotes > 0 && $topCandidate) {
                                        echo number_format(($topCandidate['vote_count'] / $totalVotes) * 100, 1) . '%';
                                    } else {
                                        echo '0%';
                                    }
                                    ?>
                                </div>
                                <div class="text-muted">Persentase Tertinggi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    <?php else: ?>
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-users fa-5x text-muted mb-4"></i>
                    <h3 class="text-muted">Belum Ada Kandidat</h3>
                    <p class="text-muted mb-4">
                        Kandidat akan ditampilkan ketika sudah ditambahkan oleh admin.
                    </p>
                    
                    <?php if (!$current_period): ?>
                        <div class="alert alert-warning d-inline-block">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Tidak ada periode pemilihan yang aktif saat ini.
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-4">
                        <a href="<?= base_url() ?>" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>