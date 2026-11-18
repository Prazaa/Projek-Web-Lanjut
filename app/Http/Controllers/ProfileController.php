<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('mahasiswa.profil', [
            'mahasiswa' => $request->user()->mahasiswa,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $pengguna = $request->user();
        $mahasiswa = $pengguna->mahasiswa;

        $data = $request->validate([
            'nim' => [
                'required',
                'string',
                'max:30',
                Rule::unique('mahasiswa', 'nim')->ignore($mahasiswa?->id),
            ],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'program_studi' => [
                'required',
                'string',
                Rule::in([
                    'S1 Sistem Informasi',
                    'D3 Manajemen Informatika',
                    'S1 Matematika',
                    'S1 Statistika',
                    'S1 Fisika',
                    'S1 Kimia',
                    'S1 Biologi',
                ]),
            ],
            'no_hp' => ['required', 'string', 'max:25'],
            'alamat' => ['required', 'string', 'max:500'],
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah terdaftar pada akun mahasiswa lain.',
            'semester.required' => 'Semester aktif wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'program_studi.required' => 'Program studi wajib dipilih.',
            'no_hp.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'alamat.required' => 'Alamat saat ini wajib diisi.',
        ]);

        Mahasiswa::updateOrCreate(
            ['pengguna_id' => $pengguna->id],
            $data
        );

        return redirect()
            ->route('mahasiswa.profil.edit')
            ->with('success', 'Profil mahasiswa berhasil diperbarui.');
    }
}