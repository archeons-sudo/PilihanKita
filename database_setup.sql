-- PilihanKita Voting System Database Setup
-- Created by CodeIgniter 4 Migration System
-- Use this file for manual database setup

-- Create database
CREATE DATABASE IF NOT EXISTS `pilihankita_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pilihankita_db`;

-- ========================================
-- PERIODS TABLE (Election Periods)
-- ========================================
CREATE TABLE `periods` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `start_date` DATETIME NOT NULL,
    `end_date` DATETIME NOT NULL,
    `is_active` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- CLASSES TABLE (Student Classes)
-- ========================================
CREATE TABLE `classes` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `grade` INT(2) NOT NULL,
    `major` VARCHAR(50) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- STUDENTS TABLE (Student Data)
-- ========================================
CREATE TABLE `students` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `nis` VARCHAR(20) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `class_id` INT(11) UNSIGNED NOT NULL,
    `google_id` VARCHAR(255) NULL,
    `has_voted` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_nis` (`nis`),
    KEY `idx_email` (`email`),
    CONSTRAINT `fk_students_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- CANDIDATES TABLE (Election Candidates)
-- ========================================
CREATE TABLE `candidates` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `period_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `photo` VARCHAR(255) NULL,
    `vision` TEXT NULL,
    `mission` TEXT NULL,
    `vote_count` INT(11) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_candidates_period` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- VOTES TABLE (Voting Records)
-- ========================================
CREATE TABLE `votes` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` INT(11) UNSIGNED NOT NULL,
    `candidate_id` INT(11) UNSIGNED NOT NULL,
    `period_id` INT(11) UNSIGNED NOT NULL,
    `vote_hash` VARCHAR(64) NOT NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `voted_at` DATETIME NOT NULL,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_vote_hash` (`vote_hash`),
    CONSTRAINT `fk_votes_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_votes_candidate` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_votes_period` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- ADMINS TABLE (Admin Users)
-- ========================================
CREATE TABLE `admins` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `role` ENUM('super_admin', 'admin') DEFAULT 'admin',
    `is_active` TINYINT(1) DEFAULT 1,
    `last_login` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_username` (`username`),
    KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- SAMPLE DATA
-- ========================================

