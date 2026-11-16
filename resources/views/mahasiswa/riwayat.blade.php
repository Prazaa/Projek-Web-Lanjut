
@extends('layouts.app')

@section('title', 'Riwayat Pengajuan — SIMKA')
@section('meta_description', 'Pantau seluruh status dan riwayat pengajuan surat kemahasiswaan Anda di SIMKA FMIPA Universitas Riau.')

@section('content')
  <div class="mx-auto max-w-1200 px-5 pb-20 pt-6 pt-md-8">
    <nav aria-label="Breadcrumb" class="fs-8 text-slate">
      <ol class="d-flex flex-wrap align-items-center gap-2">
        <li><a class="hover-text-forest" href="{{ route('mahasiswa.dashboard') }}">Portal Mahasiswa</a></li>
        <li aria-hidden="true">/</li>
        <li aria-current="page" class="text-charcoal">Riwayat Pengajuan</li>
      </ol>
    </nav>

    <div class="mt-3 d-flex flex-column gap-4 flex-md-row align-items-md-start justify-content-md-between">
      <div>
        <h1 class="page-title">Riwayat Pengajuan Surat</h1>
      </div>
      <a href="{{ route('mahasiswa.ajukan.create') }}" class="btn btn-primary w-100 flex-shrink-0 w-sm-auto align-self-md-start">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
        Buat Pengajuan Baru
      </a>
    </div>

    <div class="mt-6 d-flex flex-column gap-3 flex-lg-row flex-lg-nowrap align-items-lg-center justify-content-lg-between"
         data-filter data-target="#daftar-riwayat [data-status]" data-empty="#riwayat-empty">
      <div class="d-flex flex-wrap gap-2 flex-lg-nowrap" role="group" aria-label="Saring pengajuan menurut status">
        <button class="filter-chip" data-value="semua" aria-pressed="true">Semua <span class="chip-count">{{ $daftarPengajuan->count() }}</span></button>
        <button class="filter-chip" data-value="diajukan" aria-pressed="false">Validasi <span class="chip-count">{{ $daftarPengajuan->whereIn('status.value', ['diajukan', 'diproses'])->count() }}</span></button>
        <button class="filter-chip" data-value="siap_ttd" aria-pressed="false">Menunggu Tanda Tangan <span class="chip-count">{{ $daftarPengajuan->where('status.value', 'siap_ttd')->count() }}</span></button>
        <button class="filter-chip" data-value="ditolak" aria-pressed="false">Ditolak <span class="chip-count">{{ $daftarPengajuan->where('status.value', 'ditolak')->count() }}</span></button>
        <button class="filter-chip" data-value="selesai" aria-pressed="false">Selesai <span class="chip-count">{{ $daftarPengajuan->where('status.value', 'selesai')->count() }}</span></button>
      </div>
    </div>

    <ul id="daftar-riwayat" class="mt-5 border-top border-fog" @if($daftarPengajuan->isEmpty()) hidden @endif>
      @foreach ($daftarPengajuan as $item)
        @php
          $filterKey = in_array($item->status->value, ['diajukan', 'diproses']) ? 'diajukan' : $item->status->value;
        @endphp
        <li data-status="{{ $filterKey }}" class="border-bottom border-fog py-5">
          <div class="d-flex flex-column gap-3 flex-md-row align-items-md-start justify-content-md-between">
            <div class="min-w-0">
              <p class="num-tab fs-8 text-slate">
                @if ($item->status->value === 'selesai' && $item->tandaTangan)
                  No. {{ $item->tandaTangan->nomor_surat }} · Terbit {{ $item->tandaTangan->ditandatangani_pada->translatedFormat('d M Y') }}
                @else
                  {{ $item->kode_pengajuan }} · {{ $item->jenisSurat->kode }}
                @endif
              </p>
              <h2 class="mt-1 fs-6 fw-semibold text-obs">{{ $item->jenisSurat->nama }}</h2>
              <p class="mt-1 fs-8 text-slate">Diajukan {{ $item->diajukan_pada->translatedFormat('d M Y, H.i') }} WIB · {{ $item->lampiran->count() }} lampiran</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-4 flex-md-shrink-0 justify-content-md-end">
              <span class="badge {{ $item->status->warnaBadge() }}">{{ $item->status->label() }}</span>

              @if ($item->status->value === 'selesai')
                <a class="btn btn-secondary btn-sm"
                   href="{{ route('mahasiswa.surat.unduh', $item->kode_pengajuan) }}">
                  Unduh PDF
                </a>
              @endif

              <a class="fs-7 fw-medium text-forest hover-underline"
                 href="{{ route('mahasiswa.detail', $item->kode_pengajuan) }}">
                Detail
              </a>
            </div>
          </div>
        </li>
      @endforeach
    </ul>

    <div id="riwayat-empty" class="d-grid place-items-center px-5 py-24 text-center" @if($daftarPengajuan->isNotEmpty()) hidden @endif>
      <svg class="h-12 w-12 text-fog" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/></svg>
      <p class="mt-4 fs-18 fw-semibold text-obs">Belum ada pengajuan pada filter ini</p>
      <p class="mt-1 max-w-42ch fs-7 text-slate">Semua pengajuan surat Anda akan muncul di daftar ini beserta statusnya.</p>
      <a href="{{ route('mahasiswa.ajukan.create') }}" class="btn btn-secondary mt-6">Ajukan Surat Pertama</a>
    </div>
  </div>
@endsection
