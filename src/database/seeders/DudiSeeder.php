<?php

namespace Database\Seeders;

use App\Models\Dudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dus = [
            ['nama_dudi' => 'PT. Teknologi Nusantara', 'alamat' => 'Jl. Industri No. 1', 'bidang' => 'Teknologi'],
            ['nama_dudi' => 'CV. Mitra Industri', 'alamat' => 'Jl. Industri No. 2', 'bidang' => 'Manufaktur'],
        ];

        foreach ($dus as $d) {
            Dudi::firstOrCreate(['nama_dudi' => $d['nama_dudi']], $d);
        }
    }
}
