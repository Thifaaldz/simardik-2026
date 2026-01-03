<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Keahlian / program sesuai SMKN 1 Kabupaten Tangerang
        $names = [
            'Teknik Elektronika Industri',
            'Teknik Instalasi Tenaga Listrik',
            'Teknik Pendingin dan Tata Udara',
            'Teknik Komputer dan Jaringan',
            'Desain Komunikasi Visual',
            'Teknik Sepeda Motor',
            // include commonly used jurusan in repo (legacy)
            'Rekayasa Perangkat Lunak',
        ];

        foreach ($names as $n) {
            Jurusan::firstOrCreate(['nama_jurusan' => $n]);
        }
    }
}
