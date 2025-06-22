<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table            = 'students';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nis', 'name', 'email', 'class_id', 'google_id', 'has_voted'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'class_id' => 'int',
        'has_voted' => 'boolean',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    
    protected $validationRules = [
        'nis' => 'required|min_length[6]|max_length[20]|is_unique[students.nis,id,{id}]',
        'name' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|is_unique[students.email,id,{id}]',
        'class_id' => 'required|integer|is_not_unique[classes.id]',
        'google_id' => 'permit_empty|max_length[255]',
        'has_voted' => 'permit_empty|in_list[0,1]',
    ];
    protected $validationMessages   = [
        'nis' => [
            'required' => 'NIS is required',
            'min_length' => 'NIS must be at least 6 characters',
            'max_length' => 'NIS cannot exceed 20 characters',
            'is_unique' => 'This NIS is already registered',
        ],
        'name' => [
            'required' => 'Student name is required',
            'min_length' => 'Student name must be at least 3 characters',
            'max_length' => 'Student name cannot exceed 100 characters',
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please provide a valid email address',
            'is_unique' => 'This email is already registered',
        ],
        'class_id' => [
            'required' => 'Class is required',
            'integer' => 'Invalid class selection',
            'is_not_unique' => 'Selected class does not exist',
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

    public function getStudentWithClass($studentId)
    {
        return $this->select('students.*, classes.name as class_name, classes.grade, classes.major')
                    ->join('classes', 'classes.id = students.class_id')
                    ->find($studentId);
    }

    public function getStudentByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getStudentByNIS($nis)
    {
        return $this->where('nis', $nis)->first();
    }

    public function getStudentByGoogleId($googleId)
    {
        return $this->where('google_id', $googleId)->first();
    }

    public function markAsVoted($studentId)
    {
        return $this->update($studentId, ['has_voted' => 1]);
    }

    public function getVotingStats()
    {
        $totalStudents = $this->countAll();
        $votedStudents = $this->where('has_voted', 1)->countAllResults();
        
        return [
            'total_students' => $totalStudents,
            'voted_students' => $votedStudents,
            'not_voted_students' => $totalStudents - $votedStudents,
            'voting_percentage' => $totalStudents > 0 ? round(($votedStudents / $totalStudents) * 100, 2) : 0,
        ];
    }

    public function findStudent($studentId)
    {
        return $this->select('id, nis, name, email, class_id, google_id, has_voted, created_at, updated_at')
                    ->find($studentId);
    }
}
