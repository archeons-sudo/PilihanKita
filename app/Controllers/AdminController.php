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

    public function editCandidate($id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $candidate = $this->candidateModel->find($id);
        if (!$candidate) return redirect()->to(base_url('admin-system/candidates'))->with('error', 'Kandidat tidak ditemukan');
        $periods = $this->periodModel->findAll();
        $data = [
            'title' => 'Edit Kandidat',
            'candidate' => $candidate,
            'periods' => $periods
        ];
        return view('admin/candidates_form', $data);
    }

    public function updateCandidate($id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'period_id' => 'required|integer',
            'vision' => 'required',
            'mission' => 'required',
            // Foto tidak wajib saat edit
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()));
        }
        $candidate = $this->candidateModel->find($id);
        if (!$candidate) return redirect()->to(base_url('admin-system/candidates'))->with('error', 'Kandidat tidak ditemukan');
        $photo = $this->request->getFile('photo');
        $photoName = $candidate['photo'];
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            // Hapus foto lama jika ada
            if ($candidate['photo'] && file_exists(FCPATH . 'uploads/candidates/' . $candidate['photo'])) {
                @unlink(FCPATH . 'uploads/candidates/' . $candidate['photo']);
            }
            $photoName = $photo->getRandomName();
            $photo->move(FCPATH . 'uploads/candidates/', $photoName);
        }
        $data = [
            'name' => $this->request->getPost('name'),
            'period_id' => $this->request->getPost('period_id'),
            'vision' => $this->request->getPost('vision'),
            'mission' => $this->request->getPost('mission'),
            'photo' => $photoName
        ];
        $this->candidateModel->update($id, $data);
        return redirect()->to(base_url('admin-system/candidates'))->with('success', 'Kandidat berhasil diupdate');
    }

    public function deleteCandidate($id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $candidate = $this->candidateModel->find($id);
        if ($candidate && $candidate['photo'] && file_exists(FCPATH . 'uploads/candidates/' . $candidate['photo'])) {
            @unlink(FCPATH . 'uploads/candidates/' . $candidate['photo']);
        }
        $this->candidateModel->delete($id);
        return redirect()->to(base_url('admin-system/candidates'))->with('success', 'Kandidat berhasil dihapus');
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

    public function createStudent()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;

        $classes = $this->classModel->findAll();
        $data = [
            'title' => 'Tambah Siswa',
            'classes' => $classes
        ];
        return view('admin/students_form', $data);
    }

    public function storeStudent()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'nis' => 'required|min_length[6]|max_length[20]|is_unique[students.nis]',
            'email' => 'required|valid_email|is_unique[students.email]',
            'class_id' => 'required|integer|is_not_unique[classes.id]'
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }
        $data = [
            'name' => $this->request->getPost('name'),
            'nis' => $this->request->getPost('nis'),
            'email' => $this->request->getPost('email'),
            'class_id' => $this->request->getPost('class_id'),
            'has_voted' => 0
        ];
        $this->studentModel->insert($data);
        return redirect()->to(base_url('admin-system/students'))->with('success', 'Siswa berhasil ditambahkan');
    }

    public function editStudent($id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $student = $this->studentModel->find($id);
        if (!$student) return redirect()->to(base_url('admin-system/students'))->with('error', 'Siswa tidak ditemukan');
        $classes = $this->classModel->findAll();
        $data = [
            'title' => 'Edit Siswa',
            'student' => $student,
            'classes' => $classes
        ];
        return view('admin/students_form', $data);
    }

    public function updateStudent($id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'nis' => 'required|min_length[6]|max_length[20]|is_unique[students.nis,id,' . $id . ']',
            'email' => 'required|valid_email|is_unique[students.email,id,' . $id . ']',
            'class_id' => 'required|integer|is_not_unique[classes.id]'
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }
        $data = [
            'name' => $this->request->getPost('name'),
            'nis' => $this->request->getPost('nis'),
            'email' => $this->request->getPost('email'),
            'class_id' => $this->request->getPost('class_id')
        ];
        $this->studentModel->update($id, $data);
        return redirect()->to(base_url('admin-system/students'))->with('success', 'Siswa berhasil diupdate');
    }

    public function deleteStudent($id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Cek apakah siswa sudah voting
            $vote = $this->voteModel->where('student_id', $id)->first();

            if ($vote) {
                // Jika sudah, kurangi vote_count kandidat
                $this->candidateModel->decrementVoteCount($vote['candidate_id']);
            }

            // Hapus siswa (dan vote-nya via ON DELETE CASCADE)
            $this->studentModel->delete($id);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->to(base_url('admin-system/students'))->with('error', 'Gagal menghapus siswa karena masalah database.');
            }

            return redirect()->to(base_url('admin-system/students'))->with('success', 'Siswa dan data voting terkait berhasil dihapus');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Delete Student Error: ' . $e->getMessage());
            return redirect()->to(base_url('admin-system/students'))->with('error', 'Terjadi kesalahan saat menghapus siswa.');
        }
    }

    // Results & Reports
    public function results()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;

        $periods = $this->periodModel->orderBy('name', 'DESC')->findAll();
        $periodId = $this->request->getGet('period_id');
        $selectedPeriod = null;
        if ($periodId) {
            foreach ($periods as $p) {
                if ($p['id'] == $periodId) {
                    $selectedPeriod = $p;
                    break;
                }
            }
        }
        if (!$selectedPeriod && !empty($periods)) {
            $selectedPeriod = $this->periodModel->getActivePeriod() ?: $periods[0];
        }
        $results = [];
        if ($selectedPeriod) {
            $results = $this->candidateModel->getVotingResults($selectedPeriod['id']);
        }
        $votingStats = $this->studentModel->getVotingStats();
        $data = [
            'title' => 'Hasil Voting',
            'activePeriod' => $selectedPeriod,
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

    // Class Management
    public function classes()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;

        $classes = $this->classModel->findAll();
        $data = [
            'title' => 'Manajemen Kelas',
            'classes' => $classes
        ];
        return view('admin/classes', $data);
    }

    // Period Management
    public function periods()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;

        $periods = $this->periodModel->orderBy('name', 'DESC')->findAll();
        $activePeriod = $this->periodModel->getActivePeriod();
        $data = [
            'title' => 'Manajemen Periode',
            'periods' => $periods,
            'activePeriod' => $activePeriod
        ];
        return view('admin/periods', $data);
    }

    // PERIOD CRUD
    public function createPeriod()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $data = [
            'title' => 'Tambah Periode',
        ];
        return view('admin/periods_form', $data);
    }

    public function storePeriod()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]|is_unique[periods.name]',
            'description' => 'permit_empty',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }
        $start = $this->request->getPost('start_date');
        $end = $this->request->getPost('end_date');
        if (strtotime($start) >= strtotime($end)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal mulai harus sebelum tanggal selesai');
        }
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'start_date' => $start . ' 00:00:00',
            'end_date' => $end . ' 23:59:59',
            'is_active' => 0
        ];
        $this->periodModel->insert($data);
        return redirect()->to(base_url('admin-system/periods'))->with('success', 'Periode berhasil ditambahkan');
    }

    public function editPeriod($id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $period = $this->periodModel->find($id);
        if (!$period) return redirect()->to(base_url('admin-system/periods'))->with('error', 'Periode tidak ditemukan');
        $data = [
            'title' => 'Edit Periode',
            'period' => $period
        ];
        return view('admin/periods_form', $data);
    }

    public function updatePeriod($id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]|is_unique[periods.name,id,' . $id . ']',
            'description' => 'permit_empty',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }
        $start = $this->request->getPost('start_date');
        $end = $this->request->getPost('end_date');
        if (strtotime($start) >= strtotime($end)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal mulai harus sebelum tanggal selesai');
        }
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'start_date' => $start . ' 00:00:00',
            'end_date' => $end . ' 23:59:59'
        ];
        $this->periodModel->update($id, $data);
        return redirect()->to(base_url('admin-system/periods'))->with('success', 'Periode berhasil diupdate');
    }

    public function deletePeriod($id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $this->periodModel->delete($id);
        return redirect()->to(base_url('admin-system/periods'))->with('success', 'Periode berhasil dihapus');
    }

    public function activatePeriod($id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $this->periodModel->setActivePeriod($id);
        return redirect()->to(base_url('admin-system/periods'))->with('success', 'Periode berhasil diaktifkan');
    }

    // KELAS CRUD
    public function createClass()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $data = [
            'title' => 'Tambah Kelas',
        ];
        return view('admin/classes_form', $data);
    }

    public function storeClass()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $rules = [
            'name' => 'required|min_length[1]|max_length[50]|is_unique[classes.name]',
            'grade' => 'required|in_list[10,11,12]',
            'major' => 'permit_empty|max_length[50]'
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }
        $data = [
            'name' => $this->request->getPost('name'),
            'grade' => $this->request->getPost('grade'),
            'major' => $this->request->getPost('major'),
        ];
        $this->classModel->insert($data);
        return redirect()->to(base_url('admin-system/classes'))->with('success', 'Kelas berhasil ditambahkan');
    }

    public function editClass($id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $class = $this->classModel->find($id);
        if (!$class) return redirect()->to(base_url('admin-system/classes'))->with('error', 'Kelas tidak ditemukan');
        $data = [
            'title' => 'Edit Kelas',
            'class' => $class
        ];
        return view('admin/classes_form', $data);
    }

    public function updateClass($id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $rules = [
            'name' => 'required|min_length[1]|max_length[50]|is_unique[classes.name,id,' . $id . ']',
            'grade' => 'required|in_list[10,11,12]',
            'major' => 'permit_empty|max_length[50]'
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }
        $data = [
            'name' => $this->request->getPost('name'),
            'grade' => $this->request->getPost('grade'),
            'major' => $this->request->getPost('major'),
        ];
        $this->classModel->update($id, $data);
        return redirect()->to(base_url('admin-system/classes'))->with('success', 'Kelas berhasil diupdate');
    }

    public function deleteClass($id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $this->classModel->delete($id);
        return redirect()->to(base_url('admin-system/classes'))->with('success', 'Kelas berhasil dihapus');
    }

    public function settings()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $adminId = session('admin_id');
        $admin = $this->adminModel->find($adminId);
        $data = [
            'title' => 'Pengaturan Admin',
            'admin' => $admin
        ];
        return view('admin/settings', $data);
    }

    public function updateSettings()
    {
        $auth = $this->checkAdminAuth();
        if ($auth) return $auth;
        $adminId = session('admin_id');
        $admin = $this->adminModel->find($adminId);
        if (!$admin) return redirect()->back()->with('error', 'Admin tidak ditemukan');

        // Ganti password
        if ($this->request->getPost('change_password')) {
            $rules = [
                'current_password' => 'required',
                'new_password' => 'required|min_length[6]',
                'confirm_password' => 'required|matches[new_password]'
            ];
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
            }
            if (!password_verify($this->request->getPost('current_password'), $admin['password'])) {
                return redirect()->back()->with('error', 'Password lama salah');
            }
            $this->adminModel->update($adminId, [
                'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            return redirect()->back()->with('success', 'Password berhasil diubah');
        }

        // Update profil
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[admins.email,id,' . $adminId . ']'
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }
        $this->adminModel->update($adminId, [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        session()->set('admin_name', $this->request->getPost('full_name'));
        return redirect()->back()->with('success', 'Profil admin berhasil diupdate');
    }
}
