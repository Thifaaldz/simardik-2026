# Simardik — Sistem Manajemen Arsip (Ringkasan Sistem)

Dokumen ini menjelaskan arsitektur dan perilaku utama sistem penyimpanan arsip pada aplikasi.
Penekanan utama: **konsistensi penyimpanan**, **pencegahan duplikasi fisik**, **akses aman** dan **retensi**.

---

## 🎯 Tujuan Sistem

- Menyimpan file arsip dengan cara yang konsisten dan dapat dibuktikan (file path disimpan di DB).
- Mencegah duplikasi file yang menyebabkan penggunaan storage berlebih.
- Menghindari akses publik langsung ke file; hanya disajikan melalui route yang aman.
- Mengatur retensi/masa kadaluarsa arsip sehingga file yang sudah tidak perlu dapat di-prune secara terjadwal.

---

## 🧩 Komponen Utama & Alur Kerja

1. Upload (Filament)
  - Form upload di `DocumentResource` menyimpan file ke disk private (`local`) dengan direktori `arsip-dokumen`.
  - Setelah file ditulis ke disk, Observer akan menghitung **SHA-256** file tersebut.

2. Deduplication (Observer)
  - Observer (`App\Observers\DocumentObserver`) menghitung hash, ukuran, dan MIME.
  - Jika ditemukan dokumen lain dengan hash yang sama, file yang baru di-upload akan dihapus dan record baru akan menggunakan **file_path** yang sudah ada.
  - Metadata yang disimpan di tabel `documents`: `file_path`, `disk`, `file_hash`, `mime_type`, `file_size`, `expires_at`.

3. Download (Secure)
  - File tidak diakses langsung dari storage; download melalui controller `DocumentDownloadController` pada route `GET /admin/documents/{document}/download`.
  - Akses hanya untuk user yang terautentikasi dengan permission `arsip lihat`.

4. Retensi & Pruning
  - Jika `expires_at` diisi, file akan dianggap kadaluarsa setelah tanggal tersebut.
  - Command artisan `php artisan documents:prune-expired` membersihkan file kadaluarsa dan metadata yang terkait.
  - Command ini dijadwalkan berjalan harian di `App\Console\Kernel`.

---

## 📁 Struktur Database (kolom penting pada `documents`)

- `file_path` (string): lokasi relatif file pada disk.
- `disk` (string): nama disk di `config/filesystems.php` (default: `local`).
- `file_hash` (string): SHA-256 dari isi file.
- `mime_type` (string|null): tipe MIME file.
- `file_size` (bigint|null): ukuran dalam bytes.
- `expires_at` (timestamp|null): tanggal kadaluarsa file (opsional).

Semua metadata ini membantu menjaga konsistensi dan memudahkan operasi deduplikasi serta retensi.

---

## ✅ Perintah Penting

- Jalankan migrasi dan seeder (development):

```
php artisan migrate:fresh --seed
```

- Buat symlink storage (opsional untuk disk `public`):

```
php artisan storage:link
```

- Jalankan prune manual:

```
php artisan documents:prune-expired
```

---

## 🔐 Keamanan & Konsistensi

- Simpan file hanya pada disk private (tidak dapat diakses publik).
- Gunakan route yang mengautentikasi dan memeriksa permission sebelum streaming file.
- Backup database sebelum menjalankan perubahan struktural pada tabel (mis. `migrate:fresh`).

---

## 💡 Rekomendasi & Peningkatan Selanjutnya

- Jika ingin meningkatkan skala dan mencegah race condition: pindah ke model `file_assets` terpisah yang menyimpan `ref_count` per file dan hubungan many-to-many dengan dokumen.
- Tambahkan logging/audit pada upload/hapus/reuse file untuk kepatuhan.
- Tambahkan test otomatis (unit/integration) untuk deduplication, download permission, dan prune.
- Untuk penyimpanan cloud (S3), adaptasi hashing agar berbasis stream, dan pertimbangkan lifecycle policies di bucket.

---

Jika Anda ingin, saya dapat: menjalankan migrasi & seed di environment ini, atau menerapkan model `file_assets` sebagai langkah selanjutnya.

