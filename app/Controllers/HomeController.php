<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CandidateModel;
use App\Models\PeriodModel;
use App\Models\StudentModel;
use CodeIgniter\HTTP\ResponseInterface;

class HomeController extends BaseController
{
    protected $candidateModel;
    protected $periodModel;
    protected $studentModel;

    public function __construct()
    {
        $this->candidateModel = new CandidateModel();
        $this->periodModel = new PeriodModel();
        $this->studentModel = new StudentModel();
    }

    public function index()
    {
        // Get active period
        $activePeriod = $this->periodModel->getActivePeriod();
        
        // Get active candidates
        $candidates = $this->candidateModel->getActiveCandidates();
        
        // Get voting statistics
        $votingStats = $this->studentModel->getVotingStats();
        
        $data = [
            'title' => 'Beranda',
            'activePeriod' => $activePeriod,
            'candidates' => $candidates,
            'totalStudents' => $votingStats['total_students'],
            'votedStudents' => $votingStats['voted_students'],
            'votingPercentage' => $votingStats['voting_percentage']
        ];

        return view('public/home', $data);
    }

    public function candidates()
    {
        // Get active period
        $activePeriod = $this->periodModel->getActivePeriod();
        
        // Get active candidates with detailed information
        $candidates = $this->candidateModel->getActiveCandidates();
        
        $data = [
            'title' => 'Daftar Kandidat',
            'activePeriod' => $activePeriod,
            'candidates' => $candidates
        ];

        return view('public/candidates', $data);
    }

    // API Endpoints for AJAX calls
    public function apiVotingResults()
    {
        try {
            $candidates = $this->candidateModel->getActiveCandidates();
            $votingStats = $this->studentModel->getVotingStats();

            return $this->response->setJSON([
                'success' => true,
                'candidates' => $candidates,
                'stats' => $votingStats
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memuat data voting'
            ]);
        }
    }

    public function apiCandidateDetail($candidateId = null)
    {
        try {
            if (!$candidateId) {
                throw new \Exception('ID kandidat tidak valid');
            }

            $candidate = $this->candidateModel->find($candidateId);
            
            if (!$candidate) {
                throw new \Exception('Kandidat tidak ditemukan');
            }

            // Format vision & mission agar line break rapi di modal
            $candidate['vision'] = nl2br(esc($candidate['vision']));
            $candidate['mission'] = nl2br(esc($candidate['mission']));

            return $this->response->setJSON([
                'success' => true,
                'candidate' => $candidate
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
