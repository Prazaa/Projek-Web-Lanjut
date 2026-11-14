<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'pengguna_id',
        'nim',
        'program_studi',
        'semester',
        'tempat_lahir',
        'tanggal_lahir',
        'no_hp',
        'alamat',
    ];

    protected function casts(): array
    {
        return [
            'semester' => 'integer',
            'tanggal_lahir' => 'date',
        ];
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function pengajuan(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'mahasiswa_id');
    }
}