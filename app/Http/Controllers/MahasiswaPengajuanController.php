<?php

namespace App\Http\Controllers;

use App\Enums\StatusPengajuan;
use App\Models\JenisSurat;
use App\Models\Pengajuan;
use App\Services\SuratPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class MahasiswaPengajuanController extends Controller
{
    /**
     * Menampilkan dashboard mahasiswa.
     */
    public function dashboard(Request $request)
    {
        $mahasiswa = $request->user()->mahasiswa;

        if (! $mahasiswa) {
            return redirect()
                ->route('mahasiswa.profil.edit')
                ->with('info', 'Lengkapi biodata mahasiswa Anda terlebih dahulu sebelum menggunakan layanan.');
        }

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
     * Menampilkan seluruh riwayat pengajuan mahasiswa.
     */
    public function riwayat(Request $request)
    {
        $mahasiswa = $request->user()->mahasiswa;

        if (! $mahasiswa) {
            return redirect()
                ->route('mahasiswa.profil.edit')
                ->with('info', 'Lengkapi biodata mahasiswa Anda terlebih dahulu sebelum menggunakan layanan.');
        }

        $daftarPengajuan = $mahasiswa->pengajuan()
            ->with(['jenisSurat', 'tandaTangan', 'lampiran'])
            ->orderByDesc('diajukan_pada')
            ->get();

        return view('mahasiswa.riwayat', compact('daftarPengajuan'));
    }

    /**
     * Menampilkan detail pengajuan surat milik mahasiswa.
     */
    public function detail(Request $request, string $kodePengajuan)
    {
        $mahasiswa = $request->user()->mahasiswa;

        if (! $mahasiswa) {
            return redirect()
                ->route('mahasiswa.profil.edit')
                ->with('info', 'Lengkapi biodata mahasiswa Anda terlebih dahulu sebelum menggunakan layanan.');
        }

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
    public function unduhSurat(Request $request, string $kodePengajuan, SuratPdfService $suratPdfService)
    {
        $mahasiswa = $request->user()->mahasiswa;

        if (! $mahasiswa) {
            return redirect()
                ->route('mahasiswa.profil.edit')
                ->with('info', 'Lengkapi biodata mahasiswa Anda terlebih dahulu sebelum menggunakan layanan.');
        }

        $pengajuan = $mahasiswa->pengajuan()
            ->with([
                'jenisSurat',
                'isiSurat',
                'tandaTangan',
            ])
            ->where('kode_pengajuan', $kodePengajuan)
            ->firstOrFail();

        if ($pengajuan->status !== StatusPengajuan::Selesai && $pengajuan->status !== 'selesai') {
            abort(403, 'Surat belum selesai diterbitkan.');
        }

        return $suratPdfService->unduh($pengajuan);
    }

    /**
     * Menampilkan formulir pengajuan surat.
     */
    public function create(Request $request)
    {
        $mahasiswa = $request->user()->mahasiswa;

        if (! $mahasiswa) {
            return redirect()
                ->route('mahasiswa.profil.edit')
                ->with('info', 'Lengkapi biodata mahasiswa Anda terlebih dahulu sebelum menggunakan layanan.');
        }

        $daftarJenisSurat = JenisSurat::where('aktif', true)
            ->orderBy('nama')
            ->get();

        return view('mahasiswa.ajukan', compact(
            'mahasiswa',
            'daftarJenisSurat'
        ));
    }

    /**
     * Mengembalikan daftar syarat dokumen berdasarkan jenis surat dalam format JSON.
     */
    public function syarat(JenisSurat $jenisSurat)
    {
        $syarat = $jenisSurat->syaratSurat()
            ->orderBy('id')
            ->get([
                'id',
                'nama_dokumen',
                'keterangan',
                'wajib',
                'format_diizinkan',
                'maks_ukuran_kb',
            ]);

        return response()->json([
            'jenis_surat' => [
                'id' => $jenisSurat->id,
                'kode' => $jenisSurat->kode,
                'nama' => $jenisSurat->nama,
            ],
            'syarat' => $syarat,
        ]);
    }

    /**
     * Menyimpan pengajuan dan lampirannya.
     */
    public function store(Request $request)
    {
        $mahasiswa = $request->user()->mahasiswa;

        if (! $mahasiswa) {
            return redirect()
                ->route('mahasiswa.profil.edit')
                ->with('info', 'Lengkapi biodata mahasiswa Anda terlebih dahulu sebelum menggunakan layanan.');
        }

        $data = $request->validate([
            'jenis_surat_id' => [
                'required',
                'integer',
                'exists:jenis_surat,id',
            ],
            'keperluan' => [
                'required',
                'string',
                'max:1000',
            ],
            'lampiran' => [
                'nullable',
                'array',
            ],
            'lampiran.*' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ], [
            'jenis_surat_id.required' => 'Jenis surat wajib dipilih.',
            'keperluan.required' => 'Keperluan pengajuan surat wajib diisi.',
            'lampiran.*.mimes' => 'Format lampiran harus PDF, JPG, JPEG, atau PNG.',
            'lampiran.*.max' => 'Ukuran setiap berkas lampiran maksimal 5 MB.',
        ]);

        $jenisSurat = JenisSurat::with('syaratSurat')->findOrFail($data['jenis_surat_id']);
        $daftarSyarat = $jenisSurat->syaratSurat;
        $berkasMasuk = $request->file('lampiran', []);

        foreach ($daftarSyarat as $syarat) {
            if ($syarat->wajib && ! isset($berkasMasuk[$syarat->id])) {
                throw ValidationException::withMessages([
                    'lampiran.' . $syarat->id => "Dokumen {$syarat->nama_dokumen} wajib diunggah.",
                ]);
            }
        }

        $kodePengajuan = $this->buatKodePengajuan();
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
                    if (! isset($berkas[$syarat->id])) {
                        continue;
                    }

                    $file = $berkas[$syarat->id];
                    $path = $file->store('lampiran/' . $kodePengajuan, 'local');
                    $paths[] = $path;

                    $pengajuan->lampiran()->create([
                        'syarat_surat_id' => $syarat->id,
                        'nama_asli' => $file->getClientOriginalName(),
                        'path_berkas' => $path,
                        'mime_type' => $file->getClientMimeType(),
                        'ukuran_byte' => $file->getSize(),
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
                Storage::disk('local')->delete($path);
            }

            throw $e;
        }

        return redirect()
            ->route('mahasiswa.riwayat.index')
            ->with('success', 'Pengajuan berhasil dikirim. Kode pengajuan: ' . $kodePengajuan);
    }

    /**
     * Membuat kode pengajuan unik dengan format SRT-TAHUN-XXXX.
     */
    protected function buatKodePengajuan(): string
    {
        $tahun = now()->year;
        $awalan = "SRT-{$tahun}-";

        $terakhir = Pengajuan::where('kode_pengajuan', 'like', "{$awalan}%")
            ->orderByDesc('id')
            ->value('kode_pengajuan');

        $urutan = $terakhir
            ? ((int) Str::afterLast($terakhir, '-')) + 1
            : 1;

        return $awalan . str_pad((string) $urutan, 4, '0', STR_PAD_LEFT);
    }
}