<?php

namespace App\Http\Controllers;

use App\Enums\StatusPengajuan;
use App\Models\Pengajuan;
use App\Models\IsiSurat;
use App\Models\AlurPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidasiController extends Controller
{
    /**
     * Memastikan hanya staf yang dapat mengakses halaman validasi.
     */
    private function cekStaf(): void
    {
        abort_unless(auth()->user()?->isStaf(), 403, 'Akses hanya untuk staf.');
    }

    /**
     * Tugas 5.1: Menampilkan antrean pengajuan surat.
     */
    public function index(Request $request)
    {
        $this->cekStaf();

        $query = Pengajuan::with([
            'mahasiswa',
            'jenisSurat',
            'lampiran',
        ]);

        // Pencarian berdasarkan kode, nama, atau NIM.
        if ($request->filled('cari')) {
            $cari = $request->cari;

            $query->where(function ($q) use ($cari) {
                $q->where('kode_pengajuan', 'like', "%{$cari}%")
                    ->orWhere('nama_snapshot', 'like', "%{$cari}%")
                    ->orWhere('nim_snapshot', 'like', "%{$cari}%");
            });
        }

        $daftarPengajuan = $query
            ->orderByDesc('diajukan_pada')
            ->get();

        $statistik = [
            'menunggu_validasi' => Pengajuan::whereIn(
                'status',
                ['diajukan', 'diproses']
            )->count(),

            'siap_ttd' => Pengajuan::where('status', 'siap_ttd')->count(),

            'ditolak' => Pengajuan::where('status', 'ditolak')->count(),

            'selesai' => Pengajuan::where('status', 'selesai')->count(),
        ];

        return view('staf.index', compact(
            'daftarPengajuan',
            'statistik'
        ));
    }

    /**
     * Tugas 5.2: Menampilkan detail pengajuan dan template surat.
     */
    public function show(Pengajuan $pengajuan)
    {
        $this->cekStaf();

        $pengajuan->load([
            'mahasiswa',
            'jenisSurat',
            'lampiran',
            'isiSurat',
            'alurPengajuan.pelaku',
            'tandaTangan',
        ]);

        return view('staf.detail', compact('pengajuan'));
    }

    /**
     * Tugas 5.3: Menyetujui pengajuan dan menyimpan isi surat.
     */
    public function setujui(Request $request, Pengajuan $pengajuan)
    {
        $this->cekStaf();

        if (! in_array($pengajuan->status->value, ['diajukan', 'diproses'])) {
            return back()->with('error', 'Pengajuan ini tidak dapat disetujui.');
        }

        $validated = $request->validate([
            'perihal' => ['required', 'string', 'max:255'],
            'tujuan_surat' => ['nullable', 'string', 'max:255'],
            'paragraf_pembuka' => ['nullable', 'string'],
            'paragraf_isi' => ['required', 'string'],
            'paragraf_penutup' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($pengajuan, $validated) {
            $statusLama = $pengajuan->status->value;

            IsiSurat::updateOrCreate(
                ['pengajuan_id' => $pengajuan->id],
                [
                    'disusun_oleh' => auth()->id(),
                    'perihal' => $validated['perihal'],
                    'tujuan_surat' => $validated['tujuan_surat'] ?? null,
                    'paragraf_pembuka' => $validated['paragraf_pembuka'] ?? 'Dengan hormat, berdasarkan pengajuan yang telah diperiksa, berikut keterangan mahasiswa yang bersangkutan.',
                    'paragraf_isi' => $validated['paragraf_isi'],
                    'paragraf_penutup' => $validated['paragraf_penutup'] ?? 'Demikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.',
                ]
            );

            $pengajuan->update([
                'status' => StatusPengajuan::SiapTtd,
            ]);

            AlurPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'oleh_pengguna_id' => auth()->id(),
                'status_dari' => $statusLama,
                'status_ke' => 'siap_ttd',
                'keterangan' => 'Pengajuan disetujui oleh staf dan isi surat telah disusun.',
                'dibuat_pada' => now(),
            ]);
        });

        return redirect()
            ->route('staf.show', $pengajuan)
            ->with('success', 'Pengajuan berhasil disetujui dan isi surat berhasil disimpan.');
    }

    /**
     * Tugas 5.4: Menolak pengajuan dan menyimpan alasannya.
     */
    public function tolak(Request $request, Pengajuan $pengajuan)
    {
        $this->cekStaf();

        if (! in_array($pengajuan->status->value, ['diajukan', 'diproses'])) {
            return back()->with('error', 'Pengajuan ini tidak dapat ditolak.');
        }

        $validated = $request->validate([
            'alasan_ditolak' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($pengajuan, $validated) {
            $statusLama = $pengajuan->status->value;

            $pengajuan->update([
                'status' => StatusPengajuan::Ditolak,
                'alasan_ditolak' => $validated['alasan_ditolak'],
            ]);

            AlurPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'oleh_pengguna_id' => auth()->id(),
                'status_dari' => $statusLama,
                'status_ke' => 'ditolak',
                'keterangan' => $validated['alasan_ditolak'],
                'dibuat_pada' => now(),
            ]);
        });

        return redirect()
            ->route('staf.index')
            ->with('success', 'Pengajuan berhasil ditolak.');
    }
}
