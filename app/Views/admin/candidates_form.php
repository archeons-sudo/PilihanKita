<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-gradient mb-3">
                <i class="bi bi-person-plus me-2"></i><?= isset($candidate) ? 'Edit Kandidat' : 'Tambah Kandidat' ?>
            </h1>
            <a href="<?= base_url('admin-system/candidates') ?>" class="btn btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Kandidat
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="<?= isset($candidate) ? base_url('admin-system/candidates/update/' . $candidate['id']) : base_url('admin-system/candidates/store') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kandidat</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $candidate['name'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Foto Kandidat <?= isset($candidate['photo']) && $candidate['photo'] ? '(Biarkan kosong jika tidak ingin mengubah)' : '' ?></label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" <?= isset($candidate) ? '' : 'required' ?>>
                            <?php if (isset($candidate['photo']) && $candidate['photo']): ?>
                                <div class="mt-2">
                                    <img src="<?= base_url('uploads/candidates/' . $candidate['photo']) ?>" alt="Foto Kandidat" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="vision" class="form-label">Visi</label>
                            <textarea class="form-control" id="vision" name="vision" rows="2" required><?= old('vision', $candidate['vision'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="mission" class="form-label">Misi</label>
                            <textarea class="form-control" id="mission" name="mission" rows="3" required><?= old('mission', $candidate['mission'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="period_id" class="form-label">Periode</label>
                            <select class="form-select" id="period_id" name="period_id" required>
                                <option value="">-- Pilih Periode --</option>
                                <?php foreach ($periods as $period): ?>
                                    <option value="<?= $period['id'] ?>" <?= old('period_id', $candidate['period_id'] ?? '') == $period['id'] ? 'selected' : '' ?>><?= esc($period['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 