<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lampiran extends Model
{
    use HasFactory;

    protected $table = 'lampiran';

    protected $fillable = [
        'pengajuan_id',
        'syarat_surat_id',
        'nama_asli',
        'path_berkas',
        'mime_type',
        'ukuran_byte',
        'terverifikasi',
    ];

    protected function casts(): array
    {
        return [
            'ukuran_byte' => 'integer',
            'terverifikasi' => 'boolean',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function syaratSurat(): BelongsTo
    {
        return $this->belongsTo(SyaratSurat::class, 'syarat_surat_id');
    }
}