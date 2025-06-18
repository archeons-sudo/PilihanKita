<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\AdminModel;
use App\Models\ClassModel;
use CodeIgniter\HTTP\ResponseInterface;
use Google\Client as GoogleClient;

class AuthController extends BaseController
{
    protected $studentModel;
    protected $adminModel;
    protected $classModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->adminModel = new AdminModel();
        $this->classModel = new ClassModel();
    }

    // Google OAuth Login
    public function google()
    {
        // If already logged in, redirect to appropriate page
        if (session()->get('student_logged_in')) {
            return redirect()->to(base_url('voting'));
        }

        $data = [
            'title' => 'Login dengan Google'
        ];

        return view('auth/google_login', $data);
    }

    public function googleCallback()
    {
        try {
            // --- GIS JWT Credential ---
            $credential = $this->request->getGet('credential');
            if ($credential) {
                $client = new GoogleClient(['client_id' => getenv('GOOGLE_CLIENT_ID')]);
                $payload = $client->verifyIdToken($credential);
                if (!$payload) {
                    throw new \Exception('Token Google tidak valid');
                }
                $googleUser = [
                    'id'      => $payload['sub'],
                    'email'   => $payload['email'],
                    'name'    => $payload['name'] ?? '',
                    'picture' => $payload['picture'] ?? ''
                ];
            } else {
                // --- Fallback: Demo/Simulasi lama (untuk testing) ---
                $googleUser = [
                    'id' => $this->request->getGet('code'),
                    'email' => $this->request->getGet('email') ?: 'demo@example.com',
                    'name' => $this->request->getGet('name') ?: 'Demo User',
                    'picture' => $this->request->getGet('picture') ?: ''
                ];
            }

            // Cek jika student sudah ada by Google ID
            $student = $this->studentModel->getStudentByGoogleId($googleUser['id']);
            if (!$student) {
                // Cek jika student sudah ada by email
                $student = $this->studentModel->getStudentByEmail($googleUser['email']);
                if ($student) {
                    // Update Google ID untuk student lama
                    $this->studentModel->update($student['id'], ['google_id' => $googleUser['id']]);
                } else {
                    // User baru, redirect ke verifikasi NIS & kelas
                    session()->set([
                        'google_user' => $googleUser,
                        'google_verified' => false
                    ]);
                    return redirect()->to(base_url('auth/verify'))->with('info', 'Silakan masukkan NIS dan pilih kelas Anda');
                }
            }

            // Student ditemukan, set session
            $studentData = $this->studentModel->getStudentWithClass($student['id']);
            session()->set([
                'student_logged_in' => true,
                'student_id' => $student['id'],
                'student_name' => $student['name'],
                'student_email' => $student['email'],
                'student_nis' => $student['nis'],
                'student_class' => $studentData['class_name'],
                'student_has_voted' => $student['has_voted'],
                'google_user' => $googleUser
            ]);
            return redirect()->to(base_url())->with('success', 'Selamat datang, ' . $student['name'] . '!');
        } catch (\Exception $e) {
            log_message('error', 'Google OAuth Error: ' . $e->getMessage());
            return redirect()->to(base_url('auth/google'))->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }

    public function verify()
    {
        // Check if Google verification is in progress
        if (!session()->get('google_user')) {
            return redirect()->to(base_url('auth/google'))->with('error', 'Sesi login telah berakhir');
        }

        $data = [
            'title' => 'Verifikasi Data Siswa',
            'classes' => $this->classModel->findAll(),
            'google_user' => session()->get('google_user')
        ];

        return view('auth/verify', $data);
    }

    public function processVerification()
    {
        try {
            // Validate input
            $rules = [
                'nis' => 'required|min_length[6]|max_length[20]',
                'class_id' => 'required|integer'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Data tidak valid: ' . implode(', ', $this->validator->getErrors()));
            }

            $googleUser = session()->get('google_user');
            if (!$googleUser) {
                throw new \Exception('Sesi Google tidak valid');
            }

            $nis = $this->request->getPost('nis');
            $classId = $this->request->getPost('class_id');

            // Check if NIS already exists
            $existingStudent = $this->studentModel->getStudentByNIS($nis);
            
            if ($existingStudent) {
                if ($existingStudent['email'] !== $googleUser['email']) {
                    throw new \Exception('NIS sudah terdaftar dengan email lain');
                }
                
                // Update existing student with Google data
                $this->studentModel->update($existingStudent['id'], [
                    'google_id' => $googleUser['id'],
                    'class_id' => $classId,
                    'name' => $googleUser['name'] // Update name from Google
                ]);

                $studentId = $existingStudent['id'];
            } else {
                // Create new student
                $studentData = [
                    'nis' => $nis,
                    'name' => $googleUser['name'],
                    'email' => $googleUser['email'],
                    'class_id' => $classId,
                    'google_id' => $googleUser['id'],
                    'has_voted' => 0
                ];

                $studentId = $this->studentModel->insert($studentData);
                
                if (!$studentId) {
                    throw new \Exception('Gagal menyimpan data siswa');
                }
            }

            // Get student data with class
            $student = $this->studentModel->getStudentWithClass($studentId);

            // Set session
            session()->set([
                'student_logged_in' => true,
                'student_id' => $studentId,
                'student_name' => $student['name'],
                'student_email' => $student['email'],
                'student_nis' => $student['nis'],
                'student_class' => $student['class_name'],
                'student_has_voted' => $student['has_voted']
            ]);

            // Remove temporary Google session
            session()->remove(['google_user', 'google_verified']);

            return redirect()->to(base_url())->with('success', 'Verifikasi berhasil! Selamat datang, ' . $student['name']);

        } catch (\Exception $e) {
            log_message('error', 'Verification Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function logout()
    {
        // Remove all student session data
        session()->remove([
            'student_logged_in',
            'student_id',
            'student_name', 
            'student_email',
            'student_nis',
            'student_class',
            'student_has_voted',
            'google_user'
        ]);

        return redirect()->to(base_url())->with('success', 'Anda telah logout');
    }

    public function profile()
    {
        if (!session()->get('student_logged_in')) {
            return redirect()->to(base_url('auth/google'))->with('error', 'Silakan login terlebih dahulu');
        }

        $studentId = session()->get('student_id');
        $student = $this->studentModel->getStudentWithClass($studentId);

        $data = [
            'title' => 'Profile Siswa',
            'student' => $student
        ];

        return view('auth/profile', $data);
    }

    // Admin Authentication
    public function adminLogin()
    {
        $data = [
            'title' => 'Login Admin'
        ];

        return view('admin/login', $data);
    }

    public function adminAuth()
    {
        try {
            $rules = [
                'username' => 'required',
                'password' => 'required'
            ];

            if (!$this->validate($rules)) {
                log_message('error', 'Validasi login admin gagal: ' . json_encode($this->validator->getErrors()));
                return redirect()->back()->withInput()->with('error', 'Username dan password harus diisi');
            }

            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $admin = $this->adminModel->getAdminByUsername($username);
            log_message('debug', 'Hasil getAdminByUsername: ' . json_encode($admin));

            if (!$admin) {
                log_message('error', 'Admin tidak ditemukan untuk username: ' . $username);
                return redirect()->back()->withInput()->with('error', 'Username atau password salah');
            }

            if (!password_verify($password, $admin['password'])) {
                log_message('error', 'Password admin salah untuk username: ' . $username);
                return redirect()->back()->withInput()->with('error', 'Username atau password salah');
            }

            if (!$admin['is_active']) {
                log_message('error', 'Akun admin tidak aktif: ' . $username);
                return redirect()->back()->withInput()->with('error', 'Akun admin tidak aktif');
            }

            // Set admin session
            session()->set([
                'admin_logged_in' => true,
                'admin_id' => $admin['id'],
                'admin_username' => $admin['username'],
                'admin_name' => $admin['full_name'],
                'admin_role' => $admin['role']
            ]);

            // Update last login
            $updateResult = $this->adminModel->update($admin['id'], ['last_login' => date('Y-m-d H:i:s')]);
            log_message('debug', 'Update last_login result: ' . json_encode($updateResult));

            return redirect()->to(base_url('admin-system/dashboard'))->with('success', 'Selamat datang, ' . $admin['full_name']);

        } catch (\Exception $e) {
            log_message('error', 'Admin Auth Error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat login');
        }
    }

    public function adminLogout()
    {
        session()->remove([
            'admin_logged_in',
            'admin_id',
            'admin_username',
            'admin_name',
            'admin_role'
        ]);

        return redirect()->to(base_url('admin-system/login'))->with('success', 'Anda telah logout');
    }
}
