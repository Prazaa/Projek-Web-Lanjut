@extends('layouts.app')

@section('title', 'Menunggu Tanda Tangan — SIMKA')

@section('content')
  <div class="mx-auto max-w-1200 px-5 pb-16 pt-10 pt-md-14">
    <h1 class="page-title">Menunggu tanda tangan</h1>
    <p class="page-lead">Pengajuan berikut sudah lolos validasi staf. Tanda tangani untuk menerbitkan surat.</p>

    <section aria-label="Ringkasan" class="stat-strip stat-strip--2 mt-8">
      <div class="stat-strip__item">
        <p class="stat-label">Menunggu tanda tangan</p>
        <p class="stat-num num-tab">{{ $daftarSiapTtd->count() }}</p>
      </div>
      <div class="stat-strip__item">
        <p class="stat-label">Terbit 30 hari terakhir</p>
        <p class="stat-num num-tab">{{ $jumlahTerbitBulanIni ?? 0 }}</p>
      </div>
    </section>

    <div class="card mt-8">
      <div class="list-head list-head--pimpinan" aria-hidden="true">
        <span>Pemohon</span><span>Jenis surat</span><span>Kode</span><span>Status</span><span>Aksi</span>
      </div>
      @if ($daftarSiapTtd->isEmpty())
        <p class="px-5 py-8 text-center fs-7 text-slate">Tidak ada surat yang menunggu tanda tangan saat ini.</p>
      @else
        <ul class="d-grid">
          @foreach ($daftarSiapTtd as $item)
            <li>
              <div class="list-row list-row--pimpinan">
                <span class="min-w-0">
                  <span class="d-block fs-6 fw-semibold text-obs">{{ $item->nama_snapshot }}</span>
                  <span class="num-tab d-block fs-8">{{ $item->nim_snapshot }} • {{ $item->prodi_snapshot }}</span>
                </span>
                <span class="min-w-0 fs-7">{{ $item->jenisSurat->nama }}</span>
                <span class="num-tab fs-8 text-slate">{{ $item->kode_pengajuan }}</span>
                <span class="badge {{ $item->status->warnaBadge() }}">{{ $item->status->label() }}</span>
                <a class="btn btn-secondary btn-sm justify-self-md-end" href="{{ route('pimpinan.show', $item) }}">
                  <span class="d-sm-none">Tinjau</span><span class="d-none d-sm-inline">Tinjau &amp; Tanda Tangani</span>
                </a>
              </div>
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>
@endsection