<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="fw-bold text-gradient mb-4">
        <i class="bi bi-people me-2"></i>Daftar Kandidat
    </h1>
    <div class="row">
        <?php if (!empty($candidates)): ?>
            <?php foreach ($candidates as $candidate): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?= base_url($candidate['photo'] ? 'uploads/candidates/' . $candidate['photo'] : 'https://via.placeholder.com/300x250?text=Foto+Kandidat') ?>" class="card-img-top" alt="<?= esc($candidate['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($candidate['name']) ?></h5>
                            <p class="card-text"><strong>Visi:</strong> <?= nl2br(esc($candidate['vision'])) ?></p>
                            <p class="card-text"><strong>Misi:</strong> <?= nl2br(esc($candidate['mission'])) ?></p>
                            <span class="badge bg-primary"><?= number_format($candidate['vote_count']) ?> suara</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">Belum ada kandidat untuk periode ini.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>