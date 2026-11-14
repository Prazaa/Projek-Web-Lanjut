<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlurPengajuan extends Model
{
    use HasFactory;

    protected $table = 'alur_pengajuan';

    public $timestamps = false;

    protected $fillable = [
        'pengajuan_id',
        'oleh_pengguna_id',
        'status_dari',
        'status_ke',
        'keterangan',
        'dibuat_pada',
    ];

    protected function casts(): array
    {
        return [
            'dibuat_pada' => 'datetime',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function pelaku(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'oleh_pengguna_id');
    }
}