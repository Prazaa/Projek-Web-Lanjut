<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
    use HasFactory;

    protected $table = 'jenis_surat';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'kode_klasifikasi',
        'estimasi_hari',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'estimasi_hari' => 'integer',
            'aktif' => 'boolean',
        ];
    }

    public function syaratSurat(): HasMany
    {
        return $this->hasMany(SyaratSurat::class, 'jenis_surat_id');
    }

    public function pengajuan(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'jenis_surat_id');
    }
}