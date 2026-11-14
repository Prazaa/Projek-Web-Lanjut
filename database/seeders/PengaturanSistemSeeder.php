<?php

namespace Database\Seeders;

use App\Models\PengaturanSistem;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class PengaturanSistemSeeder extends Seeder
{
    public function run(): void
    {
        $pimpinan = Pengguna::where('role', 'pimpinan')->first();

        PengaturanSistem::updateOrCreate(
            ['id' => 1],
            [
                'nama_instansi' => 'Universitas Riau',
                'nama_fakultas' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'kode_unit' => 'FMIPA',
                'alamat_fakultas' => 'Kampus Bina Widya Km. 12,5 Simpang Baru, Pekanbaru 28293',
                'nama_pimpinan_default' => $pimpinan?->nama ?? 'Dr. Ahmad Fauzi, M.Si.',
                'nip_pimpinan_default' => $pimpinan?->nip ?? '197508172003121002',
                'jabatan_pimpinan_default' => $pimpinan?->jabatan ?? 'Wakil Dekan Bidang Kemahasiswaan, Kerja Sama dan Alumni',
            ]
        );
    }
}