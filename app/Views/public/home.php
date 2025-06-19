<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">
                    Selamat Datang di PilihanKita
                </h1>
                <p class="lead mb-4">
                    Sistem voting ketua OSIS yang modern, aman, dan transparan. 
                    Suaramu menentukan masa depan organisasi siswa!
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <?php if (!session()->get('student_logged_in')): ?>
                        <a href="<?= base_url('auth/google') ?>" class="btn btn-light btn-lg google-btn">
                            <i class="bi bi-google me-2"></i>
                            Login dengan Google
                        </a>
                    <?php else: ?>
                        <?php if (!session()->get('student_has_voted')): ?>
                            <a href="<?= base_url('voting') ?>" class="btn btn-light btn-lg">
                                <i class="bi bi-hand-index me-2"></i>
                                Mulai Voting
                            </a>
                        <?php else: ?>
                            <div class="btn btn-success btn-lg disabled">
                                <i class="bi bi-check-circle me-2"></i>
                                Anda Sudah Voting
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="<?= base_url('candidates') ?>" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-people me-2"></i>
                        Lihat Kandidat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Election Info -->
    <?php if (isset($activePeriod)): ?>
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="card-title text-gradient">
                            <i class="bi bi-calendar-event me-2"></i>
                            Pemilihan Ketua OSIS <?= esc($activePeriod['name']) ?>
                        </h3>
                        <p class="card-text text-muted">
                            <?= esc($activePeriod['description']) ?>
                        </p>
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="glass-card p-3 rounded">
                                    <h5 class="text-primary"><?= $totalStudents ?? 0 ?></h5>
                                    <small class="text-muted">Total Siswa</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="glass-card p-3 rounded">
                                    <h5 class="text-success"><?= $votedStudents ?? 0 ?></h5>
                                    <small class="text-muted">Sudah Voting</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="glass-card p-3 rounded">
                                    <h5 class="text-warning"><?= $votingPercentage ?? 0 ?>%</h5>
                                    <small class="text-muted">Partisipasi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Live Results -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-white fw-bold">
                    <i class="bi bi-bar-chart me-2"></i>
                    Hasil Voting Real-Time
                </h2>
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm text-success me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <small class="text-muted">Update otomatis setiap 30 detik</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Candidates Grid -->
    <div class="row" id="candidatesContainer">
        <?php if (isset($candidates) && !empty($candidates)): ?>
            <?php 
            $totalVotes = array_sum(array_column($candidates, 'vote_count'));
            $maxVotes = max(array_column($candidates, 'vote_count'));
            ?>
            <?php foreach ($candidates as $index => $candidate): ?>
                <?php 
                $percentage = $totalVotes > 0 ? round(($candidate['vote_count'] / $totalVotes) * 100, 1) : 0;
                $isLeading = $candidate['vote_count'] == $maxVotes && $maxVotes > 0;
                ?>
                <div class="col-lg-6 col-xl-3 mb-4">
                    <div class="card candidate-card h-100 <?= $isLeading ? 'border-warning' : '' ?>">
                        <?php if ($isLeading): ?>
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-trophy"></i> Terdepan
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="position-relative">
                            <?php 
                            $photoPath = $candidate['photo'] ? 'uploads/candidates/' . $candidate['photo'] : 'https://via.placeholder.com/300x250?text=Foto+Kandidat';
                            ?>
                            <img src="<?= base_url($photoPath) ?>" class="candidate-image" alt="<?= esc($candidate['name']) ?>">
                            
                            <div class="position-absolute bottom-0 start-0 m-3">
                                <span class="vote-count-badge">
                                    <i class="bi bi-hand-thumbs-up me-1"></i>
                                    <?= number_format($candidate['vote_count']) ?> suara
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?= esc($candidate['name']) ?></h5>
                            
                            <!-- Vote Percentage -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">Perolehan Suara</small>
                                    <small class="fw-bold"><?= $percentage ?>%</small>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: <?= $percentage ?>%" 
                                         aria-valuenow="<?= $percentage ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Vision Preview -->
                            <div class="mb-3">
                                <h6 class="text-primary fw-semibold">
                                    <i class="bi bi-eye me-1"></i>
                                    Visi
                                </h6>
                                <p class="text-muted small">
                                <?= character_limiter(strip_tags($candidate['vision']), 300) ?>
                                </p>
                            </div>
                            
                            <div class="d-grid">
                                <button class="btn btn-outline-primary" 
                                        onclick="showCandidateDetail(<?= $candidate['id'] ?>)">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">Belum Ada Kandidat</h4>
                        <p class="text-muted">Kandidat untuk periode ini belum tersedia.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Voting Chart -->
    <?php if (isset($candidates) && !empty($candidates)): ?>
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-pie-chart me-2"></i>
                            Diagram Perolehan Suara
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="votingChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Call to Action -->
    <?php if (!session()->get('student_logged_in')): ?>
        <div class="row mt-5 mb-5">
            <div class="col-lg-8 mx-auto">
                <div class="card glass-card text-center">
                    <div class="card-body py-5">
                        <h3 class="text-white fw-bold mb-3">
                            Belum Voting? Yuk Berpartisipasi!
                        </h3>
                        <p class="text-light mb-4">
                            Login menggunakan akun Google Anda dan berikan suara untuk kandidat pilihan Anda.
                            Suara Anda sangat berharga untuk masa depan OSIS!
                        </p>
                        <a href="<?= base_url('auth/google') ?>" class="btn btn-light btn-lg">
                            <i class="bi bi-google me-2"></i>
                            Login Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Candidate Detail Modal -->
