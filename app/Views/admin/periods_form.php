<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <?= isset(
                    $period) ? 'Edit Periode' : 'Tambah Periode' ?>
            </h2>
            <a href="<?= base_url('admin-system/periods') ?>" class="btn btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Periode
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
                    <form action="<?= isset($period) ? base_url('admin-system/periods/update/' . $period['id']) : base_url('admin-system/periods/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Periode</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $period['name'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="2"><?= old('description', $period['description'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= old('start_date', isset($period['start_date']) ? date('Y-m-d', strtotime($period['start_date'])) : '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= old('end_date', isset($period['end_date']) ? date('Y-m-d', strtotime($period['end_date'])) : '') ?>" required>
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