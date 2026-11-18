<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $pengguna = Pengguna::updateOrCreate(
            ['email' => 'mahasiswa@unri.ac.id'],
            [
                'nama' => 'Mahasiswa Uji',
                'password_hash' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'aktif' => true,
            ]
        );

        Mahasiswa::updateOrCreate(
            ['pengguna_id' => $pengguna->id],
            [
                'nim' => 'TEST23001',
                'program_studi' => 'Sistem Informasi',
                'semester' => 5,
                'tempat_lahir' => 'Pekanbaru',
                'tanggal_lahir' => '2005-01-01',
                'no_hp' => '081234567890',
                'alamat' => 'Pekanbaru, Riau',
            ]
        );
    }
}