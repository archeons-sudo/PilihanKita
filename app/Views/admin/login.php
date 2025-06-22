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
            --border-color: #334155;
            
            /* Updated Text Colors for Better Contrast */
            --text-bright: #FFFFFF;
            --text-body: #E2E8F0;
            --text-subtle: #CBD5E1;
            --text-accent: #2DD4BF;
            --text-warning: #FFD93D;
            --text-error: #FF6B6B;
            --text-success: #00F5A0;
            
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
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
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
        
        .login-container {
            width: 100%;
            max-width: 420px;
            margin: 2rem;
            position: relative;
            z-index: 1;
        }
        
        .login-card {
            background: rgba(26, 29, 35, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(139, 90, 150, 0.2);
            border-radius: 1.5rem;
            box-shadow: var(--shadow-glass);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: rgba(139, 90, 150, 0.4);
        }
        
        .login-header {
            background: var(--gradient-primary);
            color: white;
            text-align: center;
            padding: 2.5rem 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
            z-index: 1;
        }
        
        .login-logo {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
            animation: pulseGlow 2s ease-in-out infinite;
        }
        
        @keyframes pulseGlow {
            0%, 100% { text-shadow: 0 0 20px rgba(255, 255, 255, 0.3); }
            50% { text-shadow: 0 0 30px rgba(255, 255, 255, 0.5); }
        }
        
        .login-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 2;
            color: var(--text-bright);
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }
        
        .login-subtitle {
            font-size: 1rem;
            opacity: 1;
            position: relative;
            z-index: 2;
            color: var(--text-body);
        }
        
        .login-body {
            padding: 2rem 1.5rem;
            color: var(--text-body);
        }
        
        .form-floating .form-control {
            background: rgba(37, 42, 50, 0.95);
            border: 1px solid var(--border-color);
            color: var(--text-bright);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            height: calc(3.5rem + 2px);
            transition: all 0.3s ease;
        }
        
        .form-floating > label {
            padding: 1rem 1.25rem;
            color: var(--text-subtle);
        }
        
        .form-floating .form-control:focus {
            background: var(--dark-secondary);
            border-color: var(--secondary-color);
            box-shadow: var(--shadow-neon);
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            color: var(--text-bright);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, transparent 100%);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-neon);
        }
        
        .btn-primary:hover::before {
            transform: translateX(100%);
        }
        
        .alert {
            background: rgba(26, 29, 35, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
            color: var(--text-bright);
        }
        
        .alert-danger {
            border-color: var(--text-error);
            background: rgba(255, 107, 107, 0.1);
        }
        
        .alert-success {
            border-color: var(--text-success);
            background: rgba(0, 245, 160, 0.1);
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .back-link a {
            color: var(--text-accent);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            text-shadow: 0 0 10px rgba(45, 212, 191, 0.3);
        }
        
        .back-link a:hover {
            color: var(--accent-color);
            transform: translateX(-5px);
        }
        
        .admin-info {
            background: rgba(37, 42, 50, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(139, 90, 150, 0.2);
            border-radius: 1rem;
            padding: 1.25rem;
            margin-top: 1.5rem;
        }
        
        .admin-info h6 {
            color: var(--text-accent);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            text-shadow: 0 0 10px rgba(45, 212, 191, 0.3);
        }
        
        .admin-info .small {
            color: var(--text-body);
            line-height: 1.6;
        }
        
        .admin-info code {
            background: rgba(45, 212, 191, 0.15);
            color: var(--text-accent);
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 500;
            text-shadow: 0 0 10px rgba(45, 212, 191, 0.2);
        }
        
        .admin-info strong {
            color: var(--text-bright);
            font-weight: 600;
        }
        
        /* Floating particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
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
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.6; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }
    </style>
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
                        <i class="bi bi-info-circle"></i>
                        Akun Demo
                    </h6>
                    <div class="small">
                        <strong>Super Admin:</strong><br>
                        Username: <code>admin</code><br>
                        Password: <code>admin123</code>
                    </div>
                    <hr class="my-2 border-secondary">
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
            
            
            loginBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';
            loginBtn.disabled = true;
            
            
            setTimeout(() => {
                loginBtn.innerHTML = originalText;
                loginBtn.disabled = false;
            }, 10000);
        });
        
        
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'd') {
                e.preventDefault();
                document.getElementById('username').value = 'admin';
                document.getElementById('password').value = 'admin123';
            }
        });
        
        
        document.addEventListener('DOMContentLoaded', function() {
            const usernameField = document.getElementById('username');
            if (usernameField && !usernameField.value) {
                usernameField.focus();
            }
            
            
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
            
            
            for (let i = 0; i < 10; i++) {
                setTimeout(createParticle, i * 200);
            }
        });
    </script>
</body>
</html>