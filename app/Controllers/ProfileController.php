<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\VoteModel;

class ProfileController extends BaseController
{
    protected $studentModel;
    protected $voteModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->voteModel = new VoteModel();
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            // Try to detect user type from other session keys
            if (session()->get('student_logged_in')) {
                return redirect()->to('/auth/google');
            }
            // Default: redirect to homepage
            return redirect()->to('/');
        }

        $userType = session()->get('user_type');
        $userId = session()->get('user_id');

        // Only allow student
        if ($userType === 'student') {
            return $this->studentProfile($userId);
        }

        // If not student, redirect to home
        return redirect()->to('/');
    }

    private function studentProfile($studentId)
    {
        $student = $this->studentModel->getStudentWithClass($studentId);
        
        if (!$student) {
            session()->destroy();
            return redirect()->to('/auth/google')->with('error', 'Student data not found');
        }

        // Get student's voting history
        $votingHistory = $this->voteModel->getVotingHistoryByStudent($studentId);
        
        // Get current active period
        $activePeriod = $this->voteModel->getActivePeriod();
        
        // Check if student has voted in current period
        $hasVoted = false;
        $voteReceipt = null;
        if ($activePeriod) {
            $hasVoted = $this->voteModel->hasStudentVoted($studentId, $activePeriod['id']);
            if ($hasVoted) {
                $voteReceipt = $this->voteModel->getVoteReceipt($studentId, $activePeriod['id']);
            }
        }

        $data = [
            'title' => 'Student Profile - PilihanKita',
            'student' => $student,
            'votingHistory' => $votingHistory,
            'activePeriod' => $activePeriod,
            'hasVoted' => $hasVoted,
            'voteReceipt' => $voteReceipt,
            'userType' => 'student'
        ];

        return view('profile/student', $data);
    }

    public function updateProfile()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $userType = session()->get('user_type');
        $userId = session()->get('user_id');

        if ($userType === 'student') {
            return $this->updateStudentProfile($userId);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Invalid user type']);
    }

    private function updateStudentProfile($studentId)
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'phone' => 'permit_empty|min_length[10]|max_length[15]',
            'address' => 'permit_empty|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $this->studentModel->update($studentId, $data);
            
            // Update session data
            session()->set('user_name', $data['name']);
            session()->set('user_email', $data['email']);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profile updated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update profile'
            ]);
        }
    }

    public function changePassword()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Only for student (admin change password via dashboard/settings)
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Change password only available for students.'
        ]);
    }

    public function downloadVoteReceipt($periodId = null)
    {
        if (!session()->get('user_id') || session()->get('user_type') !== 'student') {
            return redirect()->to('/auth/login');
        }

        $studentId = session()->get('user_id');
        if (!$periodId) {
            $activePeriod = $this->voteModel->getActivePeriod();
            if (!$activePeriod) {
                return redirect()->to('/profile')->with('error', 'No active election period');
            }
            $periodId = $activePeriod['id'];
        }

        $voteReceipt = $this->voteModel->getVoteReceipt($studentId, $periodId);
        if (!$voteReceipt) {
            return redirect()->to('/profile')->with('error', 'Vote receipt not found');
        }

        // Map receipt data to match VotingController structure
        $receiptData = [
            'vote_id' => $voteReceipt['vote_id'],
            'vote_hash' => $voteReceipt['vote_hash'],
            'student_name' => $voteReceipt['student_name'],
            'student_nis' => $voteReceipt['student_nis'],
            'student_class' => $voteReceipt['class_name'],
            'candidate_name' => $voteReceipt['candidate_name'],
            'period_name' => $voteReceipt['period_name'],
            'vote_time' => isset($voteReceipt['vote_date']) ? $voteReceipt['vote_date'] : '',
        ];

        // Use the same HTML generator as VotingController
        $votingController = new \App\Controllers\VotingController();
        $html = $votingController->generateReceiptHTML($receiptData);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'vote_receipt_' . $studentId . '_' . $periodId . '.pdf';
        return $dompdf->stream($filename);
    }
} 