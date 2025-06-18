<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - PilihanKita</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--gradient-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 2rem;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }
        
        .login-header {
            background: var(--gradient-primary);
            color: white;
            text-align: center;
            padding: 2rem 1.5rem;
        }
        
        .login-logo {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .login-body {
            padding: 2rem 1.5rem;
        }
        
        .form-floating .form-control {
            border-radius: 0.75rem;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .form-floating .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .alert {
            border: none;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .back-link a:hover {
            color: var(--primary-dark);
        }
        
        .admin-info {
            background: #f8f9fa;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-top: 1.5rem;
            border: 1px solid #e9ecef;
        }
        
        .admin-info h6 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .admin-info .small {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h2 class="login-title">Panel Admin</h2>
                <p class="login-subtitle">Sistem Voting PilihanKita</p>
            </div>
            
            <div class="login-body">
                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>
                
                <!-- Login Form -->
                <form action="<?= base_url('admin-system/auth') ?>" method="POST" id="loginForm">
                    <?= csrf_field() ?>
                    
                    <div class="form-floating mb-3">
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               placeholder="Username atau Email"
                               value="<?= old('username') ?>"
                               required
                               autofocus>
                        <label for="username">
                            <i class="bi bi-person me-2"></i>
                            Username atau Email
                        </label>
                    </div>
                    
                    <div class="form-floating mb-4">
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               placeholder="Password"
                               required>
                        <label for="password">
                            <i class="bi bi-lock me-2"></i>
                            Password
                        </label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" id="loginBtn">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Masuk ke Panel Admin
                        </button>
                    </div>
                </form>
                
                <!-- Demo Credentials -->
                <div class="admin-info">
                    <h6>
                        <i class="bi bi-info-circle me-1"></i>
                        Akun Demo
                    </h6>
                    <div class="small">
                        <strong>Super Admin:</strong><br>
                        Username: <code>admin</code><br>
                        Password: <code>admin123</code>
                    </div>
                    <hr class="my-2">
                    <div class="small">
                        <strong>Operator:</strong><br>
                        Username: <code>operator</code><br>
                        Password: <code>operator123</code>
                    </div>
                </div>
                
                <!-- Back Link -->
                <div class="back-link">
                    <a href="<?= base_url() ?>">
                        <i class="bi bi-arrow-left me-2"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const loginBtn = document.getElementById('loginBtn');
            const originalText = loginBtn.innerHTML;
            
            // Show loading state
            loginBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';
            loginBtn.disabled = true;
            
            // Reset after 10 seconds in case of error
            setTimeout(() => {
                loginBtn.innerHTML = originalText;
                loginBtn.disabled = false;
            }, 10000);
        });
        
        // Quick fill demo credentials
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'd') {
                e.preventDefault();
                document.getElementById('username').value = 'admin';
                document.getElementById('password').value = 'admin123';
            }
        });
        
        // Focus management
        document.addEventListener('DOMContentLoaded', function() {
            const usernameField = document.getElementById('username');
            if (usernameField && !usernameField.value) {
                usernameField.focus();
            }
        });
    </script>
</body>
</html>