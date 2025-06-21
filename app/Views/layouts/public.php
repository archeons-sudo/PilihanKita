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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --secondary-color: #06b6d4;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #1f2937;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-sm);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }
        
        .main-content {
            min-height: calc(100vh - 140px);
            padding: 2rem 0;
        }
        
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: var(--gradient-primary);
        }
        
        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 0.75rem;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .hero-section {
            background: var(--gradient-primary);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
            border-radius: 0 0 2rem 2rem;
        }
        
        .candidate-card {
            transition: all 0.3s ease;
            border-radius: 1rem;
            overflow: hidden;
        }
        
        .candidate-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
        }
        
        .candidate-image {
            height: 250px;
            object-fit: cover;
            width: 100%;
        }
        
        .vote-count-badge {
            background: var(--gradient-secondary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .progress {
            height: 12px;
            border-radius: 6px;
            background: rgba(0, 0, 0, 0.1);
        }
        
        .progress-bar {
            background: var(--gradient-primary);
            border-radius: 6px;
        }
        
        .footer {
            background: rgba(31, 41, 55, 0.95);
            backdrop-filter: blur(20px);
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .google-btn {
            background: white;
            border: 2px solid #e5e7eb;
            color: #374151;
            transition: all 0.3s ease;
        }
        
        .google-btn:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #111827;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .text-gradient {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        @media (max-width: 768px) {
            .hero-section {
                padding: 2rem 0;
            }
            
            .candidate-card {
                margin-bottom: 1.5rem;
            }
            
            .main-content {
                padding: 1rem 0;
            }
        }
    </style>
    
    <!-- Additional CSS -->
    <?= $this->renderSection('css') ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="bi bi-vote-fill me-2"></i>
                PilihanKita
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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
                                <li><hr class="dropdown-divider"></li>
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
                <div class="col-md-6">
                    <h5 class="text-gradient">PilihanKita</h5>
                    <p class="text-light">Sistem Voting Ketua OSIS yang modern, aman, dan transparan untuk sekolah-sekolah di Indonesia.</p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white">Menu</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url() ?>" class="text-light text-decoration-none">Beranda</a></li>
                        <li><a href="<?= base_url('candidates') ?>" class="text-light text-decoration-none">Kandidat</a></li>
                        <li><a href="<?= base_url('auth/google') ?>" class="text-light text-decoration-none">Login</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white">Kontak</h6>
                    <p class="text-light">
                        <i class="bi bi-envelope me-2"></i>
                        support@pilihankita.local
                    </p>
                    <div class="mt-3">
                        <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-light">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="text-light mb-0">
                        &copy; <?= date('Y') ?> PilihanKita. Dibuat dengan ❤️ untuk pendidikan Indonesia.
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
        // Add animation classes when elements are in viewport
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.card, .candidate-card').forEach(el => {
            observer.observe(el);
        });
        
        // Toast notification function
        function showToast(message, type = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            
            Toast.fire({
                icon: type,
                title: message
            });
        }
        
        // Show flash messages
        <?php if (session()->getFlashdata('success')): ?>
            showToast('<?= session()->getFlashdata('success') ?>', 'success');
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            showToast('<?= session()->getFlashdata('error') ?>', 'error');
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('warning')): ?>
            showToast('<?= session()->getFlashdata('warning') ?>', 'warning');
        <?php endif; ?>
    </script>
</body>
</html>