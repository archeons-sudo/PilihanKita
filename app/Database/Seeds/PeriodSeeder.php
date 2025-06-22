<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PeriodSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => '2024/2025',
                'description' => 'Pemilihan Ketua OSIS Periode 2024/2025',
                'start_date' => '2024-09-01',
                'end_date' => '2024-12-31',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => '2023/2024',
                'description' => 'Pemilihan Ketua OSIS Periode 2023/2024',
                'start_date' => '2023-09-01',
                'end_date' => '2023-12-31',
                'is_active' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => '2025/2026',
                'description' => 'Pemilihan Ketua OSIS Periode 2025/2026',
                'start_date' => '2025-09-01',
                'end_date' => '2025-12-31',
                'is_active' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        
        $this->db->table('periods')->insertBatch($data);
        
        echo "Period data seeded successfully!\n";
        echo "Active period: 2024/2025\n";
    }
}