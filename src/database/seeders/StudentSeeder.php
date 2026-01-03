<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelasAll = Kelas::with('jurusan')->get();
        $tahun = TahunAjaran::where('status', 'aktif')->first();

        if ($kelasAll->isEmpty() || ! $tahun) {
            return;
        }

        // small pool of realistic Indonesian names
        $names = [
            'Andi Pratama','Siti Aminah','Budi Santoso','Sari Melati','Dewi Lestari','Rudi Hartono',
            'Tina Lestari','Rina Marlina','Eko Susanto','Fajar Nur','Nina Kurnia','Asep Saepudin',
        ];

        $nisBase = 210000;
        $nisnBase = 9900000000;

        foreach ($kelasAll as $kelas) {
            for ($i = 1; $i <= 12; $i++) { // create 12 students per class
                $idx = ($i - 1) % count($names);
                $name = $names[$idx] . ' ' . $kelas->nama_kelas . ' ' . $i;
                $nis = (string) ($nisBase++);
                $nisn = (string) ($nisnBase++);

                Student::firstOrCreate(['nis' => $nis], [
                    'nis' => $nis,
                    'nisn' => $nisn,
                    'nama' => $name,
                    'kelas_id' => $kelas->id,
                    'jurusan_id' => $kelas->jurusan_id,
                    'tahun_ajaran_id' => $tahun->id,
                    'status' => 'aktif',
                ]);
            }
        }
    }
}
