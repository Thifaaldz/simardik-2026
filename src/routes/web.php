<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Models\Document;

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
    Route::get(
        '/admin/documents/{document}/download',
        \App\Http\Controllers\DocumentDownloadController::class
    )->name('documents.download');

    /**
     * ================= PREVIEW (BARU)
     */
    Route::get('/admin/documents/{document}/preview', function (Document $document) {

        abort_unless(auth()->check(), 403);

        return response()->file(
            storage_path('app/' . $document->file_path),
            [
                'Content-Type'        => $document->mime_type,
                'Content-Disposition'=> 'inline; filename="' . basename($document->file_path) . '"',
                'X-Frame-Options'     => 'SAMEORIGIN',
            ]
        );
    })->name('documents.preview');
});
