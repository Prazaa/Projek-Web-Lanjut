<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
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
                'nama'    => 'Dr. Mayta Novaliza Isda, M.Si',
                'password_hash' => Hash::make('password123'),
                'role' => 'pimpinan',
                'nip'     => '197005231997032001',
                'jabatan' => 'Wakil Dekan Bidang Kemahasiswaan, Kerja Sama dan Alumni',
                'aktif' => true,
            ]
        );

        $budi = Pengguna::updateOrCreate(
            ['email' => 'budi.santoso@student.unri.ac.id'],
            [
                'nama' => 'Budi Santoso',
                'password_hash' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'aktif' => true,
            ]
        );

        Mahasiswa::updateOrCreate(
            ['pengguna_id' => $budi->id],
            [
                'nim' => '2301054012',
                'program_studi' => 'Matematika',
                'semester' => 7,
                'tempat_lahir' => 'Pekanbaru',
                'tanggal_lahir' => '2005-12-04',
                'alamat' => 'Jl. A. Yani No. 88, Pekanbaru, Riau',
            ]
        );
    }
}