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
            self::Diajukan => 'bg-blue-100 text-blue-800 border-blue-200',
            self::Diproses => 'bg-amber-100 text-amber-800 border-amber-200',
            self::Revisi => 'bg-orange-100 text-orange-800 border-orange-200',
            self::SiapTtd => 'bg-purple-100 text-purple-800 border-purple-200',
            self::Selesai => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            self::Ditolak => 'bg-rose-100 text-rose-800 border-rose-200',
        };
    }
}