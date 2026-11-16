<?php

namespace App\Http\Controllers;

use App\Models\Lampiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LampiranController extends Controller
{
    public function unduh(Request $request, int $id)
    {
        $lampiran = Lampiran::with('pengajuan.mahasiswa')->findOrFail($id);
        $user = $request->user();

        $adalahPemilik = $user->isMahasiswa()
            && $lampiran->pengajuan?->mahasiswa?->pengguna_id === $user->id;

        $adalahPetugas = $user->isStaf() || $user->isPimpinan();

        if (! $adalahPemilik && ! $adalahPetugas) {
            abort(403, 'Anda tidak memiliki izin untuk mengunduh berkas lampiran ini.');
        }

        if (! $lampiran->path_berkas || ! Storage::disk('local')->exists($lampiran->path_berkas)) {
            abort(404, 'Berkas lampiran tidak ditemukan di penyimpanan server.');
        }

        return Storage::disk('local')->download(
            $lampiran->path_berkas,
            $lampiran->nama_asli
        );
    }
}