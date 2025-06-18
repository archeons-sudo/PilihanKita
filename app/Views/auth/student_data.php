<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-graduate fa-3x text-primary mb-3"></i>
                        <h2 class="card-title">Data Siswa</h2>
                        <p class="text-muted">
                            Masukkan NIS dan pilih kelas Anda
                        </p>
                        
                        <?php if (isset($google_data) && $google_data): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                Login Google berhasil sebagai: <strong><?= esc($google_data['name']) ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?= form_open('auth/student-data', ['class' => 'needs-validation', 'novalidate' => '']) ?>
                        <div class="mb-3">
                            <label for="nis" class="form-label">
                                <i class="fas fa-id-card me-2"></i>NIS (Nomor Induk Siswa)
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nis" 
                                   name="nis" 
                                   value="<?= old('nis') ?>" 
                                   required
                                   minlength="5"
                                   maxlength="20"
                                   placeholder="Contoh: 2024001001">
                            <div class="invalid-feedback">
                                NIS harus 5-20 karakter.
                            </div>
                            <div class="form-text">
                                Masukkan NIS sesuai dengan yang terdaftar di sekolah.
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="class_id" class="form-label">
                                <i class="fas fa-graduation-cap me-2"></i>Kelas
                            </label>
                            <select class="form-select" id="class_id" name="class_id" required>
                                <option value="">Pilih Kelas</option>
                                <?php if (isset($classes) && !empty($classes)): ?>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?= $class['id'] ?>" <?= old('class_id') == $class['id'] ? 'selected' : '' ?>>
                                            <?= esc($class['name']) ?> 
                                            (<?= esc($class['major'] ?? 'Umum') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">Tidak ada kelas tersedia</option>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback">
                                Pilih kelas Anda.
                            </div>
                        </div>
                        
                        <!-- Display errors if any -->
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-arrow-right me-2"></i>
                                Lanjutkan ke Voting
                            </button>
                            
                            <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Ganti Akun Google
                            </a>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
            
            <!-- Information Card -->
            <div class="card mt-4 bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Penting untuk Diketahui
                    </h6>
                    <ul class="list-unstyled small text-muted mb-0">
                        <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Pastikan NIS yang dimasukkan benar dan sesuai data sekolah</li>
                        <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Pilih kelas yang sesuai dengan kelas Anda saat ini</li>
                        <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Data yang salah dapat menyebabkan voting gagal</li>
                        <li><i class="fas fa-lock text-success me-2"></i>Voting hanya dapat dilakukan sekali per siswa</li>
                    </ul>
                </div>
            </div>
            
            <!-- Sample Data for Development -->
            <div class="card mt-4 border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-database me-2"></i>
                        Data Sample untuk Testing
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">Gunakan data berikut untuk testing:</p>
                    <div class="row">
                        <div class="col-6">
                            <strong>NIS Sample:</strong><br>
                            <code>2024001001</code><br>
                            <code>2024001002</code><br>
                            <code>2023001001</code>
                        </div>
                        <div class="col-6">
                            <strong>Kelas:</strong><br>
                            X-MIPA-1<br>
                            X-MIPA-2<br>
                            XI-MIPA-1
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('additional_js') ?>
<script>
// Bootstrap form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// NIS formatting
document.getElementById('nis').addEventListener('input', function(e) {
    // Remove non-numeric characters
    let value = e.target.value.replace(/\D/g, '');
    e.target.value = value;
});
</script>
<?= $this->endSection() ?>