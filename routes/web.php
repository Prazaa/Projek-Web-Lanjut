<?php

use App\Enums\StatusPengajuan;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TandaTanganController;
use App\Models\AlurPengajuan;
use App\Models\IsiSurat;
use App\Models\JenisSurat;
use App\Models\Lampiran;
use App\Models\Mahasiswa;
use App\Models\Pengajuan;
use App\Models\PengaturanSistem;
use App\Models\TandaTangan;
use Illuminate\Support\Facades\Route;

// 1. Halaman Utama: otomatis arahkan sesuai status login & jabatan[cite: 2]
Route::get('/', fn () => auth()->check()
    ? redirect((new AuthController)->ruteTujuanPeran(auth()->user()->role))
    : redirect()->route('login'));

// 2. Rute Autentikasi Tamu (Belum Login)[cite: 2]
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->name('login.post');

    // Placeholder Google SSO (akan disambungkan ke GoogleAuthController oleh Anggota 2)[cite: 1, 2]
    Route::get('/auth/google', fn () => redirect()->route('login')
        ->withErrors(['sso' => 'Login Google SSO sedang disiapkan oleh Anggota 2.']))
        ->name('auth.google');
});

// 3. Rute Logout[cite: 2]
Route::post('/keluar', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// 4. Rute Publik Verifikasi Surat (sementara sebelum dikerjakan Anggota 6)[cite: 1, 4]
Route::get('/verifikasi', function () {
    $hasil = null;
    if (request()->filled('q')) {
        $jenis = new JenisSurat(['nama' => 'Surat Keterangan Aktif Kuliah']);
        $pengajuan = new Pengajuan([
            'nama_snapshot' => 'Budi Santoso',
            'nim_snapshot' => '2301054012',
        ]);
        $pengajuan->setRelation('jenisSurat', $jenis);

        $hasil = new TandaTangan([
            'nomor_surat' => '001/KM.00.01/FMIPA/2026',
            'kode_verifikasi' => request('q'),
            'ditandatangani_pada' => now(),
            'nama_penanda_tangan' => 'Dr. Dekan FMIPA, M.Si.',
            'jabatan_penanda_tangan' => 'Wakil Dekan Bidang Kemahasiswaan',
        ]);
        $hasil->setRelation('pengajuan', $pengajuan);
    }

    return view('publik.verifikasi', compact('hasil'));
})->name('verifikasi.index');

// Data sementara untuk tampilan dashboard sebelum modul controller anggota lain selesai[cite: 4]
$buatDummyPengajuan = function () {
    $jenis = new JenisSurat(['kode' => 'SKAK', 'nama' => 'Surat Keterangan Aktif Kuliah', 'deskripsi' => 'Surat keterangan resmi status aktif kuliah.']);
    $jenis->id = 1;

    $berkas = new Lampiran(['nama_asli' => 'KRS_Semester_Ganjil_2026.pdf', 'ukuran_byte' => 524288]);
    $berkas->id = 1;
    $berkas->created_at = now();

    $mhs = new Mahasiswa(['no_hp' => '+6281234567890']);

    $isi = new IsiSurat([
        'perihal' => 'Surat Keterangan Aktif Kuliah',
        'tujuan_surat' => 'Pengelola Beasiswa YBM BRILiaN',
        'paragraf_pembuka' => 'Yang bertanda tangan di bawah ini menerangkan bahwa:',
        'paragraf_isi' => 'Mahasiswa tersebut di atas benar tercatat sebagai mahasiswa aktif pada Program Studi Matematika FMIPA Universitas Riau.',
        'paragraf_penutup' => 'Demikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.',
    ]);

    $alur = new AlurPengajuan([
        'status_ke' => 'diajukan',
        'dibuat_pada' => now()->subHours(3),
        'keterangan' => 'Pengajuan dikirim oleh mahasiswa.',
    ]);

    $item1 = new Pengajuan([
        'kode_pengajuan' => 'PRJ-2026-0001',
        'nama_snapshot' => 'Budi Santoso',
        'nim_snapshot' => '2301054012',
        'prodi_snapshot' => 'Matematika',
        'semester_snapshot' => 5,
        'keperluan' => 'Syarat pendaftaran Beasiswa YBM BRILiaN',
        'status' => StatusPengajuan::Diajukan,
        'diajukan_pada' => now()->subHours(3),
    ]);
    $item1->id = 1;
    $item1->updated_at = now();
    $item1->lampiran_count = 1;
    $item1->setRelation('jenisSurat', $jenis);
    $item1->setRelation('lampiran', collect([$berkas]));
    $item1->setRelation('mahasiswa', $mhs);
    $item1->setRelation('isiSurat', $isi);
    $item1->setRelation('alurPengajuan', collect([$alur]));
    $item1->setRelation('tandaTangan', null);

    $item2 = new Pengajuan([
        'kode_pengajuan' => 'PRJ-2026-0002',
        'nama_snapshot' => 'Budi Santoso',
        'nim_snapshot' => '2301054012',
        'prodi_snapshot' => 'Matematika',
        'semester_snapshot' => 5,
        'keperluan' => 'Administrasi Tunjangan Orang Tua',
        'status' => StatusPengajuan::Selesai,
        'diajukan_pada' => now()->subDays(3),
        'selesai_pada' => now()->subDay(),
    ]);
    $item2->id = 2;
    $item2->updated_at = now()->subDay();
    $item2->lampiran_count = 1;
    $ttd = new TandaTangan(['nomor_surat' => '014/KM.00.01/FMIPA/2026', 'ditandatangani_pada' => now()->subDay()]);
    $item2->setRelation('jenisSurat', $jenis);
    $item2->setRelation('lampiran', collect([$berkas]));
    $item2->setRelation('mahasiswa', $mhs);
    $item2->setRelation('isiSurat', $isi);
    $item2->setRelation('alurPengajuan', collect([$alur]));
    $item2->setRelation('tandaTangan', $ttd);

    return collect([$item1, $item2]);
};

// 5. Area Khusus Mahasiswa (Dilindungi Middleware auth & role:mahasiswa)[cite: 2, 4]
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () use ($buatDummyPengajuan) {
    $semua = $buatDummyPengajuan();
    $mahasiswa = new Mahasiswa([
        'nim' => '2301054012',
        'program_studi' => 'Matematika',
        'semester' => 5,
        'tempat_lahir' => 'Pekanbaru',
        'tanggal_lahir' => \Illuminate\Support\Carbon::parse('2005-04-12'),
        'no_hp' => '+6281234567890',
        'alamat' => 'Jl. A. Yani No. 88, Pekanbaru, Riau',
        'nama_orang_tua' => 'Hadi Santoso',
        'pekerjaan_orang_tua' => 'Wiraswasta',
        'no_whatsapp_orang_tua' => '+6281234567890',
    ]);

    Route::get('/dashboard', fn () => view('mahasiswa.dashboard', [
        'statistik' => ['total' => 2, 'diproses' => 1, 'selesai' => 1],
        'pengajuanBerjalan' => $semua->take(1),
        'pengajuanSelesai' => $semua->skip(1),
    ]))->name('dashboard');

    Route::get('/ajukan', function () use ($mahasiswa) {
        $j1 = new JenisSurat(['nama' => 'Surat Keterangan Aktif Kuliah', 'deskripsi' => 'Untuk syarat beasiswa, tunjangan orang tua, atau BPJS.']);
        $j1->id = 1;
        $j2 = new JenisSurat(['nama' => 'Surat Pengantar Penelitian', 'deskripsi' => 'Izin pengambilan data tugas akhir atau kerja praktik.']);
        $j2->id = 2;

        return view('mahasiswa.ajukan', [
            'daftarJenisSurat' => collect([$j1, $j2]),
            'mahasiswa' => $mahasiswa,
        ]);
    })->name('ajukan.create');
    Route::post('/ajukan', fn () => redirect()->route('mahasiswa.dashboard')->with('sukses', 'Simulasi pengajuan terkirim.'))->name('ajukan.store');

    Route::get('/riwayat', fn () => view('mahasiswa.riwayat', ['daftarPengajuan' => $semua]))->name('riwayat.index');
    Route::get('/riwayat/{id}', fn () => view('mahasiswa.detail', ['pengajuan' => $semua->first()]))->name('riwayat.show');
    Route::get('/profil', fn () => view('mahasiswa.profil', compact('mahasiswa')))->name('profil.edit');
    Route::put('/profil', fn () => back()->with('sukses', 'Simulasi profil diperbarui.'))->name('profil.update');
    Route::get('/surat/{id}/unduh', fn () => back()->with('sukses', 'Simulasi unduh PDF.'))->name('surat.unduh');
});

