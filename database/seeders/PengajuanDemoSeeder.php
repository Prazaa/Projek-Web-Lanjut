<?php

namespace Database\Seeders;

use App\Enums\StatusPengajuan;
use App\Models\AlurPengajuan;
use App\Models\IsiSurat;
use App\Models\JenisSurat;
use App\Models\Mahasiswa;
use App\Models\NomorSuratUrut;
use App\Models\Pengajuan;
use App\Models\Pengguna;
use App\Models\TandaTangan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PengajuanDemoSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswa = Mahasiswa::with('pengguna')->first();
        $staf = Pengguna::where('role', 'staf')->first();
        $pimpinan = Pengguna::where('role', 'pimpinan')->first();

        $skak = JenisSurat::where('kode', 'SKAK')->first();
        $spkp = JenisSurat::where('kode', 'SPKP')->first();
        $srkb = JenisSurat::where('kode', 'SRKB')->first();

        if (! $mahasiswa || ! $skak || ! $spkp || ! $srkb) {
            return;
        }

        // 1. Pengajuan status DIAJUKAN (menunggu staf)
        $p1 = Pengajuan::updateOrCreate(
            ['kode_pengajuan' => 'SRT-2026-0001'],
            [
                'mahasiswa_id' => $mahasiswa->id,
                'jenis_surat_id' => $spkp->id,
                'keperluan' => 'Permohonan Kerja Praktik di Dinas Komunikasi Informatika dan Statistik Provinsi Riau',
                'data_tambahan' => ['instansi_tujuan' => 'Diskominfotik Provinsi Riau', 'tanggal_mulai' => '2027-01-05'],
                'status' => StatusPengajuan::Diajukan,
                'nama_snapshot' => $mahasiswa->pengguna->nama,
                'nim_snapshot' => $mahasiswa->nim,
                'prodi_snapshot' => $mahasiswa->program_studi,
                'semester_snapshot' => $mahasiswa->semester,
                'diajukan_pada' => now()->subDays(1),
            ]
        );

        AlurPengajuan::firstOrCreate(
            ['pengajuan_id' => $p1->id, 'status_ke' => 'diajukan'],
            [
                'oleh_pengguna_id' => $mahasiswa->pengguna_id,
                'status_dari' => null,
                'keterangan' => 'Pengajuan surat dikirim oleh mahasiswa.',
                'dibuat_pada' => now()->subDays(1),
            ]
        );

        // 2. Pengajuan status SIAP_TTD (sudah divalidasi staf, menunggu TTD pimpinan)
        $p2 = Pengajuan::updateOrCreate(
            ['kode_pengajuan' => 'SRT-2026-0002'],
            [
                'mahasiswa_id' => $mahasiswa->id,
                'jenis_surat_id' => $srkb->id,
                'keperluan' => 'Pendaftaran Beasiswa Bright Scholarship YBM BRILiaN',
                'data_tambahan' => ['nama_beasiswa' => 'Bright Scholarship YBM BRILiaN'],
                'status' => StatusPengajuan::SiapTtd,
                'nama_snapshot' => $mahasiswa->pengguna->nama,
                'nim_snapshot' => $mahasiswa->nim,
                'prodi_snapshot' => $mahasiswa->program_studi,
                'semester_snapshot' => $mahasiswa->semester,
                'diajukan_pada' => now()->subDays(2),
            ]
        );

        IsiSurat::updateOrCreate(
            ['pengajuan_id' => $p2->id],
            [
                'disusun_oleh' => $staf?->id,
                'perihal' => 'Rekomendasi Pendaftaran Beasiswa',
                'tujuan_surat' => 'Panitia Seleksi Beasiswa YBM BRILiaN',
                'paragraf_pembuka' => 'Pimpinan Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Riau dengan ini menerangkan bahwa:',
                'paragraf_isi' => 'Mahasiswa tersebut di atas benar berstatus aktif kuliah, berkelakuan baik, serta layak direkomendasikan untuk mengikuti seleksi Beasiswa Bright Scholarship.',
                'paragraf_penutup' => 'Demikian surat rekomendasi ini dibuat untuk dapat dipergunakan sebagaimana mestinya.',
            ]
        );

        // 3. Pengajuan status SELESAI (sudah ditandatangani & punya kode verifikasi)
        $p3 = Pengajuan::updateOrCreate(
            ['kode_pengajuan' => 'SRT-2026-0003'],
            [
                'mahasiswa_id' => $mahasiswa->id,
                'jenis_surat_id' => $skak->id,
                'keperluan' => 'Administrasi Perpanjangan Kepesertaan BPJS Kesehatan',
                'status' => StatusPengajuan::Selesai,
                'nama_snapshot' => $mahasiswa->pengguna->nama,
                'nim_snapshot' => $mahasiswa->nim,
                'prodi_snapshot' => $mahasiswa->program_studi,
                'semester_snapshot' => $mahasiswa->semester,
                'diajukan_pada' => now()->subDays(5),
                'selesai_pada' => now()->subDays(3),
            ]
        );

        IsiSurat::updateOrCreate(
            ['pengajuan_id' => $p3->id],
            [
                'disusun_oleh' => $staf?->id,
                'perihal' => 'Surat Keterangan Aktif Kuliah',
                'tujuan_surat' => 'BPJS Kesehatan Cabang Pekanbaru',
                'paragraf_pembuka' => 'Yang bertanda tangan di bawah ini, Wakil Dekan Bidang Kemahasiswaan, Kerja Sama dan Alumni FMIPA Universitas Riau menerangkan bahwa:',
                'paragraf_isi' => 'Nama tersebut benar tercatat sebagai mahasiswa aktif pada Semester Ganjil Tahun Akademik 2026/2027 di Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Riau.',
                'paragraf_penutup' => 'Demikian surat keterangan ini diterbitkan untuk keperluan administrasi kepesertaan BPJS Kesehatan.',
            ]
        );

        NomorSuratUrut::updateOrCreate(['tahun' => 2026], ['nomor_terakhir' => 1]);

        TandaTangan::updateOrCreate(
            ['pengajuan_id' => $p3->id],
            [
                'penanda_tangan_id' => $pimpinan->id,
                'nomor_surat' => '001/KM.00.01/FMIPA/2026',
                'kode_verifikasi' => 'SIMKA-2026-' . strtoupper(Str::random(8)),
                'nama_penanda_tangan' => $pimpinan->nama,
                'nip_penanda_tangan' => $pimpinan->nip,
                'jabatan_penanda_tangan' => $pimpinan->jabatan,
                'ditandatangani_pada' => now()->subDays(3),
            ]
        );
    }
}