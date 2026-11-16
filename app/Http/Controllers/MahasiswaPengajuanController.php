<?php

namespace App\Http\Controllers;

use App\Enums\StatusPengajuan;
use App\Models\JenisSurat;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;
use Barryvdh\DomPDF\Facade\Pdf;

class MahasiswaPengajuanController extends Controller
{

    /**
     * Menampilkan dashboard mahasiswa.
     */
    public function dashboard(Request $request)
    {
        $mahasiswa = $request->user()->mahasiswa;

        abort_unless($mahasiswa, 403, 'Profil mahasiswa belum tersedia.');

        $statusBerjalan = [
            'diajukan',
            'diproses',
            'revisi',
            'siap_ttd',
        ];

        $pengajuanBerjalan = $mahasiswa->pengajuan()
            ->with(['jenisSurat', 'tandaTangan'])
            ->withCount('lampiran')
            ->whereIn('status', $statusBerjalan)
            ->orderByDesc('diajukan_pada')
            ->get();

        $pengajuanSelesai = $mahasiswa->pengajuan()
            ->with(['jenisSurat', 'tandaTangan'])
            ->withCount('lampiran')
            ->where('status', 'selesai')
            ->orderByDesc('selesai_pada')
            ->get();

        $statistik = [
            'total' => $mahasiswa->pengajuan()->count(),

            'diproses' => $mahasiswa->pengajuan()
                ->whereIn('status', $statusBerjalan)
                ->count(),

            'selesai' => $mahasiswa->pengajuan()
                ->where('status', 'selesai')
                ->count(),
        ];

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'pengajuanBerjalan',
            'pengajuanSelesai',
            'statistik'
        ));
    }

    /**
 * Menampilkan detail pengajuan surat milik mahasiswa.
 */
public function detail(Request $request, string $kodePengajuan)
{
    $mahasiswa = $request->user()->mahasiswa;

    abort_unless($mahasiswa, 403, 'Profil mahasiswa belum tersedia.');

    $pengajuan = $mahasiswa->pengajuan()
        ->with([
            'jenisSurat',
            'lampiran',
            'alurPengajuan',
            'isiSurat',
            'tandaTangan',
        ])
        ->where('kode_pengajuan', $kodePengajuan)
        ->firstOrFail();

        return view('mahasiswa.detail', compact('pengajuan'));
}

/**
 * Mengunduh surat yang sudah selesai dalam format PDF.
 */
