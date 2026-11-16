<?php

namespace App\Http\Controllers;

use App\Models\TandaTangan;
use Illuminate\Http\Request;


class VerifikasiController extends Controller
{
    public function index(Request $request)
    {
        $hasil = null;

        $q = trim($request->query('q', ''));

        if ($q !== '') {
            $hasil = TandaTangan::with([
                'pengajuan.jenisSurat',
            ])
                ->where('nomor_surat', $q)
                ->orWhere('kode_verifikasi', $q)
                ->first();
        }

        return view('publik.verifikasi', compact('hasil'));
    }
}
