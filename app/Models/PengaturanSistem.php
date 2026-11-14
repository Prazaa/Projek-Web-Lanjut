<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanSistem extends Model
{
    protected $table = 'pengaturan_sistem';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_instansi',
        'nama_fakultas',
        'kode_unit',
        'alamat_fakultas',
        'nama_pimpinan_default',
        'nip_pimpinan_default',
        'jabatan_pimpinan_default',
    ];

    public static function ambil(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'nama_instansi' => 'Universitas Riau',
                'nama_fakultas' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'kode_unit' => 'FMIPA',
                'alamat_fakultas' => 'Kampus Bina Widya Km. 12,5 Simpang Baru, Pekanbaru 28293',
                'jabatan_pimpinan_default' => 'Wakil Dekan Bidang Kemahasiswaan, Kerja Sama dan Alumni',
            ]
        );
    }
}