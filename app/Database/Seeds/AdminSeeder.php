<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'email' => 'admin@pilihankita.local',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'full_name' => 'Administrator',
                'role' => 'super_admin',
                'is_active' => 1,
                'last_login' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'operator',
                'email' => 'operator@pilihankita.local',
                'password' => password_hash('operator123', PASSWORD_DEFAULT),
                'full_name' => 'Operator Voting',
                'role' => 'admin',
                'is_active' => 1,
                'last_login' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        
        $this->db->table('admins')->insertBatch($data);
        
        echo "Admin data seeded successfully!\n";
        echo "Default admin - Username: admin, Password: admin123\n";
        echo "Default operator - Username: operator, Password: operator123\n";
    }
}