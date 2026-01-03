<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\UnitKerja;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = UnitKerja::get()->keyBy('nama_unit');
        if ($units->isEmpty()) {
            return;
        }

        $pegawaiData = [];

        // Kepala sekolah and wakasek
        $pegawaiData[] = ['nip' => '19800101', 'nama' => 'Drs. H. Ahmad Hidayat', 'jabatan' => 'Kepala Sekolah', 'status_kepegawaian' => 'PNS', 'unit_kerja_id' => $units['Tata Usaha']->id ?? null];
        $pegawaiData[] = ['nip' => '19800102', 'nama' => 'Siti Rochmah, S.Pd', 'jabatan' => 'Wakil Kepala Kurikulum', 'status_kepegawaian' => 'PNS', 'unit_kerja_id' => $units['Kurikulum']->id ?? null];

        // Teachers per jurusan (2 each)
        $jurusans = \App\Models\Jurusan::all();
        $counter = 3000;
        foreach ($jurusans as $j) {
            for ($i = 1; $i <= 2; $i++) {
                $pegawaiData[] = [
                    'nip' => (string) ($counter++),
                    'nama' => $j->nama_jurusan . ' - Guru ' . $i,
                    'jabatan' => 'Guru Produktif',
                    'status_kepegawaian' => 'Honorer',
                    'unit_kerja_id' => $units['Kurikulum']->id ?? null,
                ];
            }
        }

        foreach ($pegawaiData as $p) {
            // ignore null unit ids - but include record anyway
            Pegawai::firstOrCreate(['nip' => $p['nip']], $p);
        }
    }
}
