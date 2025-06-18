# PilihanKita - Student Council Voting System

**PilihanKita** is a modern, secure, and user-friendly online voting system designed specifically for high school student council elections. Built with CodeIgniter 4, it provides a comprehensive solution for managing elections, candidates, students, and voting processes.

## 🌟 Features

### 🔐 Admin Panel Features
- **Complete Candidate Management (CRUD)**
  - Add/edit/delete candidates with photos, vision, and mission
  - Candidate photo upload and management
  - Real-time vote count tracking

- **Election Period Management**
  - Create and manage multiple election periods (e.g., 2024/2025)
  - Set election start and end dates
  - Activate/deactivate election periods

- **Student Data Management (CRUD)**
  - Import/manage student data (name, NIS, class)
  - Track voting status for each student
  - Class-based student organization

- **Results & Analytics**
  - Real-time voting results with charts (Chart.js)
  - Download results as Excel spreadsheets (Google Sheets API)
  - Voting statistics and participation rates
  - Hourly voting distribution charts

- **Class Management**
  - Manage student classes (Grade 10, 11, 12)
  - Organize by majors (MIPA, IPS, etc.)

- **Secure Admin Access**
  - Traditional username/password authentication
  - Role-based access (Super Admin, Admin)
  - Admin activity logging

### 👥 Student (Voter) Features
- **Google OAuth Login**
  - Secure authentication via Google accounts
  - No need to remember additional passwords

- **Student Verification**
  - Input NIS (Student ID) and class after Google login
  - Verification against school database

- **Secure Voting**
  - One vote per student per election period
  - Anonymous voting (secret ballot)
  - Vote confirmation and tracking

- **PDF Receipt Generation**
  - Downloadable PDF receipt as voting proof
  - Unique vote hash for verification
  - Timestamp and candidate information

### 🏠 Public Homepage
- **Live Results Display**
  - Real-time candidate vote counts
  - Election information and timeline
  - Responsive design for all devices

- **Easy Access**
  - Google login button for students
  - Clean, intuitive interface

## 🛠️ Technology Stack

- **Backend:** CodeIgniter 4 (PHP 8.1+)
- **Database:** MySQL 8.0+
- **Frontend:** Bootstrap 5, Chart.js
- **Authentication:** Google OAuth 2.0
- **PDF Generation:** DomPDF
- **Spreadsheet Export:** PhpSpreadsheet
- **API Integration:** Google Sheets API

## ⚙️ System Requirements

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Composer
- Web server (Apache/Nginx)
- SSL Certificate (required for Google OAuth)

### Required PHP Extensions
- `php-curl`
- `php-gd`
- `php-json`
- `php-mbstring`
- `php-mysql`
- `php-xml`
- `php-zip`

## 🚀 Installation Guide

### 1. Clone the Repository
```bash
git clone https://github.com/your-username/pilihankita-voting-system.git
cd pilihankita-voting-system
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Setup
```bash
# Copy environment file
cp env .env

# Edit the .env file with your settings
nano .env
```

### 4. Database Setup

#### Option A: Using CodeIgniter Migrations (Recommended)
```bash
# Run migrations
php spark migrate

# Run seeders (optional - adds sample data)
php spark db:seed AdminSeeder
```

#### Option B: Manual SQL Setup
```bash
# Import the SQL file
mysql -u root -p < database_setup.sql
```

### 5. Configure Environment Variables

Edit the `.env` file with your specific settings:

```env
# Database Configuration
database.default.hostname = localhost
database.default.database = pilihankita_db
database.default.username = your_db_username
database.default.password = your_db_password

# Google OAuth Configuration
GOOGLE_CLIENT_ID = your_google_client_id
GOOGLE_CLIENT_SECRET = your_google_client_secret

# Google Sheets API (for Excel export)
GOOGLE_SHEETS_API_KEY = your_sheets_api_key

# App Configuration
app.baseURL = 'https://yourdomain.com'
app.appTimezone = 'Asia/Jakarta'

