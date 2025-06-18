<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-google display-1 text-danger"></i>
                        <h3 class="fw-bold mt-3">Login dengan Google</h3>
                        <p class="text-muted">
                            Masuk menggunakan akun Google Anda untuk mulai voting
                        </p>
                    </div>

                    <!-- Google Login Button -->
                    <div class="d-grid mb-4">
                        <button id="googleLoginBtn" class="btn btn-light google-btn btn-lg py-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-google me-3 fs-4"></i>
                                <span class="fw-semibold">Lanjutkan dengan Google</span>
                            </div>
                        </button>
                    </div>

                    <!-- Demo Login for Testing -->
                    <div class="border-top pt-4">
                        <h6 class="text-center text-muted mb-3">Demo Login (Untuk Testing)</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <button class="btn btn-outline-primary btn-sm w-100" onclick="demoLogin('student1')">
                                    <i class="bi bi-person me-1"></i>
                                    Demo Siswa 1
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-primary btn-sm w-100" onclick="demoLogin('student2')">
                                    <i class="bi bi-person me-1"></i>
                                    Demo Siswa 2
                                </button>
                            </div>
                        </div>
                        <small class="text-muted d-block text-center mt-2">
                            * Untuk keperluan testing tanpa Google OAuth
                        </small>
                    </div>

                    <!-- Information -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="text-primary mb-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Informasi Penting
                        </h6>
                        <ul class="small text-muted mb-0">
                            <li>Gunakan email sekolah yang terdaftar</li>
                            <li>Setelah login, Anda perlu memasukkan NIS dan kelas</li>
                            <li>Setiap siswa hanya bisa voting sekali</li>
                            <li>Voting bersifat rahasia dan aman</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Google OAuth Configuration
    const GOOGLE_CLIENT_ID = '<?= getenv('GOOGLE_CLIENT_ID') ?: 'YOUR_GOOGLE_CLIENT_ID_HERE' ?>';
    const REDIRECT_URI = '<?= base_url('auth/google/callback') ?>';

    document.getElementById('googleLoginBtn').addEventListener('click', function() {
        // Check if Google API is loaded
        if (typeof gapi === 'undefined') {
            showToast('Google API tidak tersedia. Gunakan demo login untuk testing.', 'warning');
            return;
        }

        // In production, this would initiate actual Google OAuth flow
        // For now, we'll simulate the flow
        simulateGoogleLogin();
    });

    function simulateGoogleLogin() {
        // Simulate Google OAuth flow for demo purposes
        const mockGoogleUser = {
            code: 'demo_google_id_' + Date.now(),
            email: 'demo.student@school.edu',
            name: 'Demo Student',
            picture: 'https://via.placeholder.com/150'
        };

        // Redirect to callback with simulated data
        const params = new URLSearchParams(mockGoogleUser);
        window.location.href = `<?= base_url('auth/google/callback') ?>?${params.toString()}`;
    }

    function demoLogin(userType) {
        let demoUsers = {
            'student1': {
                code: 'demo_id_1',
                email: 'ahmad.rizki@student.example.com',
                name: 'Ahmad Rizki Pratama',
                picture: ''
            },
            'student2': {
                code: 'demo_id_2', 
                email: 'siti.nurhaliza@student.example.com',
                name: 'Siti Nurhaliza',
                picture: ''
            }
        };

        const user = demoUsers[userType];
        if (user) {
            const params = new URLSearchParams(user);
            window.location.href = `<?= base_url('auth/google/callback') ?>?${params.toString()}`;
        }
    }

    // Initialize Google API (commented out for demo)
    /*
    function initGoogleAuth() {
        gapi.load('auth2', function() {
            gapi.auth2.init({
                client_id: GOOGLE_CLIENT_ID,
                scope: 'profile email'
            }).then(function(authInstance) {
                document.getElementById('googleLoginBtn').addEventListener('click', function() {
                    authInstance.signIn().then(function(googleUser) {
                        const profile = googleUser.getBasicProfile();
                        const authResponse = googleUser.getAuthResponse();
                        
                        // Send to server
                        const userData = {
                            id: profile.getId(),
                            email: profile.getEmail(),
                            name: profile.getName(),
                            picture: profile.getImageUrl(),
                            access_token: authResponse.access_token
                        };
                        
                        // Redirect with user data
                        const params = new URLSearchParams(userData);
                        window.location.href = REDIRECT_URI + '?' + params.toString();
                    });
                });
            });
        });
    }
    */
</script>
<?= $this->endSection() ?>