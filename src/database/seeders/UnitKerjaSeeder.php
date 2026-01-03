<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitKerja;


class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['nama_unit' => 'Kurikulum', 'jenis_unit' => 'akademik'],
            ['nama_unit' => 'Kesiswaan', 'jenis_unit' => 'akademik'],
            ['nama_unit' => 'Tata Usaha', 'jenis_unit' => 'non-akademik'],
            ['nama_unit' => 'Keuangan', 'jenis_unit' => 'non-akademik'],
            ['nama_unit' => 'Sarana dan Prasarana', 'jenis_unit' => 'non-akademik'],
            ['nama_unit' => 'Hubungan Industri', 'jenis_unit' => 'non-akademik'],
            ['nama_unit' => 'Bimbingan Konseling', 'jenis_unit' => 'akademik'],
        ];

        foreach ($units as $u) {
            UnitKerja::firstOrCreate(['nama_unit' => $u['nama_unit']], $u);
        }
    }
}
