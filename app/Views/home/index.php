<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-vote-yea me-3"></i>
                    PilihanKita
                </h1>
                <p class="lead mb-4">
                    Sistem Pemilihan OSIS yang Modern, Aman, dan Transparan
                </p>
                <p class="fs-5 mb-4">
                    Periode: <strong><?= $current_period['name'] ?? 'Tidak Ada Periode Aktif' ?></strong>
                </p>
                
                <?php if (isset($current_period) && $current_period): ?>
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-6">
                            <div class="card bg-white bg-opacity-20 border-0">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Waktu Pemilihan</h5>
                                    <p class="card-text text-white">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        <?= date('d F Y, H:i', strtotime($current_period['start_date'])) ?> - 
                                        <?= date('d F Y, H:i', strtotime($current_period['end_date'])) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!session()->get('student_logged_in')): ?>
                        <a href="<?= base_url('auth/google') ?>" class="btn btn-light btn-lg px-5 py-3">
                            <i class="fab fa-google me-2"></i>
                            Login untuk Voting
                        </a>
                    <?php elseif (!session()->get('student_has_voted')): ?>
                        <a href="<?= base_url('voting') ?>" class="btn btn-light btn-lg px-5 py-3">
                            <i class="fas fa-vote-yea me-2"></i>
                            Mulai Voting
                        </a>
                    <?php else: ?>
                        <div class="alert alert-success d-inline-block">
                            <i class="fas fa-check-circle me-2"></i>
                            Anda sudah melakukan voting. Terima kasih!
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning d-inline-block">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Saat ini tidak ada periode pemilihan yang aktif.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<?php if (isset($voting_stats) && $voting_stats): ?>
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">
            <i class="fas fa-chart-bar me-2 text-primary"></i>
            Statistik Pemilihan
        </h2>
        
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h3 class="text-primary"><?= number_format($voting_stats['total_students']) ?></h3>
                        <p class="text-muted">Total Siswa</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-vote-yea fa-3x text-success mb-3"></i>
                        <h3 class="text-success"><?= number_format($voting_stats['students_voted']) ?></h3>
                        <p class="text-muted">Sudah Voting</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-percent fa-3x text-info mb-3"></i>
                        <h3 class="text-info"><?= number_format($voting_stats['voting_percentage'], 1) ?>%</h3>
                        <p class="text-muted">Partisipasi</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-user-graduate fa-3x text-warning mb-3"></i>
                        <h3 class="text-warning"><?= count($candidates ?? []) ?></h3>
                        <p class="text-muted">Kandidat</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Candidates Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">
            <i class="fas fa-users me-2 text-primary"></i>
            Kandidat Calon Ketua OSIS
        </h2>
        
        <?php if (isset($candidates) && !empty($candidates)): ?>
            <div class="row">
                <?php foreach ($candidates as $candidate): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="candidate-card">
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
                                
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-primary fs-6">
                                        #<?= $candidate['id'] ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="p-4">
                                <h4 class="card-title text-center mb-3"><?= esc($candidate['name']) ?></h4>
                                
                                <?php if (!empty($candidate['vision'])): ?>
                                    <div class="mb-3">
                                        <h6 class="text-primary">
                                            <i class="fas fa-eye me-2"></i>Visi
                                        </h6>
                                        <p class="text-muted small">
                                            <?= character_limiter(esc($candidate['vision']), 100) ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($candidate['mission'])): ?>
                                    <div class="mb-3">
                                        <h6 class="text-primary">
                                            <i class="fas fa-bullseye me-2"></i>Misi
                                        </h6>
                                        <p class="text-muted small">
                                            <?= character_limiter(esc($candidate['mission']), 100) ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="text-center">
                                    <div class="vote-count mb-3">
                                        <i class="fas fa-vote-yea me-2"></i>
                                        <strong><?= number_format($candidate['vote_count']) ?> Suara</strong>
                                    </div>
                                    
                                    <a href="<?= base_url('candidates/' . $candidate['id']) ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Detail Kandidat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-5x text-muted mb-4"></i>
                <h4 class="text-muted">Belum Ada Kandidat</h4>
                <p class="text-muted">Kandidat akan ditampilkan ketika tersedia.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Voting Results Chart Section -->
<?php if (isset($candidates) && !empty($candidates) && array_sum(array_column($candidates, 'vote_count')) > 0): ?>
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">
            <i class="fas fa-chart-pie me-2 text-primary"></i>
            Hasil Pemilihan Real-time
        </h2>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <canvas id="votingChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- How to Vote Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">
            <i class="fas fa-question-circle me-2 text-primary"></i>
            Cara Melakukan Voting
        </h2>
        
        <div class="row">
            <div class="col-md-3 text-center mb-4">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <span class="fs-2 fw-bold">1</span>
                </div>
                <h5>Login dengan Google</h5>
                <p class="text-muted">Gunakan akun Google sekolah untuk login</p>
            </div>
            
            <div class="col-md-3 text-center mb-4">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <span class="fs-2 fw-bold">2</span>
                </div>
                <h5>Input Data Siswa</h5>
                <p class="text-muted">Masukkan NIS dan pilih kelas Anda</p>
            </div>
            
            <div class="col-md-3 text-center mb-4">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <span class="fs-2 fw-bold">3</span>
                </div>
                <h5>Pilih Kandidat</h5>
                <p class="text-muted">Pilih satu kandidat yang Anda sukai</p>
            </div>
            
            <div class="col-md-3 text-center mb-4">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <span class="fs-2 fw-bold">4</span>
                </div>
                <h5>Download Bukti</h5>
                <p class="text-muted">Unduh bukti voting dalam format PDF</p>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('additional_js') ?>
<?php if (isset($candidates) && !empty($candidates) && array_sum(array_column($candidates, 'vote_count')) > 0): ?>
<script>
// Voting Results Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('votingChart').getContext('2d');
    
    const candidateNames = <?= json_encode(array_column($candidates, 'name')) ?>;
    const voteCounts = <?= json_encode(array_column($candidates, 'vote_count')) ?>;
    
    // Generate colors for each candidate
    const colors = [
        'rgba(255, 99, 132, 0.8)',
        'rgba(54, 162, 235, 0.8)',
        'rgba(255, 205, 86, 0.8)',
        'rgba(75, 192, 192, 0.8)',
        'rgba(153, 102, 255, 0.8)',
        'rgba(255, 159, 64, 0.8)'
    ];
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: candidateNames,
            datasets: [{
                data: voteCounts,
                backgroundColor: colors.slice(0, candidateNames.length),
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' suara (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
});
</script>
<?php endif; ?>
<?= $this->endSection() ?>