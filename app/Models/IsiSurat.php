<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IsiSurat extends Model
{
    use HasFactory;

    protected $table = 'isi_surat';

    protected $fillable = [
        'pengajuan_id',
        'disusun_oleh',
        'perihal',
        'tujuan_surat',
        'paragraf_pembuka',
        'paragraf_isi',
        'paragraf_penutup',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function penyusun(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'disusun_oleh');
    }
}