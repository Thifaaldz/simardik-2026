<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class DocumentDownloadController
{
    public function __invoke(Request $request, Document $document)
    {
        // Basic authorization: user must be authenticated and have 'arsip lihat' permission
        if (! $request->user() || ! $request->user()->can('arsip lihat')) {
            abort(403);
        }

        if (! $document->file_path) {
            abort(404);
        }

        $disk = $document->disk ?? 'local';
        if (! Storage::disk($disk)->exists($document->file_path)) {
            abort(404);
        }

        $stream = Storage::disk($disk)->readStream($document->file_path);
        if (! $stream) {
            abort(404);
        }

        return response()->streamDownload(function () use ($stream) {
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, $document->nama_dokumen . '.pdf', [
            'Content-Type' => $document->mime_type ?? 'application/pdf',
        ]);
    }
}
