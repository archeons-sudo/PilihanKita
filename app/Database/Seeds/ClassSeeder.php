<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Kelas 10 (X)
            [
                'name' => 'X MIPA 1',
                'grade' => 10,
                'major' => 'MIPA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'X MIPA 2',
                'grade' => 10,
                'major' => 'MIPA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'X MIPA 3',
                'grade' => 10,
                'major' => 'MIPA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'X IPS 1',
                'grade' => 10,
                'major' => 'IPS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'X IPS 2',
                'grade' => 10,
                'major' => 'IPS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Kelas 11 (XI)
            [
                'name' => 'XI MIPA 1',
                'grade' => 11,
                'major' => 'MIPA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'XI MIPA 2',
                'grade' => 11,
                'major' => 'MIPA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'XI MIPA 3',
                'grade' => 11,
                'major' => 'MIPA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'XI IPS 1',
                'grade' => 11,
                'major' => 'IPS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'XI IPS 2',
                'grade' => 11,
                'major' => 'IPS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Kelas 12 (XII)
            [
                'name' => 'XII MIPA 1',
                'grade' => 12,
                'major' => 'MIPA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'XII MIPA 2',
                'grade' => 12,
                'major' => 'MIPA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'XII MIPA 3',
                'grade' => 12,
                'major' => 'MIPA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'XII IPS 1',
                'grade' => 12,
                'major' => 'IPS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'XII IPS 2',
                'grade' => 12,
                'major' => 'IPS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Using Query Builder to insert data
        $this->db->table('classes')->insertBatch($data);
        
        echo "Class data seeded successfully!\n";
        echo "Created 15 classes (X, XI, XII with MIPA and IPS majors)\n";
    }
}