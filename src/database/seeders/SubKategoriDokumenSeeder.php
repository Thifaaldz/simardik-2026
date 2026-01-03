<?php

namespace Database\Seeders;

use App\Models\SubKategoriDokumen;
use App\Models\KategoriDokumen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubKategoriDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
        {
            $data = [
                'Arsip Akademik' => [
                    ['nama_sub_kategori' => 'Ijazah'],
                    ['nama_sub_kategori' => 'Transkrip Nilai'],
                    ['nama_sub_kategori' => 'Rapor Peserta Didik'],
                    ['nama_sub_kategori' => 'Daftar Nilai'],
                ],
                'Arsip Kesiswaan' => [
                    ['nama_sub_kategori' => 'Data Peserta Didik'],
                    ['nama_sub_kategori' => 'Surat Mutasi'],
                    ['nama_sub_kategori' => 'Surat Dispensasi'],
                    ['nama_sub_kategori' => 'Data Alumni'],
                ],
                'Arsip Kepegawaian' => [
                    ['nama_sub_kategori' => 'SK Pengangkatan'],
                    ['nama_sub_kategori' => 'SK Penugasan'],
                    ['nama_sub_kategori' => 'Daftar Hadir Guru'],
                ],
                'Arsip Sarana dan Prasarana' => [
                    ['nama_sub_kategori' => 'Inventaris Barang'],
                    ['nama_sub_kategori' => 'Laporan Kerusakan'],
                    ['nama_sub_kategori' => 'Berita Acara Serah Terima'],
                ],
                'Arsip Kerjasama dan PKL' => [
                    ['nama_sub_kategori' => 'Surat Permohonan PKL', 'butuh_pkl' => true],
                    ['nama_sub_kategori' => 'Surat Balasan Industri', 'butuh_pkl' => true],
                    ['nama_sub_kategori' => 'Laporan PKL', 'butuh_pkl' => true],
                    ['nama_sub_kategori' => 'MoU Industri', 'butuh_pkl' => true],
                ],
                'Arsip Administrasi Umum' => [
                    ['nama_sub_kategori' => 'Surat Masuk'],
                    ['nama_sub_kategori' => 'Surat Keluar'],
                    ['nama_sub_kategori' => 'Notulen Rapat'],
                ],
            ];

            foreach ($data as $kategoriName => $subs) {
                $kategori = KategoriDokumen::where('nama_kategori', $kategoriName)->first();
                if (! $kategori) {
                    continue;
                }

                foreach ($subs as $s) {
                    SubKategoriDokumen::firstOrCreate([
                        'kategori_dokumen_id' => $kategori->id,
                        'nama_sub_kategori' => $s['nama_sub_kategori'],
                    ], [
                        'deskripsi' => $s['deskripsi'] ?? null,
                        'butuh_student' => $s['butuh_student'] ?? false,
                        'butuh_pkl' => $s['butuh_pkl'] ?? false,
                        'butuh_pegawai' => $s['butuh_pegawai'] ?? false,
                    ]);
                }
            }
        }
}
