@extends('layouts.app')

@section('title', 'Profil — SIMKA')

@section('content')
  <div class="mx-auto max-w-720 px-5 pb-16 pt-10 pt-md-14">
    <h1 class="page-title">Profil</h1>
    <p class="page-lead">Data ini dipakai otomatis pada setiap pengajuan. Lengkapi sebelum mengajukan surat.</p>

    <form method="POST" action="{{ route('mahasiswa.profil.update') }}" class="d-grid gap-6 pt-8" novalidate>
      @csrf
      @method('PUT')

      @if ($errors->any())
        <div class="error-summary is-visible" role="alert" tabindex="-1">
          <p class="fs-7 fw-semibold">Profil belum bisa disimpan. Perbaiki bagian berikut:</p>
          <ul class="mt-2 list-disc pl-5 fs-7">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="field">
        <label class="field-label" for="nama">Nama lengkap <span class="req" aria-hidden="true">*</span></label>
        <input class="input" id="nama" type="text" value="{{ auth()->user()->nama }}" disabled>
        <p class="field-help">Diambil dari akun kampus dan tidak dapat diubah di sini.</p>
      </div>

      <div class="d-grid gap-6 grid-cols-sm-2">
        <div class="field">
          <label class="field-label" for="nim">NIM <span class="req" aria-hidden="true">*</span></label>
          <input class="input num-tab" id="nim" name="nim" type="text" inputmode="numeric" value="{{ old('nim', $mahasiswa?->nim) }}" required>
        </div>
        <div class="field">
          <label class="field-label" for="semester">Semester Aktif <span class="req" aria-hidden="true">*</span></label>
          <input class="input num-tab" id="semester" name="semester" type="number" min="1" max="14" value="{{ old('semester', $mahasiswa?->semester ?? 1) }}" required>
        </div>
      </div>

      <div class="d-grid gap-6 grid-cols-sm-2">
        <div class="field">
          <label class="field-label" for="tempat_lahir">Tempat lahir <span class="req" aria-hidden="true">*</span></label>
          <input class="input" id="tempat_lahir" name="tempat_lahir" type="text" value="{{ old('tempat_lahir', $mahasiswa?->tempat_lahir) }}" required>
        </div>
        <div class="field">
          <label class="field-label" for="tanggal_lahir">Tanggal lahir <span class="req" aria-hidden="true">*</span></label>
          <input class="input num-tab" id="tanggal_lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir', $mahasiswa?->tanggal_lahir?->format('Y-m-d')) }}" required>
        </div>
      </div>

      <div class="field">
        <label class="field-label" for="program_studi">Program studi <span class="req" aria-hidden="true">*</span></label>
        <select class="input input-select" id="program_studi" name="program_studi" required>
          <option value="" disabled @selected(!old('program_studi', $mahasiswa?->program_studi))>Pilih program studi FMIPA</option>
          @foreach (['S1 Sistem Informasi', 'S1 Ilmu Komputer', 'S1 Matematika', 'S1 Statistika', 'S1 Fisika', 'S1 Kimia', 'S1 Biologi'] as $prodi)
            <option value="{{ $prodi }}" @selected(old('program_studi', $mahasiswa?->program_studi) === $prodi)>{{ $prodi }}</option>
          @endforeach
        </select>
      </div>

      <div class="field">
        <label class="field-label" for="no_hp">Nomor WhatsApp aktif <span class="req" aria-hidden="true">*</span></label>
        <input class="input num-tab" id="no_hp" name="no_hp" type="tel" inputmode="tel" value="{{ old('no_hp', $mahasiswa?->no_hp) }}" required>
        <p class="field-help">Staf kemahasiswaan memakai nomor ini bila berkas perlu diperiksa ulang.</p>
      </div>

      <div class="field">
        <label class="field-label" for="alamat">Alamat saat ini <span class="req" aria-hidden="true">*</span></label>
        <textarea class="input input-textarea" id="alamat" name="alamat" required>{{ old('alamat', $mahasiswa?->alamat) }}</textarea>
      </div>

      <div class="d-flex flex-column-reverse gap-3 flex-sm-row align-items-sm-center justify-content-sm-end">
        <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">Batal</a>
        <button class="btn btn-primary" type="submit">Simpan Profil</button>
      </div>
    </form>
  </div>
@endsection