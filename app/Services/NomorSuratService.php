<?php

namespace App\Services;

use App\Models\NomorSuratUrut;
use App\Models\Pengajuan;
use App\Models\PengaturanSistem;
use App\Models\TandaTangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NomorSuratService
{
    /**
     * Tugas 6.1: Menghasilkan nomor surat aman dari race condition menggunakan tabel nomor_surat_urut.
     */
    public function buatNomorSurat(Pengajuan $pengajuan): string
    {
        return DB::transaction(function () use ($pengajuan) {
            $tahun = now()->year;

            // Pastikan baris tahun berjalan tersedia di tabel nomor_surat_urut
            NomorSuratUrut::firstOrCreate(
                ['tahun' => $tahun],
                ['nomor_terakhir' => 0]
            );

            // Kunci baris tahun berjalan (Pessimistic Locking) agar tidak bentrok saat 2 pimpinan klik bersamaan
            $urut = NomorSuratUrut::where('tahun', $tahun)
                ->lockForUpdate()
                ->first();

            $nomorBaru = $urut->nomor_terakhir + 1;
            $urut->update(['nomor_terakhir' => $nomorBaru]);

            // Format 3 digit: 001, 002, dst.
            $nomorFormatted = str_pad((string) $nomorBaru, 3, '0', STR_PAD_LEFT);

            // Ambil kode jenis surat (misal: SKAK) atau default KM.00.01
            $kodeJenis = $pengajuan->jenisSurat?->kode ?? 'KM.00.01';
            $kodeUnit = PengaturanSistem::first()?->kode_unit ?? 'FMIPA';

            return "{$nomorFormatted}/{$kodeJenis}/{$kodeUnit}/{$tahun}";
        });
    }

    /**
     * Tugas 6.2: Membuat kode verifikasi unik untuk portal cek keaslian (/verifikasi)
     */
    public function buatKodeVerifikasi(): string
    {
        do {
            $kode = 'SIMKA-' . strtoupper(Str::random(12));
        } while (TandaTangan::where('kode_verifikasi', $kode)->exists());

        return $kode;
    }

    /**
     * Tugas 6.2: Pembangkitan URL QR Code siap pakai untuk Anggota 6 di template PDF
     */
    public function buatQrCodeUrl(string $kodeVerifikasi): string
    {
        $urlVerifikasi = urlencode(route('verifikasi.index', ['q' => $kodeVerifikasi]));
        return "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={$urlVerifikasi}";
    }
}
