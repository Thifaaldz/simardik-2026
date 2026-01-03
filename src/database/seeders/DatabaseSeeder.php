<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SekolahSeeder::class,
            UnitKerjaSeeder::class,
            JurusanSeeder::class,
            TahunAjaranSeeder::class,
            KelasSeeder::class,
            DudiSeeder::class,
            PegawaiSeeder::class,
            StudentSeeder::class,
            KategoriDokumenSeeder::class,
            SubKategoriDokumenSeeder::class,
            ArsipKategoriSeeder::class,
            PklSeeder::class,
            SampleFilesSeeder::class,
            DocumentSeeder::class,
            MoreDocumentSeeder::class,
            DocumentMetadataSeeder::class,
        ]);
    }
}
