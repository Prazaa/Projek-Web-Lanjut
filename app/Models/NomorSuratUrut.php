<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomorSuratUrut extends Model
{
    use HasFactory;

    protected $table = 'nomor_surat_urut';

    protected $fillable = [
        'tahun',
        'nomor_terakhir',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'nomor_terakhir' => 'integer',
        ];
    }
}