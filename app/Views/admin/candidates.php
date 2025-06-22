<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold text-gradient mb-3">
                <i class="bi bi-people me-2"></i>Manajemen Kandidat
            </h1>
            <a href="<?= base_url('admin-system/candidates/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Kandidat
            </a>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Daftar Kandidat</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Visi</th>
                                    <th>Misi</th>
                                    <th>Periode</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($candidates as $i => $candidate): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td>
                                            <?php if ($candidate['photo']): ?>
                                                <img src="<?= base_url('uploads/candidates/' . $candidate['photo']) ?>" alt="Foto Kandidat" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($candidate['name']) ?></td>
                                        <td style="max-width: 180px; white-space: pre-line;"> <?= esc($candidate['vision']) ?> </td>
                                        <td style="max-width: 180px; white-space: pre-line;"> <?= esc($candidate['mission']) ?> </td>
                                        <td><?= esc($candidate['period_name']) ?></td>
                                        <td>
                                            <a href="<?= base_url('admin-system/candidates/edit/' . $candidate['id']) ?>" class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil"></i></a>
                                            <form action="<?= base_url('admin-system/candidates/delete/' . $candidate['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus kandidat ini?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($candidates)): ?>
                                    <tr><td colspan="7" class="text-center text-muted">Belum ada kandidat.</td></tr>
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