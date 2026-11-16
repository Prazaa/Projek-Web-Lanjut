<?php

namespace App\Services;

use App\Models\Pengajuan;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Response;

class SuratPdfService
{
    public function unduh(Pengajuan $pengajuan): Response
    {
        $pengajuan->loadMissing(['jenisSurat', 'isiSurat', 'tandaTangan']);

        $urlVerifikasi = route('verifikasi.index', [
            'q' => $pengajuan->tandaTangan->kode_verifikasi,
        ]);

        $opsiQr = new QROptions([
            'scale'        => 5,
            'outputBase64' => true,
        ]);

        $qrCodeDataUri = (new QRCode($opsiQr))->render($urlVerifikasi);

        $pdf = Pdf::loadView('mahasiswa.surat-pdf', [
            'pengajuan'     => $pengajuan,
            'qrCodeDataUri' => $qrCodeDataUri,
            'urlVerifikasi' => $urlVerifikasi,
        ])->setPaper('A4', 'portrait');

        return $pdf->download('Surat-' . $pengajuan->kode_pengajuan . '.pdf');
    }
}