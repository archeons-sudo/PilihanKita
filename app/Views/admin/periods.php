<?php
$this->extend('layouts/admin');
$this->section('content');
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold text-stats-purple mb-3">
                <i class="bi bi-calendar2-week me-2"></i>Manajemen Periode
            </h1>
            <a href="<?= base_url('admin-system/periods/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Periode
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"> <?= session()->getFlashdata('success') ?> </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 text-stats-green"><i class="bi bi-list-ul me-2"></i>Daftar Periode</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: var(--dark-tertiary);">
                        <tr>
                            <th class="text-stats-blue">#</th>
                            <th class="text-stats-purple">Nama Periode</th>
                            <th class="text-stats-green">Status</th>
                            <th class="text-stats-yellow">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($periods)): ?>
                            <?php foreach ($periods as $i => $period): ?>
                                <tr style="border-color: var(--border-color);">
                                    <td class="text-stats-blue"><?= $i+1 ?></td>
                                    <td class="text-stats-purple fw-semibold"><?= esc($period['name']) ?></td>
                                    <td>
                                        <?php if (!empty($activePeriod) && $activePeriod['id'] == $period['id']): ?>
                                            <span class="badge" style="background: var(--stats-green);">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge" style="background: var(--dark-tertiary);">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('admin-system/periods/edit/'.$period['id']) ?>" class="btn btn-sm btn-warning me-1">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                        <form action="<?= base_url('admin-system/periods/delete/'.$period['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin hapus periode ini?')">
                                            <button type="submit" class="btn btn-sm btn-danger me-1">
                                                <i class="bi bi-trash me-1"></i>Hapus
                                            </button>
                                        </form>
                                        <?php if (empty($activePeriod) || $activePeriod['id'] != $period['id']): ?>
                                            <form action="<?= base_url('admin-system/periods/activate/'.$period['id']) ?>" method="post" class="d-inline">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-circle me-1"></i>Aktifkan
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr style="border-color: var(--border-color);">
                                <td colspan="4" class="text-center text-muted">Belum ada data periode.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?> 