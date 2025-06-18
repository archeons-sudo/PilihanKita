<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Homepage
$routes->get('/', 'HomeController::index');

// Candidates
$routes->get('candidates', 'HomeController::candidates');
$routes->get('candidates/(:num)', 'HomeController::candidateDetail/$1');

// Authentication
$routes->group('auth', function($routes) {
    $routes->get('google', 'AuthController::googleLogin');
    $routes->get('google/callback', 'AuthController::googleCallback');
    $routes->get('logout', 'AuthController::logout');
    $routes->get('student-data-form', 'AuthController::studentDataForm');
    $routes->post('student-data', 'AuthController::saveStudentData');
    $routes->post('mock-authenticate', 'AuthController::mockAuthenticate');
});

// Voting
$routes->group('voting', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'VotingController::index');
    $routes->post('submit', 'VotingController::submit');
    $routes->get('receipt/(:num)', 'VotingController::downloadReceipt/$1');
});

// Admin System
$routes->group('admin-system', function($routes) {
    // Admin Authentication
    $routes->get('/', 'AdminController::login');
    $routes->get('login', 'AdminController::login');
    $routes->post('login', 'AdminController::authenticate');
    $routes->get('logout', 'AdminController::logout');
    
    // Admin Dashboard (protected)
    $routes->group('', ['filter' => 'admin_auth'], function($routes) {
        $routes->get('dashboard', 'AdminController::dashboard');
        
        // Period Management
        $routes->get('periods', 'AdminController::periods');
        $routes->get('periods/add', 'AdminController::addPeriod');
        $routes->post('periods/save', 'AdminController::savePeriod');
        $routes->get('periods/edit/(:num)', 'AdminController::editPeriod/$1');
        $routes->post('periods/update/(:num)', 'AdminController::updatePeriod/$1');
        $routes->post('periods/delete/(:num)', 'AdminController::deletePeriod/$1');
        $routes->post('periods/activate/(:num)', 'AdminController::activatePeriod/$1');
        
        // Candidate Management
        $routes->get('candidates', 'AdminController::candidates');
        $routes->get('candidates/add', 'AdminController::addCandidate');
        $routes->post('candidates/save', 'AdminController::saveCandidate');
        $routes->get('candidates/edit/(:num)', 'AdminController::editCandidate/$1');
        $routes->post('candidates/update/(:num)', 'AdminController::updateCandidate/$1');
        $routes->post('candidates/delete/(:num)', 'AdminController::deleteCandidate/$1');
        
        // Student Management
        $routes->get('students', 'AdminController::students');
        $routes->get('students/add', 'AdminController::addStudent');
        $routes->post('students/save', 'AdminController::saveStudent');
        $routes->get('students/edit/(:num)', 'AdminController::editStudent/$1');
        $routes->post('students/update/(:num)', 'AdminController::updateStudent/$1');
        $routes->post('students/delete/(:num)', 'AdminController::deleteStudent/$1');
        $routes->post('students/import', 'AdminController::importStudents');
        
        // Class Management
        $routes->get('classes', 'AdminController::classes');
        $routes->get('classes/add', 'AdminController::addClass');
        $routes->post('classes/save', 'AdminController::saveClass');
        $routes->get('classes/edit/(:num)', 'AdminController::editClass/$1');
        $routes->post('classes/update/(:num)', 'AdminController::updateClass/$1');
        $routes->post('classes/delete/(:num)', 'AdminController::deleteClass/$1');
        
        // Voting Results & Reports
        $routes->get('results', 'AdminController::results');
        $routes->get('reports', 'AdminController::reports');
        $routes->get('reports/export/excel', 'AdminController::exportExcel');
        $routes->get('reports/export/pdf', 'AdminController::exportPDF');
        
        // System Settings
        $routes->get('settings', 'AdminController::settings');
        $routes->post('settings/update', 'AdminController::updateSettings');
        
        // Admin Management
        $routes->get('admins', 'AdminController::admins');
        $routes->get('admins/add', 'AdminController::addAdmin');
        $routes->post('admins/save', 'AdminController::saveAdmin');
        $routes->get('admins/edit/(:num)', 'AdminController::editAdmin/$1');
        $routes->post('admins/update/(:num)', 'AdminController::updateAdmin/$1');
        $routes->post('admins/delete/(:num)', 'AdminController::deleteAdmin/$1');
    });
});

// API Routes for AJAX requests
$routes->group('api', function($routes) {
    $routes->get('candidates/(:num)', 'ApiController::getCandidate/$1');
    $routes->get('voting-stats', 'ApiController::getVotingStats');
    $routes->get('results/live', 'ApiController::getLiveResults');
});

// Service Worker for PWA (optional)
$routes->get('sw.js', 'HomeController::serviceWorker');

// Error pages
$routes->set404Override(function() {
    return view('errors/html/error_404');
});
