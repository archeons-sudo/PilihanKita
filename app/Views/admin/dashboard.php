<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-primary mb-3">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin
            </h1>
            <p class="text-secondary">Selamat datang di panel admin PilihanKita. Pantau statistik pemilihan, kelola data, dan unduh hasil voting di sini.</p>
        </div>
    </div>
    <!-- Quick Access Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('admin-system/candidates') ?>" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="bi bi-person-badge fs-1 text-stats-purple"></i>
                        </div>
                        <h6 class="card-title mb-1">Manajemen Kandidat</h6>
                        <div class="text-secondary small">Tambah, edit, dan hapus kandidat</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('admin-system/students') ?>" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="bi bi-people fs-1 text-stats-green"></i>
                        </div>
                        <h6 class="card-title mb-1">Manajemen Siswa</h6>
                        <div class="text-secondary small">Kelola data siswa & kelas</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('admin-system/classes') ?>" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="bi bi-journal fs-1 text-stats-blue"></i>
                        </div>
                        <h6 class="card-title mb-1">Manajemen Kelas</h6>
                        <div class="text-secondary small">Kelola data kelas</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('admin-system/periods') ?>" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="bi bi-calendar2-week fs-1 text-stats-yellow"></i>
                        </div>
                        <h6 class="card-title mb-1">Manajemen Periode</h6>
                        <div class="text-secondary small">Kelola periode pemilihan</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-stats-purple mb-1"><?= $stats['total_students'] ?? 0 ?></h5>
                    <div class="text-secondary">Total Siswa</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-stats-green mb-1"><?= $stats['total_candidates'] ?? 0 ?></h5>
                    <div class="text-secondary">Total Kandidat</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-stats-yellow mb-1"><?= $stats['total_votes'] ?? 0 ?></h5>
                    <div class="text-secondary">Total Suara</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-stats-blue mb-1"><?= $stats['total_classes'] ?? 0 ?></h5>
                    <div class="text-secondary">Total Kelas</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart and Recent Votes -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-stats-purple"><i class="bi bi-pie-chart me-2"></i>Diagram Perolehan Suara</h5>
                    <?php if ($activePeriod): ?>
                        <span class="badge bg-primary">Periode: <?= esc($activePeriod['name']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <canvas id="adminVotingChart" height="180"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0 text-stats-green"><i class="bi bi-clock-history me-2"></i>Voting Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php if (!empty($recentVotes)): ?>
                            <?php foreach ($recentVotes as $vote): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center" style="background: var(--dark-secondary); border-color: var(--border-color);">
                                    <div>
                                        <span class="text-stats-purple fw-semibold"><?= esc($vote['student_name']) ?></span>
                                        <span class="badge bg-dark text-stats-blue ms-2">NIS: <?= esc($vote['nis']) ?></span>
                                        <br>
                                        <small class="text-stats-green">Kandidat: <span class="text-stats-yellow fw-semibold"><?= esc($vote['candidate_name']) ?></span></small>
                                    </div>
                                    <span class="text-secondary small"><i class="bi bi-clock me-1"></i><?= date('d/m H:i', strtotime($vote['created_at'])) ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-center text-secondary" style="background: var(--dark-secondary); border-color: var(--border-color);">Belum ada voting terbaru.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Candidate Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-stats-purple"><i class="bi bi-people me-2"></i>Rekap Kandidat</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: var(--dark-tertiary);">
                                <tr>
                                    <th class="text-stats-blue">#</th>
                                    <th class="text-stats-purple">Nama Kandidat</th>
                                    <th class="text-stats-green">Perolehan Suara</th>
                                    <th class="text-stats-yellow">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalVotes = array_sum(array_column($candidates, 'vote_count'));
                                ?>
                                <?php foreach ($candidates as $i => $candidate): ?>
                                    <?php $percentage = $totalVotes > 0 ? round(($candidate['vote_count'] / $totalVotes) * 100, 1) : 0; ?>
                                    <tr style="border-color: var(--border-color);">
                                        <td class="text-stats-blue fw-bold"><?= $i + 1 ?></td>
                                        <td class="text-stats-purple fw-semibold"><?= esc($candidate['name']) ?></td>
                                        <td><span class="text-stats-green fw-bold"><?= number_format($candidate['vote_count']) ?></span></td>
                                        <td><span class="text-stats-yellow fw-semibold"><?= $percentage ?>%</span></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($candidates)): ?>
                                    <tr><td colspan="4" class="text-center text-secondary">Belum ada kandidat.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Voting Chart
    <?php if (!empty($candidates)): ?>
    const adminChartData = {
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
                '#C084FC', // Purple
                '#34D399', // Green
                '#FBBF24', // Yellow
                '#60A5FA', // Blue
                '#F472B6', // Pink
                '#8B5CF6'  // Indigo
            ],
            borderWidth: 0,
            hoverOffset: 10
        }]
    };
    const adminChartConfig = {
        type: 'doughnut',
        data: adminChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#E2E8F0',
                        usePointStyle: true,
                        padding: 20,
                        font: { 
                            family: 'Space Grotesk',
                            size: 12,
                            weight: 'bold'
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
    new Chart(document.getElementById('adminVotingChart'), adminChartConfig);
    <?php endif; ?>
</script>
<?= $this->endSection() ?> 