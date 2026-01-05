<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});

/*
/ END
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * =====================================================
 * AUTH ROUTES
 * =====================================================
 */
Route::middleware(['auth'])->group(function () {

    /**
     * ================= DOWNLOAD (SUDAH ADA)
     */
Route::get('/admin/documents/{document}/preview', function (Document $document) {

    abort_unless(auth()->check(), 403);

    // VALIDASI DATA MINIMAL
    if (! $document->disk || ! $document->file_path) {
        abort(404, 'Metadata file tidak lengkap');
    }

    // GUNAKAN DISK DARI DATABASE
    $disk = Storage::disk($document->disk);

    if (! $disk->exists($document->file_path)) {
        abort(404, 'File tidak ditemukan');
    }

    return response()->file(
        $disk->path($document->file_path),
        [
            'Content-Type'        => $document->mime_type ?? 'application/pdf',
            'Content-Disposition'=> 'inline',
            'X-Frame-Options'     => 'SAMEORIGIN',
        ]
    );
})->name('documents.preview');
});
