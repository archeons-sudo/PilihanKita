<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CandidateModel;
use App\Models\StudentModel;
use App\Models\VoteModel;
use App\Models\PeriodModel;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

class VotingController extends BaseController
{
    protected $candidateModel;
    protected $studentModel;
    protected $voteModel;
    protected $periodModel;

    public function __construct()
    {
        $this->candidateModel = new CandidateModel();
        $this->studentModel = new StudentModel();
        $this->voteModel = new VoteModel();
        $this->periodModel = new PeriodModel();
    }

    public function index()
    {
        // Check if student is logged in
        if (!session()->get('student_logged_in')) {
            return redirect()->to(base_url('auth/google'))->with('error', 'Silakan login terlebih dahulu');
        }

        // Check if student has already voted
        if (session()->get('student_has_voted')) {
            return redirect()->to(base_url())->with('info', 'Anda sudah melakukan voting');
        }

        // Get active period
        $activePeriod = $this->periodModel->getActivePeriod();
        if (!$activePeriod) {
            return redirect()->to(base_url())->with('error', 'Saat ini tidak ada periode voting yang aktif');
        }

        // Get active candidates
        $candidates = $this->candidateModel->getActiveCandidates();
        if (empty($candidates)) {
            return redirect()->to(base_url())->with('error', 'Belum ada kandidat yang tersedia untuk voting');
        }

        $data = [
            'title' => 'Voting Ketua OSIS',
            'activePeriod' => $activePeriod,
            'candidates' => $candidates,
            'student' => [
                'name' => session()->get('student_name'),
                'nis' => session()->get('student_nis'),
                'class' => session()->get('student_class')
            ]
        ];

        return view('voting/vote', $data);
    }

