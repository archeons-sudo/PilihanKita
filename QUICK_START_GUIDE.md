# PilihanKita - Quick Start Guide

## Panduan Cepat Memulai Sistem Pemilihan OSIS

### � Persyaratan Sistem

- PHP 8.1 atau lebih tinggi
- MySQL 8.0 atau MariaDB 10.3+
- Composer (Package Manager PHP)
- Web Server (Apache/Nginx/Built-in PHP Server)

### 🚀 Instalasi Cepat

#### 1. Clone/Download Project
```bash
# Jika menggunakan Git
git clone <repository-url> pilihankita
cd pilihankita

# Atau download dan extract ke folder pilihankita
```

#### 2. Install Dependencies
```bash
composer install
```

#### 3. Konfigurasi Environment
```bash
# Copy file environment
cp env .env

# Edit konfigurasi database di .env
```

#### 4. Konfigurasi Database

**Opsi A: Menggunakan MySQL/MariaDB**
```bash
# Buat database
mysql -u root -p -e "CREATE DATABASE pilihankita_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Jalankan migrasi
php spark migrate

# Atau import manual
mysql -u root -p pilihankita_db < database_setup.sql
```

**Opsi B: Menggunakan SQLite (untuk development)**
```bash
# Pastikan extension SQLite3 PHP aktif
mkdir -p writable/database
```

#### 5. Setup File Uploads
```bash
mkdir -p public/uploads/candidates
chmod 755 public/uploads/candidates
```

#### 6. Jalankan Server Development
```bash
php spark serve
```

Buka browser ke: `http://localhost:8080`

### 🏗️ Struktur Aplikasi

#### Frontend (Siswa)
- **Homepage**: `/` - Menampilkan kandidat dan hasil voting
- **Login**: `/auth/google` - Login dengan Google (mock untuk development)
- **Voting**: `/voting` - Halaman untuk melakukan voting
- **Kandidat**: `/candidates` - Daftar lengkap kandidat

#### Backend (Admin)
- **Login Admin**: `/admin-system` - Login administrator
- **Dashboard**: `/admin-system/dashboard` - Dashboard utama admin
- **Manajemen Kandidat**: `/admin-system/candidates` - CRUD kandidat
- **Manajemen Siswa**: `/admin-system/students` - CRUD siswa
- **Laporan**: `/admin-system/reports` - Export hasil voting

### 👥 Data Default

#### Admin Default
- **Username**: `admin`
- **Password**: `admin123`
- **Email**: `admin@pilihankita.local`

#### Siswa Sample
```
NIS: 2024001001 | Nama: Ahmad Rizki Pratama | Kelas: X-MIPA-1
NIS: 2024001002 | Nama: Siti Nurhaliza     | Kelas: X-MIPA-1
NIS: 2024001003 | Nama: Budi Santoso      | Kelas: X-MIPA-2
```

#### Kandidat Sample
```
1. Ahmad & Siti (Paslon 1) - Inovatif, Kreatif, Berprestasi
2. Budi & Dina (Paslon 2) - Peduli Lingkungan, Berprestasi Akademik
3. Eko & Fatimah (Paslon 3) - Berkarakter, Religius, Berbudaya
```

### 🔧 Mode Development

Aplikasi berjalan dalam mode development dengan fitur:

1. **Mock Google Login**: Tidak perlu konfigurasi Google OAuth
2. **Sample Data**: Data contoh sudah tersedia
3. **Error Display**: Error ditampilkan lengkap untuk debugging
4. **Database Creation**: Otomatis membuat tabel dan sample data

### 📱 Fitur Utama

#### Untuk Siswa
- ✅ Login dengan Google OAuth (mock)
- ✅ Input NIS dan kelas setelah login
- ✅ Voting sekali untuk satu kandidat
- ✅ Lihat hasil voting real-time
- ✅ Download bukti voting (PDF)

#### Untuk Admin
- ✅ Login tradisional (username/password)
- ✅ CRUD Periode Pemilihan
- ✅ CRUD Kandidat (nama, foto, visi, misi)
- ✅ CRUD Siswa dan Kelas
- ✅ Export hasil voting (Excel/PDF)
- ✅ Dashboard dengan statistik

### �️ Troubleshooting

#### Database Connection Error
```bash
# Pastikan MySQL berjalan
sudo systemctl start mysql

# Atau gunakan SQLite untuk development
# Edit .env dan ubah DBDriver ke SQLite3
```

#### Permission Error
```bash
# Fix permission untuk folder writable
chmod -R 755 writable/
chmod -R 755 public/uploads/
```

#### Missing Extensions
```bash
# Install PHP extensions yang diperlukan
sudo apt-get install php-mysql php-mbstring php-xml php-zip php-gd
```

### � Dokumentasi Lanjutan

- **Database Schema**: Lihat file `database_setup.sql`
- **API Documentation**: Folder `docs/api/`
- **User Manual**: Folder `docs/user/`
- **Implementation Status**: File `IMPLEMENTATION_STATUS.md`

### 🔐 Keamanan

#### Production Setup
1. Ganti password admin default
2. Setup Google OAuth credentials
3. Enable HTTPS
4. Setup database user dengan privileges terbatas
5. Configure proper file permissions

#### Environment Variables
```bash
# Google OAuth (untuk production)
google.oauth.client_id=your_client_id
google.oauth.client_secret=your_client_secret

# Database (production)
database.default.hostname=your_db_host
database.default.username=pilihankita_user
database.default.password=secure_password
```

### 📞 Support

Jika mengalami masalah:
1. Periksa log di `writable/logs/`
2. Pastikan semua dependency terinstall
3. Cek konfigurasi database
4. Hubungi developer untuk support

---

**Selamat menggunakan PilihanKita! �️**