<?php

namespace App\Http\Controllers;

use App\Enums\StatusPengajuan;
use App\Models\AlurPengajuan;
use App\Models\Pengajuan;
use App\Models\PengaturanSistem;
use App\Models\TandaTangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PimpinanController extends Controller
{
    private function cekPimpinan(): void
    {
        abort_unless(
            auth()->user()?->role === 'pimpinan',
            403,
            'Anda tidak memiliki akses ke halaman pimpinan.'
        );
    }

    /**
     * 1. Daftar pengajuan yang siap ditandatangani.
     */
    public function index()
    {
        $this->cekPimpinan();

        $daftarSiapTtd = Pengajuan::with(['jenisSurat'])
            ->where('status', StatusPengajuan::SiapTtd)
            ->orderBy('diajukan_pada')
            ->get();

        $jumlahTerbitBulanIni = TandaTangan::whereMonth(
            'ditandatangani_pada',
            now()->month
        )->whereYear(
            'ditandatangani_pada',
            now()->year
        )->count();

        return view('pimpinan.index', compact(
            'daftarSiapTtd',
            'jumlahTerbitBulanIni'
        ));
    }

    /**
     * 2. Detail dan pratinjau surat.
     */
    public function show(Pengajuan $pengajuan)
    {
        $this->cekPimpinan();

        $pengajuan->load([
            'jenisSurat',
            'isiSurat',
            'tandaTangan',
        ]);

        abort_unless(
            in_array(
                $pengajuan->status,
                [
                    StatusPengajuan::SiapTtd,
                    StatusPengajuan::Selesai,
                ],
                true
            ),
            404,
            'Pengajuan belum siap ditinjau pimpinan.'
        );

        $pengaturan = PengaturanSistem::ambil();

        return view('pimpinan.detail', compact(
            'pengajuan',
            'pengaturan'
        ));
    }

    /**
     * 3. Tanda tangan dan menerbitkan surat.
     */
    public function tandatangani(Pengajuan $pengajuan)
    {
        $this->cekPimpinan();

        $pengguna = auth()->user();

        $hasil = DB::transaction(function () use ($pengajuan, $pengguna) {

            // Kunci data pengajuan agar tidak diproses bersamaan.
            $data = Pengajuan::whereKey($pengajuan->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Pastikan status masih siap ditandatangani.
            abort_unless(
                $data->status === StatusPengajuan::SiapTtd,
                422,
                'Surat sudah ditandatangani atau belum siap diterbitkan.'
            );

            // Pastikan belum pernah memiliki tanda tangan.
            abort_if(
                TandaTangan::where('pengajuan_id', $data->id)->exists(),
                422,
                'Surat ini sudah pernah diterbitkan.'
            );

            $pengaturan = PengaturanSistem::ambil();
            $tahun = (int) now()->format('Y');

            // Siapkan nomor urut untuk tahun berjalan.
            DB::table('nomor_surat_urut')->insertOrIgnore([
                'tahun' => $tahun,
                'nomor_terakhir' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Kunci nomor urut agar tidak ganda.
            $nomorUrut = DB::table('nomor_surat_urut')
                ->where('tahun', $tahun)
                ->lockForUpdate()
                ->first();

            $nomorBaru = $nomorUrut->nomor_terakhir + 1;

            DB::table('nomor_surat_urut')
                ->where('tahun', $tahun)
                ->update([
                    'nomor_terakhir' => $nomorBaru,
                    'updated_at' => now(),
                ]);

            // Format nomor surat sementara:
            // FMIPA/0001/2026
            $kodeUnit = $pengaturan->kode_unit ?: 'FMIPA';

            $nomorSurat = $kodeUnit . '/'
                . str_pad($nomorBaru, 4, '0', STR_PAD_LEFT)
                . '/'
                . $tahun;

            // Buat kode verifikasi unik.
            $kodeVerifikasi = Str::upper(Str::random(32));

            $jabatan = $pengguna->jabatan
                ?: $pengaturan->jabatan_pimpinan_default;

            // Simpan data tanda tangan.
            TandaTangan::create([
                'pengajuan_id' => $data->id,
                'penanda_tangan_id' => $pengguna->id,
                'nomor_surat' => $nomorSurat,
                'kode_verifikasi' => $kodeVerifikasi,
                'nama_penanda_tangan' => $pengguna->nama,
                'nip_penanda_tangan' => $pengguna->nip
                    ?: $pengaturan->nip_pimpinan_default,
                'jabatan_penanda_tangan' => $jabatan,
                'ditandatangani_pada' => now(),
            ]);

            // Ubah status menjadi selesai.
            $statusLama = $data->status->value;

            $data->update([
                'status' => StatusPengajuan::Selesai,
                'selesai_pada' => now(),
            ]);

            // Catat riwayat perubahan status.
            AlurPengajuan::create([
                'pengajuan_id' => $data->id,
                'oleh_pengguna_id' => $pengguna->id,
                'status_dari' => $statusLama,
                'status_ke' => StatusPengajuan::Selesai->value,
                'keterangan' => 'Surat telah ditandatangani dan diterbitkan oleh pimpinan. Nomor surat: ' . $nomorSurat,
                'dibuat_pada' => now(),
            ]);

            return $nomorSurat;
        });

        return redirect()
            ->route('pimpinan.show', $pengajuan)
            ->with(
                'success',
                'Surat berhasil ditandatangani dan diterbitkan dengan nomor: ' . $hasil
            );
    }
}
