# Sistem Manajemen Apotek

Aplikasi manajemen apotek berbasis web menggunakan Laravel 11 untuk mengelola obat, supplier, pelanggan, apoteker, pembelian, dan penjualan.

## Fitur

- 🔐 **Autentikasi Multi-Role** (Admin, Apoteker, Pelanggan)
- 💊 **Manajemen Obat** - CRUD obat dengan stok
- 👥 **Manajemen Pelanggan** - Data pelanggan dan riwayat
- 🏪 **Manajemen Supplier** - Data supplier dan transaksi
- 👨‍⚕️ **Manajemen Apoteker** - Data apoteker dan izin praktik
- 🛒 **Pembelian** - Transaksi pembelian dari supplier
- 💰 **Penjualan** - Transaksi penjualan ke pelanggan
- 📊 **Dashboard** - Statistik dan visualisasi data

## Teknologi

- **Backend:** Laravel 11 (PHP 8.2+)
- **Database:** MySQL
- **Frontend:** Bootstrap 5, jQuery

## Persyaratan Sistem

- PHP >= 8.2
- Composer
- MySQL >= 5.7
- Node.js & NPM (opsional, untuk asset compilation)
- Web Server (Apache/Nginx) atau PHP Development Server

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/GilangLiswiyanaPutra/Apotek-Lsp.git
cd Apotek-Lsp
```

### 2. Install Dependencies PHP

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
# Windows (PowerShell)
Copy-Item .env.example .env

# Linux/Mac
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apotek-lsp
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Buat Database

Buat database MySQL baru dengan nama `apotek-lsp`:

```sql
CREATE DATABASE `apotek-lsp` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Atau gunakan phpMyAdmin/MySQL Workbench untuk membuat database.

### 6. Migrasi Database & Seeder

Jalankan migration untuk membuat tabel dan seeder untuk data awal:

```bash
php artisan migrate --seed
```

Seeder akan membuat user default:
- **Admin:** email: `admin@gmail.com`, password: `admin12345`
- User lain dapat dilihat di `database/seeders/AdminApotekerSeeder.php`

### 7. Install Dependencies Frontend

Jika ingin compile asset frontend:

```bash
npm install
```

### 8. Jalankan Aplikasi

**Menggunakan PHP Development Server:**

```bash
php artisan serve
```

Aplikasi akan berjalan di: `http://localhost:8000`

**Compile Asset dengan Vite:**

Di terminal terpisah, jalankan:

```bash
npm run dev
```

### 9. Login

Akses `http://localhost:8000/login` dan gunakan kredensial:
- Email: `admin@gmail.com`
- Password: `admin12345`

## Struktur Folder Utama

```
apotek-lsp/
├── app/
│   ├── Http/Controllers/     # Controller aplikasi
│   │   └── Auth/            # Controller autentikasi
│   └── Models/              # Model Eloquent
├── database/
│   ├── migrations/          # File migrasi database
│   └── seeders/            # File seeder
├── public/                 # Asset publik (CSS, JS, images)
├── resources/
│   └── views/              # Blade templates
├── routes/
│   └── web.php            # Route definitions
└── .env                   # Konfigurasi environment (JANGAN di-commit!)
```

## Kontribusi

Kontribusi sangat diterima! Silakan fork repository ini dan buat pull request.
