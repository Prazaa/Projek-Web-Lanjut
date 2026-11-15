@extends('layouts.app')

@section('title', 'Antrean Validasi — SIMKA')

@section('content')
  <div class="mx-auto max-w-1200 px-5 pb-16 pt-10 pt-md-14">
    <h1 class="page-title">Antrean validasi</h1>
    <p class="page-lead">Periksa data pemohon dan lampiran. Setujui atau tolak dengan alasan tertulis.</p>

    <section aria-label="Ringkasan status pengajuan" class="stat-strip stat-strip--4 mt-8">
      <div class="stat-strip__item">
        <p class="stat-label">Menunggu validasi</p>
        <p class="stat-num num-tab">{{ $statistik['menunggu_validasi'] ?? 0 }}</p>
      </div>
      <div class="stat-strip__item">
        <p class="stat-label">Menunggu tanda tangan</p>
        <p class="stat-num num-tab">{{ $statistik['siap_ttd'] ?? 0 }}</p>
      </div>
      <div class="stat-strip__item">
        <p class="stat-label">Ditolak</p>
        <p class="stat-num num-tab">{{ $statistik['ditolak'] ?? 0 }}</p>
      </div>
      <div class="stat-strip__item">
        <p class="stat-label">Selesai</p>
        <p class="stat-num num-tab">{{ $statistik['selesai'] ?? 0 }}</p>
      </div>
    </section>

    <div class="mt-10" data-filter data-target="#daftar-antrean [data-status]" data-count="#staf-count" data-empty="#staf-empty">
      <p class="fs-7 text-slate" role="status">Tampil: <b id="staf-count" class="num-tab">{{ $daftarPengajuan->count() }}</b> pengajuan</p>
      <div class="mt-3 d-flex flex-wrap gap-2" role="group" aria-label="Saring pengajuan menurut status">
        <button class="filter-chip" data-value="semua" aria-pressed="true">Semua</button>
        <button class="filter-chip" data-value="diajukan" aria-pressed="false">Validasi</button>
        <button class="filter-chip" data-value="siap_ttd" aria-pressed="false">Tanda tangan</button>
        <button class="filter-chip" data-value="ditolak" aria-pressed="false">Ditolak</button>
        <button class="filter-chip" data-value="selesai" aria-pressed="false">Selesai</button>
      </div>
    </div>

    <div id="daftar-antrean" class="card mt-4" @if($daftarPengajuan->isEmpty()) hidden @endif>
      <div class="list-head list-head--staf" aria-hidden="true">
        <span>Pemohon</span><span>Jenis surat</span><span>Waktu</span><span>Status</span><span>Aksi</span>
      </div>
      <ul class="d-grid">
        @foreach ($daftarPengajuan as $item)
          @php
            $filterKey = in_array($item->status->value, ['diajukan', 'diproses']) ? 'diajukan' : $item->status->value;
          @endphp
          <li>
            <div class="list-row list-row--staf" data-status="{{ $filterKey }}">
              <span class="min-w-0">
                <span class="d-block fs-6 fw-semibold text-obs">{{ $item->nama_snapshot }}</span>
                <span class="num-tab d-block fs-8">{{ $item->nim_snapshot }} • {{ $item->prodi_snapshot }}</span>
              </span>
              <span class="min-w-0 fs-7">{{ $item->jenisSurat->nama }}</span>
              <span class="num-tab fs-8 text-slate">{{ $item->diajukan_pada->translatedFormat('d M Y, H.i') }} WIB • {{ $item->lampiran->count() }} lampiran</span>
              <span class="badge {{ $item->status->warnaBadge() }}">{{ $item->status->label() }}</span>
              @if (in_array($item->status->value, ['diajukan', 'diproses']))
                <a class="btn btn-secondary btn-sm justify-self-md-end" href="{{ route('staf.show', $item) }}">Tinjau</a>
              @else
                <a class="fs-7 fw-medium text-forest hover-underline justify-self-md-end" href="{{ route('staf.show', $item) }}">Detail</a>
              @endif
            </div>
          </li>
        @endforeach
      </ul>
    </div>

    <div id="staf-empty" class="d-grid place-items-center px-5 py-24 text-center" @if($daftarPengajuan->isNotEmpty()) hidden @endif>
      <svg class="h-12 w-12 text-fog" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/></svg>
      <p class="mt-4 fs-18 fw-semibold text-obs">Tidak ada pengajuan pada filter ini</p>
      <p class="mt-1 max-w-42ch fs-7">Antrean akan terisi otomatis saat mahasiswa mengajukan surat.</p>
    </div>
  </div>
@endsection