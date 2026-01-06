# Simardik - Sistem Manajemen Arsip Digital

Aplikasi manajemen arsip digital untuk sekolah dengan fitur deduplikasi file, keamanan data, dan retensi dokumen berbasis Laravel 12 dan Filament 3.

![Laravel](https://img.shields.io/badge/Laravel-12.x-ff2d20?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-777bb4?style=flat-square&logo=php)
![Filament](https://img.shields.io/badge/Filament-3.3-747bff?style=flat-square)
![MariaDB](https://img.shields.io/badge/MariaDB-10.11-003545?style=flat-square&logo=mariadb)
![Docker](https://img.shields.io/badge/Docker-3.8-2496ed?style=flat-square&logo=docker)

---

## 📋 Daftar Isi

- [Tentang Proyek](#tentang-proyek)
- [Fitur Utama](#fitur-utama)
- [Arsitektur Sistem](#arsitektur-sistem)
- [Struktur Database](#struktur-database)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Penggunaan](#penggunaan)
- [API Routes](#api-routes)
- [Keamanan](#keamanan)
- [Perintah Artisan](#perintah-artisan)
- [Pengembangan](#pengembangan)
- [Lisensi](#lisensi)

---

## 🎯 Tentang Proyek

**Simardik** adalah sistem manajemen arsip digital yang dirancang khusus untuk kebutuhan institusi pendidikan. Sistem ini menyediakan platform terpusat untuk mengelola berbagai dokumen seperti:

- Dokumen siswa dan staf
- Arsip pembelajaran dan kurikulum
- Dokumentasi PKL (Praktik Kerja Lapangan)
- Data mitra industri (DUDI)
- Kategori dan sub-kategori dokumen yang fleksibel

### Masalah yang Diselesaikan

- **Duplikasi File**: Menghindari penyimpanan ganda file yang sama
- **Keamanan Data**: File tidak dapat diakses langsung dari server
- **Retensi Otomatis**: Dokument yang kadaluarsa dihapus secara terjadwal
- **Manajemen Terpusat**: Semua arsip dalam satu sistem terintegrasi

---

## ✨ Fitur Utama

### 1. Manajemen Dokumen
- Upload file dengan kategorisasi
- Preview dokumen sebelum download
- Pencarian dan filtering dokumen
- Riwayat perubahan (activity log)

### 2. Deduplikasi Cerdas
- Perhitungan hash SHA-256 untuk setiap file
- Otomatis mendeteksi file duplikat
- Menghemat penyimpanan dengan menggunakan satu file untuk multiple referensi

### 3. Sistem Keamanan
- Penyimpanan file di disk private (tidak dapat diakses publik)
- Autentikasi dan otorisasi berbasis peran
- Permission management dengan Filament Shield
- Download hanya melalui route yang aman

### 4. Retensi & Auto-Pruning
- Pengaturan tanggal kadaluarsa dokumen
- Command artisan untuk membersihkan dokumen kadaluarsa
- Penjadwalan otomatis pembersihan

### 5. Multi-Entity Management
- Sekolah dan Unit Kerja
- Jurusan dan Kelas
- Tahun Ajaran
- Siswa dan Pegawai
- DUDI (Mitra Industri)
- PKL (Praktik Kerja Lapangan)

---

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────┐
│                        CLIENT                               │
│                    (Web Browser)                           │
└─────────────────────────┬───────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│                      NGINX                                  │
│                  (Reverse Proxy)                            │
│                    Port: 80/443                             │
└─────────────────────────┬───────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│                    PHP-FPM (Laravel)                        │
│                   Filament Admin                            │
│                   Document Controller                       │
└─────────────────────────┬───────────────────────────────────┘
                          │
              ┌───────────┴───────────┐
              ▼                       ▼
┌─────────────────────┐   ┌─────────────────────────────┐
│      MARIADB        │   │      STORAGE (Private)      │
│   (Document DB)     │   │   /storage/app/arsip-dokumen│
│                     │   │   - File deduplication      │
│   Tables:           │   │   - Secure storage          │
│   - documents       │   │   - No public access        │
│   - students        │   └─────────────────────────────┘
│   - pegawais        │
│   - jurusans        │
│   - etc.            │
└─────────────────────┘
```

### Alur Kerja Dokumen

```
┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│  Upload  │───▶│  Hash    │───▶│  Check   │───▶│  Store   │
│  File    │    │  SHA-256 │    │  Exists? │    │  File    │
└──────────┘    └──────────┘    └──────────┘    └──────────┘
                                              │
                              ┌───────────────┼───────────────┐
                              ▼               ▼               ▼
                        ┌──────────┐   ┌──────────┐    ┌──────────┐
                        │  New     │   │  Reuse   │    │  Delete  │
                        │  File    │   │  Existing│    │  Upload  │
                        └──────────┘   └──────────┘    └──────────┘
```

---

## 📊 Struktur Database

### Tabel Utama

| Tabel | Deskripsi |
|-------|-----------|
| `users` | Data pengguna sistem dengan role dan permission |
| `sekolahs` | Informasi sekolah |
| `unit_kerjas` | Unit kerja dalam sekolah |
| `jurusans` | Jurusan/program studi |
| `kelas` | Kelas perjurusan |
| `tahun_ajarans` | Tahun ajaran aktif |
| `students` | Data siswa |
| `pegawais` | Data pegawai/guru |
| `dudis` | Data mitra industri |
| `pkls` | Data praktik kerja lapangan |
| `kategori_dokumens` | Kategori dokumen arsip |
| `sub_kategori_dokumens` | Sub-kategori dokumen |
| `documents` | Dokumen arsip utama |
| `document_metadata` | Metadata tambahan dokumen |
| `activity_logs` | Log aktivitas pengguna |

### Struktur Tabel Documents

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| `id` | bigint | Primary key |
| `file_path` | string | Lokasi relatif file pada disk |
| `disk` | string | Nama disk (default: local) |
| `file_hash` | string | SHA-256 hash file |
| `mime_type` | string | MIME type file |
| `file_size` | bigint | Ukuran file dalam bytes |
| `expires_at` | timestamp | Tanggal kadaluarsa (opsional) |
| `created_at` | timestamp | Waktu pembuatan |
| `updated_at` | timestamp | Waktu update |

---

## 🛠️ Teknologi yang Digunakan

### Backend
- **Laravel 12** - PHP Framework
- **PHP 8.2** - Programming Language
- **Filament 3.3** - Admin Panel Framework

### Database
- **MariaDB 10.11** - Relational Database

### Frontend
- **Tailwind CSS** - Utility-first CSS
- **Alpine.js** - Lightweight JavaScript

### DevOps & Tools
- **Docker** - Containerization
- **Nginx** - Web Server
- **Laravel Sail** - Docker development environment

### Filament Plugins
| Plugin | Fungsi |
|--------|--------|
| filament-shield | Manajemen permission berbasis peran |
| filament-logger | Logging aktivitas pengguna |
| filament-edit-profile | Halaman edit profil |
| filament-slim-scrollbar | UI scrollbar styler |
| filament-progressbar | Progress bar indicator |
| filament-themes | Tema UI kustom |

---

## 📦 Persyaratan Sistem

- Docker & Docker Compose
- PHP 8.2+ (jika running lokal)
- Composer 2+
- Node.js & npm (untuk asset compilation)
- 4GB+ RAM
- 10GB+ Storage

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd simardik
```

### 2. Setup Environment

```bash
# Copy environment file
cp src/.env.example src/.env

# Configure environment variables
# Edit src/.env according to your needs
```

### 3. Build & Start Containers

```bash
# Build dan jalankan container
docker-compose up -d --build

# Atau untuk development dengan hot-reload
docker-compose up
```

### 4. Install Dependencies

```bash
# Masuk ke container PHP
docker-compose exec php bash

# Install PHP dependencies
composer install

# Install NPM dependencies
npm install

# Compile assets
npm run build
```

### 5. Setup Database

```bash
# Jalankan migrasi dan seeder
php artisan migrate:fresh --seed

# Buat storage link
php artisan storage:link
```

### 6. Akses Aplikasi

- **URL**: http://localhost atau https://localhost
- **Admin Panel**: http://localhost/admin
- **Database**: localhost:13306 (root/p455w0rd)

---

## 📖 Penggunaan

### Login ke Admin Panel

1. Akses `http://localhost/admin`
2. Login dengan kredensial default (jika ada seeder)
3. Atau daftar user baru jika registration enabled

### Upload Dokumen

1. Navigasi ke menu **Documents**
2. Klik tombol **Create Document**
3. Isi form dengan:
   - Judul dokumen
   - Kategori dan sub-kategori
   - File yang akan diupload
   - Tanggal kadaluarsa (opsional)
4. Submit form

### Download Dokumen

1. Buka detail dokumen
2. Klik tombol **Download**
3. Sistem akan memverifikasi permission
4. File akan di-stream melalui controller aman

### Auto-Pruning

Dokumen dengan `expires_at` akan dihapus otomatis via scheduled command:

```bash
# Manual execution
php artisan documents:prune-expired

# Scheduled di kernel untuk daily run
```

---

## 🌐 API Routes

### Web Routes (`routes/web.php`)

| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | /admin/documents/{document}/download | DocumentController@download | Secure document download |

### Console Routes

| Command | Description |
|---------|-------------|
| `php artisan documents:prune-expired` | Hapus dokumen kadaluarsa |
| `php artisan migrate:fresh --seed` | Reset dan seed database |
| `php artisan storage:link` | Buat symbolic link storage |

---

## 🔐 Keamanan

### Implementasi Keamanan

1. **Private File Storage**
   - File disimpan di `storage/app/private`
   - Tidak dapat diakses langsung via URL
   - Hanya served melalui controller dengan autentikasi

2. **Permission-Based Access**
   - Setiap download memerlukan autentikasi
   - Permission `arsip lihat` diperlukan untuk download
   - Filament Shield untuk manajemen role

3. **Deduplication Security**
   - SHA-256 hash untuk integritas file
   - Mencegah file duplikat
   - Audit trail untuk setiap operasi

4. **Secure Headers**
   - Konfigurasi Nginx untuk security headers
   - SSL/TLS enabled (dalam docker-compose)

### Rekomendasi Keamanan

- Enable HTTPS di production
- Regular backup database
- Monitor activity logs
- Rotate API keys secara berkala

---

## 🖥️ Perintah Artisan

### Database Commands

```bash
# Migrasi
php artisan migrate

# Migrasi fresh dengan seed
php artisan migrate:fresh --seed

# Rollback migrasi
php artisan migrate:rollback

# Seed database
php artisan db:seed
```

### Document Commands

```bash
# Prune dokumen kadaluarsa
php artisan documents:prune-expired

# Optimize aplikasi
php artisan optimize

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Other Commands

```bash
# Create new user
php artisan make:filament-user

# Clear logs
php artisan log:clear

# List routes
php artisan route:list
```

---

## 🔧 Pengembangan

### Menambahkan Fitur Baru

1. **Buat Model baru:**
   ```bash
   php artisan make:model NamaModel -m
   ```

2. **Buat Resource Filament:**
   ```bash
   php artisan make:filament-resource NamaModelResource
   ```

3. **Buat Migration:**
   ```bash
   php artisan make:migration create_nama_models_table
   ```

4. **Buat Seeder:**
   ```bash
   php artisan make:seeder NamaModelSeeder
   ```

### Testing

```bash
# Jalankan semua test
docker-compose exec php php artisan test

# Jalankan dengan coverage
docker-compose exec php php artisan test --coverage
```

### Code Style

```bash
# Format code dengan Pint
docker-compose exec php php artisan pint

# IDE Helper
docker-compose exec php php artisan ide-helper:generate
```

---

## 📁 Struktur Proyek

```
simardik/
├── db/
│   ├── conf.d/          # MariaDB configuration
│   └── data/            # Database files
├── nginx/
│   ├── default.conf     # Nginx configuration
│   ├── Dockerfile
│   └── ssl/             # SSL certificates
├── php/
│   ├── Dockerfile
│   ├── docker-entrypoint.sh
│   └── local.ini        # PHP configuration
├── src/
│   ├── app/
│   │   ├── Console/     # Artisan commands
│   │   ├── Filament/    # Admin resources & pages
│   │   ├── Http/        # Controllers
│   │   ├── Models/      # Eloquent models
│   │   ├── Observers/   # Model observers
│   │   ├── Policies/    # Authorization policies
│   │   └── Providers/   # Service providers
│   ├── bootstrap/       # Laravel bootstrap
│   ├── config/          # Configuration files
│   ├── database/
│   │   ├── factories/   # Model factories
│   │   ├── migrations/  # Database migrations
│   │   └── seeders/     # Database seeders
│   ├── public/          # Public assets
│   ├── resources/       # Views & assets
│   ├── routes/          # Route definitions
│   ├── storage/         # Storage files
│   └── tests/           # Test cases
├── docker-compose.yml
└── README.md
```

---

## 📝 Catatan Pengembangan

### Deduplication Logic

Sistem menggunakan pendekatan:
1. File di-upload ke disk private
2. SHA-256 hash dihitung
3. Cek apakah file dengan hash sama sudah ada
4. Jika ada, hapus file baru dan reuse path yang ada
5. Jika tidak, simpan file baru

### Skalabilitas

Untuk production dengan skala besar, pertimbangkan:
- Pindah ke model `file_assets` terpisah dengan `ref_count`
- Cloud storage (S3, Google Cloud Storage)
- CDN untuk download
- Queue system untuk processing

---

## 📄 Lisensi

Proyek ini adalah perangkat lunak open-source yang dilisensikan di bawah [MIT License](LICENSE).

---

## 📞 Kontak

Untuk pertanyaan atau kontribusi, silakan hubungi tim pengembang atau buat issue di repository.

---

## 🙏 Kredit

- [Laravel](https://laravel.com) - The PHP Framework for Web Artisans
- [Filament](https://filamentphp.com) - The elegant TALL stack admin panel
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework

