<?php

use App\Http\Controllers\ValidasiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\MahasiswaPengajuanController;
use App\Http\Controllers\PimpinanController;

Route::redirect('/', '/login');

Route::get('/verifikasi', [VerifikasiController::class, 'index'])
    ->name('verifikasi.index');


Route::get('/dashboard', function () {
    $pengguna = auth()->user();

    return match ($pengguna->role) {
        'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
        'staf' => redirect()->route('staf.index'),
        'pimpinan' => redirect()->route('pimpinan.index'),
        default => abort(403, 'Role pengguna tidak dikenali.'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');


// Tambahan route untuk mahasiswa
Route::get('/mahasiswa/dashboard', [MahasiswaPengajuanController::class, 'dashboard'])
    ->middleware('auth')
    ->name('mahasiswa.dashboard');

Route::view('/mahasiswa/profil', 'mahasiswa.profil')
    ->middleware('auth')
    ->name('mahasiswa.profil.edit');

// Route pengajuan mahasiswa
Route::middleware('auth')->group(function () {
    Route::get('/mahasiswa/ajukan', [MahasiswaPengajuanController::class, 'create'])
        ->name('mahasiswa.ajukan.create');

    Route::post('/mahasiswa/ajukan', [MahasiswaPengajuanController::class, 'store'])
        ->name('mahasiswa.ajukan.store');

    Route::get('/jenis-surat/{jenisSurat}/syarat', [MahasiswaPengajuanController::class, 'syarat'])
        ->name('api.jenis-surat.syarat');
});

Route::view('/mahasiswa/riwayat', 'mahasiswa.riwayat')
    ->middleware('auth')
    ->name('mahasiswa.riwayat.index');

Route::get('/mahasiswa/riwayat/{nomor}', function ($nomor) {
    return view('mahasiswa.detail', compact('nomor'));
})
    ->middleware('auth')
    ->name('mahasiswa.detail');

// Route untuk staf kemahasiswaan
Route::middleware('auth')->prefix('staf')->name('staf.')->group(function () {
    Route::get('/pengajuan', [ValidasiController::class, 'index'])
        ->name('index');

    Route::get('/pengajuan/{pengajuan}', [ValidasiController::class, 'show'])
        ->name('show');

    Route::post('/pengajuan/{pengajuan}/setujui', [ValidasiController::class, 'setujui'])
        ->name('setujui');

    Route::post('/pengajuan/{pengajuan}/tolak', [ValidasiController::class, 'tolak'])
        ->name('tolak');
});

// Route untuk pimpinan fakultas
Route::middleware('auth')
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';