<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SampleFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the public disk has an example directory and files for development
        $files = [
            'arsip-dokumen/example.pdf' => "Placeholder PDF content - example.pdf",
            'arsip-dokumen/sample-1.pdf' => "Placeholder PDF content - sample 1",
            'arsip-dokumen/sample-2.pdf' => "Placeholder PDF content - sample 2",
        ];

        foreach ($files as $path => $contents) {
            if (! Storage::disk('local')->exists($path)) {
                Storage::disk('local')->put($path, $contents);
            }
        }
    }
}
