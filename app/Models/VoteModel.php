<?php

namespace App\Models;

use CodeIgniter\Model;

class VoteModel extends Model
{
    protected $table            = 'votes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['student_id', 'candidate_id', 'period_id', 'vote_hash', 'ip_address', 'user_agent', 'voted_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'student_id' => 'int',
        'candidate_id' => 'int',
        'period_id' => 'int',
        // 'voted_at' => 'datetime',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'student_id' => 'required|integer|is_not_unique[students.id]',
        'candidate_id' => 'required|integer|is_not_unique[candidates.id]',
        'period_id' => 'required|integer|is_not_unique[periods.id]',
        'vote_hash' => 'required|min_length[32]|max_length[64]',
        'ip_address' => 'permit_empty|valid_ip',
        'user_agent' => 'permit_empty',
        'voted_at' => 'required|valid_date',
    ];
    protected $validationMessages   = [
        'student_id' => [
            'required' => 'Student ID is required',
            'integer' => 'Invalid student ID',
            'is_not_unique' => 'Student does not exist',
        ],
        'candidate_id' => [
            'required' => 'Candidate ID is required',
            'integer' => 'Invalid candidate ID',
            'is_not_unique' => 'Candidate does not exist',
        ],
        'period_id' => [
            'required' => 'Period ID is required',
            'integer' => 'Invalid period ID',
            'is_not_unique' => 'Period does not exist',
        ],
        'vote_hash' => [
            'required' => 'Vote hash is required',
            'min_length' => 'Invalid vote hash format',
            'max_length' => 'Invalid vote hash format',
        ],
        'ip_address' => [
            'valid_ip' => 'Invalid IP address format',
        ],
        'voted_at' => [
            'required' => 'Vote timestamp is required',
            'valid_date' => 'Invalid vote timestamp',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateVoteHash'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function generateVoteHash(array $data)
    {
        if (isset($data['data']['student_id'], $data['data']['candidate_id'], $data['data']['period_id'])) {
            $hashData = $data['data']['student_id'] . 
                       $data['data']['candidate_id'] . 
                       $data['data']['period_id'] . 
                       time() . 
                       uniqid();
            $data['data']['vote_hash'] = hash('sha256', $hashData);
            $data['data']['voted_at'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    public function hasStudentVoted($studentId, $periodId)
    {
        return $this->where('student_id', $studentId)
                    ->where('period_id', $periodId)
                    ->first() !== null;
    }

    public function getVoteByHash($voteHash)
    {
        return $this->select('votes.*, students.name as student_name, students.nis, 
                             candidates.name as candidate_name, periods.name as period_name')
                    ->join('students', 'students.id = votes.student_id')
                    ->join('candidates', 'candidates.id = votes.candidate_id')
                    ->join('periods', 'periods.id = votes.period_id')
                    ->where('vote_hash', $voteHash)
                    ->first();
    }

    public function castVote($studentId, $candidateId, $periodId, $ipAddress = null, $userAgent = null)
    {
        
        if ($this->hasStudentVoted($studentId, $periodId)) {
            return ['success' => false, 'message' => 'You have already voted in this period'];
        }

        $voteData = [
            'student_id' => $studentId,
            'candidate_id' => $candidateId,
            'period_id' => $periodId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            
            $voteId = $this->insert($voteData);
            
            if (!$voteId) {
                $db->transRollback();
                return ['success' => false, 'message' => 'Failed to record vote'];
            }

            
            $candidateModel = new \App\Models\CandidateModel();
            if (!$candidateModel->incrementVoteCount($candidateId)) {
                $db->transRollback();
                return ['success' => false, 'message' => 'Failed to update vote count'];
            }

            
            $studentModel = new \App\Models\StudentModel();
            if (!$studentModel->markAsVoted($studentId)) {
                $db->transRollback();
                return ['success' => false, 'message' => 'Failed to update student status'];
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return ['success' => false, 'message' => 'Transaction failed'];
            }

            
            $vote = $this->find($voteId);
            
            return [
                'success' => true, 
                'message' => 'Vote cast successfully',
                'vote_hash' => $vote['vote_hash'],
                'vote_id' => $voteId
            ];

        } catch (\Exception $e) {
            $db->transRollback();
            return ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
        }
    }

    public function getVotingStatsByPeriod($periodId)
    {
        return $this->select('candidates.name as candidate_name, COUNT(votes.id) as vote_count')
                    ->join('candidates', 'candidates.id = votes.candidate_id')
                    ->where('votes.period_id', $periodId)
                    ->groupBy('votes.candidate_id')
                    ->orderBy('vote_count', 'DESC')
                    ->findAll();
    }

    public function getHourlyVotingData($periodId)
    {
        return $this->select("DATE_FORMAT(voted_at, '%H:00') as hour, COUNT(*) as vote_count")
                    ->where('period_id', $periodId)
                    ->groupBy("DATE_FORMAT(voted_at, '%H')")
                    ->orderBy('hour')
                    ->findAll();
    }

    
    public function getVotingHistoryByStudent($studentId)
    {
        return $this->select('votes.*, periods.name as period_name, periods.id as period_id, 
                             candidates.name as candidate_name, candidates.vision as candidate_vision')
                    ->join('periods', 'periods.id = votes.period_id')
                    ->join('candidates', 'candidates.id = votes.candidate_id')
                    ->where('votes.student_id', $studentId)
                    ->orderBy('votes.voted_at', 'DESC')
                    ->findAll();
    }

    public function getActivePeriod()
    {
        $periodModel = new \App\Models\PeriodModel();
        return $periodModel->where('is_active', 1)
                          ->where('start_date <=', date('Y-m-d'))
                          ->where('end_date >=', date('Y-m-d'))
                          ->first();
    }

    public function getVoteReceipt($studentId, $periodId)
    {
        $vote = $this->select('votes.*, students.name as student_name, students.nis, students.class_id,
                             candidates.name as candidate_name, candidates.vision as candidate_vision,
                             periods.name as period_name, classes.name as class_name')
                    ->join('students', 'students.id = votes.student_id')
                    ->join('candidates', 'candidates.id = votes.candidate_id')
                    ->join('periods', 'periods.id = votes.period_id')
                    ->join('classes', 'classes.id = students.class_id')
                    ->where('votes.student_id', $studentId)
                    ->where('votes.period_id', $periodId)
                    ->first();

        if ($vote) {
            return [
                'vote_id' => $vote['id'],
                'vote_hash' => $vote['vote_hash'],
                'vote_date' => $vote['voted_at'],
                'student_name' => $vote['student_name'],
                'student_nis' => $vote['nis'],
                'class_name' => $vote['class_name'],
                'period_name' => $vote['period_name'],
                'candidate_name' => $vote['candidate_name'],
                'candidate_vision' => $vote['candidate_vision']
            ];
        }

        return null;
    }
}
