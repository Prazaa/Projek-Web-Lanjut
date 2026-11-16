<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MahasiswaPengajuanController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\ValidasiController;
use App\Http\Controllers\VerifikasiController;
use Illuminate\Support\Facades\Route;

// Halaman utama: arahkan pengguna sesuai status login
Route::get('/', fn () => auth()->check()
    ? redirect((new AuthController)->ruteTujuanPeran(auth()->user()->role))
    : redirect()->route('login'));

// Autentikasi pengguna yang belum login
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/masuk', [AuthController::class, 'login'])
        ->name('login.post');

    Route::get('/auth/google', fn () => redirect()->route('login')
        ->withErrors(['sso' => 'Login Google SSO sedang disiapkan oleh Anggota 2.']))
        ->name('auth.google');
});

// Logout
Route::post('/keluar', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Pengalihan dashboard berdasarkan role
Route::get('/dashboard', function () {
    $pengguna = auth()->user();

    return match ($pengguna->role) {
        'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
        'staf' => redirect()->route('staf.index'),
        'pimpinan' => redirect()->route('pimpinan.index'),
        default => abort(403, 'Role pengguna tidak dikenali.'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Verifikasi surat publik
Route::get('/verifikasi', [VerifikasiController::class, 'index'])
    ->name('verifikasi.index');

// ==============================
// AREA MAHASISWA
// ==============================

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {

    Route::get('/mahasiswa/dashboard', [MahasiswaPengajuanController::class, 'dashboard'])
        ->name('mahasiswa.dashboard');

    Route::view('/mahasiswa/profil', 'mahasiswa.profil')
        ->name('mahasiswa.profil.edit');

    Route::get('/mahasiswa/ajukan', [MahasiswaPengajuanController::class, 'create'])
        ->name('mahasiswa.ajukan.create');

    Route::post('/mahasiswa/ajukan', [MahasiswaPengajuanController::class, 'store'])
        ->name('mahasiswa.ajukan.store');

    Route::get('/jenis-surat/{jenisSurat}/syarat', [MahasiswaPengajuanController::class, 'syarat'])
        ->name('api.jenis-surat.syarat');

    Route::get('/mahasiswa/riwayat', [MahasiswaPengajuanController::class, 'riwayat'])
    ->name('mahasiswa.riwayat.index');

    Route::get('/mahasiswa/pengajuan/{kodePengajuan}', [MahasiswaPengajuanController::class, 'detail'])
    ->name('mahasiswa.detail');

    Route::get('/mahasiswa/pengajuan/{kodePengajuan}/unduh', [MahasiswaPengajuanController::class, 'unduhSurat'])
    ->name('mahasiswa.surat.unduh');
});

// ==============================
// AREA STAF
// ==============================

Route::middleware(['auth', 'role:staf'])
    ->prefix('staf')
    ->name('staf.')
    ->group(function () {

        Route::get('/pengajuan', [ValidasiController::class, 'index'])
            ->name('index');

        Route::get('/pengajuan/{pengajuan}', [ValidasiController::class, 'show'])
            ->name('show');

        Route::post('/pengajuan/{pengajuan}/setujui', [ValidasiController::class, 'setujui'])
            ->name('setujui');

        Route::post('/pengajuan/{pengajuan}/tolak', [ValidasiController::class, 'tolak'])
            ->name('tolak');
    });

// ==============================
// AREA PIMPINAN
// ==============================

Route::middleware(['auth', 'role:pimpinan'])
    ->prefix('pimpinan')
    ->name('pimpinan.')
    ->group(function () {

        Route::get('/pengajuan', [PimpinanController::class, 'index'])
            ->name('index');

        Route::get('/pengajuan/{pengajuan}', [PimpinanController::class, 'show'])
            ->name('show');

        Route::post('/pengajuan/{pengajuan}/tandatangani', [PimpinanController::class, 'tandatangani'])
            ->name('tandatangani');
    });

// ==============================
// ROUTE LAMPIRAN DAN API
// ==============================

Route::middleware('auth')->group(function () {

    Route::get('/lampiran/{id}/unduh', fn () => back()
        ->with('sukses', 'Simulasi lihat lampiran.'))
        ->name('lampiran.unduh');

});

// Halaman akses ditolak
Route::view('/403', 'errors.403')
    ->name('errors.403');
