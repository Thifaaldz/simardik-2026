<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = [
            ['tahun' => '2023/2024', 'status' => 'nonaktif'],
            ['tahun' => '2024/2025', 'status' => 'aktif'],
        ];

        foreach ($years as $y) {
            TahunAjaran::firstOrCreate(['tahun' => $y['tahun']], $y);
        }
    }
}

