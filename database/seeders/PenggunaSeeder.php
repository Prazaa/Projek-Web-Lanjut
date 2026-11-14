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
        $mhsUser = Pengguna::updateOrCreate(
            ['email' => 'irsal.mahasiswa@student.unri.ac.id'],
            [
                'nama' => 'Mhd. Irsal',
                'password_hash' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'aktif' => true,
            ]
        );

        Mahasiswa::updateOrCreate(
            ['pengguna_id' => $mhsUser->id],
            [
                'nim' => '2403114255',
                'program_studi' => 'S1 Sistem Informasi',
                'semester' => 5,
                'tempat_lahir' => 'Perigi Raja',
                'tanggal_lahir' => '2005-07-23',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Bina Widya, Simpang Baru, Pekanbaru',
            ]
        );

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