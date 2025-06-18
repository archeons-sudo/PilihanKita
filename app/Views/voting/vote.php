<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="container">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body text-center py-4">
                    <h2 class="fw-bold mb-2">
                        <i class="bi bi-vote-fill me-2"></i>
                        Voting Ketua OSIS
                    </h2>
                    <p class="mb-3">
                        Periode <?= esc($activePeriod['name']) ?> - <?= esc($activePeriod['description']) ?>
                    </p>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Nama:</strong> <?= esc($student['name']) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>NIS:</strong> <?= esc($student['nis']) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Kelas:</strong> <?= esc($student['class']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h5 class="alert-heading">
                    <i class="bi bi-info-circle me-2"></i>
                    Petunjuk Voting
                </h5>
                <ul class="mb-0">
                    <li>Pilih <strong>SATU</strong> kandidat yang Anda inginkan sebagai Ketua OSIS</li>
                    <li>Baca visi dan misi masing-masing kandidat dengan cermat</li>
                    <li>Setelah memilih, klik tombol "Vote" untuk mengkonfirmasi</li>
                    <li>Anda <strong>HANYA BISA VOTING SEKALI</strong> - pilihan tidak bisa diubah</li>
                    <li>Setelah voting berhasil, Anda akan mendapat bukti voting dalam bentuk PDF</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Voting Form -->
    <form id="votingForm" action="<?= base_url('voting/process') ?>" method="POST">
        <?= csrf_field() ?>
        
        <!-- Candidates -->
        <div class="row" id="candidatesContainer">
            <?php foreach ($candidates as $index => $candidate): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card candidate-vote-card h-100" data-candidate-id="<?= $candidate['id'] ?>">
                        <div class="position-relative">
                            <?php 
                            $photoPath = $candidate['photo'] ? 'uploads/candidates/' . $candidate['photo'] : 'https://via.placeholder.com/400x300?text=Foto+Kandidat';
                            ?>
                            <img src="<?= base_url($photoPath) ?>" class="card-img-top candidate-image" alt="<?= esc($candidate['name']) ?>" style="height: 250px; object-fit: cover;">
                            
                            <!-- Vote Radio Button Overlay -->
                            <div class="position-absolute top-0 end-0 m-3">
                                <input type="radio" 
                                       class="btn-check candidate-radio" 
                                       name="candidate_id" 
                                       value="<?= $candidate['id'] ?>" 
                                       id="candidate_<?= $candidate['id'] ?>"
                                       required>
                                <label class="btn btn-outline-light btn-lg" for="candidate_<?= $candidate['id'] ?>">
                                    <i class="bi bi-circle"></i>
                                    <span class="selected-icon d-none">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                </label>
                            </div>
                            
                            <!-- Vote Count Badge -->
                            <div class="position-absolute bottom-0 start-0 m-3">
                                <span class="badge bg-dark bg-opacity-75 fs-6">
                                    <i class="bi bi-hand-thumbs-up me-1"></i>
                                    <?= number_format($candidate['vote_count']) ?> suara
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h4 class="card-title fw-bold text-center">
                                <?= esc($candidate['name']) ?>
                            </h4>
                            
                            <!-- Vision -->
                            <div class="mb-3">
                                <h6 class="text-primary fw-semibold">
                                    <i class="bi bi-eye me-1"></i>
                                    Visi
                                </h6>
                                <p class="text-muted small">
                                    <?= nl2br(esc($candidate['vision'])) ?>
                                </p>
                            </div>
                            
                            <!-- Mission -->
                            <div class="mb-3">
                                <h6 class="text-primary fw-semibold">
                                    <i class="bi bi-target me-1"></i>
                                    Misi
                                </h6>
                                <div class="text-muted small">
                                    <?= $candidate['mission'] ?>
                                </div>
                            </div>
                            
                            <!-- Vote Button -->
                            <div class="d-grid">
                                <button type="button" 
                                        class="btn btn-outline-primary btn-lg vote-btn" 
                                        data-candidate-id="<?= $candidate['id'] ?>"
                                        data-candidate-name="<?= esc($candidate['name']) ?>">
                                    <i class="bi bi-hand-index me-2"></i>
                                    Pilih Kandidat Ini
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Submit Section -->
        <div class="row mt-4" id="submitSection" style="display: none;">
            <div class="col-12">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <h5 class="text-success fw-bold">
                            <i class="bi bi-check-circle me-2"></i>
                            Pilihan Anda: <span id="selectedCandidateName"></span>
                        </h5>
                        <p class="text-muted mb-4">
                            Pastikan pilihan Anda sudah benar. Setelah mengkonfirmasi, pilihan tidak dapat diubah.
                        </p>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button type="button" class="btn btn-outline-secondary btn-lg" onclick="resetSelection()">
                                <i class="bi bi-arrow-left me-2"></i>
                                Ubah Pilihan
                            </button>
                            <button type="submit" class="btn btn-success btn-lg" id="confirmVoteBtn">
                                <i class="bi bi-check-circle me-2"></i>
                                Konfirmasi & Vote
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Security Notice -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <h6 class="alert-heading">
                    <i class="bi bi-shield-exclamation me-2"></i>
                    Penting untuk Diketahui
                </h6>
                <div class="small">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li>Voting bersifat <strong>rahasia dan anonim</strong></li>
                                <li>Admin tidak dapat melihat pilihan Anda</li>
                                <li>Setiap siswa hanya bisa voting <strong>satu kali</strong></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li>Data voting dienkripsi dengan aman</li>
                                <li>Bukti voting akan diberikan setelah selesai</li>
                                <li>Hasil voting real-time dapat dilihat di beranda</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Konfirmasi Voting
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="bi bi-question-circle display-1 text-warning"></i>
                </div>
                <h5>Apakah Anda yakin dengan pilihan ini?</h5>
                <p class="text-muted">
                    Kandidat yang Anda pilih: <br>
                    <strong id="modalCandidateName"></strong>
                </p>
                <div class="alert alert-warning">
                    <small>
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Perhatian:</strong> Setelah mengkonfirmasi, pilihan Anda tidak dapat diubah lagi.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x me-2"></i>
                    Batal
                </button>
                <button type="button" class="btn btn-primary" id="finalConfirmBtn">
                    <i class="bi bi-check me-2"></i>
                    Ya, Saya Yakin
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
    .candidate-vote-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }
    
    .candidate-vote-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .candidate-vote-card.selected {
        border-color: #198754;
        box-shadow: 0 0 20px rgba(25, 135, 84, 0.3);
    }
    
    .candidate-radio:checked + label {
        background-color: #198754 !important;
        border-color: #198754 !important;
        color: white !important;
    }
    
    .candidate-radio:checked + label .selected-icon {
        display: inline !important;
    }
    
    .candidate-radio:checked + label i:first-child {
        display: none;
    }
    
    .vote-btn {
        transition: all 0.3s ease;
    }
    
    .vote-btn:hover {
        transform: translateY(-2px);
    }
    
    .candidate-vote-card.selected .vote-btn {
        background-color: #198754;
        color: white;
        border-color: #198754;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let selectedCandidate = null;
    
    // Handle candidate selection
    document.querySelectorAll('.vote-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const candidateId = this.dataset.candidateId;
            const candidateName = this.dataset.candidateName;
            
            selectCandidate(candidateId, candidateName);
        });
    });
    
    // Handle radio button change
    document.querySelectorAll('.candidate-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                const candidateId = this.value;
                const candidateName = this.closest('.candidate-vote-card').querySelector('.card-title').textContent.trim();
                selectCandidate(candidateId, candidateName);
            }
        });
    });
    
    function selectCandidate(candidateId, candidateName) {
        selectedCandidate = {
            id: candidateId,
            name: candidateName
        };
        
        // Update UI
        document.querySelectorAll('.candidate-vote-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        const selectedCard = document.querySelector(`[data-candidate-id="${candidateId}"]`);
        selectedCard.classList.add('selected');
        
        // Check the radio button
        document.getElementById(`candidate_${candidateId}`).checked = true;
        
        // Show submit section
        document.getElementById('selectedCandidateName').textContent = candidateName;
        document.getElementById('submitSection').style.display = 'block';
        
        // Scroll to submit section
        document.getElementById('submitSection').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
    }
    
    function resetSelection() {
        selectedCandidate = null;
        
        // Reset UI
        document.querySelectorAll('.candidate-vote-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Uncheck all radio buttons
        document.querySelectorAll('.candidate-radio').forEach(radio => {
            radio.checked = false;
        });
        
        // Hide submit section
        document.getElementById('submitSection').style.display = 'none';
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    // Handle form submission
    document.getElementById('votingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!selectedCandidate) {
            showToast('Silakan pilih kandidat terlebih dahulu', 'error');
            return;
        }
        
        // Show confirmation modal
        document.getElementById('modalCandidateName').textContent = selectedCandidate.name;
        new bootstrap.Modal(document.getElementById('confirmationModal')).show();
    });
    
    // Handle final confirmation
    document.getElementById('finalConfirmBtn').addEventListener('click', function() {
        // Close modal
        bootstrap.Modal.getInstance(document.getElementById('confirmationModal')).hide();
        
        // Show loading state
        const confirmBtn = document.getElementById('confirmVoteBtn');
        const originalText = confirmBtn.innerHTML;
        confirmBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses Vote...';
        confirmBtn.disabled = true;
        
        // Submit form
        setTimeout(() => {
            document.getElementById('votingForm').submit();
        }, 1000);
    });
    
    // Prevent accidental page leave
    window.addEventListener('beforeunload', function(e) {
        if (selectedCandidate && !confirm('Anda yakin ingin meninggalkan halaman? Pilihan voting Anda akan hilang.')) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
    // Auto-focus and scroll to candidates
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('candidatesContainer').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    });
</script>
<?= $this->endSection() ?>