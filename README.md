# PilihanKita 

## Kebutuhan Tools

## 1. `Composer` 

- Kunjungi [website resmi composer](https://getcomposer.org/download/), lalu download hingga selesai
- Setelah download, install `composer` sampai selesai.

## 2. `Git bash`

- Kunjungi [website resmi Git bash](https://git-scm.com/downloads), lalu download hingga selesai
- Setelah download, install `Git bash` sampai selesai.


## Cara menjalankan project `PilihanKita`

### 1. Clone repository `PilihanKita` pada folder `htdocs`

```bash
git clone https://github.com/archeons-sudo/PilihanKita.git
```

```bash
cd PilihanKita
```

### 2. Lakukan `composer install`

```bash
composer install
```

### 3. Buat file `.env`, lalu masukan isi dari file `.env` yang diberikan oleh `pembantu tugas`

```bash
nano .env
```

### 4. Buat database dengan nama `pilihankita_db`

```bash
CREATE DATABASE IF NOT EXISTS `pilihankita_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. `Migrate` dan `Seeder`

```bash
php spark migrate
```

```bash
php spark db:seed DatabaseSeeder
```

### 6. Buka `xampp`, lalu pada module `apache` klik button `config` dan buka file `php.ini`, dan dengan code dibawah ini.

```bash
extension=curl
;extension=ffi
;extension=ftp
extension=fileinfo
extension=gd
extension=gettext
;extension=gmp
extension=intl
;extension=imap
extension=mbstring
extension=exif      ; Must be after mbstring as it depends on it
extension=mysqli
;extension=oci8_12c  ; Use with Oracle Database 12c Instant Client
;extension=oci8_19  ; Use with Oracle Database 19 Instant Client
;extension=odbc
;extension=openssl
;extension=pdo_firebird
extension=pdo_mysql
;extension=pdo_oci
;extension=pdo_odbc
;extension=pdo_pgsql
extension=pdo_sqlite
;extension=pgsql
;extension=shmop
```

### 7. Jalankan project

```bash
php spark serve
```

## Teknologi yang dipakai

- **Backend:** CodeIgniter 4
- **Database:** MySQL
- **Frontend:** Bootstrap 5, Chart.js
- **Authentication:** Google OAuth 2.0
- **PDF Generation:** DomPDF
- **Spreadsheet Export:** PhpSpreadsheet
- **API Integration:** Google Sheets API


## Ini cara dapatin `Google API` (Info saja)

### 1. Buat Google Project
1. Kunjungi website [Google Cloud Console](https://console.cloud.google.com/)
2. Buat Project baru
3. Enable Google+ API and Google People API

### 2. Konfigurasi Consent Screen
1. Pergi "OAuth consent screen"
2. Isi informasi yang dibutuhkan

### 3. Buat OAuth Credentials
1. Pergi ke  "Credentials" → "Create Credentials" → "OAuth 2.0 Client ID"
2. Setting tipe aplikasi: "Web Application"
3. Add Authorized Redirect URIs:
   - `http://localhost:8080/auth/google/callback`


## Google Sheets API Setup

Buat fungsi eksport `google sheets`:

1. Enable Google Sheets API di Google Cloud Console
2. Buat service akun kredensial
3. Download JSON key file

## Default Admin Access

**Default Admin Credentials:**
- Username: `admin`
- Password: `admin123`


## Database Schema

- `periods` - Periode pemilihan
- `classes` - Kelas siswa
- `students` - Data siswa
- `candidates` - Data kandidat
- `votes` - Hasil voting
- `admins` - Admin users


## Note : 
- Kalian bisa ubah semua data pada `Database/Seeds` sebelum melakukan `php spark db:seed DatabaseSeeder`