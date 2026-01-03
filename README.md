# Simardik — Archive Management (Filament)

This repository contains a Laravel application with a Filament admin to manage school documents (arsip). Recent changes added a robust file storage and retention system to prevent duplication, ensure data consistency, and protect private files.

---

## 🔧 Key Concepts & Design Decisions

- **File metadata is stored in the database** (on the `documents` table) — `file_path`, `disk`, `file_hash`, `mime_type`, `file_size`, `expires_at`. This keeps the storage and application data consistent.
- **Physical file deduplication (mandatory)**: files are hashed (SHA-256) on create/update. If an uploaded file matches an existing file hash, the uploaded duplicate is removed and the existing file path is reused. This prevents storage bloat.
- **Private storage only**: uploads are stored on the private `local` disk (configured to `storage/app/private` in `config/filesystems.php`). Files are not served directly by the web server.
- **Secure delivery**: downloads are served via an authenticated route (`/admin/documents/{document}/download`) that checks permissions (`arsip lihat`) and streams the file.
- **Retention & Pruning**: Documents may have an optional `expires_at` date. The command `php artisan documents:prune-expired` will remove or clear files for expired documents and is scheduled to run daily in `App\Console\Kernel`.

---

## ✅ Files & Changes (summary)

- Migrations
  - `src/database/migrations/2026_01_03_145205_create_documents_table.php` — **added** file metadata columns (`disk`, `file_hash`, `mime_type`, `file_size`, `expires_at`).

- Models / Observers
  - `src/app/Models/Document.php` — **fillable & casts** updated for file metadata.
  - `src/app/Observers/DocumentObserver.php` — **new**: computes file hash/mime/size on create/update, performs deduplication, and deletes orphaned physical files when a document is deleted.

- Controllers & Routes
  - `src/app/Http/Controllers/DocumentDownloadController.php` — **new** secure streaming download controller.
  - `src/routes/web.php` — **added** auth-protected route `documents.download` for secure downloads.

- Filament
  - `src/app/Filament/Admin/Resources/DocumentResource.php` — **updated** FileUpload to use `disk('local')` and **added** `expires_at` field and a **Download** action in the table.

- Commands / Scheduling
  - `src/Console/Commands/PruneExpiredDocuments.php` — **new**: prune expired documents and clear files.
  - `src/app/Console/Kernel.php` — register and schedule the prune command daily.

- Seeders
  - `src/database/seeders/SampleFilesSeeder.php` — sample placeholder files are placed on the `local` disk.
  - `src/database/seeders/DocumentSeeder.php` / `MoreDocumentSeeder.php` — ensure seeded documents use `disk = local`.

Notes: I considered a separate `file_assets` table for shared assets with a `ref_count`, but you asked for file metadata directly on `documents`, so I implemented it there. If you later want to move to a `file_assets` table, it is straightforward to migrate.

---

## ⚙️ Setup & Commands

1. Install deps and environment

   - composer install
   - copy `.env.example` to `.env` and configure DB

2. Storage & DB

   - (Optional) create storage symlink for public disk: `php artisan storage:link`
   - Run migrations and seeders:

     ```bash
     php artisan migrate:fresh --seed
     ```

3. Filament admin

   - Login using seeded users (see `UserSeeder`) like `admin@admin.com` / `password`.
   - Upload documents under the **Dokumen** resource — uploads go to `storage/app/private/arsip-dokumen` (private `local` disk).

4. Downloading a document (secure)

   - In Filament, click the **Download** action. The route `GET /admin/documents/{document}/download` streams the file only for authenticated users with `arsip lihat` permission.

5. Pruning expired documents

   - Run manually: `php artisan documents:prune-expired`.
   - This is scheduled to run daily via the application scheduler if your server runs `php artisan schedule:run`.

---

## 🔍 How deduplication works (developer notes)

1. On create/update, the observer locates the saved file on the configured disk and computes SHA-256.
2. If a different `Document` already has the same `file_hash`, the new uploaded file is deleted from the disk and the new `Document` reuses the existing `file_path` and metadata.
3. `deleted` observer behavior: when a `Document` is deleted, and no other document references the same `file_hash`, the physical file is removed.

Important: the deduplication strategy assumes uploads are saved first by Filament and are accessible via `Storage::disk($disk)->path($path)` during the observer. Filesystems such as S3 may require different handling (stream-based hashing) or a separate `file_assets` ref-count table.

---

## 🔐 Security & Consistency Notes

- Files are not publicly accessible; they are served only via the authorized download route.
- Database-level foreign key and column constraints should be observed when migrating live data — review your backups before running `migrate:fresh` in production.
- Consider auditing (activity log) of file operations for compliance in production.

---

## ✅ Next steps & optional improvements

- Add a `file_assets` table (with `ref_count`) for stronger referential integrity and to avoid race conditions in dedup workflow.
- Add unit/integration tests for deduplication, download permissions, and pruning.
- Add an admin UI to view file hashes and find duplicates manually.
- Add virus/malware scanning for uploaded PDFs if you expose the system to public uploads.

---

If you want, I can now:
- Run `php artisan migrate:fresh --seed` in this environment and validate the Filament flows (requires confirmation), or
- Implement `file_assets` and a migration to move to that model, or
- Add tests and logs for these flows.

Tell me which next step you want and I'll proceed. ✅
