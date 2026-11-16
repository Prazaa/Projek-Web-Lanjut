@extends('layouts.app')

@section('title', 'Detail Pengajuan — SIMKA')
@section('meta_description', 'Detail status, audit log, data pemohon, dan lampiran pengajuan surat kemahasiswaan di SIMKA FMIPA Universitas Riau.')

@section('content')
  <div class="mx-auto max-w-720 px-5 pb-20 pt-6 pt-md-8">
    <a href="{{ route('mahasiswa.riwayat.index') }}" class="d-inline-flex align-items-center gap-2 fs-7 fw-medium text-forest hover-text-primary">
      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
      Kembali ke Riwayat Pengajuan
    </a>

    <div class="mt-4 d-flex flex-column gap-4 flex-sm-row align-items-sm-start justify-content-sm-between">
      <div>
        <div class="d-flex flex-wrap align-items-center gap-3">
          <h1 class="page-title">{{ $pengajuan->jenisSurat->nama }}</h1>
          <span class="badge {{ $pengajuan->status->warnaBadge() }}">{{ $pengajuan->status->label() }}</span>
        </div>
        <p class="num-tab mt-2 fs-7 text-slate">{{ $pengajuan->kode_pengajuan }} <span aria-hidden="true">•</span> Diajukan pada {{ $pengajuan->diajukan_pada->translatedFormat('d M Y, H:i') }} WIB <span aria-hidden="true">•</span> Memuat {{ $pengajuan->lampiran->count() }} berkas lampiran</p>
      </div>
      @if ($pengajuan->status->value === 'selesai')
    <a href="{{ route('mahasiswa.surat.unduh', $pengajuan->kode_pengajuan) }}" class="btn btn-primary btn-sm flex-shrink-0 align-self-start">
        Unduh PDF Surat
    </a>
@endif
    </div>

    <section aria-labelledby="judul-riwayat-status" class="card mt-6 p-5 p-md-6">
      <h2 id="judul-riwayat-status" class="text-title">Riwayat status</h2>
      <hr class="mt-4 border-fog">
      <div class="mt-5">
        <ul class="d-grid gap-5 fs-7">
          @foreach ($pengajuan->alurPengajuan as $alur)
            @php
              $dotClass = match ($alur->status_ke) {
                  'ditolak' => 'bg-alarm',
                  'revisi', 'siap_ttd' => 'bg-warning',
                  default => 'bg-forest',
              };
            @endphp
            <li class="d-grid grid-cols-dot align-items-start gap-3">
              <span class="mt-1-5 h-2-5 w-2-5 rounded-pill {{ $dotClass }}" aria-hidden="true"></span>
              <div>
                <p>
                  <b class="text-obs">{{ \App\Enums\StatusPengajuan::tryFrom($alur->status_ke)?->label() ?? ucfirst($alur->status_ke) }}</b>
                  <span class="num-tab mt-0-5 d-block fs-8 fw-normal text-slate">{{ $alur->dibuat_pada->translatedFormat('d M Y, H:i') }} WIB</span>
                </p>
                @if ($alur->keterangan)
                  <p class="mt-1 leading-relaxed text-slate @if($alur->status_ke === 'ditolak') border-start border-2 border-alarm pl-3 @endif">{{ $alur->keterangan }}</p>
                @endif
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    </section>

    <section aria-labelledby="judul-pemohon" class="card mt-6 p-5 p-md-6">
      <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
        <h2 id="judul-pemohon" class="text-title">Data pemohon</h2>
        <span class="fs-8 fw-medium text-slate">Data terkunci saat pengajuan</span>
      </div>
      <hr class="mt-4 border-fog">
      <dl class="mt-5 d-grid gap-x-8 gap-y-5 fs-7 grid-cols-sm-2">
        <div>
          <dt class="fs-8 fw-medium text-slate">Nama Lengkap Mahasiswa</dt>
          <dd class="mt-1 fw-medium text-obs">{{ $pengajuan->nama_snapshot }}</dd>
        </div>
        <div>
          <dt class="fs-8 fw-medium text-slate">Nomor Induk Mahasiswa (NIM)</dt>
          <dd class="num-tab mt-1 fw-medium text-obs">{{ $pengajuan->nim_snapshot }}</dd>
        </div>
        <div>
          <dt class="fs-8 fw-medium text-slate">Program Studi</dt>
          <dd class="mt-1 fw-medium text-obs">{{ $pengajuan->prodi_snapshot }}</dd>
        </div>
        <div>
          <dt class="fs-8 fw-medium text-slate">Semester Saat Pengajuan</dt>
          <dd class="num-tab mt-1 fw-medium text-obs">Semester {{ $pengajuan->semester_snapshot }}</dd>
        </div>
        <div class="col-span-sm-2">
          <dt class="fs-8 fw-medium text-slate">Keperluan Surat</dt>
          <dd class="mt-1 fw-medium text-obs">{{ $pengajuan->keperluan }}</dd>
        </div>
      </dl>
    </section>

    <section aria-labelledby="judul-lampiran" class="card mt-6 p-5 p-md-6">
      <h2 id="judul-lampiran" class="text-title">Lampiran pengajuan</h2>
      <hr class="mt-4 border-fog">
      <div class="mt-4 d-grid gap-3">
        @forelse ($pengajuan->lampiran as $berkas)
          <div class="d-flex flex-column gap-3 rounded-10 border border-fog p-3-5 flex-sm-row align-items-sm-center gap-sm-4">
            <div class="d-flex min-w-0 flex-1 align-items-center gap-4">
              <span class="d-grid h-11 w-9 flex-shrink-0 place-items-center rounded-6 bg-linen fs-8 fw-bold tracking-wide text-forest" aria-hidden="true">{{ strtoupper(pathinfo($berkas->nama_asli, PATHINFO_EXTENSION) ?: 'FILE') }}</span>
              <span class="min-w-0 flex-1">
                <span class="d-block text-break fs-7 fw-medium text-obs">{{ $berkas->nama_asli }}</span>
                <span class="num-tab d-block fs-8 text-slate">{{ number_format($berkas->ukuran_byte / 1048576, 2) }} MB <span aria-hidden="true">•</span> Diunggah {{ $berkas->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
              </span>
            </div>
            <a class="btn btn-secondary btn-sm w-100 flex-shrink-0 w-sm-auto" href="{{ route('lampiran.unduh', $berkas) }}" target="_blank">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
              Lihat Berkas
            </a>
          </div>
        @empty
          <p class="fs-7 text-slate">Tidak ada berkas lampiran pada pengajuan ini.</p>
        @endforelse
      </div>
      <p class="field-help mt-3">Lampiran disimpan di penyimpanan privat (NFR-02). Hanya pemilik pengajuan dan staf kemahasiswaan yang dapat membukanya.</p>
    </section>
  </div>
@endsection