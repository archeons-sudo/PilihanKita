<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Header -->
            <div class="card mb-4">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="bi bi-check-circle text-white" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    
                    <h2 class="text-success fw-bold mb-3">
                        🎉 Voting Berhasil!
                    </h2>
                    
                    <p class="lead text-muted mb-4">
                        Terima kasih atas partisipasi Anda dalam pemilihan Ketua OSIS. 
                        Suara Anda telah berhasil disimpan dengan aman.
                    </p>
                    
                    <div class="alert alert-success">
                        <h5 class="alert-heading">
                            <i class="bi bi-info-circle me-2"></i>
                            Vote ID: <?= esc($receipt['vote_id']) ?>
                        </h5>
                        <p class="mb-0">
                            Simpan ID ini sebagai referensi. Voting Anda telah tercatat pada: 
                            <strong><?= date('d/m/Y H:i:s', strtotime($receipt['vote_time'])) ?></strong>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Vote Details -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clipboard-check me-2"></i>
                        Detail Voting
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold text-muted">Nama Siswa:</td>
                                    <td><?= esc($receipt['student_name']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">NIS:</td>
                                    <td><?= esc($receipt['student_nis']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Kelas:</td>
                                    <td><?= esc($receipt['student_class']) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold text-muted">Periode:</td>
                                    <td><?= esc($receipt['period_name']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Waktu Voting:</td>
                                    <td><?= date('d/m/Y H:i:s', strtotime($receipt['vote_time'])) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Status:</td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Berhasil
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Candidate Selection -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-check me-2"></i>
                        Kandidat Pilihan Anda
                    </h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="bg-light rounded p-4">
                        <i class="bi bi-trophy text-warning display-1 mb-3"></i>
                        <h3 class="fw-bold text-primary">
                            <?= esc($receipt['candidate_name']) ?>
                        </h3>
                        <p class="text-muted mb-0">
                            Kandidat Ketua OSIS Periode <?= esc($receipt['period_name']) ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Vote Hash -->
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h6 class="mb-0 text-dark">
                        <i class="bi bi-shield-lock me-2"></i>
                        Hash Verifikasi Voting
                    </h6>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" 
                               class="form-control font-monospace small" 
                               value="<?= esc($receipt['vote_hash']) ?>" 
                               readonly
                               id="voteHash">
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Hash ini adalah bukti digital bahwa vote Anda telah tercatat dengan aman dan tidak dapat diubah.
                    </small>
                </div>
            </div>

            <!-- Download PDF Receipt -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>
                        Unduh Bukti Voting
                    </h5>
                    <p class="card-text text-muted mb-4">
                        Dapatkan bukti voting resmi dalam format PDF yang dapat Anda simpan atau cetak
                    </p>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="<?= base_url('voting/download-receipt') ?>" 
                           class="btn btn-danger btn-lg">
                            <i class="bi bi-download me-2"></i>
                            Unduh PDF Receipt
                        </a>
                        <button type="button" 
                                class="btn btn-outline-primary btn-lg" 
                                onclick="printReceipt()">
                            <i class="bi bi-printer me-2"></i>
                            Cetak Langsung
                        </button>
                    </div>
                </div>
            </div>

            <!-- Important Information -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-exclamation-triangle me-2 text-warning"></i>
                        Informasi Penting
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">✅ Yang Sudah Dilakukan:</h6>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check text-success me-2"></i>Vote telah disimpan dengan aman</li>
                                <li><i class="bi bi-check text-success me-2"></i>Data terenkripsi dan dilindungi</li>
                                <li><i class="bi bi-check text-success me-2"></i>Identitas tetap rahasia</li>
                                <li><i class="bi bi-check text-success me-2"></i>Bukti vote telah dibuat</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">📋 Yang Perlu Diingat:</h6>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-info text-primary me-2"></i>Vote tidak dapat diubah lagi</li>
                                <li><i class="bi bi-info text-primary me-2"></i>Simpan bukti PDF dengan baik</li>
                                <li><i class="bi bi-info text-primary me-2"></i>Hasil dapat dilihat di beranda</li>
                                <li><i class="bi bi-info text-primary me-2"></i>Pengumuman akan diinformasikan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="text-center mb-5">
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="<?= base_url() ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-house me-2"></i>
                        Kembali ke Beranda
                    </a>
                    <a href="<?= base_url('candidates') ?>" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-people me-2"></i>
                        Lihat Semua Kandidat
                    </a>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">
                        Atau <a href="<?= base_url('auth/logout') ?>" class="text-decoration-none">logout</a> 
                        jika Anda selesai menggunakan sistem
                    </small>
                </div>
            </div>

            <!-- Thank You Message -->
            <div class="alert alert-success border-0">
                <div class="text-center">
                    <h5 class="alert-heading text-success">
                        🙏 Terima Kasih!
                    </h5>
                    <p class="mb-0">
                        Partisipasi Anda sangat berarti untuk kemajuan OSIS dan sekolah. 
                        Demokrasi dimulai dari langkah kecil seperti ini!
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Modal -->
<div class="modal fade" id="printModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Bukti Voting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="printContent">
                <!-- Print content will be generated here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Cetak
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Copy hash to clipboard
    function copyToClipboard() {
        const hashInput = document.getElementById('voteHash');
        hashInput.select();
        hashInput.setSelectionRange(0, 99999); // For mobile devices
        
        navigator.clipboard.writeText(hashInput.value).then(function() {
            showToast('Hash berhasil disalin ke clipboard', 'success');
        }, function(err) {
            console.error('Could not copy text: ', err);
            showToast('Gagal menyalin hash', 'error');
        });
    }

    // Print receipt
    function printReceipt() {
        const printContent = `
            <div style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif;">
                <div style="text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 20px;">
                    <h2 style="color: #007bff; margin: 0;">🗳️ PilihanKita</h2>
                    <h3 style="margin: 10px 0;">BUKTI VOTING KETUA OSIS</h3>
                    <p style="color: #666; margin: 0;">Periode <?= esc($receipt['period_name']) ?></p>
                </div>
                
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold; width: 30%;">Nama Siswa:</td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?= esc($receipt['student_name']) ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">NIS:</td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?= esc($receipt['student_nis']) ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Kelas:</td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?= esc($receipt['student_class']) ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Waktu Voting:</td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?= date('d/m/Y H:i:s', strtotime($receipt['vote_time'])) ?></td>
                    </tr>
                </table>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745;">
                    <h4 style="color: #28a745; margin-bottom: 10px;">✅ VOTE BERHASIL DISIMPAN</h4>
                    <h3 style="margin: 0; color: #333;"><?= esc($receipt['candidate_name']) ?></h3>
                    <p style="margin: 5px 0 0 0; color: #666;">Kandidat Ketua OSIS yang Anda pilih</p>
                </div>
                
                <div style="background: #fff3cd; padding: 15px; border-radius: 5px; border: 1px solid #ffeaa7; margin-top: 20px;">
                    <h6 style="font-weight: bold; color: #856404; margin-bottom: 5px;">🔐 Hash Verifikasi:</h6>
                    <p style="font-family: monospace; font-size: 10px; color: #6c757d; word-break: break-all; margin: 0;"><?= esc($receipt['vote_hash']) ?></p>
                </div>
                
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; text-align: center; color: #6c757d; font-size: 12px;">
                    <p><strong>PilihanKita - Sistem Voting Ketua OSIS</strong><br>
                    Dicetak pada: ${new Date().toLocaleString('id-ID')}<br>
                    © ${new Date().getFullYear()} PilihanKita. Sistem ini dibuat untuk mendukung demokrasi siswa.</p>
                </div>
            </div>
        `;
        
        document.getElementById('printContent').innerHTML = printContent;
        new bootstrap.Modal(document.getElementById('printModal')).show();
    }

    // Auto focus to download button
    document.addEventListener('DOMContentLoaded', function() {
        // Add some celebration animation
        const successIcon = document.querySelector('.bg-success i');
        if (successIcon) {
            successIcon.style.animation = 'pulse 2s infinite';
        }
        
        // Show a congratulations toast
        setTimeout(() => {
            showToast('Selamat! Voting Anda berhasil disimpan 🎉', 'success');
        }, 1000);
    });

    // Add CSS for pulse animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    `;
    document.head.appendChild(style);

    // Prevent page refresh/back that might lose receipt data
    window.addEventListener('beforeunload', function(e) {
        // Don't show warning - user has completed voting
        return undefined;
    });
</script>
<?= $this->endSection() ?>