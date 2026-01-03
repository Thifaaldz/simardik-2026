<?php

namespace Database\Seeders;

use App\Models\KategoriDokumen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
        {
            $names = [
                'Arsip Akademik',
                'Arsip Kesiswaan',
                'Arsip Kepegawaian',
                'Arsip Sarana dan Prasarana',
                'Arsip Kerjasama dan PKL',
                'Arsip Administrasi Umum',
            ];

            foreach ($names as $n) {
                KategoriDokumen::firstOrCreate(['nama_kategori' => $n]);
            }
        }
}
