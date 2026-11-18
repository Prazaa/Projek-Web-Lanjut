<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (blank(config('services.google.client_id')) || blank(config('services.google.client_secret'))) {
            return redirect()
                ->route('login')
                ->withErrors(['sso' => 'Konfigurasi Google Client ID atau Secret pada .env belum diisi.']);
        }

        return Socialite::driver('google')
            ->with(['hd' => 'student.unri.ac.id'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable) {
            return redirect()
                ->route('login')
                ->withErrors(['sso' => 'Autentikasi Google dibatalkan atau gagal diproses. Silakan coba lagi.']);
        }

        $email = strtolower(trim((string) $googleUser->getEmail()));

        if (! Str::endsWith($email, '@student.unri.ac.id')) {
            return redirect()
                ->route('login')
                ->withErrors(['sso' => 'Login SSO hanya diizinkan menggunakan email mahasiswa UNRI (@student.unri.ac.id).']);
        }

        $namaAsli = $googleUser->getName() ?: Str::before($email, '@');
        $namaBersih = trim((string) preg_replace('/\s*\d{6,}$/', '', $namaAsli));

        $pengguna = Pengguna::where('email', $email)->first();

        if (! $pengguna) {
            $pengguna = Pengguna::create([
                'nama' => $namaBersih,
                'email' => $email,
                'role' => 'mahasiswa',
                'aktif' => true,
            ]);
        } else {
            $pengguna->update([
                'nama' => $namaBersih,
            ]);
        }

        if (! $pengguna->aktif) {
            return redirect()
                ->route('login')
                ->withErrors(['sso' => 'Akun mahasiswa Anda sedang dinonaktifkan. Hubungi staf kemahasiswaan.']);
        }

        if ($pengguna->role !== 'mahasiswa') {
            return redirect()
                ->route('login')
                ->withErrors(['sso' => 'Akun staf dan pimpinan wajib masuk menggunakan email serta kata sandi.']);
        }

        Auth::login($pengguna, true);
        request()->session()->regenerate();

        if (! $pengguna->mahasiswa) {
            return redirect()
                ->route('mahasiswa.profil.edit')
                ->with('info', 'Selamat datang! Lengkapi biodata mahasiswa Anda terlebih dahulu sebelum mengajukan surat.');
        }

        return redirect()->intended(route('mahasiswa.dashboard'));
    }
}