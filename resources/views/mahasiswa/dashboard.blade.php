
@extends('layouts.app')

@section('title', 'Dashboard — SIMKA')

@section('content')
  <div class="mx-auto max-w-1200 px-5 pb-8 pt-10 pt-md-14">
    <h1 class="page-title">Halo, {{ Str::before(auth()->user()->nama, ' ') }}.</h1>

    {{-- Ringkasan pengajuan --}}
    <section aria-label="Ringkasan pengajuan" class="stat-strip stat-strip--3 mt-8">
      <div class="stat-strip__item">
        <p class="stat-label">Pengajuan</p>
        <p class="stat-num num-tab">{{ $statistik['total'] ?? 0 }}</p>
      </div>

      <div class="stat-strip__item">
        <p class="stat-label">Diproses</p>
        <p class="stat-num num-tab">{{ $statistik['diproses'] ?? 0 }}</p>
      </div>

      <div class="stat-strip__item">
        <p class="stat-label">Selesai</p>
        <p class="stat-num num-tab">{{ $statistik['selesai'] ?? 0 }}</p>
      </div>
    </section>

    {{-- Tombol aksi --}}
    <section aria-labelledby="cta-ajukan" class="cta-panel mt-6 rounded-16 px-5 py-6 px-md-8 py-md-7">
      <div class="d-flex flex-column gap-6 flex-md-row align-items-md-center justify-content-md-between">
        <div class="max-w-54ch">
          <h2 id="cta-ajukan" class="mt-2 fs-4 fw-bold tracking-n1 text-white fs-md-28">
            Ajukan surat baru
          </h2>
        </div>

        <div class="d-flex flex-column gap-2 flex-sm-row flex-md-shrink-0">
          <a href="{{ route('mahasiswa.ajukan.create') }}" class="btn btn-primary">
            Ajukan Surat
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round"
                 aria-hidden="true">
              <path d="M5 12h14m-6-6 6 6-6 6"/>
            </svg>
          </a>

          <a href="{{ route('mahasiswa.profil.edit') }}" class="btn btn-on-dark">
            Perbarui Profil
          </a>
        </div>
      </div>
    </section>

    <div class="mt-10 d-grid gap-6">

      {{-- Pengajuan berjalan --}}
      <section aria-labelledby="judul-berjalan" class="card">
        <div class="d-flex align-items-center justify-content-between gap-4 border-bottom border-fog px-5 py-4 px-md-6">
          <h2 id="judul-berjalan" class="text-title">
            Pengajuan berjalan
          </h2>
        </div>

        @if ($pengajuanBerjalan->isEmpty())
          <p class="px-5 py-6 fs-7 text-slate px-md-6">
            Belum ada pengajuan yang sedang diproses.
          </p>
        @else
          <ul class="d-grid">
            @foreach ($pengajuanBerjalan as $item)
              <li>
                <a class="list-row list-row--dash"
                   href="{{ route('mahasiswa.detail', $item->kode_pengajuan) }}">

                  <span class="min-w-0">
                    <span class="d-block fs-6 fw-semibold text-obs">
                      {{ $item->jenisSurat->nama }}
                    </span>

                    <span class="d-block fs-8">
                      Diajukan
                      {{ $item->diajukan_pada->translatedFormat('d M Y') }}

                      <span class="d-none d-md-inline">
                        , {{ $item->diajukan_pada->format('H.i') }} WIB
                      </span>

                      <span class="d-md-none">
                        · {{ $item->lampiran_count }} lampiran
                      </span>
                    </span>
                  </span>

                  <span class="fs-8 text-slate">
                    {{ $item->lampiran_count }} lampiran
                  </span>

                  <span class="badge {{ $item->status->warnaBadge() }}">
                    {{ $item->status->label() }}
                  </span>

                  <span class="row-action fs-7 fw-medium text-forest text-decoration-underline justify-self-md-end">
                    Detail
                  </span>
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </section>

      {{-- Surat selesai --}}
      <section aria-labelledby="judul-selesai" class="card">
        <h2 id="judul-selesai" class="border-bottom border-fog px-5 py-4 text-title px-md-6">
          Surat siap diunduh
        </h2>

        @if ($pengajuanSelesai->isEmpty())
          <p class="px-5 py-6 fs-7 text-slate px-md-6">
            Belum ada surat yang selesai diterbitkan.
          </p>
        @else
          <ul class="d-grid">
            @foreach ($pengajuanSelesai as $item)
              <li>
                <div class="list-row list-row--dash list-row--dash-done">

                  <span class="min-w-0">
                    <span class="d-block fs-6 fw-semibold text-obs">
                      {{ $item->jenisSurat->nama }}
                    </span>

                    <span class="d-block num-tab fs-8">
                      No. {{ $item->tandaTangan?->nomor_surat ?? '-' }}
                      ·
                      {{ ($item->selesai_pada ?? $item->updated_at)->translatedFormat('d M Y') }}
                    </span>
                  </span>

                  <span class="fs-8 text-slate">
                    {{ $item->lampiran_count }} lampiran
                  </span>

                  <span class="badge {{ $item->status->warnaBadge() }}">
                    {{ $item->status->label() }}
                  </span>

                  <a class="btn btn-secondary btn-sm justify-self-md-end"
                     href="{{ route('mahasiswa.detail', $item->kode_pengajuan) }}">
                    Lihat Surat
                  </a>

                </div>
              </li>
            @endforeach
          </ul>
        @endif
      </section>

    </div>
  </div>
@endsection
