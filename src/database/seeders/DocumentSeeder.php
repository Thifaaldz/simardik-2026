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

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = KategoriDokumen::first();
        $sub = SubKategoriDokumen::where('kategori_dokumen_id', $kategori->id)->first();
        $unit = UnitKerja::first();
        $student = Student::first();
        $pegawai = Pegawai::first();
        $pkl = Pkl::first();
        $user = User::first();

        if (! $kategori || ! $sub || ! $unit || ! $user) {
            return;
        }

        Document::firstOrCreate([
            'kode_dokumen' => 'DOC-001',
        ], [
            'nama_dokumen' => 'Contoh Dokumen',
            'kategori_dokumen_id' => $kategori->id,
            'sub_kategori_dokumen_id' => $sub->id,
            'unit_kerja_id' => $unit->id,
            'student_id' => $student?->id,
            'pegawai_id' => $pegawai?->id,
            'pkl_id' => $pkl?->id,
            'tahun' => now()->year,
            'tanggal_dokumen' => now()->toDateString(),
            'status_dokumen' => 'valid',
            'tingkat_kerahasiaan' => 'biasa',
            'file_path' => 'arsip-dokumen/example.pdf',
            'disk' => 'local',
            'created_by' => $user->id,
        ]);
    }
}
