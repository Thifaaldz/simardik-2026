<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusans = Jurusan::all();
        if ($jurusans->isEmpty()) {
            return;
        }

        // Create kelas per tingkat (X/XI/XII) - two parallel classes per tingkatan
        foreach ($jurusans as $jurusan) {
            $short = implode('', array_map(function ($w) { return strtoupper(substr($w,0,1)); }, explode(' ', $jurusan->nama_jurusan)));
            // fallback short codes for known jurusan
            $map = [
                'Teknik Elektronika Industri' => 'TEI',
                'Teknik Instalasi Tenaga Listrik' => 'TITL',
                'Teknik Pendingin dan Tata Udara' => 'TPTU',
                'Teknik Komputer dan Jaringan' => 'TKJ',
                'Desain Komunikasi Visual' => 'DKV',
                'Teknik Sepeda Motor' => 'TSM',
                'Rekayasa Perangkat Lunak' => 'RPL',
            ];

            $code = $map[$jurusan->nama_jurusan] ?? $short;

            foreach (['X','XI','XII'] as $tingkat) {
                for ($i = 1; $i <= 2; $i++) {
                    $name = "$tingkat $code $i";
                    Kelas::firstOrCreate(['nama_kelas' => $name], ['nama_kelas' => $name, 'tingkat' => $tingkat, 'jurusan_id' => $jurusan->id]);
                }
            }
        }
    }
}