# Security
encryption.key = your_32_character_encryption_key
```

### 6. Set Permissions
```bash
chmod -R 755 writable/
chmod -R 755 public/uploads/
```

### 7. Configure Web Server

#### Apache (.htaccess)
The project includes `.htaccess` files. Ensure mod_rewrite is enabled.

#### Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/pilihankita/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 🔧 Google OAuth Setup

### 1. Create Google Project
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing
3. Enable Google+ API and Google People API

### 2. Create OAuth Credentials
1. Go to "Credentials" → "Create Credentials" → "OAuth 2.0 Client ID"
2. Set Application Type: "Web Application"
3. Add Authorized Redirect URIs:
   - `https://yourdomain.com/auth/google/callback`
   - `http://localhost:8080/auth/google/callback` (for development)

### 3. Configure Consent Screen
1. Go to "OAuth consent screen"
2. Fill required information
3. Add your domain to authorized domains

## 📊 Google Sheets API Setup (Optional)

For Excel export functionality:

1. Enable Google Sheets API in Google Cloud Console
2. Create Service Account credentials
3. Download JSON key file
4. Add the API key to your `.env` file

## 🎯 Default Admin Access

**Default Admin Credentials:**
- Username: `admin`
- Password: `admin123`
- Email: `admin@pilihankita.local`

⚠️ **Important:** Change the default password immediately after setup!

## 🗄️ Database Schema

The system includes the following main tables:
- `periods` - Election periods
- `classes` - Student classes
- `students` - Student data
- `candidates` - Election candidates
- `votes` - Voting records
- `admins` - Admin users

## 🔐 Security Features

- **Password Hashing:** BCrypt encryption
- **CSRF Protection:** Built-in CodeIgniter protection
- **SQL Injection Prevention:** Query Builder with parameterized queries
- **Anonymous Voting:** Vote records are hashed and anonymized
- **Rate Limiting:** Protection against spam voting
- **Input Validation:** Comprehensive server-side validation

## 📱 Usage Guide

### For Administrators
1. Access admin panel: `https://yourdomain.com/admin-system`
2. Login with admin credentials
3. Manage election periods, candidates, and students
4. Monitor real-time voting results
5. Export results to Excel

### For Students
1. Visit homepage: `https://yourdomain.com`
2. Click "Login with Google"
3. Enter NIS and select class
4. Vote for preferred candidate
5. Download PDF receipt

## 🎨 Customization

### Templates and Themes
- Templates are located in `app/Views/`
- CSS files in `public/assets/css/`
- JavaScript files in `public/assets/js/`
- The system uses Bootstrap 5 for responsive design

### Adding Custom Features
- Controllers: `app/Controllers/`
- Models: `app/Models/`
- Libraries: `app/Libraries/`

## 🔄 Backup and Maintenance

### Database Backup
```bash
# Create backup
mysqldump -u username -p pilihankita_db > backup_$(date +%Y%m%d).sql

# Restore backup
mysql -u username -p pilihankita_db < backup_file.sql
```

### Log Files
- Application logs: `writable/logs/`
- Error logs: Check web server error logs

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `.env`
   - Ensure MySQL service is running
   - Verify database exists

2. **Google OAuth Error**
   - Check OAuth credentials
   - Verify redirect URIs
   - Ensure SSL certificate is valid

3. **File Upload Issues**
   - Check `writable/` permissions
   - Verify PHP upload limits
   - Check disk space

4. **Performance Issues**
   - Enable database query caching
   - Optimize images
   - Use CDN for static assets

## 📞 Support

For technical support or questions:
- Email: support@pilihankita.local
- Documentation: [Wiki Pages]
- Issues: [GitHub Issues]

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 🎉 Acknowledgments

- CodeIgniter 4 Framework
- Bootstrap 5 for UI components
- Chart.js for data visualization
- Google APIs for authentication and sheets
- DomPDF for PDF generation

---

**Built with ❤️ for Indonesian high schools**

*PilihanKita - Empowering student democracy through technology*
