<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\CandidateModel;
use App\Models\StudentModel;
use App\Models\VoteModel;
use App\Models\PeriodModel;
use App\Models\ClassModel;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminController extends BaseController
{
    protected $adminModel;
    protected $candidateModel;
    protected $studentModel;
    protected $voteModel;
    protected $periodModel;
    protected $classModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->candidateModel = new CandidateModel();
        $this->studentModel = new StudentModel();
        $this->voteModel = new VoteModel();
        $this->periodModel = new PeriodModel();
        $this->classModel = new ClassModel();
    }

    // Middleware check for admin authentication
    protected function checkAdminAuth()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to(base_url('admin-system/login'))->with('error', 'Silakan login sebagai admin');
        }
        return null;
    }

    public function dashboard()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;

        // Get statistics
        $stats = [
            'total_students' => $this->studentModel->countAll(),
            'total_candidates' => $this->candidateModel->where('is_active', 1)->countAllResults(),
            'total_votes' => $this->voteModel->countAll(),
            'total_classes' => $this->classModel->countAll(),
        ];

        // Get voting statistics
        $votingStats = $this->studentModel->getVotingStats();
        $stats = array_merge($stats, $votingStats);

        // Get active period
        $activePeriod = $this->periodModel->getActivePeriod();

        // Get recent votes (last 10)
        $recentVotes = $this->voteModel
            ->select('votes.*, students.name as student_name, students.nis, candidates.name as candidate_name')
            ->join('students', 'students.id = votes.student_id')
            ->join('candidates', 'candidates.id = votes.candidate_id')
            ->orderBy('votes.created_at', 'DESC')
            ->limit(10)
            ->findAll();

        // Get candidates with vote counts
        $candidates = $this->candidateModel->getActiveCandidates();

        $data = [
            'title' => 'Dashboard Admin',
            'stats' => $stats,
            'activePeriod' => $activePeriod,
            'recentVotes' => $recentVotes,
            'candidates' => $candidates
        ];

        return view('admin/dashboard', $data);
    }

    // Candidate Management
    public function candidates()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;

        $candidates = $this->candidateModel
            ->select('candidates.*, periods.name as period_name')
            ->join('periods', 'periods.id = candidates.period_id')
            ->orderBy('candidates.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Manajemen Kandidat',
            'candidates' => $candidates
        ];

        return view('admin/candidates', $data);
    }

    public function createCandidate()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;

        $periods = $this->periodModel->findAll();

        $data = [
            'title' => 'Tambah Kandidat',
            'periods' => $periods
        ];

        return view('admin/candidates_form', $data);
    }

    public function storeCandidate()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;

        try {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'period_id' => 'required|integer',
                'vision' => 'required',
                'mission' => 'required',
                'photo' => 'uploaded[photo]|max_size[photo,2048]|is_image[photo]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()));
            }

            // Handle photo upload
            $photo = $this->request->getFile('photo');
            $photoName = null;

            if ($photo && $photo->isValid() && !$photo->hasMoved()) {
                $photoName = $photo->getRandomName();
                $photo->move(FCPATH . 'uploads/candidates/', $photoName);
            }

            $candidateData = [
                'name' => $this->request->getPost('name'),
                'period_id' => $this->request->getPost('period_id'),
                'vision' => $this->request->getPost('vision'),
                'mission' => $this->request->getPost('mission'),
                'photo' => $photoName,
                'vote_count' => 0,
                'is_active' => 1
            ];

            if ($this->candidateModel->insert($candidateData)) {
                return redirect()->to(base_url('admin-system/candidates'))->with('success', 'Kandidat berhasil ditambahkan');
            } else {
                throw new \Exception('Gagal menyimpan data kandidat');
            }

        } catch (\Exception $e) {
            log_message('error', 'Create Candidate Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    // Student Management
    public function students()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;

        $students = $this->studentModel
            ->select('students.*, classes.name as class_name')
            ->join('classes', 'classes.id = students.class_id')
            ->orderBy('students.name')
            ->findAll();

        $data = [
            'title' => 'Manajemen Siswa',
            'students' => $students
        ];

        return view('admin/students', $data);
    }

    // Results & Reports
    public function results()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;

        $activePeriod = $this->periodModel->getActivePeriod();
        $results = [];

        if ($activePeriod) {
            $results = $this->candidateModel->getVotingResults($activePeriod['id']);
        }

        $periods = $this->periodModel->orderBy('name', 'DESC')->findAll();
        $votingStats = $this->studentModel->getVotingStats();

        $data = [
            'title' => 'Hasil Voting',
            'activePeriod' => $activePeriod,
            'results' => $results,
            'periods' => $periods,
            'stats' => $votingStats
        ];

        return view('admin/results', $data);
    }

    public function exportExcel()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;

        try {
            $activePeriod = $this->periodModel->getActivePeriod();
            
            if (!$activePeriod) {
                throw new \Exception('Tidak ada periode aktif');
            }

            $results = $this->candidateModel->getVotingResults($activePeriod['id']);
            $totalVotes = $this->candidateModel->getTotalVotes($activePeriod['id']);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $sheet->setCellValue('A1', 'HASIL VOTING KETUA OSIS');
            $sheet->setCellValue('A2', 'Periode: ' . $activePeriod['name']);
            $sheet->setCellValue('A3', 'Tanggal Export: ' . date('d/m/Y H:i:s'));

            // Table Header
            $sheet->setCellValue('A5', 'No');
            $sheet->setCellValue('B5', 'Nama Kandidat');
            $sheet->setCellValue('C5', 'Jumlah Suara');
            $sheet->setCellValue('D5', 'Persentase');

            // Data
            $row = 6;
            foreach ($results as $index => $result) {
                $percentage = $totalVotes > 0 ? round(($result['vote_count'] / $totalVotes) * 100, 2) : 0;
                
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $result['name']);
                $sheet->setCellValue('C' . $row, $result['vote_count']);
                $sheet->setCellValue('D' . $row, $percentage . '%');
                $row++;
            }

            // Summary
            $row += 2;
            $sheet->setCellValue('A' . $row, 'Total Suara: ' . $totalVotes);

            // Style
            $sheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A5:D5')->getFont()->setBold(true);

            $writer = new Xlsx($spreadsheet);
            
            $filename = 'hasil_voting_' . $activePeriod['name'] . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            log_message('error', 'Excel Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export Excel: ' . $e->getMessage());
        }
    }

    // API Methods for AJAX
    public function apiDashboardStats()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $this->response->setJSON(['error' => 'Unauthorized']);

        try {
            $stats = [
                'total_students' => $this->studentModel->countAll(),
                'total_candidates' => $this->candidateModel->where('is_active', 1)->countAllResults(),
                'total_votes' => $this->voteModel->countAll(),
                'voting_percentage' => $this->studentModel->getVotingStats()['voting_percentage']
            ];

            return $this->response->setJSON([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function apiVotingChart($periodId = null)
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $this->response->setJSON(['error' => 'Unauthorized']);

        try {
            if (!$periodId) {
                $activePeriod = $this->periodModel->getActivePeriod();
                $periodId = $activePeriod ? $activePeriod['id'] : null;
            }

            if (!$periodId) {
                throw new \Exception('No active period found');
            }

            $results = $this->candidateModel->getVotingResults($periodId);
            
            $chartData = [
                'labels' => array_column($results, 'name'),
                'data' => array_column($results, 'vote_count'),
                'backgroundColor' => [
                    '#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'
                ]
            ];

            return $this->response->setJSON([
                'success' => true,
                'chartData' => $chartData
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