public function unduhSurat(Request $request, string $kodePengajuan)
{
    $mahasiswa = $request->user()->mahasiswa;

    abort_unless($mahasiswa, 403, 'Profil mahasiswa belum tersedia.');

    $pengajuan = $mahasiswa->pengajuan()
        ->with([
            'jenisSurat',
            'isiSurat',
            'tandaTangan',
        ])
        ->where('kode_pengajuan', $kodePengajuan)
        ->firstOrFail();

    abort_unless(
        $pengajuan->status === StatusPengajuan::Selesai,
        403,
        'Surat belum selesai dan belum dapat diunduh.'
    );

    abort_unless(
        $pengajuan->isiSurat && $pengajuan->tandaTangan,
        404,
        'Data isi surat atau tanda tangan belum tersedia.'
    );

    $pdf = Pdf::loadView('mahasiswa.surat-pdf', compact('pengajuan'))
        ->setPaper('A4', 'portrait');

    return $pdf->download(
        'Surat-' . $pengajuan->kode_pengajuan . '.pdf'
    );
}

    /**
     * Menampilkan formulir pengajuan surat.
    public function create(Request $request)
    {
        
        $mahasiswa = $request->user()->mahasiswa;

        abort_unless($mahasiswa, 403, 'Profil mahasiswa belum tersedia.');

        $daftarJenisSurat = JenisSurat::where('aktif', true)
            ->orderBy('nama')
            ->get();

        return view('mahasiswa.ajukan', compact(
            'mahasiswa',
            'daftarJenisSurat'
        ));
    }

    /**
     * Mengambil persyaratan berdasarkan jenis surat.
     */
    public function syarat(JenisSurat $jenisSurat)
    {
        abort_unless($jenisSurat->aktif, 404);

        return response()->json([
            'syarat' => $jenisSurat->syaratSurat()
                ->orderBy('id')
                ->get([
                    'id',
                    'nama_dokumen',
                    'wajib',
                    'format_diizinkan',
                    'keterangan',
                    'maks_ukuran_kb',
                ]),
        ]);
    }

    /**
     * Menyimpan pengajuan dan lampirannya.
     */
    public function store(Request $request)
    {
        $mahasiswa = $request->user()->mahasiswa;

        abort_unless($mahasiswa, 403, 'Profil mahasiswa belum tersedia.');

        $data = $request->validate([
            'jenis_surat_id' => [
                'required',
                'integer',
                'exists:jenis_surat,id',
            ],
            'keperluan' => [
                'required',
                'string',
                'max:255',
            ],
            'lampiran' => [
                'nullable',
                'array',
            ],
        ]);

        $jenisSurat = JenisSurat::where('aktif', true)
            ->whereKey($data['jenis_surat_id'])
            ->first();

        if (!$jenisSurat) {
            throw ValidationException::withMessages([
                'jenis_surat_id' => 'Jenis surat tidak tersedia atau sudah tidak aktif.',
            ]);
        }

        $daftarSyarat = $jenisSurat->syaratSurat()->get();

        // Validasi setiap file sesuai persyaratan jenis surat.
        $aturanFile = [];

        foreach ($daftarSyarat as $syarat) {
            $format = collect(explode(',', $syarat->format_diizinkan))
                ->map(fn ($item) => strtolower(trim($item)))
                ->filter()
                ->implode(',');

            $aturanFile['lampiran.' . $syarat->id] = [
                $syarat->wajib ? 'required' : 'nullable',
                'file',
                'mimes:' . ($format ?: 'pdf,jpg,jpeg,png'),
                'max:' . $syarat->maks_ukuran_kb,
            ];
        }

        $request->validate($aturanFile);

        $kodePengajuan = 'SIMKA-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));

        $paths = [];

        try {
            DB::transaction(function () use (
                $request,
                $mahasiswa,
                $jenisSurat,
                $data,
                $daftarSyarat,
                $kodePengajuan,
                &$paths
            ) {
                $pengajuan = Pengajuan::create([
                    'kode_pengajuan' => $kodePengajuan,
                    'mahasiswa_id' => $mahasiswa->id,
                    'jenis_surat_id' => $jenisSurat->id,
                    'keperluan' => $data['keperluan'],
                    'status' => StatusPengajuan::Diajukan,
                    'nama_snapshot' => $request->user()->nama,
                    'nim_snapshot' => $mahasiswa->nim,
                    'prodi_snapshot' => $mahasiswa->program_studi,
                    'semester_snapshot' => $mahasiswa->semester,
                    'diajukan_pada' => now(),
                ]);

                $berkas = $request->file('lampiran', []);

                foreach ($daftarSyarat as $syarat) {
                    $file = $berkas[$syarat->id] ?? null;

                    if (!$file) {
                        continue;
                    }

                    $path = $file->store(
                        'lampiran/' . $kodePengajuan,
                        'public'
                    );

                    $paths[] = $path;

                    $pengajuan->lampiran()->create([
                        'syarat_surat_id' => $syarat->id,
                        'nama_asli' => $file->getClientOriginalName(),
                        'path_berkas' => $path,
                        'mime_type' => $file->getMimeType(),
                        'ukuran_byte' => $file->getSize(),
                        'terverifikasi' => false,
                    ]);
                }

                $pengajuan->alurPengajuan()->create([
                    'oleh_pengguna_id' => $request->user()->id,
                    'status_dari' => null,
                    'status_ke' => 'diajukan',
                    'keterangan' => 'Pengajuan surat dibuat oleh mahasiswa.',
                    'dibuat_pada' => now(),
                ]);
            });
        } catch (Throwable $e) {
            foreach ($paths as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $e;
        }

        return redirect()
            ->route('mahasiswa.riwayat.index')
            ->with('success', 'Pengajuan berhasil dikirim. Kode pengajuan: ' . $kodePengajuan);
    }
}