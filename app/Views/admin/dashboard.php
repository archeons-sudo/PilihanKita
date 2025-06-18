<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-gradient mb-3">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin
            </h1>
            <p class="text-muted">Selamat datang di panel admin PilihanKita. Pantau statistik pemilihan, kelola data, dan unduh hasil voting di sini.</p>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-primary mb-1"><?= $stats['total_students'] ?? 0 ?></h5>
                    <div class="text-muted">Total Siswa</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-success mb-1"><?= $stats['total_candidates'] ?? 0 ?></h5>
                    <div class="text-muted">Total Kandidat</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-warning mb-1"><?= $stats['total_votes'] ?? 0 ?></h5>
                    <div class="text-muted">Total Suara</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="text-info mb-1"><?= $stats['total_classes'] ?? 0 ?></h5>
                    <div class="text-muted">Total Kelas</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Diagram Perolehan Suara</h5>
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
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Voting Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php if (!empty($recentVotes)): ?>
                            <?php foreach ($recentVotes as $vote): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-semibold"><?= esc($vote['student_name']) ?></span>
                                        <span class="badge bg-light text-dark ms-2">NIS: <?= esc($vote['nis']) ?></span>
                                        <br>
                                        <small class="text-muted">Kandidat: <span class="fw-semibold text-primary"><?= esc($vote['candidate_name']) ?></span></small>
                                    </div>
                                    <span class="text-muted small"><i class="bi bi-clock me-1"></i><?= date('d/m H:i', strtotime($vote['created_at'])) ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-center text-muted">Belum ada voting terbaru.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Rekap Kandidat</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Kandidat</th>
                                    <th>Perolehan Suara</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalVotes = array_sum(array_column($candidates, 'vote_count'));
                                ?>
                                <?php foreach ($candidates as $i => $candidate): ?>
                                    <?php $percentage = $totalVotes > 0 ? round(($candidate['vote_count'] / $totalVotes) * 100, 1) : 0; ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= esc($candidate['name']) ?></td>
                                        <td><span class="fw-bold text-primary"><?= number_format($candidate['vote_count']) ?></span></td>
                                        <td><span class="fw-semibold text-muted"><?= $percentage ?>%</span></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($candidates)): ?>
                                    <tr><td colspan="4" class="text-center text-muted">Belum ada kandidat.</td></tr>
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
                '#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'
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
                        usePointStyle: true,
                        padding: 20,
                        font: { family: 'Inter' }
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