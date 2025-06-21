<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold text-stats-purple mb-3">
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
                    <h5 class="card-title mb-0 text-stats-green"><i class="bi bi-list-ul me-2"></i>Daftar Kandidat</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: var(--dark-tertiary);">
                                <tr>
                                    <th class="text-stats-blue">#</th>
                                    <th class="text-stats-purple">Foto</th>
                                    <th class="text-stats-green">Nama</th>
                                    <th class="text-stats-yellow">Visi</th>
                                    <th class="text-stats-blue">Misi</th>
                                    <th class="text-stats-purple">Periode</th>
                                    <th class="text-stats-green">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($candidates as $i => $candidate): ?>
                                    <tr style="border-color: var(--border-color);">
                                        <td class="text-stats-blue"><?= $i + 1 ?></td>
                                        <td>
                                            <?php if ($candidate['photo']): ?>
                                                <img src="<?= base_url('uploads/candidates/' . $candidate['photo']) ?>" alt="Foto Kandidat" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                            <?php else: ?>
                                                <span class="text-secondary">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-stats-green fw-semibold"><?= esc($candidate['name']) ?></td>
                                        <td class="text-stats-yellow" style="max-width: 180px; white-space: pre-line;"><?= esc($candidate['vision']) ?></td>
                                        <td class="text-stats-blue" style="max-width: 180px; white-space: pre-line;"><?= esc($candidate['mission']) ?></td>
                                        <td class="text-stats-purple"><?= esc($candidate['period_name']) ?></td>
                                        <td>
                                            <a href="<?= base_url('admin-system/candidates/edit/' . $candidate['id']) ?>" class="btn btn-sm btn-warning me-1" style="background: var(--stats-yellow); border: none;"><i class="bi bi-pencil"></i></a>
                                            <a href="<?= base_url('admin-system/candidates/delete/' . $candidate['id']) ?>" class="btn btn-sm btn-danger" style="background: var(--accent-color); border: none;" onclick="return confirm('Yakin ingin menghapus kandidat ini?')"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($candidates)): ?>
                                    <tr><td colspan="7" class="text-center text-secondary">Belum ada kandidat.</td></tr>
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