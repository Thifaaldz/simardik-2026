<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

/* NOTE: Do Not Remove
| Livewire asset handling if using sub folder in domain
*/
Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/* END */

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
     * =====================================================
     * DOWNLOAD
     * =====================================================
     */
    Route::get(
        '/admin/documents/{document}/download',
        \App\Http\Controllers\DocumentDownloadController::class
    )->name('documents.download');

    /**
     * =====================================================
     * PREVIEW (PDF via iframe)
     * =====================================================
     */
    Route::get('/admin/documents/{document}/preview', function (Document $document) {

        // Validasi metadata
        if (! $document->disk || ! $document->file_path) {
            abort(404, 'File tidak valid');
        }

        $disk = Storage::disk($document->disk);

        // Pastikan file benar-benar ada
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
