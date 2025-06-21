<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="bi bi-gear me-2"></i>Pengaturan Admin</h2>
            <p class="text-muted">Kelola profil dan keamanan akun admin Anda.</p>
        </div>
    </div>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"> <?= session()->getFlashdata('success') ?> </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-person-circle me-2"></i>Profil Admin
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin-system/settings/update') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?= esc($admin['full_name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= esc($admin['email']) ?>" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-shield-lock me-2"></i>Ubah Password
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin-system/settings/update') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="change_password" value="1">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Lama</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-secondary">
                                <i class="bi bi-key me-1"></i>Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 