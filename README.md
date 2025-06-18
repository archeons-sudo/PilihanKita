# 🗳️ PilihanKita - Sistem Pemilihan OSIS

![PilihanKita Banner](https://via.placeholder.com/800x200/667eea/ffffff?text=PilihanKita+Voting+System)

## 📖 Deskripsi

**PilihanKita** adalah sistem pemilihan OSIS (Organisasi Siswa Intra Sekolah) yang modern, aman, dan transparan. Dibangun menggunakan **CodeIgniter 4** dengan desain yang responsif dan user-friendly untuk memfasilitasi pemilihan ketua OSIS di sekolah menengah.

### ✨ Fitur Utama

#### 🎓 Untuk Siswa (Voters)
- **Login Google OAuth** - Autentikasi menggunakan akun Google sekolah
- **Verifikasi Data Siswa** - Input NIS dan kelas untuk validasi
- **Voting Sekali** - Satu siswa hanya dapat voting satu kali
- **Voting Anonim** - Rahasia pemilihan terjaga
- **Bukti Voting PDF** - Download bukti setelah voting
- **Hasil Real-time** - Lihat hasil pemilihan langsung

#### 👨‍💼 Untuk Admin
- **Dashboard Komprehensif** - Statistik dan overview sistem
- **Manajemen Periode** - CRUD periode pemilihan
- **Manajemen Kandidat** - CRUD kandidat dengan foto, visi, misi
- **Manajemen Siswa** - CRUD data siswa dan kelas
- **Export Data** - Download hasil dalam format Excel/PDF
- **Visualisasi Data** - Charts dan diagram hasil voting
- **Audit Trail** - Log aktivitas admin

#### 📊 Homepage Publik
- **Galeri Kandidat** - Tampilan kandidat dengan vote count
- **Statistik Voting** - Partisipasi dan progress voting
- **Chart Real-time** - Grafik hasil pemilihan
- **Info Periode** - Informasi waktu pemilihan

## 🚀 Quick Start

### Persyaratan Sistem
- PHP 8.1+ dengan extensions: mysqli, mbstring, xml, gd
- MySQL 8.0+ atau MariaDB 10.3+
- Composer (Package Manager PHP)
- Web Server (Apache/Nginx/Built-in PHP)

### Instalasi

```bash
# 1. Download/Clone project
git clone <repository-url> pilihankita
cd pilihankita

# 2. Install dependencies
composer install

# 3. Setup environment
cp env .env
# Edit .env sesuai konfigurasi database Anda

# 4. Buat database
mysql -u root -p -e "CREATE DATABASE pilihankita_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 5. Import database atau jalankan migrasi
mysql -u root -p pilihankita_db < database_setup.sql
# ATAU
php spark migrate

# 6. Setup uploads folder
mkdir -p public/uploads/candidates
chmod 755 public/uploads/candidates

# 7. Jalankan server
php spark serve
```

Buka browser ke: `http://localhost:8080`

## 🏗️ Arsitektur Sistem

### Tech Stack
- **Backend**: CodeIgniter 4 (PHP Framework)
- **Frontend**: Bootstrap 5, JavaScript ES6, Chart.js
- **Database**: MySQL/MariaDB
- **Authentication**: Google OAuth 2.0
- **PDF Generation**: DomPDF
- **Excel Export**: PhpSpreadsheet

### Struktur Database

```sql
-- Tabel utama
├── periods (periode pemilihan)
├── classes (kelas siswa)
├── students (data siswa)
├── candidates (kandidat)
├── votes (data voting)
└── admins (admin users)
```

### Struktur Aplikasi

```
PilihanKita/
├── app/
│   ├── Controllers/          # Logic aplikasi
│   │   ├── HomeController.php      # Homepage & kandidat
│   │   ├── AuthController.php      # Autentikasi siswa
│   │   ├── VotingController.php    # Proses voting
│   │   └── AdminController.php     # Panel admin
│   ├── Models/              # Database models
│   │   ├── StudentModel.php        # Model siswa
│   │   ├── CandidateModel.php      # Model kandidat
│   │   ├── VoteModel.php          # Model voting
│   │   └── ...
│   ├── Views/               # Template UI
│   │   ├── layout/main.php         # Layout utama
│   │   ├── home/                   # Homepage
│   │   ├── candidates/             # Halaman kandidat
│   │   ├── auth/                   # Autentikasi
│   │   ├── voting/                 # Voting interface
│   │   └── admin/                  # Panel admin
│   └── Database/Migrations/  # Database migrations
├── public/
│   ├── uploads/candidates/   # Foto kandidat
│   └── assets/              # CSS, JS, images
└── writable/                # Logs, cache, sessions
```

## 🔧 Konfigurasi

### Environment Variables (.env)

```env
# Database Configuration
database.default.hostname = localhost
database.default.database = pilihankita_db
database.default.username = your_username
database.default.password = your_password
database.default.DBDriver = MySQLi

# Google OAuth (Production)
google.oauth.client_id = your_google_client_id
google.oauth.client_secret = your_google_client_secret
google.oauth.redirect_uri = http://yourdomain.com/auth/google/callback

# App Settings
app.baseURL = 'http://localhost:8080/'
app.voting_enabled = true
app.max_votes_per_student = 1
```

### Data Default

#### Admin Login
- **URL**: `/admin-system`
- **Username**: `admin`
- **Password**: `admin123`

#### Sample Data untuk Testing
```
Siswa Sample:
- NIS: 2024001001 | Nama: Ahmad Rizki Pratama | Kelas: X-MIPA-1
- NIS: 2024001002 | Nama: Siti Nurhaliza | Kelas: X-MIPA-1

Kandidat Sample:
1. Ahmad & Siti (Paslon 1)
2. Budi & Dina (Paslon 2)
3. Eko & Fatimah (Paslon 3)
```

## 📱 Cara Penggunaan

### Untuk Siswa

1. **Akses Homepage** - Kunjungi website untuk melihat kandidat
2. **Login Google** - Klik "Login Siswa" dan masukkan akun Google
3. **Input Data** - Masukkan NIS dan pilih kelas
4. **Voting** - Pilih kandidat dan konfirmasi pilihan
5. **Download Bukti** - Unduh bukti voting dalam format PDF

### Untuk Admin

1. **Login Admin** - Akses `/admin-system` dengan username/password
2. **Setup Periode** - Buat periode pemilihan baru
3. **Tambah Kandidat** - Input data kandidat dengan foto, visi, misi
4. **Kelola Siswa** - Import atau tambah data siswa manual
5. **Monitor Voting** - Pantau proses voting real-time
6. **Export Hasil** - Download laporan dalam Excel/PDF

## � Keamanan

### Fitur Keamanan
- **CSRF Protection** - Protection against cross-site request forgery
- **XSS Prevention** - Input sanitization dan output escaping
- **SQL Injection Prevention** - Prepared statements
- **Session Management** - Secure session handling
- **Rate Limiting** - Prevent spam voting attempts
- **Audit Trail** - Log semua aktivitas admin

### Best Practices
- Password admin menggunakan bcrypt hashing
- Voting menggunakan hash untuk anonymity
- File upload validation untuk foto kandidat
- Input validation pada semua form

## 📊 API & Integration

### External APIs
- **Google OAuth 2.0** - Student authentication
- **Google Sheets API** - Export results to Google Sheets
- **Chart.js** - Data visualization
- **DomPDF** - PDF generation

### Internal API Endpoints
```
GET  /api/candidates/{id}     # Detail kandidat
GET  /api/voting-stats        # Statistik voting
GET  /api/results/live        # Hasil real-time
POST /voting/submit           # Submit vote
```

## � Status Implementasi

### ✅ Sudah Selesai
- [x] Database schema & migrations
- [x] Models dengan relationships
- [x] Homepage dengan kandidat display
- [x] Google OAuth simulation (mock)
- [x] Student data input form
- [x] Authentication system
- [x] Responsive UI dengan Bootstrap 5
- [x] Chart.js integration
- [x] Basic admin structure

### � Dalam Pengembangan
- [ ] VotingController completion
- [ ] AdminController full implementation
- [ ] PDF receipt generation
- [ ] Excel export functionality
- [ ] Real Google OAuth integration
- [ ] Admin dashboard views

### 📅 Roadmap
- [ ] Email notifications
- [ ] Real-time updates (WebSockets)
- [ ] Mobile app (PWA)
- [ ] Multi-language support
- [ ] Advanced analytics

## 🧪 Testing

### Unit Testing
```bash
# Run tests
./vendor/bin/phpunit

# Test specific feature
./vendor/bin/phpunit --filter VotingTest
```

### Manual Testing
1. **Student Flow** - Test complete voting process
2. **Admin Flow** - Test CRUD operations
3. **Security** - Test authentication & authorization
4. **Performance** - Test with multiple concurrent users

## 📈 Performance

### Optimization Features
- Database indexing pada tabel utama
- Query caching untuk kandidat dan hasil
- Image optimization untuk foto kandidat
- Gzip compression untuk assets
- CDN ready untuk static files

### Scalability
- Support untuk ribuan siswa
- Real-time hasil tanpa lag
- Efficient database queries
- Horizontal scaling ready

## �️ Development

### Development Mode
```bash
# Mode development dengan debug
CI_ENVIRONMENT = development

# Features:
# - Mock Google OAuth
# - Detailed error messages
# - Auto-reload
# - Sample data included
```

### Contributing
1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

### Coding Standards
- Follow CodeIgniter 4 conventions
- PSR-4 autoloading
- Proper commenting
- Security best practices

## 📞 Support & Documentation

### Dokumentasi
- **Quick Start**: `QUICK_START_GUIDE.md`
- **Implementation Status**: `IMPLEMENTATION_STATUS.md`
- **Database Schema**: `database_setup.sql`
- **API Docs**: `/docs/api/`

### Troubleshooting
```bash
# Check logs
tail -f writable/logs/log-$(date +%Y-%m-%d).log

# Clear cache
php spark cache:clear

# Check database connection
php spark db:table students
```

### Support Channels
- 📧 Email: support@pilihankita.com
- 📖 Wiki: Project Wiki on GitHub
- 🐛 Issues: GitHub Issues
- 💬 Discussion: GitHub Discussions

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

Contributions are welcome! Please read our [Contributing Guidelines](CONTRIBUTING.md) before submitting pull requests.

## 🙏 Acknowledgments

- **CodeIgniter Team** - Amazing PHP framework
- **Bootstrap Team** - Responsive CSS framework
- **Chart.js Team** - Beautiful charts
- **Indonesian Schools** - Inspiration for this project

---

### 🌟 Star this project if you find it useful!

**Made with ❤️ for Indonesian Schools**

![GitHub stars](https://img.shields.io/github/stars/username/pilihankita?style=social)
![GitHub forks](https://img.shields.io/github/forks/username/pilihankita?style=social)
![GitHub issues](https://img.shields.io/github/issues/username/pilihankita)
![GitHub license](https://img.shields.io/github/license/username/pilihankita)

---

## 📸 Screenshots

### Homepage
![Homepage Screenshot](docs/screenshots/homepage.png)

### Voting Interface
![Voting Screenshot](docs/screenshots/voting.png)

### Admin Dashboard
![Admin Dashboard Screenshot](docs/screenshots/admin-dashboard.png)

### Mobile Responsive
![Mobile Screenshot](docs/screenshots/mobile.png)
