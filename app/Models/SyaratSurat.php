<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyaratSurat extends Model
{
    use HasFactory;

    protected $table = 'syarat_surat';

    protected $fillable = [
        'jenis_surat_id',
        'nama_dokumen',
        'keterangan',
        'wajib',
        'format_diizinkan',
        'maks_ukuran_kb',
    ];

    protected function casts(): array
    {
        return [
            'wajib' => 'boolean',
            'maks_ukuran_kb' => 'integer',
        ];
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }
}