<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Super Admin', 'password' => Hash::make('password')]
        );
        $user->assignRole('super_admin');

        $user = User::firstOrCreate(
            ['email' => 'user@admin.com'],
            ['name' => 'User Account', 'password' => Hash::make('password')]
        );
        $user->assignRole('user');

        // Additional convenience accounts for testing
        $operator = User::firstOrCreate(
            ['email' => 'operator@simardik.local'],
            ['name' => 'Operator', 'password' => Hash::make('password')]
        );
        $operator->assignRole('operator');

        $tu = User::firstOrCreate(
            ['email' => 'tu@simardik.local'],
            ['name' => 'Tata Usaha', 'password' => Hash::make('password')]
        );
        $tu->assignRole('tata_usaha');

        $kepala = User::firstOrCreate(
            ['email' => 'kepala@simardik.local'],
            ['name' => 'Kepala Sekolah', 'password' => Hash::make('password')]
        );
        $kepala->assignRole('kepala_sekolah');
    }
}