<div class="modal fade" id="candidateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Kandidat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="candidateModalBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Voting Chart
    <?php if (isset($candidates) && !empty($candidates)): ?>
    const chartData = {
        labels: [
            <?php foreach ($candidates as $candidate): ?>
                '<?= esc($candidate['name']) ?>',
            <?php endforeach; ?>
        ],
        datasets: [{
            data: [
                <?php foreach ($candidates as $candidate): ?>
                    <?= $candidate['vote_count'] ?>,
                <?php endforeach; ?>
            ],
            backgroundColor: [
                '#4f46e5',
                '#06b6d4', 
                '#10b981',
                '#f59e0b',
                '#ef4444',
                '#8b5cf6'
            ],
            borderWidth: 0,
            hoverOffset: 10
        }]
    };

    const chartConfig = {
        type: 'doughnut',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            family: 'Inter'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                            return context.label + ': ' + context.parsed + ' suara (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    };

    const chart = new Chart(document.getElementById('votingChart'), chartConfig);
    <?php endif; ?>

    // Show candidate detail
    function showCandidateDetail(candidateId) {
        fetch(`<?= base_url('api/candidate/') ?>${candidateId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const candidate = data.candidate;
                    const photoPath = candidate.photo ? 
                        '<?= base_url('uploads/candidates/') ?>' + candidate.photo : 
                        'https://via.placeholder.com/300x200?text=Foto+Kandidat';
                    
                    document.getElementById('candidateModalBody').innerHTML = `
                        <div class="row">
                            <div class="col-md-4">
                                <img src="${photoPath}" class="img-fluid rounded" alt="${candidate.name}">
                                <div class="text-center mt-3">
                                    <span class="badge bg-primary fs-6">
                                        <i class="bi bi-hand-thumbs-up me-1"></i>
                                        ${candidate.vote_count} suara
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h4 class="fw-bold">${candidate.name}</h4>
                                
                                <div class="mt-4">
                                    <h6 class="text-primary fw-semibold">
                                        <i class="bi bi-eye me-1"></i> Visi
                                    </h6>
                                    <p class="text-muted">${candidate.vision}</p>
                                </div>
                                
                                <div class="mt-4">
                                    <h6 class="text-primary fw-semibold">
                                        <i class="bi bi-target me-1"></i> Misi
                                    </h6>
                                    <div class="text-muted">${candidate.mission}</div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    new bootstrap.Modal(document.getElementById('candidateModal')).show();
                } else {
                    showToast('Gagal memuat detail kandidat', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat memuat data', 'error');
            });
    }

    // Auto refresh voting results every 30 seconds
    setInterval(() => {
        fetch('<?= base_url('api/voting-results') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateVotingResults(data.candidates);
                }
            })
            .catch(error => {
                console.error('Error refreshing data:', error);
            });
    }, 30000);

    function updateVotingResults(candidates) {
        // Update chart
        <?php if (isset($candidates) && !empty($candidates)): ?>
        if (chart) {
            chart.data.datasets[0].data = candidates.map(c => c.vote_count);
            chart.update();
        }
        <?php endif; ?>

        // Update vote counts and percentages
        const totalVotes = candidates.reduce((sum, c) => sum + c.vote_count, 0);
        
        candidates.forEach((candidate, index) => {
            const percentage = totalVotes > 0 ? ((candidate.vote_count / totalVotes) * 100).toFixed(1) : 0;
            
            // Update vote count badge
            const voteCountElement = document.querySelector(`[data-candidate-id="${candidate.id}"] .vote-count-badge`);
            if (voteCountElement) {
                voteCountElement.innerHTML = `<i class="bi bi-hand-thumbs-up me-1"></i>${candidate.vote_count.toLocaleString()} suara`;
            }
            
            // Update progress bar
            const progressBar = document.querySelector(`[data-candidate-id="${candidate.id}"] .progress-bar`);
            if (progressBar) {
                progressBar.style.width = percentage + '%';
                progressBar.setAttribute('aria-valuenow', percentage);
            }
            
            // Update percentage text
            const percentageElement = document.querySelector(`[data-candidate-id="${candidate.id}"] .percentage-text`);
            if (percentageElement) {
                percentageElement.textContent = percentage + '%';
            }
        });
    }
</script>
<?= $this->endSection() ?>