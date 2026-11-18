<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        Pengguna::updateOrCreate(
            ['email' => 'staf.akademik@unri.ac.id'],
            [
                'nama' => 'Siti Aminah, S.Kom.',
                'password_hash' => Hash::make('password123'),
                'role' => 'staf',
                'nip' => '198805122015042001',
                'jabatan' => 'Staf Administrasi Akademik & Kemahasiswaan',
                'aktif' => true,
            ]
        );

        Pengguna::updateOrCreate(
            ['email' => 'pimpinan.fmipa@unri.ac.id'],
            [
                'nama' => 'Dr. Ahmad Fauzi, M.Si.',
                'password_hash' => Hash::make('password123'),
                'role' => 'pimpinan',
                'nip' => '197508172003121002',
                'jabatan' => 'Wakil Dekan Bidang Kemahasiswaan, Kerja Sama dan Alumni',
                'aktif' => true,
            ]
        );
    }
}