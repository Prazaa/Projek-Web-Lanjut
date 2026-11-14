<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAudit extends Model
{
    use HasFactory;

    protected $table = 'log_audit';

    public $timestamps = false;

    protected $fillable = [
        'pengguna_id',
        'aksi',
        'entitas',
        'entitas_id',
        'detail',
        'ip_address',
        'dibuat_pada',
    ];

    protected function casts(): array
    {
        return [
            'detail' => 'array',
            'dibuat_pada' => 'datetime',
        ];
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}