<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-google text-white fs-3"></i>
                            </div>
                            <i class="bi bi-arrow-right text-muted fs-4"></i>
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center ms-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-person-check text-white fs-3"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold">Verifikasi Data Siswa</h3>
                        <p class="text-muted">
                            Halo, <strong><?= esc($google_user['name']) ?></strong>! <br>
                            Silakan lengkapi data berikut untuk melanjutkan.
                        </p>
                    </div>

                    <!-- Google User Info -->
                    <div class="alert alert-success d-flex align-items-center mb-4">
                        <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                        <div>
                            <strong>Login Google Berhasil</strong><br>
                            <small class="text-muted"><?= esc($google_user['email']) ?></small>
                        </div>
                    </div>

                    <!-- Verification Form -->
                    <form action="<?= base_url('auth/verify/process') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label for="nis" class="form-label fw-semibold">
                                <i class="bi bi-card-text me-1"></i>
                                Nomor Induk Siswa (NIS) *
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   id="nis" 
                                   name="nis" 
                                   placeholder="Masukkan NIS Anda" 
                                   value="<?= old('nis') ?>"
                                   required
                                   maxlength="20">
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Masukkan NIS sesuai dengan yang terdaftar di sekolah
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="class_id" class="form-label fw-semibold">
                                <i class="bi bi-building me-1"></i>
                                Kelas *
                            </label>
                            <select class="form-select form-select-lg" id="class_id" name="class_id" required>
                                <option value="">Pilih Kelas Anda</option>
                                <?php if (isset($classes)): ?>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?= $class['id'] ?>" <?= old('class_id') == $class['id'] ? 'selected' : '' ?>>
                                            <?= esc($class['name']) ?> - <?= esc($class['major']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Security Notice -->
                        <div class="alert alert-info mb-4">
                            <h6 class="alert-heading">
                                <i class="bi bi-shield-check me-1"></i>
                                Keamanan Data
                            </h6>
                            <ul class="mb-0 small">
                                <li>Data Anda akan disimpan dengan aman</li>
                                <li>NIS hanya digunakan untuk verifikasi identitas</li>
                                <li>Voting tetap bersifat rahasia</li>
                                <li>Akun Google tidak akan digunakan selain untuk login</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>
                                Verifikasi & Lanjutkan
                            </button>
                            <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>
                                Kembali & Logout
                            </a>
                        </div>
                    </form>

                    <!-- Help Section -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="text-primary mb-2">
                            <i class="bi bi-question-circle me-1"></i>
                            Butuh Bantuan?
                        </h6>
                        <div class="small text-muted">
                            <p class="mb-2">
                                <strong>NIS tidak dikenali?</strong><br>
                                Pastikan NIS yang Anda masukkan sesuai dengan data sekolah. 
                                Hubungi admin jika tetap bermasalah.
                            </p>
                            <p class="mb-0">
                                <strong>Kelas tidak ada?</strong><br>
                                Pilih kelas yang paling sesuai atau hubungi admin untuk menambahkan kelas baru.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const nis = document.getElementById('nis').value.trim();
        const classId = document.getElementById('class_id').value;
        
        if (!nis || nis.length < 6) {
            e.preventDefault();
            showToast('NIS harus diisi minimal 6 karakter', 'error');
            document.getElementById('nis').focus();
            return;
        }
        
        if (!classId) {
            e.preventDefault();
            showToast('Silakan pilih kelas Anda', 'error');
            document.getElementById('class_id').focus();
            return;
        }
        
        // Show loading state
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memverifikasi...';
        submitBtn.disabled = true;
        
        // Reset after 10 seconds in case of error
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });

    // NIS input formatting
    document.getElementById('nis').addEventListener('input', function(e) {
        // Remove non-numeric characters
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Limit length
        if (this.value.length > 20) {
            this.value = this.value.substring(0, 20);
        }
    });

    // Auto-focus on first field
    document.getElementById('nis').focus();
</script>
<?= $this->endSection() ?>