<?php
$this->extend('layouts/admin');
$this->section('content');
?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manajemen Periode</h2>
        <a href="<?= base_url('admin-system/periods/create') ?>" class="btn btn-primary">Tambah Periode</a>
    </div>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"> <?= session()->getFlashdata('success') ?> </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($periods)): ?>
                            <?php foreach ($periods as $i => $period): ?>
                                <tr>
                                    <td><?= $i+1 ?></td>
                                    <td><?= esc($period['name']) ?></td>
                                    <td>
                                        <?php if (!empty($activePeriod) && $activePeriod['id'] == $period['id']): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('admin-system/periods/edit/'.$period['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="<?= base_url('admin-system/periods/delete/'.$period['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin hapus periode ini?')">
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                        <?php if (empty($activePeriod) || $activePeriod['id'] != $period['id']): ?>
                                            <form action="<?= base_url('admin-system/periods/activate/'.$period['id']) ?>" method="post" class="d-inline">
                                                <button type="submit" class="btn btn-sm btn-success">Aktifkan</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">Belum ada data periode.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?> 