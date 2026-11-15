<?php

namespace App\Enums;

enum StatusPengajuan: string
{
    case Diajukan = 'diajukan';
    case Diproses = 'diproses';
    case Revisi = 'revisi';
    case SiapTtd = 'siap_ttd';
    case Selesai = 'selesai';
    case Ditolak = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::Diajukan => 'Diajukan',
            self::Diproses => 'Sedang Diproses',
            self::Revisi => 'Perlu Revisi',
            self::SiapTtd => 'Siap Ditandatangani',
            self::Selesai => 'Selesai',
            self::Ditolak => 'Ditolak',
        };
    }

    public function warnaBadge(): string
    {
        return match ($this) {
            self::Diajukan => 'badge-wait',
            self::Diproses => 'badge-wait',
            self::Revisi => 'badge-warn',
            self::SiapTtd => 'badge-info',
            self::Selesai => 'badge-success',
            self::Ditolak => 'badge-danger',
        };
    }
}