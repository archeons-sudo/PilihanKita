<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Students from X MIPA 1
            [
                'nis' => '2024001001',
                'name' => 'Ahmad Rizki Pratama',
                'email' => 'ahmad.rizki@student.example.com',
                'class_id' => 1, // X MIPA 1
                'google_id' => null,
                'has_voted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2024001002',
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@student.example.com',
                'class_id' => 1, // X MIPA 1
                'google_id' => null,
                'has_voted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2024001003',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@student.example.com',
                'class_id' => 1, // X MIPA 1
                'google_id' => null,
                'has_voted' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Students from X MIPA 2
            [
                'nis' => '2024002001',
                'name' => 'Dewi Anggraini',
                'email' => 'dewi.anggraini@student.example.com',
                'class_id' => 2, // X MIPA 2
                'google_id' => null,
                'has_voted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2024002002',
                'name' => 'Fajar Nugroho',
                'email' => 'fajar.nugroho@student.example.com',
                'class_id' => 2, // X MIPA 2
                'google_id' => null,
                'has_voted' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Students from X IPS 1
            [
                'nis' => '2024004001',
                'name' => 'Rina Permatasari',
                'email' => 'rina.permata@student.example.com',
                'class_id' => 4, // X IPS 1
                'google_id' => null,
                'has_voted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2024004002',
                'name' => 'Andi Setiawan',
                'email' => 'andi.setiawan@student.example.com',
                'class_id' => 4, // X IPS 1
                'google_id' => null,
                'has_voted' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Students from XI MIPA 1
            [
                'nis' => '2023006001',
                'name' => 'Maya Sari',
                'email' => 'maya.sari@student.example.com',
                'class_id' => 6, // XI MIPA 1
                'google_id' => null,
                'has_voted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2023006002',
                'name' => 'Reza Pratama',
                'email' => 'reza.pratama@student.example.com',
                'class_id' => 6, // XI MIPA 1
                'google_id' => null,
                'has_voted' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Students from XI IPS 1
            [
                'nis' => '2023009001',
                'name' => 'Indira Cahyani',
                'email' => 'indira.cahyani@student.example.com',
                'class_id' => 9, // XI IPS 1
                'google_id' => null,
                'has_voted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2023009002',
                'name' => 'Dimas Prasetyo',
                'email' => 'dimas.prasetyo@student.example.com',
                'class_id' => 9, // XI IPS 1
                'google_id' => null,
                'has_voted' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Students from XII MIPA 1
            [
                'nis' => '2022011001',
                'name' => 'Sarah Fitria',
                'email' => 'sarah.fitria@student.example.com',
                'class_id' => 11, // XII MIPA 1
                'google_id' => null,
                'has_voted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2022011002',
                'name' => 'Kevin Adityawan',
                'email' => 'kevin.aditya@student.example.com',
                'class_id' => 11, // XII MIPA 1
                'google_id' => null,
                'has_voted' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Students from XII IPS 1
            [
                'nis' => '2022014001',
                'name' => 'Laila Maharani',
                'email' => 'laila.maharani@student.example.com',
                'class_id' => 14, // XII IPS 1
                'google_id' => null,
                'has_voted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2022014002',
                'name' => 'Arief Rahman',
                'email' => 'arief.rahman@student.example.com',
                'class_id' => 14, // XII IPS 1
                'google_id' => null,
                'has_voted' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Using Query Builder to insert data
        $this->db->table('students')->insertBatch($data);
        
        echo "Student data seeded successfully!\n";
        echo "Created 15 sample students across different classes\n";
        echo "7 students have already voted, 8 students haven't voted yet\n";
    }
}