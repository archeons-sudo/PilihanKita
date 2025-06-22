<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PilihanKita - Sistem Voting Ketua OSIS Online">
    <meta name="keywords" content="voting, osis, pemilihan, ketua osis, pilihankita">
    <meta name="author" content="PilihanKita">
    <title><?= isset($title) ? $title . ' - ' : '' ?>PilihanKita | Sistem Voting Ketua OSIS</title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
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
            --text-secondary: #CBD5E1;
            --text-muted: #94A3B8;
            --border-color: #334155;
            
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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
        
        /* Floating particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--secondary-color);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
            opacity: 0.6;
        }
        
        .particle:nth-child(odd) {
            background: var(--accent-color);
            animation-delay: -2s;
        }
        
        .particle:nth-child(3n) {
            background: var(--primary-color);
            animation-delay: -4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.6; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }
        
        /* Navigation Styles */
        .navbar {
            background: rgba(26, 29, 35, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-glass);
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.75rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: var(--shadow-neon);
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
            filter: brightness(1.2);
        }
        
        .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease;
            margin: 0 0.5rem;
        }
        
        .nav-link::before {
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
        
        .nav-link:hover {
            color: var(--text-primary) !important;
            transform: translateY(-2px);
        }
        
        .nav-link:hover::before {
            width: 100%;
        }
        
        /* Main Content */
        .main-content {
            min-height: calc(100vh - 140px);
            padding: 2rem 0;
        }
        
        /* Glass Card Effects */
        .card {
            background: rgba(26, 29, 35, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(139, 90, 150, 0.2);
            border-radius: 1.5rem;
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
            height: 1px;
            background: var(--gradient-primary);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-hover);
            border-color: rgba(139, 90, 150, 0.4);
        }
        
        .card:hover::before {
            opacity: 1;
        }
        
        /* Text Color Fixes */
        .card-title, .card-text, h1, h2, h3, h4, h5, h6, p {
            color: var(--text-primary);
        }
        
        .text-muted {
            color: var(--text-muted) !important;
        }
        
        .text-secondary {
            color: var(--text-secondary) !important;
        }
        
        /* Buttons */
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: 1rem;
            padding: 0.875rem 2.5rem;
            font-weight: 600;
            font-family: 'Space Grotesk', sans-serif;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-neon);
            color: white;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
            filter: brightness(1.1);
            color: white;
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-outline-primary {
            border: 2px solid;
            border-image: var(--gradient-primary) 1;
            color: var(--text-primary);
            background: transparent;
            border-radius: 1rem;
            padding: 0.875rem 2.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-outline-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: var(--gradient-primary);
            transition: width 0.3s ease;
            z-index: -1;
        }
        
        .btn-outline-primary:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow-neon);
        }
        
        .btn-outline-primary:hover::before {
            width: 100%;
        }
        
        /* Hero Section */
        .hero-section {
            background: var(--gradient-dark);
            position: relative;
            padding: 5rem 0;
            margin-bottom: 4rem;
            border-radius: 0 0 3rem 3rem;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                linear-gradient(135deg, rgba(139, 90, 150, 0.1) 0%, rgba(45, 212, 191, 0.1) 100%);
            z-index: 1;
        }
        
        .hero-section .container {
            position: relative;
            z-index: 2;
        }
        
        .hero-section h1, .hero-section p {
            color: var(--text-primary);
        }
        
        /* Candidate Cards */
        .candidate-card {
            background: rgba(37, 42, 50, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(45, 212, 191, 0.2);
            border-radius: 2rem;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            box-shadow: var(--shadow-glass);
        }
        
        .candidate-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-accent);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .candidate-card:hover {
            transform: translateY(-15px) rotateX(5deg);
            box-shadow: var(--shadow-hover);
            border-color: rgba(45, 212, 191, 0.4);
        }
        
        .candidate-card:hover::before {
            transform: scaleX(1);
        }
        
        .candidate-card .card-title,
        .candidate-card .card-text {
            color: var(--text-primary);
        }
        
        .candidate-image {
            height: 280px;
            object-fit: cover;
            width: 100%;
            transition: all 0.3s ease;
            filter: grayscale(20%);
        }
        
        .candidate-card:hover .candidate-image {
            filter: grayscale(0%) brightness(1.1);
            transform: scale(1.05);
        }
        
        /* Vote Count Badge */
        .vote-count-badge {
            background: var(--gradient-accent);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: var(--shadow-coral);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        /* Progress Bars */
        .progress {
            height: 15px;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.3);
            overflow: hidden;
            position: relative;
        }
        
        .progress-bar {
            background: var(--gradient-primary);
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            animation: progressFill 2s ease-in-out;
        }
        
        .progress-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.3) 50%, transparent 70%);
            animation: shimmer 2s infinite;
        }
        
        @keyframes progressFill {
            from { width: 0%; }
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Google Button */
        .google-btn {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: var(--text-primary);
            transition: all 0.3s ease;
            border-radius: 1rem;
            padding: 0.875rem 2rem;
            font-weight: 600;
        }
        
        .google-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            color: var(--text-primary);
            transform: translateY(-3px);
            box-shadow: var(--shadow-glass);
        }
        
        /* Glass Card Variant */
        .glass-card {
            background: rgba(26, 29, 35, 0.6);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 2rem;
            box-shadow: var(--shadow-glass);
        }
        
        /* Text Gradient */
        .text-gradient {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }
        
        /* Footer */
        .footer {
            background: var(--dark-secondary);
            border-top: 1px solid var(--border-color);
            padding: 3rem 0 2rem;
            margin-top: 4rem;
            position: relative;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient-primary);
            opacity: 0.5;
        }
        
        .footer h5, .footer h6 {
            color: var(--text-primary);
        }
        
        .footer p, .footer a {
            color: var(--text-secondary);
        }
        
        .footer a:hover {
            color: var(--text-primary);
        }
        
        /* Animation Classes */
        .animate-fade-in {
            animation: fadeInUp 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .animate-slide-in {
            animation: slideInRight 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        @keyframes fadeInUp {
            from { 
                opacity: 0; 
                transform: translateY(30px) scale(0.95); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0) scale(1); 
            }
        }
        
        @keyframes slideInRight {
            from { 
                opacity: 0; 
                transform: translateX(30px); 
            }
            to { 
                opacity: 1; 
                transform: translateX(0); 
            }
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--dark-secondary);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--gradient-primary);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
        
        /* Dropdown Styles */
        .dropdown-menu {
            background: rgba(26, 29, 35, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: var(--shadow-glass);
        }
        
        .dropdown-item {
            color: var(--text-secondary);
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            margin: 0.25rem;
        }
        
        .dropdown-item:hover {
            background: rgba(139, 90, 150, 0.2);
            color: var(--text-primary);
            transform: translateX(5px);
        }
        
        /* Loading Animation */
        .loading-spinner {
            border: 3px solid rgba(139, 90, 150, 0.3);
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Additional text color fixes */
        .list-unstyled a {
            color: var(--text-secondary);
            transition: color 0.3s ease;
        }
        
        .list-unstyled a:hover {
            color: var(--text-primary);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-section {
                padding: 3rem 0;
                border-radius: 0 0 2rem 2rem;
            }
            
            .candidate-card {
                margin-bottom: 2rem;
            }
            
            .main-content {
                padding: 1.5rem 0;
            }
            
            .card {
                border-radius: 1rem;
            }
            
            .candidate-card {
                border-radius: 1.5rem;
            }
            
            .btn-primary, .btn-outline-primary {
                padding: 0.75rem 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1.5rem;
            }
            
            .hero-section {
                padding: 2rem 0;
            }
            
            .candidate-image {
                height: 220px;
            }
            
            .btn-primary, .btn-outline-primary {
                padding: 0.625rem 1.5rem;
                font-size: 0.9rem;
            }
        }
        
        /* Custom Utilities */
        .bg-glass {
            background: rgba(26, 29, 35, 0.8);
            backdrop-filter: blur(20px);
        }
        
        .border-gradient {
            border: 1px solid;
            border-image: var(--gradient-primary) 1;
        }
        
        .shadow-neon {
            box-shadow: var(--shadow-neon);
        }
        
        .shadow-teal {
            box-shadow: var(--shadow-teal);
        }
        
        .shadow-coral {
            box-shadow: var(--shadow-coral);
        }
    </style>
    
    <!-- Additional CSS -->
    <?= $this->renderSection('css') ?>
</head>
<body>
    <!-- Floating Particles -->
    <div class="particles">
        <div class="particle" style="left: 10%; top: 20%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 20%; top: 60%; animation-delay: -1s;"></div>
        <div class="particle" style="left: 30%; top: 10%; animation-delay: -2s;"></div>
        <div class="particle" style="left: 40%; top: 70%; animation-delay: -3s;"></div>
        <div class="particle" style="left: 50%; top: 30%; animation-delay: -4s;"></div>
        <div class="particle" style="left: 60%; top: 80%; animation-delay: -5s;"></div>
        <div class="particle" style="left: 70%; top: 40%; animation-delay: -1.5s;"></div>
        <div class="particle" style="left: 80%; top: 15%; animation-delay: -2.5s;"></div>
        <div class="particle" style="left: 90%; top: 55%; animation-delay: -3.5s;"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="bi bi-vote-fill me-2"></i>
                PilihanKita
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>">
                            <i class="bi bi-house me-1"></i>
                            Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('candidates') ?>">
                            <i class="bi bi-people me-1"></i>
                            Kandidat
                        </a>
                    </li>
                    <?php if (session()->get('user_type') === 'student' && session()->get('user_id')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('voting') ?>">
                                <i class="bi bi-hand-index me-1"></i>
                                Voting
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                <?= session()->get('student_name') ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= base_url('profile') ?>">
                                    <i class="bi bi-person me-2"></i>My Profile
                                </a></li>
                                <li><hr class="dropdown-divider border-secondary"></li>
                                <li><a class="dropdown-item" href="<?= base_url('auth/logout') ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('auth/google') ?>">
                                <i class="bi bi-google me-1"></i>
                                Login
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link text-muted" href="<?= base_url('admin-system') ?>" target="_blank">
                            <i class="bi bi-gear me-1"></i>
                            Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h5 class="text-gradient mb-3">PilihanKita</h5>
                    <p class="text-secondary">Sistem Voting Ketua OSIS yang modern, aman, dan transparan untuk sekolah-sekolah di Indonesia. Memberikan pengalaman demokrasi yang interaktif dan futuristik.</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="text-white mb-3">Menu</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?= base_url() ?>" class="text-secondary text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="<?= base_url('candidates') ?>" class="text-secondary text-decoration-none">Kandidat</a></li>
                        <li class="mb-2"><a href="<?= base_url('auth/google') ?>" class="text-secondary text-decoration-none">Login</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="text-white mb-3">Kontak</h6>
                    <p class="text-secondary">
                        <i class="bi bi-envelope me-2"></i>
                        support@pilihankita.local
                    </p>
                    <div class="mt-3">
                        <a href="#" class="text-secondary me-3 fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-secondary me-3 fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-secondary me-3 fs-5"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="text-secondary mb-0">
                        &copy; <?= date('Y') ?> PilihanKita. Dibuat dengan <span class="text-danger">❤️</span> untuk pendidikan Indonesia.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Google API -->
    <script src="https://apis.google.com/js/api.js"></script>
    
    <!-- Additional JavaScript -->
    <?= $this->renderSection('scripts') ?>
    
    <!-- Global Scripts -->
    <script>
        
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('animate-fade-in');
                    }, index * 100);
                }
            });
        }, observerOptions);
        
        
        document.querySelectorAll('.card, .candidate-card').forEach(el => {
            observer.observe(el);
        });
        
        
        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar');
        
        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                navbar.style.transform = 'translateY(-100%)';
            } else {
                navbar.style.transform = 'translateY(0)';
            }
            
            
            if (scrollTop > 50) {
                navbar.style.background = 'rgba(26, 29, 35, 0.98)';
                navbar.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.4)';
            } else {
                navbar.style.background = 'rgba(26, 29, 35, 0.95)';
                navbar.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.3)';
            }
            
            lastScrollTop = scrollTop;
        });
        
        
        const revealElements = document.querySelectorAll('.card, .candidate-card, .btn');
        revealElements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                el.style.transition = 'all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 150);
        });
        
        
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroSection = document.querySelector('.hero-section');
            
            if (heroSection) {
                const rate = scrolled * -0.5;
                heroSection.style.transform = `translateY(${rate}px)`;
            }
        });
        
        
        function createParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 6 + 's';
            
            const colors = ['#8B5A96', '#2DD4BF', '#FF6B6B', '#4ECDC4'];
            particle.style.background = colors[Math.floor(Math.random() * colors.length)];
            
            document.querySelector('.particles').appendChild(particle);
            
            setTimeout(() => {
                particle.remove();
            }, 6000);
        }
        
        
        setInterval(createParticle, 2000);
        
        
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('mouseenter', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
        
        
        document.querySelectorAll('.card, .candidate-card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(10px)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0px)';
            });
        });
        
        
        function animateProgressBars() {
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const width = bar.getAttribute('aria-valuenow');
                bar.style.width = '0%';
                
                setTimeout(() => {
                    bar.style.transition = 'width 2s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                    bar.style.width = width + '%';
                }, 500);
            });
        }
        
        
        const progressObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateProgressBars();
                    progressObserver.unobserve(entry.target);
                }
            });
        });
        
        document.querySelectorAll('.progress').forEach(progress => {
            progressObserver.observe(progress);
        });
        
        
        function showToast(message, type = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: 'rgba(26, 29, 35, 0.95)',
                color: '#E2E8F0',
                iconColor: type === 'success' ? '#00F5A0' : type === 'error' ? '#FF4757' : '#FFD93D',
                customClass: {
                    popup: 'colored-toast'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            
            Toast.fire({
                icon: type,
                title: message
            });
        }
        
        
        function showLoading(element) {
            const spinner = document.createElement('div');
            spinner.className = 'loading-spinner mx-auto';
            element.innerHTML = '';
            element.appendChild(spinner);
        }
        
        
        function typeWriter(element, text, speed = 50) {
            let i = 0;
            element.innerHTML = '';
            
            function type() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(type, speed);
                }
            }
            
            type();
        }
        
        
        const heroTitle = document.querySelector('.hero-section h1');
        if (heroTitle) {
            const originalText = heroTitle.textContent;
            setTimeout(() => {
                typeWriter(heroTitle, originalText, 100);
            }, 1000);
        }
        
        
        function confirmVote(candidateName, candidateId) {
            Swal.fire({
                title: 'Konfirmasi Pilihan',
                html: `
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-person-check-fill text-success" style="font-size: 4rem;"></i>
                        </div>
                        <p class="mb-0">Apakah Anda yakin ingin memilih</p>
                        <h5 class="text-gradient mt-2">${candidateName}</h5>
                        <p class="text-muted">Pilihan Anda tidak dapat diubah setelah dikonfirmasi</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Ya, Pilih Sekarang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#8B5A96',
                cancelButtonColor: '#6c757d',
                background: 'rgba(26, 29, 35, 0.95)',
                color: '#E2E8F0',
                customClass: {
                    popup: 'border-gradient'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    showToast(`Terima kasih! Pilihan Anda untuk ${candidateName} telah berhasil disimpan.`, 'success');
                }
            });
        }
        
        
        <?php if (session()->getFlashdata('success')): ?>
            showToast('<?= session()->getFlashdata('success') ?>', 'success');
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            showToast('<?= session()->getFlashdata('error') ?>', 'error');
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('warning')): ?>
            showToast('<?= session()->getFlashdata('warning') ?>', 'warning');
        <?php endif; ?>
        
        
        const style = document.createElement('style');
        style.textContent = `
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            }
            
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            .colored-toast {
                backdrop-filter: blur(20px);
                border: 1px solid rgba(139, 90, 150, 0.3);
                border-radius: 1rem;
            }
        `;
        document.head.appendChild(style);
        
        
        document.addEventListener('DOMContentLoaded', function() {
            
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.5s ease';
            
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
            
            
            if (typeof bootstrap !== 'undefined') {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });
        
        
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>