// 6. Area Khusus Staf (Dilindungi Middleware auth & role:staf)[cite: 2, 4]
Route::middleware(['auth', 'role:staf'])->prefix('staf')->name('staf.')->group(function () use ($buatDummyPengajuan) {
    $semua = $buatDummyPengajuan();

    Route::get('/', fn () => view('staf.index', [
        'statistik' => ['menunggu_validasi' => 1, 'siap_ttd' => 0, 'ditolak' => 0, 'selesai' => 1],
        'daftarPengajuan' => $semua,
    ]))->name('index');

    Route::get('/{id}', fn () => view('staf.detail', ['pengajuan' => $semua->first()]))->name('show');
    Route::post('/{id}/setujui', fn () => redirect()->route('staf.index')->with('sukses', 'Simulasi surat disetujui.'))->name('setujui');
    Route::post('/{id}/tolak', fn () => redirect()->route('staf.index')->with('sukses', 'Simulasi pengajuan ditolak.'))->name('tolak');
});

// 7. Area Khusus Pimpinan (Dilindungi Middleware auth & role:pimpinan)
Route::middleware(['auth', 'role:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
    Route::get('/', [TandaTanganController::class, 'index'])->name('index');
    Route::get('/{pengajuan}', [TandaTanganController::class, 'show'])->name('show');
    Route::post('/{pengajuan}/tandatangani', [TandaTanganController::class, 'tandatangani'])->name('tandatangani');
});

Route::middleware('auth')->group(function () {
    Route::get('/api/jenis-surat/{id}/syarat', fn () => response()->json([
        'syarat' => [
            ['id' => 1, 'nama_dokumen' => 'Kartu Rencana Studi (KRS) Semester Berjalan', 'format_diizinkan' => 'PDF/JPG', 'keterangan' => 'Wajib bertanda tangan PA', 'maks_ukuran_kb' => 2048, 'wajib' => true],
            ['id' => 2, 'nama_dokumen' => 'Kartu Tanda Mahasiswa (KTM)', 'format_diizinkan' => 'PDF/JPG/PNG', 'keterangan' => 'Scan jelas', 'maks_ukuran_kb' => 2048, 'wajib' => true],
        ],
    ]))->name('api.jenis-surat.syarat');

    Route::get('/lampiran/{id}/unduh', fn () => back()->with('sukses', 'Simulasi lihat lampiran.'))->name('lampiran.unduh');
});

Route::view('/403', 'errors.403')->name('errors.403');