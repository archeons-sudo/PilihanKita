# PilihanKita - Quick Start Guide for Developers

## 🚀 Getting Started (5 Minutes)

### Prerequisites Check
```bash
# Check PHP version (need 8.1+)
php --version

# Check Composer
composer --version

# Check MySQL
mysql --version
```

### 1. Environment Setup
```bash
# Install dependencies
composer install

# Setup environment
cp env .env

# Edit database config in .env
nano .env
```

### 2. Database Setup
```bash
# Option A: Run migrations
php spark migrate

# Option B: Use SQL file
mysql -u root -p < database_setup.sql
```

### 3. Test Installation
```bash
# Start development server
php spark serve

# Visit: http://localhost:8080
```

## 🛠️ Development Workflow

### Working with Models (Already Complete ✅)
```php
// Example: Using the StudentModel
$studentModel = new \App\Models\StudentModel();

// Get student by NIS
$student = $studentModel->getStudentByNIS('2024001001');

// Check if student voted
$hasVoted = $student['has_voted'];

// Get voting statistics
$stats = $studentModel->getVotingStats();
```

### Next: Implement Controllers

#### 1. Start with HomeController
```php
// app/Controllers/HomeController.php
<?php
namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        $candidateModel = new \App\Models\CandidateModel();
        $periodModel = new \App\Models\PeriodModel();
        
        $activePeriod = $periodModel->getActivePeriod();
        $candidates = $candidateModel->getActiveCandidates();
        
        return view('home', [
            'period' => $activePeriod,
            'candidates' => $candidates
        ]);
    }
}
```

#### 2. Create Home View
```php
// app/Views/home.php
<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1>PilihanKita - Pemilihan OSIS</h1>
    
    <?php if($period): ?>
        <h2><?= $period['name'] ?></h2>
        
        <div class="row">
            <?php foreach($candidates as $candidate): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5><?= $candidate['name'] ?></h5>
                            <p>Votes: <strong><?= $candidate['vote_count'] ?></strong></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
```

#### 3. Update Routes
```php
// app/Config/Routes.php
$routes->get('/', 'HomeController::index');
$routes->get('/admin-system', 'AdminController::dashboard');
$routes->post('/admin-system/login', 'AuthController::adminLogin');
$routes->get('/auth/google', 'AuthController::googleLogin');
$routes->get('/auth/google/callback', 'AuthController::googleCallback');
$routes->get('/vote', 'VotingController::index');
$routes->post('/vote/cast', 'VotingController::castVote');
```

## 📁 Project Structure

```
PilihanKita/
├── app/
│   ├── Controllers/          # 🔄 Next: Implement these
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   └── VotingController.php
│   ├── Models/              # ✅ Complete
│   │   ├── AdminModel.php
│   │   ├── CandidateModel.php
│   │   ├── ClassModel.php
│   │   ├── PeriodModel.php
│   │   ├── StudentModel.php
│   │   └── VoteModel.php
│   ├── Views/               # 🔄 Next: Create these
│   │   ├── layouts/
│   │   ├── admin/
│   │   ├── voting/
│   │   └── home.php
│   └── Database/
│       └── Migrations/      # ✅ Complete
├── public/
│   ├── assets/             # 🔄 Next: Add CSS/JS
│   └── uploads/            # For candidate photos
├── database_setup.sql      # ✅ Complete
├── .env                    # ✅ Configured
└── README.md              # ✅ Complete
```

## 🎯 Implementation Priority

### Week 1: Core Functionality
1. **HomeController** - Public homepage
2. **Basic views** - Homepage template
3. **Routes setup** - Basic navigation
4. **AdminController** - Basic admin functions

### Week 2: Authentication
1. **Google OAuth** - Student login
2. **Admin auth** - Admin login/logout
3. **Session management** - User sessions
4. **Security** - CSRF, validation

### Week 3: Voting System
1. **VotingController** - Voting interface
2. **Vote processing** - Secure voting
3. **PDF receipts** - Vote confirmation
4. **Admin panel** - Management interface

### Week 4: Advanced Features
1. **Charts** - Results visualization
2. **Excel export** - Data export
3. **Testing** - Quality assurance
4. **Polish** - UI improvements

## 🔧 Useful Commands

```bash
# Generate new controller
php spark make:controller ControllerName

# Generate new model
php spark make:model ModelName

# Run migrations
php spark migrate

# Create migration
php spark make:migration CreateTableName

# Start development server
php spark serve

# Clear cache
php spark cache:clear

# Check routes
php spark routes
```

## 📝 Code Templates

### Controller Template
```php
<?php
namespace App\Controllers;

class YourController extends BaseController
{
    public function index()
    {
        // Your logic here
        return view('your_view');
    }
    
    public function create()
    {
        // Handle creation
    }
    
    public function store()
    {
        // Handle form submission
    }
}
```

### View Template
```php
<?= $this->extend('layouts/public') ?>

<?= $this->section('title') ?>Page Title<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <!-- Your content here -->
</div>
<?= $this->endSection() ?>
```

## 🔍 Testing Your Changes

### 1. Database Testing
```php
// Test in controller or spark shell
$studentModel = new \App\Models\StudentModel();
$students = $studentModel->findAll();
var_dump($students);
```

### 2. Route Testing
```bash
# Test if route exists
curl http://localhost:8080/your-route

# Check routes list
php spark routes
```

### 3. Model Testing
```php
// In controller
$candidateModel = new \App\Models\CandidateModel();
$candidates = $candidateModel->getActiveCandidates();

if (empty($candidates)) {
    echo "No active candidates found";
} else {
    foreach ($candidates as $candidate) {
        echo $candidate['name'] . " - " . $candidate['vote_count'] . " votes\n";
    }
}
```

## 🎨 Frontend Resources

### Bootstrap 5 CDN
```html
<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```

### Chart.js CDN
```html
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
```

### Google OAuth JavaScript
```html
<script src="https://apis.google.com/js/platform.js" async defer></script>
```

## 🔑 Environment Variables

Essential variables to configure:
```env
# Database
database.default.hostname = localhost
database.default.database = pilihankita_db
database.default.username = your_username
database.default.password = your_password

# Google OAuth
GOOGLE_CLIENT_ID = your_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET = your_client_secret

# Security
encryption.key = hex2bin:your_32_character_hex_key

# App
app.baseURL = http://localhost:8080/
app.appTimezone = Asia/Jakarta
```

## 💡 Pro Tips

1. **Use the Models**: All database operations are ready in the models
2. **Follow CodeIgniter Conventions**: Use proper naming and structure
3. **Test Incrementally**: Test each feature as you build it
4. **Use Git**: Commit changes frequently
5. **Check Logs**: Monitor `writable/logs/` for errors

## 🆘 Quick Fixes

### Database Connection Error
```bash
# Check MySQL service
systemctl status mysql

# Test connection
mysql -u root -p pilihankita_db
```

### Permission Issues
```bash
chmod -R 755 writable/
chmod -R 755 public/uploads/
```

### Composer Issues
```bash
composer clear-cache
composer install --no-cache
```

## 📞 Need Help?

1. Check `IMPLEMENTATION_STATUS.md` for current progress
2. Review model methods in `app/Models/`
3. Look at database schema in `database_setup.sql`
4. Refer to CodeIgniter 4 documentation

Ready to code? Start with implementing the `HomeController::index()` method! 🚀