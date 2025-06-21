<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold text-stats-purple mb-3">
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
                    <h5 class="card-title mb-0 text-stats-green"><i class="bi bi-list-ul me-2"></i>Daftar Siswa</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: var(--dark-tertiary);">
                                <tr>
                                    <th class="text-stats-blue">#</th>
                                    <th class="text-stats-purple">Nama</th>
                                    <th class="text-stats-green">NIS</th>
                                    <th class="text-stats-yellow">Kelas</th>
                                    <th class="text-stats-blue">Status Voting</th>
                                    <th class="text-stats-purple">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $i => $student): ?>
                                    <tr style="border-color: var(--border-color);">
                                        <td class="text-stats-blue"><?= $i + 1 ?></td>
                                        <td class="text-stats-purple fw-semibold"><?= esc($student['name']) ?></td>
                                        <td class="text-stats-green"><?= esc($student['nis']) ?></td>
                                        <td class="text-stats-yellow"><?= esc($student['class_name']) ?></td>
                                        <td>
                                            <?php if ($student['has_voted']): ?>
                                                <span class="badge" style="background: var(--stats-green);">Sudah Voting</span>
                                            <?php else: ?>
                                                <span class="badge" style="background: var(--dark-tertiary);">Belum Voting</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin-system/students/edit/' . $student['id']) ?>" class="btn btn-sm btn-warning me-1" style="background: var(--stats-yellow); border: none;"><i class="bi bi-pencil"></i></a>
                                            <form action="<?= base_url('admin-system/students/delete/' . $student['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger" style="background: var(--accent-color); border: none;"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($students)): ?>
                                    <tr><td colspan="6" class="text-center text-secondary">Belum ada data siswa.</td></tr>
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