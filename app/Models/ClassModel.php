<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table            = 'classes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'grade', 'major'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'grade' => 'int',
    ];
    protected array $castHandlers = [];

    
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    
    protected $validationRules = [
        'name' => 'required|min_length[1]|max_length[50]',
        'grade' => 'required|integer|in_list[10,11,12]',
        'major' => 'permit_empty|max_length[50]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Class name is required',
            'max_length' => 'Class name cannot exceed 50 characters',
        ],
        'grade' => [
            'required' => 'Grade is required',
            'integer' => 'Grade must be a number',
            'in_list' => 'Grade must be 10, 11, or 12',
        ],
        'major' => [
            'max_length' => 'Major cannot exceed 50 characters',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getClassesByGrade($grade)
    {
        return $this->where('grade', $grade)->findAll();
    }

    public function getClassWithStudentCount()
    {
        return $this->select('classes.*, COUNT(students.id) as student_count')
                    ->join('students', 'students.class_id = classes.id', 'left')
                    ->groupBy('classes.id')
                    ->findAll();
    }
}
