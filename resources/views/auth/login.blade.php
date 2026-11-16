@extends('layouts.app')

@section('title', 'Masuk Layanan — SIMKA')
@section('meta_description', 'Masuk ke layanan pengajuan surat kemahasiswaan FMIPA Universitas Riau. Mahasiswa memakai akun Google kampus, staf dan pimpinan memakai akun pengelola.')

@section('content')
  <div class="mx-auto max-w-520 px-5 pb-16 pt-10 pt-md-12">
    <h1 class="page-title">Masuk Layanan</h1>
    <p class="page-lead">Layanan surat kemahasiswaan FMIPA Universitas Riau</p>

    @if ($errors->has('sso'))
      <div class="alert alert-warn mt-6" role="alert">
        <p class="fs-7 fw-medium">{{ $errors->first('sso') }}</p>
      </div>
    @endif

    <section aria-labelledby="judul-mahasiswa" class="card mt-6 p-6 p-md-4">
      <button type="button"
    class="btn btn-primary btn-block px-4 fs-15"
    disabled>
    Masuk dengan Google UNRI (Belum Tersedia)
</button>
        <span class="d-grid h-5 w-5 place-items-center rounded-pill bg-white" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.1 6.1 29.3 4 24 4 13 4 4 13 4 24s9 20 20 20 20-9 20-20c0-1.2-.1-2.3-.4-3.5z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15.1 19 12 24 12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.1 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35.2 26.7 36 24 36c-5.3 0-9.7-3.4-11.3-8.1l-6.5 5C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.2-2.2 4.2-4.2 5.6l6.2 5.2C36.9 39.3 44 34 44 24c0-1.2-.1-2.3-.4-3.5z"/></svg>
        </span>
        Masuk dengan Google UNRI
      </a>
    </section>

    <section aria-labelledby="judul-staf" class="card mt-6 p-6 p-md-8">
      <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
        <h2 id="judul-staf" class="text-title">Staf &amp; Pimpinan</h2>
      </div>

      <form method="POST" action="{{ route('login.post') }}" class="mt-5 d-grid gap-4" novalidate>
        @csrf

        @if ($errors->any() && !$errors->has('sso'))
          <div class="error-summary is-visible" role="alert" tabindex="-1">
            <p class="fs-7 fw-semibold text-alarm">Perbaiki bagian berikut:</p>
            <ul class="mt-2 list-disc pl-5 fs-7">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="field">
          <label class="field-label" for="email">Alamat Email <span class="req" aria-hidden="true">*</span></label>
          <div class="position-relative">
            <svg class="pe-none position-absolute left-3-5 top-50 h-4 w-4 translate-middle-y text-pebble" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
            <input class="input pl-11" id="email" name="email" type="email" autocomplete="username" value="{{ old('email') }}" placeholder="nama@unri.ac.id" required>
          </div>
        </div>

        <div class="field">
          <div class="d-flex align-items-baseline justify-content-between gap-4">
            <label class="field-label" for="sandi">Kata Sandi <span class="req" aria-hidden="true">*</span></label>
          </div>
          <div class="position-relative">
            <svg class="pe-none position-absolute left-3-5 top-50 h-4 w-4 translate-middle-y text-pebble" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
            <input class="input pl-11" id="sandi" name="password" type="password" autocomplete="current-password" placeholder="••••••••" required>
          </div>
        </div>

        <button class="btn btn-secondary btn-block mt-1" type="submit">Masuk ke Panel Layanan</button>
      </form>
    </section>

    <p class="mt-8 text-center fs-7 leading-relaxed text-slate">Data diri Anda terlindungi dan terintegrasi langsung dengan database<br class="d-none d-sm-block"> kemahasiswaan FMIPA UNRI.</p>
  </div>
@endsection