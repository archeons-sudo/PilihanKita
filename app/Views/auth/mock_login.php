<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fab fa-google fa-3x text-danger mb-3"></i>
                        <h2 class="card-title">Login Siswa</h2>
                        <p class="text-muted">
                            Gunakan akun Google sekolah untuk masuk
                        </p>
                        
                        <!-- Development Notice -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Mode Development:</strong> Gunakan email dan password apapun untuk simulasi login Google.
                        </div>
                    </div>
                    
                    <?= form_open('auth/mock-authenticate', ['class' => 'needs-validation', 'novalidate' => '']) ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email Google
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?= old('email', 'student@example.com') ?>" 
                                   required
                                   placeholder="nama@example.com">
                            <div class="invalid-feedback">
                                Masukkan email yang valid.
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   value="password123"
                                   required
                                   placeholder="Password Anda">
                            <div class="invalid-feedback">
                                Masukkan password.
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="fab fa-google me-2"></i>
                                Lanjutkan dengan Google
                            </button>
                        </div>
                    <?= form_close() ?>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="text-muted mb-2">
                            <i class="fas fa-shield-alt me-2"></i>
                            Data Anda aman dan terlindungi
                        </p>
                        <small class="text-muted">
                            Dengan melanjutkan, Anda menyetujui penggunaan data untuk keperluan pemilihan OSIS.
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Information Card -->
            <div class="card mt-4 bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-question-circle me-2 text-primary"></i>
                        Mengapa perlu login dengan Google?
                    </h6>
                    <ul class="list-unstyled small text-muted mb-0">
                        <li><i class="fas fa-check text-success me-2"></i>Memastikan hanya siswa yang berhak yang dapat voting</li>
                        <li><i class="fas fa-check text-success me-2"></i>Mencegah voting ganda oleh siswa yang sama</li>
                        <li><i class="fas fa-check text-success me-2"></i>Menjaga keamanan dan integritas pemilihan</li>
                        <li><i class="fas fa-check text-success me-2"></i>Memberikan audit trail yang transparan</li>
                    </ul>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="<?= base_url() ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali ke Beranda
                </a>
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
</script>
<?= $this->endSection() ?>