<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <?= isset($class) ? 'Edit Kelas' : 'Tambah Kelas' ?>
            </h2>
            <a href="<?= base_url('admin-system/classes') ?>" class="btn btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Kelas
            </a>
        </div>
    </div>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="<?= isset($class) ? base_url('admin-system/classes/update/' . $class['id']) : base_url('admin-system/classes/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kelas</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $class['name'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="grade" class="form-label">Tingkat</label>
                            <select class="form-select" id="grade" name="grade" required>
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="10" <?= old('grade', $class['grade'] ?? '') == '10' ? 'selected' : '' ?>>10</option>
                                <option value="11" <?= old('grade', $class['grade'] ?? '') == '11' ? 'selected' : '' ?>>11</option>
                                <option value="12" <?= old('grade', $class['grade'] ?? '') == '12' ? 'selected' : '' ?>>12</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="major" class="form-label">Jurusan (opsional)</label>
                            <input type="text" class="form-control" id="major" name="major" value="<?= old('major', $class['major'] ?? '') ?>">
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