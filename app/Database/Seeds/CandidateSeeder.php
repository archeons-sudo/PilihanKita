<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CandidateSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'period_id' => 1, // 2024/2025
                'name' => 'Alicia Putri Maharani',
                'photo' => 'candidate_1.jpg',
                'vision' => 'Mewujudkan OSIS yang inovatif, kreatif, dan berdaya saing tinggi dalam mengembangkan potensi siswa di era digital dengan tetap menjunjung tinggi nilai-nilai budaya Indonesia.',
                'mission' => '1. Mengembangkan program-program kreatif yang mendukung minat dan bakat siswa<br>
                              2. Meningkatkan kualitas kegiatan ekstrakurikuler dan organisasi siswa<br>
                              3. Menciptakan lingkungan sekolah yang nyaman, bersih, dan kondusif untuk belajar<br>
                              4. Menjalin kerjasama yang baik antara siswa, guru, dan orang tua<br>
                              5. Mengoptimalkan penggunaan teknologi dalam kegiatan organisasi siswa',
                'vote_count' => 45,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'period_id' => 1, // 2024/2025
                'name' => 'Muhammad Farhan Adiluhung',
                'photo' => 'candidate_2.jpg',
                'vision' => 'Membangun OSIS yang solid, transparan, dan aspiratif sebagai wadah pengembangan kepemimpinan siswa yang berkarakter dan berintegritas tinggi.',
                'mission' => '1. Meningkatkan transparansi dan akuntabilitas dalam setiap kegiatan OSIS<br>
                              2. Menciptakan program pembinaan karakter dan kepemimpinan siswa<br>
                              3. Mengembangkan kegiatan sosial dan peduli lingkungan<br>
                              4. Memfasilitasi aspirasi siswa melalui forum diskusi dan saran<br>
                              5. Menyelenggarakan event-event yang mempererat persaudaraan antar siswa',
                'vote_count' => 38,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'period_id' => 1, // 2024/2025
                'name' => 'Nadia Salsabila Rahman',
                'photo' => 'candidate_3.jpg',
                'vision' => 'Menciptakan OSIS yang dinamis dan progresif dalam menyuarakan aspirasi siswa serta mengembangkan potensi akademik dan non-akademik secara optimal.',
                'mission' => '1. Mengoptimalkan peran OSIS sebagai jembatan komunikasi siswa dan pihak sekolah<br>
                              2. Mengembangkan program literasi dan peningkatan prestasi akademik<br>
                              3. Menyelenggarakan kompetisi dan olimpiade untuk meningkatkan daya saing<br>
                              4. Membangun jejaring dengan sekolah lain untuk pertukaran pengalaman<br>
                              5. Menciptakan program pengembangan soft skill dan hard skill siswa',
                'vote_count' => 42,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'period_id' => 1, // 2024/2025
                'name' => 'Raffi Ahmad Nugraha',
                'photo' => 'candidate_4.jpg',
                'vision' => 'Mewujudkan OSIS yang harmonis dan berkeadilan sosial dalam mengayomi seluruh siswa tanpa memandang latar belakang dengan semangat gotong royong.',
                'mission' => '1. Menciptakan kegiatan yang dapat diikuti oleh seluruh siswa dari berbagai kalangan<br>
                              2. Mengembangkan program beasiswa dan bantuan untuk siswa kurang mampu<br>
                              3. Menyelenggarakan kegiatan budaya dan seni tradisional Indonesia<br>
                              4. Membangun sistem mentoring antar siswa senior dan junior<br>
                              5. Mengoptimalkan fasilitas sekolah untuk kesejahteraan bersama',
                'vote_count' => 28,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Previous period candidates (2023/2024) - inactive
            [
                'period_id' => 2, // 2023/2024
                'name' => 'Dian Pertiwi Sari',
                'photo' => 'candidate_prev_1.jpg',
                'vision' => 'OSIS yang kreatif dan inovatif untuk kemajuan sekolah',
                'mission' => '1. Program kreativitas siswa<br>2. Inovasi dalam kegiatan sekolah<br>3. Peningkatan fasilitas belajar',
                'vote_count' => 156,
                'is_active' => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 year')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 year')),
            ],
            [
                'period_id' => 2, // 2023/2024
                'name' => 'Bayu Anggara Putra',
                'photo' => 'candidate_prev_2.jpg',
                'vision' => 'OSIS yang peduli dan responsif terhadap kebutuhan siswa',
                'mission' => '1. Mendengarkan aspirasi siswa<br>2. Program sosial dan peduli lingkungan<br>3. Kegiatan yang bermanfaat',
                'vote_count' => 134,
                'is_active' => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 year')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 year')),
            ]
        ];

        // Using Query Builder to insert data
        $this->db->table('candidates')->insertBatch($data);
        
        echo "Candidate data seeded successfully!\n";
        echo "Created 4 active candidates for period 2024/2025\n";
        echo "Created 2 inactive candidates from previous period 2023/2024\n";
        echo "Total votes cast: " . (45 + 38 + 42 + 28) . " votes\n";
    }
}