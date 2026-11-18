@extends('layouts.app')

@section('title', 'Akses Ditolak — SIMKA')

@section('content')
  <div class="mx-auto max-w-480 px-5 pb-16 pt-16">
    <section class="card p-8 text-center">
      <svg class="mx-auto h-12 w-12 text-fog" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6z"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
      <h1 class="page-title page-title--sm">Anda tidak memiliki akses</h1>
      <p class="page-lead mx-auto text-center">Akun ini tidak memiliki izin untuk membuka halaman ini.</p>
      <p>Periksa peran akun yang sedang digunakan.</p>
      <div class="mt-6 d-flex flex-column gap-3 flex-sm-row justify-content-sm-center">
        <a class="btn btn-secondary" href="{{ route('login') }}">Masuk dengan akun lain</a>
      </div>
    </section>
  </div>
@endsection