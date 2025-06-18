<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'admins';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['username', 'email', 'password', 'full_name', 'role', 'is_active', 'last_login'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
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
        'username' => 'required|min_length[3]|max_length[50]|is_unique[admins.username,id,{id}]',
        'email' => 'required|valid_email|is_unique[admins.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'full_name' => 'required|min_length[3]|max_length[100]',
        'role' => 'permit_empty|in_list[super_admin,admin]',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];
    protected $validationMessages   = [
        'username' => [
            'required' => 'Username is required',
            'min_length' => 'Username must be at least 3 characters',
            'max_length' => 'Username cannot exceed 50 characters',
            'is_unique' => 'This username is already taken',
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please provide a valid email address',
            'is_unique' => 'This email is already registered',
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 6 characters',
        ],
        'full_name' => [
            'required' => 'Full name is required',
            'min_length' => 'Full name must be at least 3 characters',
            'max_length' => 'Full name cannot exceed 100 characters',
        ],
        'role' => [
            'in_list' => 'Role must be either super_admin or admin',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function authenticate($username, $password)
    {
        $admin = $this->where('username', $username)
                      ->orWhere('email', $username)
                      ->where('is_active', 1)
                      ->first();

        if ($admin && password_verify($password, $admin['password'])) {
            // Update last login
            $this->update($admin['id'], ['last_login' => date('Y-m-d H:i:s')]);
            
            // Remove password from return data
            unset($admin['password']);
            return $admin;
        }

        return false;
    }

    public function getAdminById($adminId)
    {
        $admin = $this->find($adminId);
        if ($admin) {
            unset($admin['password']);
        }
        return $admin;
    }

    public function getAdminByUsername($username)
    {
        return $this->where('username', $username)
                    ->orWhere('email', $username)
                    ->first();
    }

    public function updatePassword($adminId, $newPassword)
    {
        return $this->update($adminId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }

    public function getActiveAdmins()
    {
        return $this->select('id, username, email, full_name, role, last_login, created_at')
                    ->where('is_active', 1)
                    ->orderBy('full_name')
                    ->findAll();
    }

    public function createDefaultAdmin()
    {
        // Check if any admin exists
        if ($this->countAll() > 0) {
            return false;
        }

        $defaultAdmin = [
            'username' => 'admin',
            'email' => 'admin@pilihankita.local',
            'password' => 'admin123',
            'full_name' => 'System Administrator',
            'role' => 'super_admin',
            'is_active' => 1,
        ];

        return $this->insert($defaultAdmin);
    }

    public function deactivateAdmin($adminId)
    {
        return $this->update($adminId, ['is_active' => 0]);
    }

    public function activateAdmin($adminId)
    {
        return $this->update($adminId, ['is_active' => 1]);
    }

    public function isSuperAdmin($adminId)
    {
        $admin = $this->find($adminId);
        return $admin && $admin['role'] === 'super_admin';
    }
}
