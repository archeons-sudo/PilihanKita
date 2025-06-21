<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ========================================
// PUBLIC ROUTES
// ========================================

// Homepage
$routes->get('/', 'HomeController::index');
$routes->get('home', 'HomeController::index');

// Candidates
$routes->get('candidates', 'HomeController::candidates');

// ========================================
// AUTHENTICATION ROUTES
// ========================================

// Google OAuth
$routes->group('auth', [], function($routes) {
    $routes->get('google', 'AuthController::google');
    $routes->get('google/callback', 'AuthController::googleCallback');
    $routes->get('verify', 'AuthController::verify');
    $routes->post('verify/process', 'AuthController::processVerification');
    $routes->get('logout', 'AuthController::logout');
});

// ========================================
// PROFILE ROUTES
// ========================================

$routes->get('profile', 'ProfileController::index');
$routes->group('profile', [], function($routes) {
    $routes->get('/', 'ProfileController::index');
    $routes->post('update', 'ProfileController::updateProfile');
    $routes->post('change-password', 'ProfileController::changePassword');
    $routes->get('download-receipt/(:num)', 'ProfileController::downloadVoteReceipt/$1');
});

// ========================================
// VOTING ROUTES (Students)
// ========================================

$routes->group('voting', [], function($routes) {
    $routes->get('/', 'VotingController::index');
    $routes->post('process', 'VotingController::processVote');
    $routes->get('confirmation', 'VotingController::confirmation');
    $routes->get('download-receipt', 'VotingController::downloadReceipt');
});

// ========================================
// ADMIN ROUTES
// ========================================

// Admin Authentication
$routes->group('admin-system', [], function($routes) {
    $routes->get('/', 'AuthController::adminLogin');
    $routes->get('login', 'AuthController::adminLogin');
    $routes->post('auth', 'AuthController::adminAuth');
    $routes->get('logout', 'AuthController::adminLogout');
    
    // Admin Panel (Protected)
    $routes->get('dashboard', 'AdminController::dashboard');
    
    // Candidate Management
    $routes->get('candidates', 'AdminController::candidates');
    $routes->get('candidates/create', 'AdminController::createCandidate');
    $routes->post('candidates/store', 'AdminController::storeCandidate');
    $routes->get('candidates/edit/(:num)', 'AdminController::editCandidate/$1');
    $routes->post('candidates/update/(:num)', 'AdminController::updateCandidate/$1');
    $routes->post('candidates/delete/(:num)', 'AdminController::deleteCandidate/$1');
    $routes->post('candidates/toggle-status/(:num)', 'AdminController::toggleCandidateStatus/$1');
    
    // Student Management
    $routes->get('students', 'AdminController::students');
    $routes->get('students/create', 'AdminController::createStudent');
    $routes->post('students/store', 'AdminController::storeStudent');
    $routes->get('students/edit/(:num)', 'AdminController::editStudent/$1');
    $routes->post('students/update/(:num)', 'AdminController::updateStudent/$1');
    $routes->post('students/delete/(:num)', 'AdminController::deleteStudent/$1');
    $routes->post('students/import', 'AdminController::importStudents');
    
    // Class Management
    $routes->get('classes', 'AdminController::classes');
    $routes->get('classes/create', 'AdminController::createClass');
    $routes->post('classes/store', 'AdminController::storeClass');
    $routes->get('classes/edit/(:num)', 'AdminController::editClass/$1');
    $routes->post('classes/update/(:num)', 'AdminController::updateClass/$1');
    $routes->post('classes/delete/(:num)', 'AdminController::deleteClass/$1');
    
    // Period Management
    $routes->get('periods', 'AdminController::periods');
    $routes->get('periods/create', 'AdminController::createPeriod');
    $routes->post('periods/store', 'AdminController::storePeriod');
    $routes->get('periods/edit/(:num)', 'AdminController::editPeriod/$1');
    $routes->post('periods/update/(:num)', 'AdminController::updatePeriod/$1');
    $routes->post('periods/delete/(:num)', 'AdminController::deletePeriod/$1');
    $routes->post('periods/activate/(:num)', 'AdminController::activatePeriod/$1');
    
    // Results & Reports
    $routes->get('results', 'AdminController::results');
    $routes->get('results/period/(:num)', 'AdminController::resultsByPeriod/$1');
    $routes->get('results/export/excel', 'AdminController::exportExcel');
    $routes->get('results/export/pdf', 'AdminController::exportPDF');
    
    // Admin Management
    $routes->get('admins', 'AdminController::admins');
    $routes->get('admins/create', 'AdminController::createAdmin');
    $routes->post('admins/store', 'AdminController::storeAdmin');
    $routes->get('admins/edit/(:num)', 'AdminController::editAdmin/$1');
    $routes->post('admins/update/(:num)', 'AdminController::updateAdmin/$1');
    $routes->post('admins/delete/(:num)', 'AdminController::deleteAdmin/$1');
    
    // Settings
    $routes->get('settings', 'AdminController::settings');
    $routes->post('settings/update', 'AdminController::updateSettings');
});

// ========================================
// API ROUTES (AJAX)
// ========================================

$routes->group('api', [], function($routes) {
    // Public API
    $routes->get('voting-results', 'HomeController::apiVotingResults');
    $routes->get('candidate/(:num)', 'HomeController::apiCandidateDetail/$1');
    
    // Admin API
    $routes->get('admin/dashboard-stats', 'AdminController::apiDashboardStats');
    $routes->get('admin/voting-chart/(:num)', 'AdminController::apiVotingChart/$1');
    $routes->post('admin/upload-candidate-photo', 'AdminController::apiUploadCandidatePhoto');
});

// ========================================
// FILE UPLOAD & SERVE ROUTES
// ========================================

$routes->get('uploads/candidates/(:any)', function($filename) {
    $filePath = FCPATH . 'uploads/candidates/' . $filename;
    if (file_exists($filePath)) {
        $mimeType = mime_content_type($filePath);
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
});

// ========================================
// CATCH-ALL ROUTES
// ========================================

// 404 Handler
$routes->set404Override(function() {
    $data = [
        'title' => 'Halaman Tidak Ditemukan',
        'message' => 'Maaf, halaman yang Anda cari tidak ditemukan.'
    ];
    return view('errors/html/error_404', $data);
});

// CLI Routes (for maintenance and seeding)
if (is_cli()) {
    $routes->cli('seed/all', 'DatabaseSeeder::run');
    $routes->cli('migrate/fresh', function() {
        $migrate = \Config\Services::migrations();
        $migrate->setNamespace(null);
        $migrate->latest();
    });
}
