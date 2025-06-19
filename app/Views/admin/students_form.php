<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-gradient mb-3">
                <i class="bi bi-person-plus me-2"></i><?= isset($student) ? 'Edit Siswa' : 'Tambah Siswa' ?>
            </h1>
            <a href="<?= base_url('admin-system/students') ?>" class="btn btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Siswa
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="<?= isset($student) ? base_url('admin-system/students/update/' . $student['id']) : base_url('admin-system/students/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Siswa</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $student['name'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="nis" class="form-label">NIS</label>
                            <input type="text" class="form-control" id="nis" name="nis" value="<?= old('nis', $student['nis'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="class_id" class="form-label">Kelas</label>
                            <select class="form-select" id="class_id" name="class_id" required>
                                <option value="">-- Pilih Kelas --</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class['id'] ?>" <?= old('class_id', $student['class_id'] ?? '') == $class['id'] ? 'selected' : '' ?>><?= esc($class['name']) ?></option>
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