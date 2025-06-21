<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PilihanKita - Admin Panel">
    <meta name="keywords" content="admin, voting, osis, pilihankita">
    <meta name="author" content="PilihanKita">
    <title><?= isset($title) ? $title . ' - ' : '' ?>Admin | PilihanKita</title>
    <link rel="icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #8B5A96;
            --primary-dark: #6B4673;
            --secondary-color: #2DD4BF;
            --accent-color: #FF6B6B;
            --tertiary-color: #4ECDC4;
            --success-color: #00F5A0;
            --warning-color: #FFD93D;
            --danger-color: #FF4757;
            --dark-bg: #0A0B0D;
            --dark-secondary: #1A1D23;
            --dark-tertiary: #252A32;
            --text-primary: #FFFFFF;
            --text-secondary: #E2E8F0;
            --text-muted: #CBD5E1;
            --border-color: rgba(139, 90, 150, 0.3);
            --border-color-hover: rgba(139, 90, 150, 0.5);
            
            /* Text Colors for Stats */
            --stats-purple: #C084FC;
            --stats-green: #34D399;
            --stats-yellow: #FBBF24;
            --stats-blue: #60A5FA;
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, #8B5A96 0%, #2DD4BF 50%, #FF6B6B 100%);
            --gradient-secondary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-accent: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 100%);
            --gradient-dark: linear-gradient(135deg, #0A0B0D 0%, #1A1D23 100%);
            --gradient-card: linear-gradient(135deg, rgba(139, 90, 150, 0.1) 0%, rgba(45, 212, 191, 0.05) 100%);
            
            /* Shadows */
            --shadow-neon: 0 0 20px rgba(139, 90, 150, 0.3);
            --shadow-teal: 0 0 20px rgba(45, 212, 191, 0.3);
            --shadow-coral: 0 0 20px rgba(255, 107, 107, 0.3);
            --shadow-glass: 0 8px 32px rgba(0, 0, 0, 0.3);
            --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.4);
        }
        body {
            font-family: 'Space Grotesk', sans-serif;
            background: var(--dark-bg);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(139, 90, 150, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(45, 212, 191, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(255, 107, 107, 0.1) 0%, transparent 50%);
            z-index: -1;
            animation: backgroundShift 20s ease-in-out infinite;
        }
        
        @keyframes backgroundShift {
            0%, 100% { transform: scale(1) rotate(0deg); }
            33% { transform: scale(1.1) rotate(1deg); }
            66% { transform: scale(0.9) rotate(-1deg); }
        }
        
        /* Admin Navbar */
        .admin-navbar {
            background: rgba(26, 29, 35, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-glass);
            transition: all 0.3s ease;
        }
        
        .admin-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.75rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: var(--shadow-neon);
            transition: all 0.3s ease;
        }
        
        .admin-navbar .navbar-brand:hover {
            transform: scale(1.05);
            filter: brightness(1.2);
        }
        
        .admin-navbar .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease;
            margin: 0 0.5rem;
        }
        
        .admin-navbar .nav-link::before {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .admin-navbar .nav-link:hover {
            color: var(--text-primary) !important;
            transform: translateY(-2px);
        }
        
        .admin-navbar .nav-link:hover::before {
            width: 100%;
        }
        
        .admin-navbar .nav-link.active {
            color: var(--secondary-color) !important;
        }
        
        .admin-navbar .dropdown-menu {
            background: var(--dark-secondary);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-glass);
        }
        
        .admin-navbar .dropdown-item {
            color: var(--text-secondary) !important;
            transition: all 0.3s ease;
        }
        
        .admin-navbar .dropdown-item:hover {
            background: var(--dark-tertiary);
            color: var(--text-primary) !important;
            transform: translateX(5px);
        }
        
        /* Main Content */
        .main-content {
            min-height: calc(100vh - 180px);
            padding: 2rem 0;
        }
        
        /* Cards */
        .card {
            background: rgba(26, 29, 35, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: var(--shadow-glass);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient-primary);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            border-color: var(--border-color-hover);
            box-shadow: var(--shadow-hover);
        }
        
        .card:hover::before {
            opacity: 1;
        }
        
        .card-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        /* Admin Footer */
        .admin-footer {
            background: var(--dark-secondary);
            color: var(--text-secondary);
            padding: 2rem 0 1rem 0;
            margin-top: 3rem;
            border-top: 1px solid var(--border-color);
        }
        
        .admin-footer .footer-title {
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }
        
        .admin-footer .footer-link {
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .admin-footer .footer-link:hover {
            color: var(--secondary-color);
            transform: translateX(5px);
        }
        
        /* Text Colors */
        .text-primary { color: var(--text-primary) !important; }
        .text-secondary { color: var(--text-secondary) !important; }
        .text-muted { color: var(--text-muted) !important; }
        
        /* Stats Colors */
        .text-stats-purple { color: var(--stats-purple) !important; }
        .text-stats-green { color: var(--stats-green) !important; }
        .text-stats-yellow { color: var(--stats-yellow) !important; }
        .text-stats-blue { color: var(--stats-blue) !important; }
        
        /* Buttons */
        .btn {
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            box-shadow: var(--shadow-neon);
        }
        
        .btn-warning {
            background: var(--warning-color);
            border: none;
            color: var(--dark-bg);
        }
        
        .btn-danger {
            background: var(--danger-color);
            border: none;
        }
        
        .btn-success {
            background: var(--success-color);
            border: none;
            color: var(--dark-bg);
        }
        
        /* Tables */
        .table {
            color: var(--text-secondary);
            border-color: var(--border-color);
        }
        
        .table thead th {
            background: var(--dark-tertiary);
            color: var(--text-primary);
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
            padding: 1rem;
        }
        
        .table tbody td {
            color: var(--text-secondary);
            padding: 1rem;
            border-color: var(--border-color);
        }
        
        .table tbody tr {
            border-color: var(--border-color);
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background: rgba(139, 90, 150, 0.1);
        }
        
        /* Forms */
        .form-control, .form-select {
            background: var(--dark-tertiary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 0.75rem;
            padding: 0.75rem 1.25rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            background: var(--dark-secondary);
            border-color: var(--primary-color);
            color: var(--text-primary);
            box-shadow: var(--shadow-neon);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--dark-bg);
            border-left: 1px solid var(--border-color);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--dark-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
        
        /* Card Text */
        .card-title {
            color: var(--text-primary);
            font-weight: 600;
        }
        
        .card-text {
            color: var(--text-secondary);
        }
        
        /* Form Labels */
        label {
            color: var(--text-primary);
            font-weight: 500;
        }
        
        /* Links */
        a {
            color: var(--secondary-color);
            transition: all 0.3s ease;
        }
        
        a:hover {
            color: var(--accent-color);
            text-decoration: none;
        }
    </style>
    <?= $this->renderSection('css') ?>
</head>
<body>
    <!-- Admin Navbar -->
    <nav class="navbar navbar-expand-lg admin-navbar sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('admin-system/dashboard') ?>">
                <i class="bi bi-shield-lock me-2"></i>Admin PilihanKita
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link<?= (url_is('admin-system/dashboard')) ? ' active' : '' ?>" href="<?= base_url('admin-system/dashboard') ?>">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= (url_is('admin-system/candidates*')) ? ' active' : '' ?>" href="<?= base_url('admin-system/candidates') ?>">
                            <i class="bi bi-person-badge me-1"></i>Kandidat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= (url_is('admin-system/students*')) ? ' active' : '' ?>" href="<?= base_url('admin-system/students') ?>">
                            <i class="bi bi-people me-1"></i>Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= (url_is('admin-system/classes*')) ? ' active' : '' ?>" href="<?= base_url('admin-system/classes') ?>">
                            <i class="bi bi-journal me-1"></i>Kelas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= (url_is('admin-system/periods*')) ? ' active' : '' ?>" href="<?= base_url('admin-system/periods') ?>">
                            <i class="bi bi-calendar2-week me-1"></i>Periode
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= (url_is('admin-system/results*')) ? ' active' : '' ?>" href="<?= base_url('admin-system/results') ?>">
                            <i class="bi bi-bar-chart me-1"></i>Hasil Voting
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i><?= session('admin_name') ?? 'Admin' ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= base_url('admin-system/settings') ?>"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin-system/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Main Content -->
    <main class="main-content">
        <?= $this->renderSection('content') ?>
    </main>
    <!-- Admin Footer -->
    <footer class="admin-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="footer-title">PilihanKita Admin</div>
                    <div class="small">Panel administrasi sistem voting OSIS modern, aman, dan transparan.</div>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="footer-title">Menu Admin</div>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url('admin-system/dashboard') ?>" class="footer-link">Dashboard</a></li>
                        <li><a href="<?= base_url('admin-system/candidates') ?>" class="footer-link">Kandidat</a></li>
                        <li><a href="<?= base_url('admin-system/students') ?>" class="footer-link">Siswa</a></li>
                        <li><a href="<?= base_url('admin-system/classes') ?>" class="footer-link">Kelas</a></li>
                        <li><a href="<?= base_url('admin-system/periods') ?>" class="footer-link">Periode</a></li>
                        <li><a href="<?= base_url('admin-system/results') ?>" class="footer-link">Hasil Voting</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <div class="footer-title">Kontak Admin</div>
                    <div class="small mb-2"><i class="bi bi-envelope me-2"></i>admin@pilihankita.local</div>
                    <div class="small"><i class="bi bi-shield-lock me-2"></i>Panel Admin PilihanKita</div>
                </div>
            </div>
            <hr class="border-primary">
            <div class="row">
                <div class="col-12 text-center small">
                    &copy; <?= date('Y') ?> PilihanKita Admin. Dibuat dengan <span style="color: var(--accent-color)">&#10084;</span> untuk pendidikan Indonesia.
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html> 