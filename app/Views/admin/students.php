<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold text-gradient mb-3">
                <i class="bi bi-person-lines-fill me-2"></i>Manajemen Siswa
            </h1>
            <a href="<?= base_url('admin-system/students/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Siswa
            </a>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Daftar Siswa</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>NIS</th>
                                    <th>Kelas</th>
                                    <th>Status Voting</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $i => $student): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= esc($student['name']) ?></td>
                                        <td><?= esc($student['nis']) ?></td>
                                        <td><?= esc($student['class_name']) ?></td>
                                        <td>
                                            <?php if ($student['has_voted']): ?>
                                                <span class="badge bg-success">Sudah Voting</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Belum Voting</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin-system/students/edit/' . $student['id']) ?>" class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil"></i></a>
                                            <form action="<?= base_url('admin-system/students/delete/' . $student['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($students)): ?>
                                    <tr><td colspan="6" class="text-center text-muted">Belum ada data siswa.</td></tr>
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