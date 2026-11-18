<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        $daftarSurat = [
            [
                'kode' => 'SKAK',
                'nama' => 'Surat Aktif Kuliah',
                'deskripsi' => 'Surat keterangan resmi yang menyatakan mahasiswa terdaftar aktif kuliah pada semester berjalan.',
                'kode_klasifikasi' => 'KM.00.01',
                'estimasi_hari' => 2,
                'syarat' => [
                    ['nama_dokumen' => 'Bukti Pembayaran UKT Semester Berjalan', 'keterangan' => 'Slip pembayaran atau tangkapan layar status lunas portal akademik', 'wajib' => true],
                    ['nama_dokumen' => 'Kartu Rencana Studi (KRS)', 'keterangan' => 'KRS semester aktif yang telah disetujui dosen PA', 'wajib' => true],
                ],
            ],
            [
                'kode' => 'SKTMB',
                'nama' => 'Surat Keterangan Tidak Menerima Beasiswa dari Pihak Lain',
                'deskripsi' => 'Surat pernyataan resmi dari fakultas bahwa mahasiswa tidak sedang menerima bantuan beasiswa dari instansi atau pihak lain.',
                'kode_klasifikasi' => 'KM.01.01',
                'estimasi_hari' => 2,
                'syarat' => [
                    ['nama_dokumen' => 'Kartu Tanda Mahasiswa (KTM)', 'keterangan' => 'Scan atau foto KTM yang masih berlaku', 'wajib' => true],
                    ['nama_dokumen' => 'Kartu Rencana Studi (KRS)', 'keterangan' => 'KRS semester aktif yang telah disetujui dosen PA', 'wajib' => true],
                ],
            ],
            [
                'kode' => 'SKBB',
                'nama' => 'Surat Keterangan Berkelakuan Baik',
                'deskripsi' => 'Surat keterangan resmi yang menyatakan mahasiswa tidak pernah melakukan pelanggaran tata tertib dan kode etik kampus.',
                'kode_klasifikasi' => 'KM.00.02',
                'estimasi_hari' => 2,
                'syarat' => [
                    ['nama_dokumen' => 'Kartu Tanda Mahasiswa (KTM)', 'keterangan' => 'Scan atau foto KTM yang masih berlaku', 'wajib' => true],
                    ['nama_dokumen' => 'Transkrip Nilai Sementara', 'keterangan' => 'Transkrip nilai terbaru dari portal akademik', 'wajib' => true],
                ],
            ],
            [
                'kode' => 'SKTG',
                'nama' => 'Surat Keterangan Tunjangan Gaji/Taspen/Jamsosetek/Askes',
                'deskripsi' => 'Surat keterangan aktif kuliah khusus untuk keperluan pengurusan tunjangan keluarga, Taspen, Jamsostek, atau Askes orang tua.',
                'kode_klasifikasi' => 'KM.00.03',
                'estimasi_hari' => 2,
                'syarat' => [
                    ['nama_dokumen' => 'Bukti Pembayaran UKT Semester Berjalan', 'keterangan' => 'Slip pembayaran atau tangkapan layar status lunas portal akademik', 'wajib' => true],
                    ['nama_dokumen' => 'Kartu Keluarga (KK) atau SK Terakhir Orang Tua', 'keterangan' => 'Dokumen pendukung status tanggungan keluarga', 'wajib' => true],
                ],
            ],
            [
                'kode' => 'SKKTM',
                'nama' => 'Surat Kehilangan KTM',
                'deskripsi' => 'Surat pengantar resmi dari fakultas sebagai pengganti sementara atau syarat pengurusan ulang Kartu Tanda Mahasiswa yang hilang.',
                'kode_klasifikasi' => 'KM.00.04',
                'estimasi_hari' => 2,
                'syarat' => [
                    ['nama_dokumen' => 'Surat Keterangan Kehilangan dari Kepolisian', 'keterangan' => 'Scan surat tanda penerimaan laporan kehilangan dari Polsek atau Polres', 'wajib' => true],
                    ['nama_dokumen' => 'Kartu Rencana Studi (KRS) Aktif', 'keterangan' => 'Bukti status terdaftar sebagai mahasiswa aktif', 'wajib' => true],
                ],
            ],
        ];

        $kodeAktif = collect($daftarSurat)->pluck('kode')->all();
        JenisSurat::whereNotIn('kode', $kodeAktif)->delete();

        foreach ($daftarSurat as $item) {
            $syaratList = $item['syarat'];
            unset($item['syarat']);

            $jenis = JenisSurat::updateOrCreate(['kode' => $item['kode']], $item);

            foreach ($syaratList as $syarat) {
                $jenis->syaratSurat()->updateOrCreate(
                    ['nama_dokumen' => $syarat['nama_dokumen']],
                    $syarat
                );
            }
        }
    }
}