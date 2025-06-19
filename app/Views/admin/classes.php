<?php
$this->extend('layouts/admin');
$this->section('content');
?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manajemen Kelas</h2>
        <a href="<?= base_url('admin-system/classes/create') ?>" class="btn btn-primary">Tambah Kelas</a>
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
                            <th>Nama Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($classes)): ?>
                            <?php foreach ($classes as $i => $class): ?>
                                <tr>
                                    <td><?= $i+1 ?></td>
                                    <td><?= esc($class['name']) ?></td>
                                    <td>
                                        <a href="<?= base_url('admin-system/classes/edit/'.$class['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="<?= base_url('admin-system/classes/delete/'.$class['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin hapus kelas ini?')">
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center">Belum ada data kelas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?> 