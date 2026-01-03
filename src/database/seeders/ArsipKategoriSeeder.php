<?php

namespace Database\Seeders;

use App\Models\KategoriDokumen;
use App\Models\SubKategoriDokumen;
use Illuminate\Database\Seeder;

class ArsipKategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Arsip Akademik' => [
                'Ijazah',
                'Transkrip Nilai',
                'Rapor Peserta Didik',
                'Daftar Nilai',
            ],
            'Arsip Kesiswaan' => [
                'Data Peserta Didik',
                'Surat Mutasi',
                'Surat Dispensasi',
                'Data Alumni',
            ],
            'Arsip Kepegawaian' => [
                'SK Pengangkatan',
                'SK Penugasan',
                'Daftar Hadir Guru',
            ],
            'Arsip Sarana dan Prasarana' => [
                'Inventaris Barang',
                'Laporan Kerusakan',
                'Berita Acara Serah Terima',
            ],
            'Arsip Kerjasama dan PKL' => [
                'Surat Permohonan PKL',
                'Surat Balasan Industri',
                'Laporan PKL',
                'MoU Industri',
            ],
            'Arsip Administrasi Umum' => [
                'Surat Masuk',
                'Surat Keluar',
                'Notulen Rapat',
            ],
        ];

        foreach ($data as $kategoriNama => $subs) {
            $kategori = KategoriDokumen::firstOrCreate([
                'nama_kategori' => $kategoriNama,
            ]);

            foreach ($subs as $subNama) {
                SubKategoriDokumen::firstOrCreate([
                    'kategori_dokumen_id' => $kategori->id,
                    'nama_sub_kategori' => $subNama,
                ]);
            }
        }
    }
}
