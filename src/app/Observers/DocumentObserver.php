<?php

namespace App\Observers;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentObserver
{
    public function creating(Document $document): void
    {
        $this->handleFileAttributes($document);
    }

    public function updating(Document $document): void
    {
        $this->handleFileAttributes($document);
    }

    public function deleted(Document $document): void
    {
        // if no other documents reference the same file_hash, remove the physical file
        if (! $document->file_hash) {
            return;
        }

        $other = Document::where('file_hash', $document->file_hash)->where('id', '!=', $document->id)->exists();
        if (! $other && $document->file_path) {
            try {
                Storage::disk($document->disk ?? 'local')->delete($document->file_path);
            } catch (\Throwable $e) {
                // silent
            }
        }
    }

    protected function handleFileAttributes(Document $document): void
    {
        if (! $document->file_path) {
            return;
        }

        $disk = $document->disk ?? 'local';
        // compute hash, size, mime
        try {
            $path = Storage::disk($disk)->path($document->file_path);
            if (file_exists($path)) {
                $hash = hash_file('sha256', $path);
                $size = filesize($path);
                $mime = mime_content_type($path) ?: null;

                // Validate MIME type is a valid PDF type
                $validPdfMimeTypes = ['application/pdf', 'application/x-pdf'];
                if ($mime && ! in_array($mime, $validPdfMimeTypes)) {
                    // Invalid file type - do not process
                    return;
                }

                // deduplicate: if another document already has the same hash, reuse its path and delete the current file
                $existing = Document::where('file_hash', $hash)->where('file_path', '!=', $document->file_path)->first();
                if ($existing) {
                    // delete uploaded duplicate
                    try {
                        Storage::disk($disk)->delete($document->file_path);
                    } catch (\Throwable $e) {
                    }

                    // reuse existing path and attributes
                    $document->file_path = $existing->file_path;
                    $document->file_hash = $existing->file_hash;
                    $document->file_size = $existing->file_size;
                    $document->mime_type = $existing->mime_type;
                    $document->disk = $existing->disk;
                    return;
                }

                $document->file_hash = $hash;
                $document->file_size = $size;
                $document->mime_type = $mime;
                $document->disk = $disk;
            }
        } catch (\Throwable $e) {
            // ignore hashing failures
        }
    }
}
