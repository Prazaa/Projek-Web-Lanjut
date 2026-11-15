@extends('layouts.app')

@section('title', 'Tanda Tangan Digital — SIMKA')

@section('content')
  <div class="mx-auto max-w-1200 px-5 pb-16 pt-10 pt-md-14">
    <a href="{{ route('pimpinan.index') }}" class="d-inline-flex align-items-center gap-1 fs-7 fw-medium text-forest hover-text-primary">
      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
      Menunggu tanda tangan
    </a>
    <div class="d-flex flex-wrap align-items-center gap-x-4 gap-y-2">
      <h1 class="page-title">Tinjau surat</h1>
      <span class="badge {{ $pengajuan->status->warnaBadge() }}">{{ $pengajuan->status->label() }}</span>
    </div>
    <p class="num-tab mt-2 fs-7">{{ $pengajuan->jenisSurat->nama }} — {{ $pengajuan->nama_snapshot }} ({{ $pengajuan->nim_snapshot }}, {{ $pengajuan->prodi_snapshot }})</p>

    <div class="mt-8 d-grid gap-6 grid-cols-lg-5">
      <section aria-labelledby="judul-pratinjau" class="card p-5 p-md-8 col-span-lg-3">
        <h2 id="judul-pratinjau" class="text-title">Pratinjau isi surat</h2>
        <p class="field-help">Teks dari draf yang disiapkan staf, diisi otomatis dari salinan data pemohon (FR-16, REK-04).</p>

        <article class="mt-6 rounded-10 border border-fog p-4 p-sm-6 p-md-10" aria-label="Pratinjau dokumen surat">
          <div class="d-grid place-items-center gap-1 rounded-10 bg-fog-60 p-4 text-center p-sm-6">
            <p class="fs-6 fw-bold text-uppercase tracking-8 text-obs">{{ $pengaturan->nama_instansi }}</p>
            <p class="fs-8 fw-semibold text-uppercase tracking-6 text-obs fs-sm-7 tracking-sm-8">{{ $pengaturan->nama_fakultas }}</p>
            <p class="fs-8 text-slate">{{ $pengaturan->alamat_fakultas }}</p>
          </div>

          <div class="num-tab mt-8 text-center fs-7">
            <p>No. <b>{{ $pengajuan->tandaTangan?->nomor_surat ?? 'akan diterbitkan saat ditandatangani' }}</b></p>
          </div>

          <h3 class="mt-8 text-center fs-6 fw-bold text-uppercase tracking-6 text-obs">{{ $pengajuan->isiSurat?->perihal ?? $pengajuan->jenisSurat->nama }}</h3>
          <div class="mt-6 d-grid gap-3 fs-7 leading-relaxed">
            <p>{{ $pengajuan->isiSurat?->paragraf_pembuka ?? 'Yang bertanda tangan di bawah ini menerangkan bahwa:' }}</p>
            <dl class="d-grid gap-x-8 gap-y-2 rounded-10 border border-fog p-4">
              <div class="doc-dl gap-4"><dt class="text-slate">Nama</dt><dd class="fw-medium text-obs">{{ $pengajuan->nama_snapshot }}</dd></div>
              <div class="doc-dl gap-4"><dt class="num-tab text-slate">NIM</dt><dd class="num-tab fw-medium text-obs">{{ $pengajuan->nim_snapshot }}</dd></div>
              <div class="doc-dl gap-4"><dt class="text-slate">Program studi</dt><dd class="fw-medium text-obs">{{ $pengajuan->prodi_snapshot }}</dd></div>
              <div class="doc-dl gap-4"><dt class="num-tab text-slate">Semester</dt><dd class="num-tab fw-medium text-obs">Semester {{ $pengajuan->semester_snapshot }}</dd></div>
            </dl>
            <p>{{ $pengajuan->isiSurat?->paragraf_isi }}</p>
            <p>{{ $pengajuan->isiSurat?->paragraf_penutup ?? 'Demikian surat ini dibuat untuk dapat dipergunakan sebagaimana mestinya.' }}</p>
          </div>

          <div class="mt-10 d-flex justify-content-end">
            <div class="w-100 max-w-16rem text-center">
              <div class="d-grid h-20 place-items-center rounded-10 border border-dashed border-pebble fs-8 text-slate">
                Pengesahan digital SIMKA
              </div>
              <p class="mt-2 fs-7 fw-medium text-obs">{{ auth()->user()->nama }}</p>
              <p class="fs-8 text-slate">{{ auth()->user()->jabatan ?? $pengaturan->jabatan_pimpinan_default }}</p>
            </div>
          </div>
        </article>
      </section>

      <aside class="col-span-lg-2">
        <div class="card position-sticky top-20 p-5 p-md-6">
          <h2 class="text-title">Tanda tangan digital</h2>
          <p class="mt-3 fs-7 fw-medium text-alarm">Surat yang sudah terbit tidak dapat ditandatangani ulang.</p>
          @if ($pengajuan->status->value === 'siap_ttd')
            <button class="btn btn-primary btn-block mt-5" data-open-modal="modal-ttd">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19l7-7-3.7-3.7A2 2 0 0 0 14 8l-6 6v5h4z"/><path d="m15 6 3 3"/><path d="M5 21h14"/></svg>
              Tanda Tangani
            </button>
          @endif
        </div>
      </aside>
    </div>
  </div>

  <div class="modal" id="modal-ttd" role="dialog" aria-modal="true" aria-labelledby="judul-modal-ttd" data-locked>
    <div class="modal__overlay"></div>
    <div class="modal__panel">
      <div class="d-flex align-items-start justify-content-between gap-4">
        <h2 id="judul-modal-ttd" class="text-title">Tanda tangani surat ini?</h2>
        <button class="modal__close" data-close-modal aria-label="Tutup dialog">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>
      <p class="mt-3 fs-7 leading-relaxed">Hasil tindakan ini terkunci: nomor surat resmi langsung diterbitkan, kode verifikasi dibuat, dan status berubah menjadi Selesai.</p>
      <form method="POST" action="{{ route('pimpinan.tandatangani', $pengajuan) }}" class="mt-6 d-flex flex-column-reverse gap-3 flex-sm-row justify-content-sm-end">
        @csrf
        <button type="button" class="btn btn-secondary" data-close-modal>Batal</button>
        <button type="submit" class="btn btn-primary">Tanda Tangan &amp; Terbitkan Surat</button>
      </form>
    </div>
  </div>
@endsection