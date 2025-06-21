<?php
$this->extend('layouts/admin');
$this->section('content');
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold text-stats-purple mb-3">
                <i class="bi bi-journal me-2"></i>Manajemen Kelas
            </h1>
            <a href="<?= base_url('admin-system/classes/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Kelas
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
            <h5 class="card-title mb-0 text-stats-green"><i class="bi bi-list-ul me-2"></i>Daftar Kelas</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: var(--dark-tertiary);">
                        <tr>
                            <th class="text-stats-blue">#</th>
                            <th class="text-stats-purple">Nama Kelas</th>
                            <th class="text-stats-green">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($classes)): ?>
                            <?php foreach ($classes as $i => $class): ?>
                                <tr style="border-color: var(--border-color);">
                                    <td class="text-stats-blue"><?= $i+1 ?></td>
                                    <td class="text-stats-purple fw-semibold"><?= esc($class['name']) ?></td>
                                    <td>
                                        <a href="<?= base_url('admin-system/classes/edit/'.$class['id']) ?>" class="btn btn-sm btn-warning me-1">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                        <form action="<?= base_url('admin-system/classes/delete/'.$class['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin hapus kelas ini?')">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash me-1"></i>Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr style="border-color: var(--border-color);">
                                <td colspan="3" class="text-center text-muted">Belum ada data kelas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?> 