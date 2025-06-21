<?php

namespace App\Models;

use CodeIgniter\Model;

class PeriodModel extends Model
{
    protected $table            = 'periods';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'description', 'start_date', 'end_date', 'is_active'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_active' => 'boolean',
        // 'start_date' => 'datetime',
        // 'end_date' => 'datetime',
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
        'name' => 'required|min_length[3]|max_length[100]',
        'start_date' => 'required|valid_date',
        'end_date' => 'required|valid_date',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Period name is required',
            'min_length' => 'Period name must be at least 3 characters',
            'max_length' => 'Period name cannot exceed 100 characters',
        ],
        'start_date' => [
            'required' => 'Start date is required',
            'valid_date' => 'Please provide a valid start date',
        ],
        'end_date' => [
            'required' => 'End date is required',
            'valid_date' => 'Please provide a valid end date',
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

    public function getActivePeriod()
    {
        return $this->where('is_active', 1)->first();
    }

    public function setActivePeriod($periodId)
    {
        // Deactivate all periods first
        $this->update(null, ['is_active' => 0]);
        
        // Activate the specified period
        return $this->update($periodId, ['is_active' => 1]);
    }
}
