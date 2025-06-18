# 🚀 PilihanKita Quick Start Guide

## 📋 Status Implementasi

### ✅ Yang Sudah Selesai (100%)

#### 1. Database & Model Layer
- ✅ 6 Migration files lengkap (periods, classes, students, candidates, votes, admins)
- ✅ 6 Model lengkap dengan validasi dan relationships
- ✅ Seeds untuk data awal (admin, periods, classes, students, candidates)

#### 2. Controller Layer  
- ✅ **HomeController** - Homepage dengan live voting results
- ✅ **AuthController** - Google OAuth + Admin authentication
- ✅ **VotingController** - Proses voting dengan PDF receipt
- ✅ **AdminController** - Dashboard dan manajemen (basic)

#### 3. View Layer
- ✅ **Public Layout** - Modern responsive design dengan Bootstrap 5
- ✅ **Homepage** - Real-time voting results dengan Chart.js
- ✅ **Google Login** - UI dengan demo login untuk testing
- ✅ **Verification** - Form input NIS dan kelas siswa
- ✅ **Voting Interface** - Interactive candidate selection
- ✅ **Confirmation** - Success page dengan PDF download
- ✅ **Admin Login** - Clean admin panel login

#### 4. Features Implemented
- ✅ **Google OAuth flow** (dengan demo untuk testing)
- ✅ **PDF Receipt generation** (DomPDF)
- ✅ **Excel Export** (PhpSpreadsheet)  
- ✅ **Real-time voting charts** (Chart.js)
- ✅ **Responsive design** (Bootstrap 5)
- ✅ **Security features** (CSRF, password hashing, vote hashing)

#### 5. Technical Infrastructure
- ✅ **Complete routing** system untuk semua fitur
- ✅ **API endpoints** untuk AJAX calls
- ✅ **File upload** handling untuk foto kandidat
- ✅ **Session management** untuk login
- ✅ **Error handling** dan flash messages

## 🛠️ Setup dan Instalasi

### Prerequisites
```bash
- PHP 8.1+
- MySQL 8.0+ atau SQLite3
- Composer
- Web server (Apache/Nginx)
```

### Langkah Setup

#### 1. Install Dependencies
```bash
composer install
```

#### 2. Environment Configuration
```bash
# Copy environment file
cp env .env

# Edit konfigurasi database di .env
database.default.hostname = localhost
database.default.database = pilihankita_db
database.default.username = root
database.default.password = your_password
database.default.DBDriver = MySQLi
```

#### 3. Database Setup
```bash
# Buat database
mysql -u root -p -e "CREATE DATABASE pilihankita_db"

# Jalankan migrasi
php spark migrate

# Jalankan seeder
php spark db:seed DatabaseSeeder
```

#### 4. Set Permissions
```bash
chmod -R 755 writable/
chmod -R 755 public/uploads/
```

#### 5. Start Server
```bash
php spark serve --port=8080
```

## 🎯 Akses Aplikasi

### 🏠 Public Access
- **Homepage**: `http://localhost:8080/`
- **Login Google**: `http://localhost:8080/auth/google` 
- **Lihat Kandidat**: `http://localhost:8080/candidates`

### 👨‍💼 Admin Access
- **Admin Login**: `http://localhost:8080/admin-system/`
- **Dashboard**: `http://localhost:8080/admin-system/dashboard`

### 🔑 Default Login Credentials

#### Admin
- **Username**: `admin`
- **Password**: `admin123`
- **Role**: Super Admin

#### Operator  
- **Username**: `operator`
- **Password**: `operator123`
- **Role**: Admin

#### Demo Student (untuk testing)
- **Login via Google demo**: Gunakan tombol "Demo Siswa 1" atau "Demo Siswa 2"
- **NIS**: `2024001001` (Ahmad Rizki)
- **Kelas**: Pilih dari dropdown yang tersedia

## 🎮 Cara Menggunakan

### Untuk Admin:

1. **Login Admin**
   - Akses `/admin-system/`
   - Login dengan credentials di atas
   
2. **Kelola Kandidat**
   - Dashboard → Kandidat → Tambah/Edit/Hapus
   - Upload foto kandidat
   - Set visi dan misi
   
3. **Kelola Siswa**
   - Dashboard → Siswa → Lihat/Tambah/Edit
   - Import data siswa bulk
   
4. **Lihat Hasil**
   - Dashboard → Hasil Voting
   - Export ke Excel
   - Lihat grafik real-time

### Untuk Siswa:

1. **Login**
   - Klik "Login dengan Google" di homepage
   - Untuk demo: gunakan tombol "Demo Siswa"
   
