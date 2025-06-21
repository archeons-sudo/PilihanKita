<?php

namespace App\Models;

use CodeIgniter\Model;

class CandidateModel extends Model
{
    protected $table            = 'candidates';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['period_id', 'name', 'photo', 'vision', 'mission', 'vote_count', 'is_active'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'period_id' => 'int',
        'vote_count' => 'int',
        'is_active' => 'boolean',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'period_id' => 'required|integer|is_not_unique[periods.id]',
        'name' => 'required|min_length[3]|max_length[100]',
        'photo' => 'permit_empty|max_length[255]',
        'vision' => 'permit_empty',
        'mission' => 'permit_empty',
        'vote_count' => 'permit_empty|integer',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];
    protected $validationMessages   = [
        'period_id' => [
            'required' => 'Period is required',
            'integer' => 'Invalid period selection',
            'is_not_unique' => 'Selected period does not exist',
        ],
        'name' => [
            'required' => 'Candidate name is required',
            'min_length' => 'Candidate name must be at least 3 characters',
            'max_length' => 'Candidate name cannot exceed 100 characters',
        ],
        'photo' => [
            'max_length' => 'Photo path cannot exceed 255 characters',
        ],
        'vote_count' => [
            'integer' => 'Vote count must be a number',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getCandidatesByPeriod($periodId)
    {
        return $this->where('period_id', $periodId)
                    ->where('is_active', 1)
                    ->orderBy('vote_count', 'DESC')
                    ->findAll();
    }

    public function getActiveCandidates()
    {
        $periodModel = new \App\Models\PeriodModel();
        $activePeriod = $periodModel->getActivePeriod();
        
        if (!$activePeriod) {
            return [];
        }
        
        return $this->getCandidatesByPeriod($activePeriod['id']);
    }

    public function incrementVoteCount($candidateId)
    {
        $candidate = $this->find($candidateId);
        if ($candidate) {
            return $this->update($candidateId, [
                'vote_count' => $candidate['vote_count'] + 1
            ]);
        }
        return false;
    }

    public function decrementVoteCount($candidateId)
    {
        $candidate = $this->find($candidateId);
        if ($candidate && $candidate['vote_count'] > 0) {
            return $this->update($candidateId, [
                'vote_count' => $candidate['vote_count'] - 1
            ]);
        }
        return false;
    }

    public function getVotingResults($periodId = null)
    {
        if ($periodId === null) {
            $periodModel = new \App\Models\PeriodModel();
            $activePeriod = $periodModel->getActivePeriod();
            if (!$activePeriod) {
                return [];
            }
            $periodId = $activePeriod['id'];
        }

        return $this->select('candidates.*, periods.name as period_name')
                    ->join('periods', 'periods.id = candidates.period_id')
                    ->where('candidates.period_id', $periodId)
                    ->orderBy('candidates.vote_count', 'DESC')
                    ->findAll();
    }

    public function resetVoteCounts($periodId)
    {
        return $this->where('period_id', $periodId)
                    ->set('vote_count', 0)
                    ->update();
    }

    public function getTotalVotes($periodId = null)
    {
        $builder = $this->builder();
        
        if ($periodId !== null) {
            $builder->where('period_id', $periodId);
        }
        
        return $builder->selectSum('vote_count')->get()->getRow()->vote_count ?? 0;
    }
}
