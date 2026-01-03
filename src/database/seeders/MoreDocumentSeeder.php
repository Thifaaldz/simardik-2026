<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\KategoriDokumen;
use App\Models\SubKategoriDokumen;
use App\Models\UnitKerja;
use App\Models\Student;
use App\Models\Pegawai;
use App\Models\Pkl;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MoreDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $unit = UnitKerja::first();
        $student = Student::first();
        $pegawai = Pegawai::first();
        $pkl = Pkl::first();

        $counter = 2; // DocumentSeeder already creates DOC-001

        $categories = KategoriDokumen::with('subKategori')->get();

        foreach ($categories as $cat) {
            if ($cat->subKategori->isEmpty()) {
                continue;
            }

            foreach ($cat->subKategori->take(2) as $sub) {
                Document::firstOrCreate([
                    'kode_dokumen' => sprintf('DOC-%03d', $counter),
                ], [
                    'nama_dokumen' => $cat->nama_kategori . ' - ' . $sub->nama_sub_kategori,
                    'kategori_dokumen_id' => $cat->id,
                    'sub_kategori_dokumen_id' => $sub->id,
                    'unit_kerja_id' => $unit?->id,
                    'student_id' => $student?->id,
                    'pegawai_id' => $pegawai?->id,
                    'pkl_id' => $pkl?->id,
                    'tahun' => now()->year,
                    'tanggal_dokumen' => now()->toDateString(),
                    'status_dokumen' => 'valid',
                    'tingkat_kerahasiaan' => 'biasa',
                    'file_path' => 'arsip-dokumen/sample-1.pdf',
                    'disk' => 'local',
                    'created_by' => $user?->id,
                ]);

                $counter++;
            }
        }
    }
}
