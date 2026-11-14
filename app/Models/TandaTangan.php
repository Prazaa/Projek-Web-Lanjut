<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TandaTangan extends Model
{
    use HasFactory;

    protected $table = 'tanda_tangan';

    protected $fillable = [
        'pengajuan_id',
        'penanda_tangan_id',
        'nomor_surat',
        'kode_verifikasi',
        'nama_penanda_tangan',
        'nip_penanda_tangan',
        'jabatan_penanda_tangan',
        'ditandatangani_pada',
    ];

    protected function casts(): array
    {
        return [
            'ditandatangani_pada' => 'datetime',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function penandaTangan(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'penanda_tangan_id');
    }
}