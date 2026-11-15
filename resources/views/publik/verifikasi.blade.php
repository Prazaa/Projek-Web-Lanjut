@extends('layouts.app')

@section('title', 'Cek Keaslian Surat — SIMKA')

@section('content')
  <div class="mx-auto max-w-560 px-5 pb-16 pt-10 pt-md-14">
    <h1 class="page-title">Cek Keaslian Surat</h1>
    <p class="page-lead">Terbuka untuk publik. Masukkan nomor surat atau kode verifikasi pada dokumen.</p>

    <form method="GET" action="{{ route('verifikasi.index') }}" class="mt-8 d-grid gap-3">
      <div class="d-flex flex-column gap-3 flex-sm-row">
        <div class="field flex-1">
          <label class="visually-hidden" for="q">Nomor surat atau kode verifikasi</label>
          <input class="input num-tab" id="q" name="q" type="text" value="{{ request('q') }}" placeholder="001/KM.00.01/FMIPA/2026 atau SIMKA-2026-..." required>
        </div>
        <button class="btn btn-primary" type="submit">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
          Cek
        </button>
      </div>
    </form>

    @if (request()->filled('q'))
      @if ($hasil)
        <section class="mt-6">
          <div class="card p-5 p-md-6">
            <div class="badge badge-success"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6z"/><path d="M9 12l2 2 4-4"/></svg>Terdaftar — dinyatakan sah</div>
            <dl class="mt-5 d-grid gap-x-8 gap-y-3 fs-7">
              <div><dt class="fs-8 fw-medium text-slate">Nomor surat</dt><dd class="num-tab mt-0-5 fw-medium text-obs">{{ $hasil->nomor_surat }}</dd></div>
              <div><dt class="fs-8 fw-medium text-slate">Kode verifikasi</dt><dd class="num-tab mt-0-5 fw-medium text-obs">{{ $hasil->kode_verifikasi }}</dd></div>
              <div><dt class="fs-8 fw-medium text-slate">Jenis surat</dt><dd class="mt-0-5 fw-medium text-obs">{{ $hasil->pengajuan->jenisSurat->nama }}</dd></div>
              <div><dt class="fs-8 fw-medium text-slate">Pemohon</dt><dd class="mt-0-5 fw-medium text-obs">{{ $hasil->pengajuan->nama_snapshot }} <span class="num-tab text-slate">— NIM {{ $hasil->pengajuan->nimSamar() }}</span></dd></div>
              <div><dt class="fs-8 fw-medium text-slate">Tanggal terbit</dt><dd class="num-tab mt-0-5 fw-medium text-obs">{{ $hasil->ditandatangani_pada->translatedFormat('d M Y, H:i') }} WIB</dd></div>
              <div><dt class="fs-8 fw-medium text-slate">Ditandatangani</dt><dd class="mt-0-5 fw-medium text-obs">{{ $hasil->nama_penanda_tangan }} ({{ $hasil->jabatan_penanda_tangan }})</dd></div>
            </dl>
            <p class="field-help mt-4">Data yang ditampilkan dibatasi demi privasi pemohon (NFR-06, REK-03); NIM disamarkan.</p>
          </div>
        </section>
      @else
        <section class="mt-6">
          <div class="card p-5 p-md-6">
            <div class="badge badge-danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>Surat tidak ditemukan</div>
            <p class="mt-5 fs-7 leading-relaxed">Kata kunci <b class="num-tab">{{ request('q') }}</b> tidak tercatat sebagai surat yang pernah terbit di SIMKA FMIPA Universitas Riau.</p>
          </div>
        </section>
      @endif
    @endif
  </div>
@endsection