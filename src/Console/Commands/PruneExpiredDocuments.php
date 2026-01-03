<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class PruneExpiredDocuments extends Command
{
    protected $signature = 'documents:prune-expired';
    protected $description = 'Prune expired document files according to expires_at';

    public function handle(): int
    {
        $expired = Document::whereNotNull('expires_at')->where('expires_at', '<', now())->get();
        $this->info('Found ' . $expired->count() . ' expired documents');

        foreach ($expired as $doc) {
            $this->line('Processing document ' . $doc->id . ' - ' . $doc->nama_dokumen);
            if (! $doc->file_hash) {
                $doc->file_path = null;
                $doc->file_size = null;
                $doc->mime_type = null;
                $doc->file_hash = null;
                $doc->disk = null;
                $doc->save();
                continue;
            }

            $others = Document::where('file_hash', $doc->file_hash)->where('id', '!=', $doc->id)->exists();
            if (! $others && $doc->file_path) {
                try {
                    Storage::disk($doc->disk ?? 'local')->delete($doc->file_path);
                } catch (\Throwable $e) {
                }
            }

            $doc->file_path = null;
            $doc->file_size = null;
            $doc->mime_type = null;
            $doc->file_hash = null;
            $doc->disk = null;
            $doc->save();
        }

        $this->info('Prune completed');
        return 0;
    }
}