2. **Verifikasi**
   - Masukkan NIS dan pilih kelas
   - Klik "Verifikasi & Lanjutkan"
   
3. **Voting**
   - Lihat kandidat dan visi/misi
   - Pilih satu kandidat
   - Konfirmasi pilihan
   
4. **Download Receipt**
   - Setelah voting berhasil
   - Download PDF bukti voting
   - Simpan sebagai bukti partisipasi

## � Fitur Unggulan

### 🔐 Security Features
- **Password Hashing**: BCrypt untuk admin
- **Vote Hashing**: SHA256 untuk verifikasi vote
- **CSRF Protection**: Built-in CodeIgniter
- **Anonymous Voting**: Vote tidak terhubung ke identitas siswa
- **Session Security**: Proper session management

### 📱 User Experience
- **Responsive Design**: Works on desktop, tablet, mobile
- **Real-time Updates**: Live vote count every 30 seconds
- **Modern UI**: Bootstrap 5 dengan custom styling
- **Interactive Charts**: Chart.js untuk visualisasi
- **Toast Notifications**: User-friendly feedback

### 📊 Analytics & Reports
- **Live Dashboard**: Real-time voting statistics
- **Excel Export**: Hasil voting ke spreadsheet
- **PDF Receipt**: Bukti voting untuk setiap siswa
- **Vote Analytics**: Grafik perolehan suara
- **Participation Rate**: Persentase partisipasi siswa

### 🔧 Technical Features
- **Google OAuth**: Easy login untuk siswa
- **File Upload**: Foto kandidat dengan validasi
- **Database Transactions**: Safe vote processing
- **API Endpoints**: AJAX untuk real-time updates
- **Error Handling**: Comprehensive error management

## 🎨 Customization

### Mengubah Tema
- Edit file `app/Views/layouts/public.php`
- Modify CSS variables di bagian `:root`
- Ganti warna primary: `--primary-color: #your-color`

### Menambah Fitur Admin
- Extend `AdminController` untuk fitur baru
- Tambah routes di `app/Config/Routes.php`
- Buat views di `app/Views/admin/`

### Google OAuth Real Implementation
- Dapatkan Client ID & Secret dari Google Console
- Update `.env` dengan credentials asli
- Uncomment kode Google API di `AuthController`

## 🔍 Testing

### Demo Mode
Aplikasi sudah include demo mode untuk testing tanpa Google OAuth:
- Gunakan tombol "Demo Siswa" di halaman login
- Data siswa demo sudah tersedia di seeder
- Admin bisa login langsung dengan credentials default

### Production Deployment
- Setup SSL certificate untuk Google OAuth
- Configure proper Google OAuth credentials
- Setup MySQL di production server
- Set environment ke 'production' di `.env`

## 📚 Dokumentasi API

### Public API Endpoints
```bash
GET /api/voting-results          # Live voting results
GET /api/candidate/{id}          # Candidate details
```

### Admin API Endpoints
```bash
GET /api/admin/dashboard-stats   # Dashboard statistics
GET /api/admin/voting-chart/{id} # Voting chart data
```

## 🐛 Troubleshooting

### Database Connection Error
- Check MySQL service: `sudo service mysql start`
- Verify database credentials di `.env`
- Create database: `CREATE DATABASE pilihankita_db`

### Migration Failed
- Check database permissions
- Verify PHP MySQL extension
- Run: `php spark migrate:refresh`

### Google OAuth Issues
- Use demo login untuk testing
- Verify SSL certificate untuk production
- Check Google Console settings

### File Upload Issues
- Check folder permissions: `chmod 755 public/uploads/`
- Verify PHP upload limits
- Check disk space

## 🎯 Next Steps untuk Production

1. **Setup Google OAuth**
   - Buat project di Google Cloud Console
   - Configure OAuth credentials
   - Update environment variables

2. **Database Optimization**
   - Add database indexes
   - Setup connection pooling
   - Configure backup strategy

3. **Security Hardening**
   - Enable HTTPS
   - Set secure headers
   - Rate limiting implementation

4. **Performance Optimization**
   - Enable caching
   - Optimize images
   - CDN setup

5. **Monitoring**
   - Setup error logging
   - Performance monitoring
   - User analytics

---

## 📞 Support

Untuk pertanyaan atau bantuan:
- **Email**: support@pilihankita.local
- **Documentation**: Lihat file README.md lengkap
- **Issues**: Check IMPLEMENTATION_STATUS.md

**Built with ❤️ for Indonesian Education**