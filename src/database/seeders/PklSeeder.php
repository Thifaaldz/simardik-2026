<?php

namespace Database\Seeders;

use App\Models\Pkl;
use App\Models\Student;
use App\Models\Dudi;
use App\Models\Pegawai;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PklSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = Student::first();
        $dudi = Dudi::first();
        $pembimbing = Pegawai::first();

        if (! $student || ! $dudi || ! $pembimbing) {
            return;
        }

        Pkl::firstOrCreate([
            'student_id' => $student->id,
            'dudi_id' => $dudi->id,
            'pembimbing_id' => $pembimbing->id,
        ], [
            'periode_mulai' => now()->subMonths(3)->toDateString(),
            'periode_selesai' => now()->toDateString(),
        ]);
    }
}
