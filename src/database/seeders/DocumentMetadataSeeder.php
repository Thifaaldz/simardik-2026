<?php

namespace Database\Seeders;

use App\Models\DocumentMetadata;
use App\Models\Document;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentMetadataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $document = Document::first();
        if (! $document) {
            return;
        }

        DocumentMetadata::firstOrCreate([
            'document_id' => $document->id,
            'attribute_key' => 'lama_arsip',
        ], [
            'attribute_value' => '5 tahun',
        ]);
    }
}
