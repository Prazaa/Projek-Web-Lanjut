<?php

namespace App\Models;

use App\Enums\StatusPengajuan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';

    protected $fillable = [
        'kode_pengajuan',
        'mahasiswa_id',
        'jenis_surat_id',
        'keperluan',
        'data_tambahan',
        'status',
        'nama_snapshot',
        'nim_snapshot',
        'prodi_snapshot',
        'semester_snapshot',
        'catatan_revisi',
        'alasan_ditolak',
        'diajukan_pada',
        'selesai_pada',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusPengajuan::class,
            'data_tambahan' => 'array',
            'semester_snapshot' => 'integer',
            'diajukan_pada' => 'datetime',
            'selesai_pada' => 'datetime',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function lampiran(): HasMany
    {
        return $this->hasMany(Lampiran::class, 'pengajuan_id');
    }

    public function isiSurat(): HasOne
    {
        return $this->hasOne(IsiSurat::class, 'pengajuan_id');
    }

    public function alurPengajuan(): HasMany
    {
        return $this->hasMany(AlurPengajuan::class, 'pengajuan_id')->orderByDesc('dibuat_pada');
    }

    public function tandaTangan(): HasOne
    {
        return $this->hasOne(TandaTangan::class, 'pengajuan_id');
    }

    public function nimSamar(): string
    {
        $nim = (string) $this->nim_snapshot;
        if (strlen($nim) <= 4) {
            return str_repeat('*', strlen($nim));
        }

        return substr($nim, 0, 2) . str_repeat('*', strlen($nim) - 4) . substr($nim, -2);
    }
}