<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
        {
            Sekolah::firstOrCreate([
                'npsn' => '20613947',
            ], [
                'nama_sekolah' => 'SMK Negeri 1 Kabupaten Tangerang',
                'alamat' => 'Jl. Raya Serang Km. 12, Kabupaten Tangerang',
                'akreditasi' => 'A',
            ]);
        }
}
