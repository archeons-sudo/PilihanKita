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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #6366f1 0%, #a5b4fc 100%);
            min-height: 100vh;
            color: #1f2937;
        }
        .admin-navbar {
            background: #232946;
            color: #fff;
            box-shadow: 0 2px 8px rgba(35,41,70,0.08);
        }
        .admin-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #a5b4fc !important;
        }
        .admin-navbar .nav-link, .admin-navbar .dropdown-item {
            color: #fff !important;
            font-weight: 500;
        }
        .admin-navbar .nav-link.active {
            color: #a5b4fc !important;
        }
        .admin-navbar .dropdown-menu {
            background: #232946;
        }
        .admin-footer {
            background: #232946;
            color: #a5b4fc;
            padding: 2rem 0 1rem 0;
            margin-top: 3rem;
        }
        .admin-footer .footer-title {
            font-weight: 700;
            color: #fff;
        }
        .admin-footer .footer-link {
            color: #a5b4fc;
            text-decoration: none;
        }
        .admin-footer .footer-link:hover {
            text-decoration: underline;
            color: #fff;
        }
        .main-content {
            min-height: calc(100vh - 180px);
            padding: 2rem 0;
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
                    &copy; <?= date('Y') ?> PilihanKita Admin. Dibuat dengan <span style="color:#ef4444">&#10084;</span> untuk pendidikan Indonesia.
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