-- Insert default admin user
INSERT INTO `admins` (`username`, `email`, `password`, `full_name`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
('admin', 'admin@pilihankita.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'super_admin', 1, NOW(), NOW());
-- Password: admin123

-- Insert sample election period
INSERT INTO `periods` (`name`, `description`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
('Pemilihan OSIS 2024/2025', 'Pemilihan Ketua dan Wakil Ketua OSIS periode 2024/2025', '2024-01-15 08:00:00', '2024-01-15 15:00:00', 1, NOW(), NOW());

-- Insert sample classes
INSERT INTO `classes` (`name`, `grade`, `major`, `created_at`, `updated_at`) VALUES
('X-MIPA-1', 10, 'MIPA', NOW(), NOW()),
('X-MIPA-2', 10, 'MIPA', NOW(), NOW()),
('X-IPS-1', 10, 'IPS', NOW(), NOW()),
('X-IPS-2', 10, 'IPS', NOW(), NOW()),
('XI-MIPA-1', 11, 'MIPA', NOW(), NOW()),
('XI-MIPA-2', 11, 'MIPA', NOW(), NOW()),
('XI-IPS-1', 11, 'IPS', NOW(), NOW()),
('XI-IPS-2', 11, 'IPS', NOW(), NOW()),
('XII-MIPA-1', 12, 'MIPA', NOW(), NOW()),
('XII-MIPA-2', 12, 'MIPA', NOW(), NOW()),
('XII-IPS-1', 12, 'IPS', NOW(), NOW()),
('XII-IPS-2', 12, 'IPS', NOW(), NOW());

-- Insert sample students
INSERT INTO `students` (`nis`, `name`, `email`, `class_id`, `has_voted`, `created_at`, `updated_at`) VALUES
('2024001001', 'Ahmad Rizki Pratama', 'ahmad.rizki@student.school.id', 1, 0, NOW(), NOW()),
('2024001002', 'Siti Nurhaliza', 'siti.nurhaliza@student.school.id', 1, 0, NOW(), NOW()),
('2024001003', 'Budi Santoso', 'budi.santoso@student.school.id', 2, 0, NOW(), NOW()),
('2024001004', 'Dina Marlina', 'dina.marlina@student.school.id', 2, 0, NOW(), NOW()),
('2024001005', 'Eko Wijaya', 'eko.wijaya@student.school.id', 3, 0, NOW(), NOW()),
('2024001006', 'Fatimah Zahra', 'fatimah.zahra@student.school.id', 3, 0, NOW(), NOW()),
('2024001007', 'Gilang Ramadhan', 'gilang.ramadhan@student.school.id', 4, 0, NOW(), NOW()),
('2024001008', 'Hani Septiani', 'hani.septiani@student.school.id', 4, 0, NOW(), NOW()),
('2023001001', 'Irfan Hakim', 'irfan.hakim@student.school.id', 5, 0, NOW(), NOW()),
('2023001002', 'Julia Perez', 'julia.perez@student.school.id', 5, 0, NOW(), NOW()),
('2023001003', 'Kevin Aprilio', 'kevin.aprilio@student.school.id', 6, 0, NOW(), NOW()),
('2023001004', 'Lina Maharani', 'lina.maharani@student.school.id', 6, 0, NOW(), NOW()),
('2022001001', 'Muhammad Iqbal', 'muhammad.iqbal@student.school.id', 9, 0, NOW(), NOW()),
('2022001002', 'Nadia Putri', 'nadia.putri@student.school.id', 9, 0, NOW(), NOW()),
('2022001003', 'Oscar Lawalata', 'oscar.lawalata@student.school.id', 10, 0, NOW(), NOW()),
('2022001004', 'Putri Ayu', 'putri.ayu@student.school.id', 10, 0, NOW(), NOW());

-- Insert sample candidates
INSERT INTO `candidates` (`period_id`, `name`, `vision`, `mission`, `vote_count`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Ahmad & Siti (Paslon 1)', 'Mewujudkan OSIS yang Inovatif, Kreatif, dan Berprestasi untuk Kemajuan Sekolah', 'Meningkatkan kegiatan ekstrakurikuler, mengadakan event-event menarik, dan menjadi jembatan komunikasi antara siswa dan guru', 0, 1, NOW(), NOW()),
(1, 'Budi & Dina (Paslon 2)', 'OSIS yang Peduli Lingkungan dan Berprestasi Akademik', 'Mengembangkan program go green, meningkatkan fasilitas belajar, dan mendorong prestasi akademik siswa', 0, 1, NOW(), NOW()),
(1, 'Eko & Fatimah (Paslon 3)', 'OSIS yang Berkarakter, Religius, dan Berbudaya', 'Memperkuat nilai-nilai karakter, mengadakan kegiatan keagamaan, dan melestarikan budaya lokal', 0, 1, NOW(), NOW());

-- ========================================
-- INDEXES FOR BETTER PERFORMANCE
-- ========================================
CREATE INDEX idx_periods_active ON periods(is_active);
CREATE INDEX idx_candidates_period_active ON candidates(period_id, is_active);
CREATE INDEX idx_students_voted ON students(has_voted);
CREATE INDEX idx_votes_period ON votes(period_id);
CREATE INDEX idx_votes_student_period ON votes(student_id, period_id);

-- ========================================
-- VIEWS FOR REPORTING
-- ========================================

-- View for voting results
CREATE VIEW view_voting_results AS
SELECT 
    c.id as candidate_id,
    c.name as candidate_name,
    c.vote_count,
    p.name as period_name,
    p.id as period_id,
    ROUND((c.vote_count * 100.0 / NULLIF((SELECT SUM(vote_count) FROM candidates WHERE period_id = c.period_id), 0)), 2) as percentage
FROM candidates c
JOIN periods p ON p.id = c.period_id
WHERE c.is_active = 1
ORDER BY c.period_id, c.vote_count DESC;

-- View for student voting status
CREATE VIEW view_student_voting_status AS
SELECT 
    s.id as student_id,
    s.nis,
    s.name as student_name,
    s.email,
    cl.name as class_name,
    cl.grade,
    cl.major,
    s.has_voted,
    CASE WHEN v.id IS NOT NULL THEN 'VOTED' ELSE 'NOT_VOTED' END as voting_status,
    v.voted_at
FROM students s
JOIN classes cl ON cl.id = s.class_id
LEFT JOIN votes v ON v.student_id = s.id AND v.period_id = (SELECT id FROM periods WHERE is_active = 1 LIMIT 1)
ORDER BY cl.grade, cl.name, s.name;

-- ========================================
-- STORED PROCEDURES
-- ========================================

DELIMITER //

-- Procedure to reset voting data for a new election
CREATE PROCEDURE ResetVotingData(IN period_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    -- Reset candidate vote counts
    UPDATE candidates SET vote_count = 0 WHERE period_id = period_id;
    
    -- Reset student voting status
    UPDATE students SET has_voted = 0;
    
    -- Delete existing votes for the period
    DELETE FROM votes WHERE period_id = period_id;
    
    COMMIT;
END //

-- Procedure to get voting statistics
CREATE PROCEDURE GetVotingStats(IN period_id INT)
BEGIN
    SELECT 
        'Total Students' as metric,
        COUNT(*) as value
    FROM students
    
    UNION ALL
    
    SELECT 
        'Students Voted' as metric,
        COUNT(*) as value
    FROM students
    WHERE has_voted = 1
    
    UNION ALL
    
    SELECT 
        'Total Votes' as metric,
        COUNT(*) as value
    FROM votes
    WHERE period_id = period_id
    
    UNION ALL
    
    SELECT 
        'Voting Percentage' as metric,
        ROUND(
            (SELECT COUNT(*) FROM students WHERE has_voted = 1) * 100.0 / 
            NULLIF((SELECT COUNT(*) FROM students), 0), 
            2
        ) as value;
END //

DELIMITER ;

-- ========================================
-- SAMPLE QUERIES FOR TESTING
-- ========================================

-- Get all active candidates with their vote counts
-- SELECT * FROM view_voting_results WHERE period_id = 1;

-- Get voting statistics
-- CALL GetVotingStats(1);

-- Get students who haven't voted yet
-- SELECT * FROM view_student_voting_status WHERE voting_status = 'NOT_VOTED';

-- Get hourly voting distribution
-- SELECT 
--     HOUR(voted_at) as hour,
--     COUNT(*) as vote_count
-- FROM votes 
-- WHERE period_id = 1 
-- GROUP BY HOUR(voted_at) 
-- ORDER BY hour;

-- ========================================
-- IMPORTANT NOTES
-- ========================================

-- 1. Change the default admin password after setup:
--    UPDATE admins SET password = '$2y$10$newhashedpassword' WHERE username = 'admin';

-- 2. Remember to update Google API credentials in .env file:
--    GOOGLE_CLIENT_ID=your_client_id
--    GOOGLE_CLIENT_SECRET=your_client_secret

-- 3. For production, create proper database user with limited privileges:
--    CREATE USER 'pilihankita_user'@'localhost' IDENTIFIED BY 'secure_password';
--    GRANT SELECT, INSERT, UPDATE, DELETE ON pilihankita_db.* TO 'pilihankita_user'@'localhost';

-- 4. Regular backup recommendation:
--    mysqldump -u root -p pilihankita_db > pilihankita_backup_$(date +%Y%m%d).sql