    public function processVote()
    {
        $db = \Config\Database::connect();
        try {
            // Check if student is logged in
            if (!session()->get('student_logged_in')) {
                log_message('error', 'Voting: Sesi login siswa berakhir');
                throw new \Exception('Sesi login telah berakhir');
            }

            // Ensure student_id is present in session
            $studentId = session()->get('student_id');
            if (!$studentId) {
                log_message('error', 'Voting: Student ID not found in session');
                throw new \Exception('Sesi login siswa tidak ditemukan. Silakan login ulang.');
            }

            // Check if student has already voted
            $student = $this->studentModel->findStudent($studentId);
            log_message('debug', 'Voting: Data student = ' . json_encode($student));
            log_message('debug', 'Voting: Student ID = ' . $studentId);
            log_message('debug', 'Voting: Student fields = ' . json_encode(array_keys($student ?? [])));
            
            if (!$student) {
                log_message('error', 'Voting: Data siswa tidak ditemukan');
                throw new \Exception('Data siswa tidak ditemukan');
            }

            // Check if has_voted field exists and is true
            if (isset($student['has_voted']) && $student['has_voted']) {
                log_message('error', 'Voting: Siswa sudah voting sebelumnya');
                throw new \Exception('Anda sudah melakukan voting sebelumnya');
            }

            // Validate input
            $candidateId = $this->request->getPost('candidate_id');
            log_message('debug', 'Voting: candidate_id = ' . $candidateId);
            if (!$candidateId) {
                log_message('error', 'Voting: Kandidat tidak dipilih');
                throw new \Exception('Silakan pilih kandidat');
            }

            // Verify candidate exists and is active
            $candidate = $this->candidateModel->find($candidateId);
            log_message('debug', 'Voting: Data kandidat = ' . json_encode($candidate));
            if (!$candidate || !$candidate['is_active']) {
                log_message('error', 'Voting: Kandidat tidak valid');
                throw new \Exception('Kandidat tidak valid');
            }

            // Get active period
            $activePeriod = $this->periodModel->getActivePeriod();
            log_message('debug', 'Voting: Data periode aktif = ' . json_encode($activePeriod));
            if (!$activePeriod || $candidate['period_id'] != $activePeriod['id']) {
                log_message('error', 'Voting: Kandidat tidak sesuai periode aktif');
                throw new \Exception('Kandidat tidak tersedia untuk periode voting saat ini');
            }

            // Start database transaction
            $db->transStart();

            // Create vote record
            $voteData = [
                'student_id' => $studentId,
                'candidate_id' => $candidateId,
                'period_id' => $activePeriod['id'],
                'vote_hash' => $this->generateVoteHash($studentId, $candidateId),
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString(),
                'voted_at' => date('Y-m-d H:i:s'),
            ];
            log_message('debug', 'Voting: voteData = ' . json_encode($voteData));

            $voteId = $this->voteModel->insert($voteData);
            log_message('debug', 'Voting: voteId = ' . json_encode($voteId) . ' | DB Error: ' . json_encode($this->voteModel->errors()));
            if (!$voteId) {
                log_message('error', 'Voting: Gagal menyimpan vote');
                throw new \Exception('Gagal menyimpan vote');
            }

            // Update candidate vote count
            $incResult = $this->candidateModel->incrementVoteCount($candidateId);
            log_message('debug', 'Voting: incrementVoteCount = ' . json_encode($incResult));
            if (!$incResult) {
                log_message('error', 'Voting: Gagal menambah jumlah vote kandidat');
                throw new \Exception('Gagal menambah jumlah vote kandidat');
            }

            // Mark student as voted
            $markResult = $this->studentModel->markAsVoted($studentId);
            log_message('debug', 'Voting: markAsVoted = ' . json_encode($markResult));
            if (!$markResult) {
                log_message('error', 'Voting: Gagal menandai siswa sudah voting');
                throw new \Exception('Gagal menandai siswa sudah voting');
            }

            // Commit transaction
            $db->transComplete();
            log_message('debug', 'Voting: transStatus = ' . json_encode($db->transStatus()));

            if ($db->transStatus() === false) {
                log_message('error', 'Voting: Terjadi kesalahan database saat commit');
                throw new \Exception('Terjadi kesalahan database');
            }

            // Update session
            session()->set('student_has_voted', true);

            // Generate and store vote receipt data
            $receiptData = [
                'vote_id' => $voteId,
                'vote_hash' => $voteData['vote_hash'],
                'student_name' => session()->get('student_name'),
                'student_nis' => session()->get('student_nis'),
                'student_class' => session()->get('student_class'),
                'candidate_name' => $candidate['name'],
                'period_name' => $activePeriod['name'],
                'vote_time' => date('Y-m-d H:i:s')
            ];

            session()->set('vote_receipt', $receiptData);

            return redirect()->to(base_url('voting/confirmation'))->with('success', 'Vote berhasil disimpan!');

        } catch (\Exception $e) {
            // Rollback transaction if still active
            if ($db->transStatus() !== false) {
                $db->transRollback();
            }

            log_message('error', 'Voting Error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function confirmation()
    {
        // Check if student is logged in
        if (!session()->get('student_logged_in')) {
            return redirect()->to(base_url('auth/google'))->with('error', 'Silakan login terlebih dahulu');
        }

        // Check if vote receipt exists
        $receiptData = session()->get('vote_receipt');
        if (!$receiptData) {
            return redirect()->to(base_url())->with('error', 'Data vote tidak ditemukan');
        }

        $data = [
            'title' => 'Konfirmasi Voting',
            'receipt' => $receiptData
        ];

        return view('voting/confirmation', $data);
    }

    public function downloadReceipt()
    {
        try {
            // Check if student is logged in
            if (!session()->get('student_logged_in')) {
                throw new \Exception('Sesi login telah berakhir');
            }

            // Get receipt data
            $receiptData = session()->get('vote_receipt');
            if (!$receiptData) {
                throw new \Exception('Data receipt tidak ditemukan');
            }

            // Generate PDF
            $html = $this->generateReceiptHTML($receiptData);
            
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Generate filename
            $filename = 'voting_receipt_' . $receiptData['student_nis'] . '_' . date('Y-m-d_H-i-s') . '.pdf';

            // Send PDF to browser
            $dompdf->stream($filename, ['Attachment' => true]);

        } catch (\Exception $e) {
            log_message('error', 'PDF Generation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menggenerate PDF: ' . $e->getMessage());
        }
    }

    private function generateVoteHash($studentId, $candidateId)
    {
        $data = $studentId . '-' . $candidateId . '-' . time() . '-' . random_bytes(16);
        return hash('sha256', $data);
    }

    public function generateReceiptHTML($receipt)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Bukti Voting - PilihanKita</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background: #f8f9fa;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    background: white;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 0 20px rgba(0,0,0,0.1);
                }
                .header {
                    text-align: center;
                    border-bottom: 3px solid #4f46e5;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .logo {
                    font-size: 24px;
                    font-weight: bold;
                    color: #4f46e5;
                    margin-bottom: 10px;
                }
                .title {
                    font-size: 18px;
                    color: #333;
                    margin: 0;
                }
                .info-grid {
                    display: table;
                    width: 100%;
                    margin-bottom: 20px;
                }
                .info-row {
                    display: table-row;
                }
                .info-label {
                    display: table-cell;
                    font-weight: bold;
                    color: #555;
                    padding: 8px 0;
                    width: 30%;
                }
                .info-value {
                    display: table-cell;
                    color: #333;
                    padding: 8px 0;
                    border-bottom: 1px solid #eee;
                }
                .vote-section {
                    background: #f8f9fa;
                    padding: 20px;
                    border-radius: 8px;
                    margin: 20px 0;
                    border-left: 4px solid #10b981;
                }
                .vote-title {
                    font-size: 16px;
                    font-weight: bold;
                    color: #10b981;
                    margin-bottom: 10px;
                }
                .candidate-name {
                    font-size: 18px;
                    font-weight: bold;
                    color: #333;
                }
                .hash-section {
                    background: #fff3cd;
                    padding: 15px;
                    border-radius: 5px;
                    border: 1px solid #ffeaa7;
                    margin-top: 20px;
                }
                .hash-title {
                    font-weight: bold;
                    color: #856404;
                    margin-bottom: 5px;
                }
                .hash-value {
                    font-family: monospace;
                    font-size: 10px;
                    color: #6c757d;
                    word-break: break-all;
                }
                .footer {
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 1px solid #dee2e6;
                    text-align: center;
                    color: #6c757d;
                    font-size: 12px;
                }
                .important-note {
                    background: #d1ecf1;
                    border: 1px solid #bee5eb;
                    border-radius: 5px;
                    padding: 15px;
                    margin-top: 20px;
                }
                .important-note h4 {
                    color: #0c5460;
                    margin-top: 0;
                    font-size: 14px;
                }
                .important-note ul {
                    margin: 10px 0 0 20px;
                    color: #0c5460;
                    font-size: 12px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <div class="logo">🗳️ PilihanKita</div>
                    <h2 class="title">BUKTI VOTING KETUA OSIS</h2>
                    <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">
                        Periode ' . esc($receipt['period_name']) . '
                    </p>
                </div>

                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Nama Siswa:</div>
                        <div class="info-value">' . esc($receipt['student_name']) . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">NIS:</div>
                        <div class="info-value">' . esc($receipt['student_nis']) . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Kelas:</div>
                        <div class="info-value">' . esc($receipt['student_class']) . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Waktu Voting:</div>
                        <div class="info-value">' . date('d/m/Y H:i:s', strtotime($receipt['vote_time'])) . '</div>
                    </div>
                </div>

                <div class="vote-section">
                    <div class="vote-title">✅ VOTE BERHASIL DISIMPAN</div>
                    <div class="candidate-name">' . esc($receipt['candidate_name']) . '</div>
                    <p style="margin: 10px 0 0 0; color: #666; font-size: 14px;">
                        Kandidat Ketua OSIS yang Anda pilih
                    </p>
                </div>

                <div class="hash-section">
                    <div class="hash-title">🔐 Hash Verifikasi:</div>
                    <div class="hash-value">' . esc($receipt['vote_hash']) . '</div>
                </div>

                <div class="important-note">
                    <h4>📋 Informasi Penting:</h4>
                    <ul>
                        <li>Bukti ini adalah konfirmasi bahwa Anda telah melakukan voting</li>
                        <li>Simpan bukti ini dengan baik sebagai tanda partisipasi</li>
                        <li>Hash verifikasi dapat digunakan untuk memverifikasi keaslian vote</li>
                        <li>Voting bersifat rahasia - admin tidak dapat melihat pilihan Anda</li>
                        <li>Terima kasih atas partisipasi Anda dalam demokrasi sekolah</li>
                    </ul>
                </div>

                <div class="footer">
                    <p>
                        <strong>PilihanKita - Sistem Voting Ketua OSIS</strong><br>
                        Diunduh pada: ' . date('d/m/Y H:i:s') . '<br>
                        © ' . date('Y') . ' PilihanKita. Sistem ini dibuat untuk mendukung demokrasi siswa.
                    </p>
                </div>
            </div>
        </body>
        </html>';

        return $html;
    }
}
