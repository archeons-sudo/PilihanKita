<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CandidateModel;
use App\Models\PeriodModel;
use App\Models\StudentModel;
use App\Models\VoteModel;

class HomeController extends BaseController
{
    protected $candidateModel;
    protected $periodModel;
    protected $studentModel;
    protected $voteModel;

    public function __construct()
    {
        $this->candidateModel = new CandidateModel();
        $this->periodModel = new PeriodModel();
        $this->studentModel = new StudentModel();
        $this->voteModel = new VoteModel();
    }

    public function index()
    {
        $data = [];
        
        try {
            // Get current active period
            $data['current_period'] = $this->periodModel->where('is_active', 1)->first();
            
            // Get candidates for current period
            $data['candidates'] = [];
            if ($data['current_period']) {
                $data['candidates'] = $this->candidateModel
                    ->where('period_id', $data['current_period']['id'])
                    ->where('is_active', 1)
                    ->orderBy('vote_count', 'DESC')
                    ->findAll();
            }
            
            // Get voting statistics
            $data['voting_stats'] = [
                'total_students' => $this->studentModel->countAll(),
                'students_voted' => $this->studentModel->where('has_voted', 1)->countAllResults(),
                'total_votes' => $this->voteModel->countAll(),
                'voting_percentage' => 0
            ];
            
            if ($data['voting_stats']['total_students'] > 0) {
                $data['voting_stats']['voting_percentage'] = 
                    ($data['voting_stats']['students_voted'] / $data['voting_stats']['total_students']) * 100;
            }
            
        } catch (\Exception $e) {
            // In case database is not set up yet, provide default values
            $data['current_period'] = null;
            $data['candidates'] = [];
            $data['voting_stats'] = [
                'total_students' => 0,
                'students_voted' => 0,
                'total_votes' => 0,
                'voting_percentage' => 0
            ];
        }
        
        $data['title'] = 'PilihanKita - Sistem Pemilihan OSIS';
        
        return view('home/index', $data);
    }

    public function candidates()
    {
        $data = [];
        
        try {
            // Get current active period
            $currentPeriod = $this->periodModel->where('is_active', 1)->first();
            
            if ($currentPeriod) {
                $data['candidates'] = $this->candidateModel
                    ->where('period_id', $currentPeriod['id'])
                    ->where('is_active', 1)
                    ->orderBy('vote_count', 'DESC')
                    ->findAll();
                    
                $data['current_period'] = $currentPeriod;
            } else {
                $data['candidates'] = [];
                $data['current_period'] = null;
            }
            
        } catch (\Exception $e) {
            $data['candidates'] = [];
            $data['current_period'] = null;
        }
        
        $data['title'] = 'Daftar Kandidat - PilihanKita';
        
        return view('candidates/index', $data);
    }

    public function candidateDetail($id)
    {
        $data = [];
        
        try {
            $data['candidate'] = $this->candidateModel->find($id);
            
            if (!$data['candidate']) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            
            // Get period info
            $data['period'] = $this->periodModel->find($data['candidate']['period_id']);
            
        } catch (\Exception $e) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $data['title'] = 'Detail Kandidat - ' . $data['candidate']['name'];
        
        return view('candidates/detail', $data);
    }
}
