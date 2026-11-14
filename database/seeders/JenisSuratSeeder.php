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
                'nama' => 'Surat Keterangan Aktif Kuliah',
                'deskripsi' => 'Surat keterangan resmi yang menyatakan mahasiswa terdaftar aktif pada semester berjalan untuk keperluan tunjangan orang tua, BPJS, atau administrasi lainnya.',
                'kode_klasifikasi' => 'KM.00.01',
                'estimasi_hari' => 2,
                'syarat' => [
                    ['nama_dokumen' => 'Bukti Pembayaran UKT Semester Berjalan', 'keterangan' => 'Slip pembayaran atau tangkapan layar status lunas portal akademik', 'wajib' => true],
                    ['nama_dokumen' => 'Kartu Rencana Studi (KRS)', 'keterangan' => 'KRS semester aktif yang telah disetujui dosen PA', 'wajib' => true],
                ],
            ],
            [
                'kode' => 'SPKP',
                'nama' => 'Surat Pengantar Kerja Praktik / Magang',
                'deskripsi' => 'Surat pengantar resmi dari fakultas kepada instansi atau perusahaan tujuan pelaksanaan Kerja Praktik (KP) / Magang.',
                'kode_klasifikasi' => 'KM.02.01',
                'estimasi_hari' => 3,
                'syarat' => [
                    ['nama_dokumen' => 'Transkrip Nilai Sementara', 'keterangan' => 'Minimal telah menempuh 80 SKS', 'wajib' => true],
                    ['nama_dokumen' => 'Proposal Singkat / Profil Instansi Tujuan', 'keterangan' => 'Dokumen berisi nama instansi dan alamat lengkap tujuan surat', 'wajib' => false],
                ],
            ],
            [
                'kode' => 'SRKB',
                'nama' => 'Surat Rekomendasi Beasiswa',
                'deskripsi' => 'Surat rekomendasi pimpinan fakultas sebagai syarat pendaftaran program beasiswa internal maupun eksternal.',
                'kode_klasifikasi' => 'KM.01.02',
                'estimasi_hari' => 3,
                'syarat' => [
                    ['nama_dokumen' => 'Transkrip Nilai Terakhir', 'keterangan' => 'Menunjukkan IPK terakhir mahasiswa', 'wajib' => true],
                    ['nama_dokumen' => 'Kartu Tanda Mahasiswa (KTM)', 'keterangan' => 'Scan atau foto KTM yang masih berlaku', 'wajib' => true],
                ],
            ],
            [
                'kode' => 'SIPN',
                'nama' => 'Surat Izin Penelitian Tugas Akhir',
                'deskripsi' => 'Surat permohonan izin pengambilan data dan penelitian skripsi pada instansi pemerintah maupun swasta.',
                'kode_klasifikasi' => 'PP.01.01',
                'estimasi_hari' => 4,
                'syarat' => [
                    ['nama_dokumen' => 'Halaman Pengesahan Proposal Skripsi', 'keterangan' => 'Telah ditandatangani pembimbing dan koordinator prodi', 'wajib' => true],
                ],
            ],
        ];

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