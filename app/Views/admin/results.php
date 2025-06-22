<?php
$this->extend('layouts/admin');
$this->section('content');
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold text-stats-purple mb-3">
                <i class="bi bi-bar-chart me-2"></i>Hasil Voting
            </h1>
            <a href="<?= base_url('admin-system/results/export/excel') ?>" class="btn btn-success">
                <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"> <?= session()->getFlashdata('success') ?> </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-check-circle fs-1 text-stats-green"></i>
                    </div>
                    <h6 class="card-title mb-1">Total Suara Masuk</h6>
                    <h2 class="text-stats-green"><?= esc($stats['total_voted'] ?? 0) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-people fs-1 text-stats-blue"></i>
                    </div>
                    <h6 class="card-title mb-1">Total Siswa</h6>
                    <h2 class="text-stats-blue"><?= esc($stats['total_students'] ?? 0) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-percent fs-1 text-stats-purple"></i>
                    </div>
                    <h6 class="card-title mb-1">Persentase Voting</h6>
                    <h2 class="text-stats-purple"><?= esc($stats['voting_percentage'] ?? 0) ?>%</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-transparent">
            <h5 class="card-title mb-0 text-stats-yellow">
                <i class="bi bi-calendar-event me-2"></i>Pilih Periode
            </h5>
        </div>
        <div class="card-body">
            <form method="get" action="<?= base_url('admin-system/results') ?>">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <select name="period_id" class="form-select" onchange="this.form.submit()">
                            <?php foreach ($periods as $period): ?>
                                <option value="<?= $period['id'] ?>" <?= (!empty($activePeriod) && $activePeriod['id'] == $period['id']) ? 'selected' : '' ?>><?= esc($period['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-transparent">
            <h5 class="card-title mb-0 text-stats-green">
                <i class="bi bi-list-ul me-2"></i>Hasil Voting Periode <?= esc($activePeriod['name'] ?? '-') ?>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive mb-4">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-stats-blue">#</th>
                            <th class="text-stats-purple">Nama Kandidat</th>
                            <th class="text-stats-green">Jumlah Suara</th>
                            <th class="text-stats-yellow">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($results)): ?>
                            <?php $totalVotes = array_sum(array_column($results, 'vote_count')); ?>
                            <?php foreach ($results as $i => $row): ?>
                                <tr>
                                    <td class="text-stats-blue"><?= $i+1 ?></td>
                                    <td class="text-stats-purple fw-semibold"><?= esc($row['name']) ?></td>
                                    <td class="text-stats-green"><?= esc($row['vote_count']) ?></td>
                                    <td class="text-stats-yellow"><?= $totalVotes > 0 ? round(($row['vote_count']/$totalVotes)*100,2) : 0 ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada hasil voting atau tidak ada aktivitas di periode ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="chart-container" style="position: relative; height: 300px;">
                <canvas id="votingChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($results)): ?>
    const ctx = document.getElementById('votingChart').getContext('2d');
    const votingChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($results, 'name')) ?>,
            datasets: [{
                label: 'Jumlah Suara',
                data: <?= json_encode(array_column($results, 'vote_count')) ?>,
                backgroundColor: [
                    'rgba(147, 51, 234, 0.8)',  // Bright Purple
                    'rgba(34, 197, 94, 0.8)',   // Bright Green
                    'rgba(234, 179, 8, 0.8)',   // Bright Yellow
                    'rgba(59, 130, 246, 0.8)',  // Bright Blue
                    'rgba(236, 72, 153, 0.8)',  // Bright Pink
                    'rgba(14, 165, 233, 0.8)'   // Bright Sky Blue
                ],
                borderColor: [
                    'rgba(147, 51, 234, 1)',    // Solid Purple
                    'rgba(34, 197, 94, 1)',     // Solid Green
                    'rgba(234, 179, 8, 1)',     // Solid Yellow
                    'rgba(59, 130, 246, 1)',    // Solid Blue
                    'rgba(236, 72, 153, 1)',    // Solid Pink
                    'rgba(14, 165, 233, 1)'     // Solid Sky Blue
                ],
                borderWidth: 1,
                borderRadius: 6,
                hoverBackgroundColor: [
                    'rgba(147, 51, 234, 1)',    // Full Purple on hover
                    'rgba(34, 197, 94, 1)',     // Full Green on hover
                    'rgba(234, 179, 8, 1)',     // Full Yellow on hover
                    'rgba(59, 130, 246, 1)',    // Full Blue on hover
                    'rgba(236, 72, 153, 1)',    // Full Pink on hover
                    'rgba(14, 165, 233, 1)'     // Full Sky Blue on hover
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: false 
                },
                title: { 
                    display: false 
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(139, 90, 150, 0.2)',
                        borderColor: 'rgba(139, 90, 150, 0.3)',
                        borderWidth: 1
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.8)',
                        font: {
                            family: "'Space Grotesk', sans-serif"
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.8)',
                        font: {
                            family: "'Space Grotesk', sans-serif"
                        }
                    },
                    border: {
                        color: 'rgba(139, 90, 150, 0.3)'
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });
<?php endif; ?>
</script>
<?php $this->endSection(); ?> 