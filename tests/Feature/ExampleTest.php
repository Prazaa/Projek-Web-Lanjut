<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_mahasiswa_login_diarahkan_ke_dashboard_mahasiswa(): void
    {
        $mahasiswa = Pengguna::create([
            'nama' => 'Mahasiswa Uji',
            'email' => 'uji.mahasiswa@student.unri.ac.id',
            'role' => 'mahasiswa',
            'aktif' => true,
        ]);

        $this->actingAs($mahasiswa)
            ->get('/')
            ->assertRedirect(route('mahasiswa.dashboard'));
    }

    public function test_staf_login_diarahkan_ke_halaman_staf(): void
    {
        $staf = Pengguna::create([
            'nama' => 'Staf Uji',
            'email' => 'uji.staf@unri.ac.id',
            'role' => 'staf',
            'aktif' => true,
        ]);

        $this->actingAs($staf)
            ->get('/')
            ->assertRedirect(route('staf.index'));
    }

    public function test_pimpinan_login_diarahkan_ke_halaman_pimpinan(): void
    {
        $pimpinan = Pengguna::create([
            'nama' => 'Pimpinan Uji',
            'email' => 'uji.pimpinan@unri.ac.id',
            'role' => 'pimpinan',
            'aktif' => true,
        ]);

        $this->actingAs($pimpinan)
            ->get('/')
            ->assertRedirect(route('pimpinan.index'));
    }
}