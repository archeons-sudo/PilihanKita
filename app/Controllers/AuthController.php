<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\ClassModel;

class AuthController extends BaseController
{
    protected $studentModel;
    protected $classModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->classModel = new ClassModel();
    }

    public function googleLogin()
    {
        // For now, we'll create a mock Google login process
        // In production, you would integrate with Google OAuth API
        
        // Check if Google API is configured
        $googleClientId = env('google.oauth.client_id');
        
        if (empty($googleClientId)) {
            // Mock login for development
            return $this->mockGoogleLogin();
        }
        
        // TODO: Implement actual Google OAuth flow
        // For now, redirect to mock login
        return $this->mockGoogleLogin();
    }

    public function googleCallback()
    {
        // Handle Google OAuth callback
        // For development, we'll simulate successful authentication
        
        $mockData = [
            'id' => 'google_' . uniqid(),
            'email' => 'student@example.com',
            'name' => 'Test Student',
            'picture' => ''
        ];
        
        // Store Google data in session temporarily
        session()->set([
            'temp_google_data' => $mockData,
            'google_authenticated' => true
        ]);
        
        // Redirect to student data form
        return redirect()->to(base_url('auth/student-data-form'));
    }

    public function mockGoogleLogin()
    {
        // Create a mock login form for development
        $data['title'] = 'Login Siswa - PilihanKita';
        return view('auth/mock_login', $data);
    }

    public function studentDataForm()
    {
        // Check if user has been authenticated with Google
        if (!session()->get('google_authenticated')) {
            return redirect()->to(base_url('auth/google'));
        }
        
        $data['title'] = 'Data Siswa - PilihanKita';
        $data['classes'] = $this->classModel->orderBy('grade', 'ASC')->orderBy('name', 'ASC')->findAll();
        $data['google_data'] = session()->get('temp_google_data');
        
        return view('auth/student_data', $data);
    }

    public function saveStudentData()
    {
        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nis' => 'required|min_length[5]|max_length[20]',
            'class_id' => 'required|is_natural_no_zero'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $nis = $this->request->getPost('nis');
        $classId = $this->request->getPost('class_id');
        $googleData = session()->get('temp_google_data');

        if (!$googleData) {
            return redirect()->to(base_url('auth/google'))->with('error', 'Sesi Google tidak valid. Silakan login ulang.');
        }

        try {
            // Check if student exists with this NIS
            $student = $this->studentModel->where('nis', $nis)->first();
            
            if (!$student) {
                return redirect()->back()->withInput()->with('error', 'NIS tidak ditemukan. Hubungi admin untuk mendaftarkan data Anda.');
            }

            // Check if student is in the correct class
            if ($student['class_id'] != $classId) {
                return redirect()->back()->withInput()->with('error', 'Kelas yang dipilih tidak sesuai dengan data NIS Anda.');
            }

            // Check if student has already voted
            if ($student['has_voted']) {
                return redirect()->to(base_url())->with('warning', 'Anda sudah melakukan voting sebelumnya.');
            }

            // Update student with Google data if not already set
            if (empty($student['google_id'])) {
                $this->studentModel->update($student['id'], [
                    'google_id' => $googleData['id'],
                    'email' => $googleData['email']
                ]);
            }

            // Set session data
            session()->set([
                'student_logged_in' => true,
                'student_id' => $student['id'],
                'student_name' => $student['name'],
                'student_nis' => $student['nis'],
                'student_class_id' => $student['class_id'],
                'student_has_voted' => $student['has_voted'],
                'student_email' => $student['email']
            ]);

            // Remove temporary data
            session()->remove(['temp_google_data', 'google_authenticated']);

            return redirect()->to(base_url('voting'))->with('success', 'Login berhasil! Selamat datang, ' . $student['name']);

        } catch (\Exception $e) {
            log_message('error', 'Auth error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function logout()
    {
        // Clear all session data
        session()->destroy();
        
        return redirect()->to(base_url())->with('success', 'Anda telah logout.');
    }

    public function mockAuthenticate()
    {
        // Mock authentication for development
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        if (empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Email dan password harus diisi.');
        }
        
        // Mock Google data
        $mockData = [
            'id' => 'google_' . md5($email),
            'email' => $email,
            'name' => ucfirst(explode('@', $email)[0]),
            'picture' => ''
        ];
        
        // Store Google data in session temporarily
        session()->set([
            'temp_google_data' => $mockData,
            'google_authenticated' => true
        ]);
        
        // Redirect to student data form
        return redirect()->to(base_url('auth/student-data-form'));
    }
}
