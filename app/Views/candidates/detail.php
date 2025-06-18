<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="row g-0">
                    <div class="col-md-5">
                        <div class="position-relative">
                            <?php if (!empty($candidate['photo']) && file_exists(FCPATH . 'uploads/candidates/' . $candidate['photo'])): ?>
                                <img src="<?= base_url('uploads/candidates/' . $candidate['photo']) ?>" 
                                     alt="<?= esc($candidate['name']) ?>" 
                                     class="img-fluid w-100 h-100" 
                                     style="object-fit: cover; min-height: 400px;">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center bg-light" style="min-height: 400px;">
                                    <i class="fas fa-user fa-5x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-primary fs-6">
                                    Kandidat #<?= $candidate['id'] ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-7">
                        <div class="card-body h-100 d-flex flex-column">
                            <div class="mb-4">
                                <h1 class="card-title display-6 fw-bold text-primary">
                                    <?= esc($candidate['name']) ?>
                                </h1>
                                
                                <?php if (isset($period) && $period): ?>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        Periode: <?= esc($period['name']) ?>
                                    </p>
                                <?php endif; ?>
                                
                                <div class="vote-count d-inline-block">
                                    <i class="fas fa-vote-yea me-2"></i>
                                    <strong><?= number_format($candidate['vote_count']) ?> Suara</strong>
                                </div>
                            </div>
                            
                            <?php if (!empty($candidate['vision'])): ?>
                                <div class="mb-4">
                                    <h4 class="text-primary mb-3">
                                        <i class="fas fa-eye me-2"></i>Visi
                                    </h4>
                                    <p class="fs-5 text-muted">
                                        <?= nl2br(esc($candidate['vision'])) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($candidate['mission'])): ?>
                                <div class="mb-4">
                                    <h4 class="text-primary mb-3">
                                        <i class="fas fa-bullseye me-2"></i>Misi
                                    </h4>
                                    <div class="fs-5 text-muted">
                                        <?= nl2br(esc($candidate['mission'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mt-auto">
                                <div class="d-grid gap-2">
                                    <?php if (session()->get('student_logged_in') && !session()->get('student_has_voted')): ?>
                                        <a href="<?= base_url('voting?candidate=' . $candidate['id']) ?>" 
                                           class="btn btn-primary btn-lg">
                                            <i class="fas fa-vote-yea me-2"></i>
                                            Pilih Kandidat Ini
                                        </a>
                                    <?php elseif (!session()->get('student_logged_in')): ?>
                                        <a href="<?= base_url('auth/google') ?>" 
                                           class="btn btn-primary btn-lg">
                                            <i class="fab fa-google me-2"></i>
                                            Login untuk Voting
                                        </a>
                                    <?php else: ?>
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Anda sudah melakukan voting. Terima kasih!
                                        </div>
                                    <?php endif; ?>
                                    
                                    <a href="<?= base_url('candidates') ?>" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Kembali ke Daftar Kandidat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Information Section -->
    <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100 bg-primary bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-trophy fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Posisi Kandidat</h5>
                            <p class="card-text text-muted">
                                Kandidat nomor <?= $candidate['id'] ?> untuk pemilihan periode ini
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100 bg-success bg-opacity-10">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Total Suara</h5>
                            <p class="card-text">
                                <span class="fs-3 fw-bold text-success"><?= number_format($candidate['vote_count']) ?></span>
                                <br>
                                <small class="text-muted">suara yang diperoleh</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Share Section -->
    <div class="row justify-content-center mt-4">
        <div class="col-lg-8">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-share-alt me-2 text-primary"></i>
                        Bagikan Kandidat Ini
                    </h5>
                    <p class="text-muted mb-3">
                        Ajak teman-teman untuk mengenal kandidat pilihan Anda
                    </p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-outline-primary" onclick="copyToClipboard()">
                            <i class="fas fa-copy me-2"></i>
                            Copy Link
                        </button>
                        
                        <a href="whatsapp://send?text=Lihat kandidat pilihan saya: <?= esc($candidate['name']) ?> - <?= current_url() ?>" 
                           class="btn btn-outline-success">
                            <i class="fab fa-whatsapp me-2"></i>
                            WhatsApp
                        </a>
                        
                        <a href="https://twitter.com/intent/tweet?text=Lihat kandidat pilihan saya: <?= esc($candidate['name']) ?>&url=<?= current_url() ?>" 
                           target="_blank" 
                           class="btn btn-outline-info">
                            <i class="fab fa-twitter me-2"></i>
                            Twitter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('additional_js') ?>
<script>
function copyToClipboard() {
    const url = window.location.href;
    
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(function() {
            // Show success message
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-success');
            
            setTimeout(function() {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-primary');
            }, 2000);
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = url;
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-success');
            
            setTimeout(function() {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-primary');
            }, 2000);
        } catch (err) {
            console.error('Failed to copy: ', err);
        }
        
        document.body.removeChild(textArea);
    }
}
</script>
<?= $this->endSection() ?>