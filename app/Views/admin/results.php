<?php
$this->extend('layouts/admin');
$this->section('content');
?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Hasil Voting</h2>
        <div>
            <a href="<?= base_url('admin-system/results/export/excel') ?>" class="btn btn-success">Export Excel</a>
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
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Suara Masuk</h5>
                    <h2><?= esc($stats['total_voted'] ?? 0) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Siswa</h5>
                    <h2><?= esc($stats['total_students'] ?? 0) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Persentase Voting</h5>
                    <h2><?= esc($stats['voting_percentage'] ?? 0) ?>%</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Pilih Periode</h5>
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
        <div class="card-body">
            <h5 class="card-title">Hasil Voting Periode <?= esc($activePeriod['name'] ?? '-') ?></h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Kandidat</th>
                            <th>Jumlah Suara</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($results)): ?>
                            <?php $totalVotes = array_sum(array_column($results, 'vote_count')); ?>
                            <?php foreach ($results as $i => $row): ?>
                                <tr>
                                    <td><?= $i+1 ?></td>
                                    <td><?= esc($row['name']) ?></td>
                                    <td><?= esc($row['vote_count']) ?></td>
                                    <td><?= $totalVotes > 0 ? round(($row['vote_count']/$totalVotes)*100,2) : 0 ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-muted">Belum ada hasil voting atau tidak ada aktivitas di periode ini.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <canvas id="votingChart" height="100"></canvas>
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
                    '#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
<?php endif; ?>
</script>
<?php $this->endSection(); ?> 