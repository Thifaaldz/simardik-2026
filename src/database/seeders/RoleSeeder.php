<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'arsip lihat',
            'arsip unggah',
            'arsip verifikasi',
            'arsip hapus',
            'manajemen pengguna',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        Role::firstOrCreate(['name' => 'super_admin'])->givePermissionTo($permissions);
        Role::firstOrCreate(['name' => 'admin'])->givePermissionTo($permissions);
        Role::firstOrCreate(['name' => 'kepala_sekolah'])->givePermissionTo(['arsip lihat', 'arsip verifikasi']);
        Role::firstOrCreate(['name' => 'tata_usaha'])->givePermissionTo(['arsip lihat', 'arsip unggah']);
        Role::firstOrCreate(['name' => 'operator'])->givePermissionTo(['arsip unggah']);
        Role::firstOrCreate(['name' => 'user']);
    }
}
