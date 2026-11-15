@extends('layouts.app')

@section('title', 'Tinjau Pengajuan — SIMKA')

@section('content')
  <div class="mx-auto max-w-1200 px-5 pb-16 pt-10 pt-md-14">
    <a href="{{ route('staf.index') }}" class="d-inline-flex align-items-center gap-1 fs-7 fw-medium text-forest hover-text-primary">
      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
      Antrean validasi
    </a>
    <div class="d-flex flex-wrap align-items-center gap-x-4 gap-y-2">
      <h1 class="page-title">Tinjau pengajuan</h1>
      <span class="badge {{ $pengajuan->status->warnaBadge() }}">{{ $pengajuan->status->label() }}</span>
    </div>
    <p class="num-tab mt-2 fs-7">{{ $pengajuan->kode_pengajuan }} • {{ $pengajuan->jenisSurat->nama }} • diajukan {{ $pengajuan->diajukan_pada->translatedFormat('d M Y, H.i') }} WIB</p>

    <div class="mt-8 d-grid gap-6 grid-cols-lg-5">
      <div class="d-grid content-start gap-6 col-span-lg-3">
        <section aria-labelledby="judul-identitas" class="card p-5 p-md-6">
          <h2 id="judul-identitas" class="text-title">Identitas pemohon</h2>
          <dl class="mt-4 d-grid gap-x-8 gap-y-3 fs-7 grid-cols-sm-2">
            <div><dt class="fs-8 fw-medium text-slate">Nama</dt><dd class="mt-0-5 fw-medium text-obs">{{ $pengajuan->nama_snapshot }}</dd></div>
            <div><dt class="fs-8 fw-medium text-slate">NIM</dt><dd class="num-tab mt-0-5 fw-medium text-obs">{{ $pengajuan->nim_snapshot }}</dd></div>
            <div><dt class="fs-8 fw-medium text-slate">Program studi</dt><dd class="mt-0-5 fw-medium text-obs">{{ $pengajuan->prodi_snapshot }}</dd></div>
            <div><dt class="fs-8 fw-medium text-slate">Semester</dt><dd class="num-tab mt-0-5 fw-medium text-obs">Semester {{ $pengajuan->semester_snapshot }}</dd></div>
            <div class="col-span-sm-2"><dt class="fs-8 fw-medium text-slate">Keperluan</dt><dd class="mt-0-5 fw-medium text-obs">{{ $pengajuan->keperluan }}</dd></div>
            <div><dt class="fs-8 fw-medium text-slate">WhatsApp aktif</dt><dd class="num-tab mt-0-5 fw-medium text-obs">{{ $pengajuan->mahasiswa?->no_hp ?? '-' }}</dd></div>
          </dl>
        </section>

        <section aria-labelledby="judul-lampiran" class="card p-5 p-md-6">
          <div class="d-flex align-items-center justify-content-between gap-4">
            <h2 id="judul-lampiran" class="text-title">Lampiran</h2>
            <span class="badge badge-neutral">{{ $pengajuan->lampiran->count() }} berkas</span>
          </div>
          <div class="mt-4 d-grid gap-3">
            @forelse ($pengajuan->lampiran as $berkas)
              <div class="d-flex flex-column gap-3 rounded-10 border border-fog p-4 flex-sm-row align-items-sm-center gap-sm-4">
                <span class="d-grid h-11 w-9 flex-shrink-0 place-items-center rounded-6 bg-linen fs-8 fw-bold tracking-wide text-forest" aria-hidden="true">{{ strtoupper(pathinfo($berkas->nama_asli, PATHINFO_EXTENSION) ?: 'PDF') }}</span>
                <span class="min-w-0 flex-1">
                  <span class="d-block text-break fs-7 fw-medium text-obs">{{ $berkas->nama_asli }}</span>
                  <span class="num-tab d-block fs-8 text-slate">{{ number_format($berkas->ukuran_byte / 1048576, 2) }} MB • diunggah {{ $berkas->created_at->translatedFormat('d M Y, H.i') }} WIB</span>
                </span>
                <a class="btn btn-secondary btn-sm w-100 w-sm-auto" href="{{ route('lampiran.unduh', $berkas) }}" target="_blank">Lihat</a>
              </div>
            @empty
              <p class="fs-7 text-slate">Tidak ada lampiran pada pengajuan ini.</p>
            @endforelse
          </div>
        </section>
      </div>

      <aside class="col-span-lg-2">
        <div class="card position-sticky top-20 p-5 p-md-6">
          <h2 class="text-title">Keputusan &amp; Redaksi Surat</h2>
          @if (in_array($pengajuan->status->value, ['diajukan', 'diproses']))
            <form method="POST" action="{{ route('staf.setujui', $pengajuan) }}" class="mt-4 d-grid gap-3">
              @csrf
              <div class="field">
                <label class="field-label" for="perihal">Perihal Surat <span class="req">*</span></label>
                <input class="input" id="perihal" name="perihal" type="text" value="{{ old('perihal', $pengajuan->isiSurat?->perihal ?? $pengajuan->jenisSurat->nama) }}" required>
              </div>
              <div class="field">
                <label class="field-label" for="tujuan_surat">Tujuan Surat</label>
                <input class="input" id="tujuan_surat" name="tujuan_surat" type="text" value="{{ old('tujuan_surat', $pengajuan->isiSurat?->tujuan_surat) }}" placeholder="Opsional">
              </div>
              <div class="field">
                <label class="field-label" for="paragraf_isi">Isi Keterangan Surat <span class="req">*</span></label>
                <textarea class="input input-textarea" id="paragraf_isi" name="paragraf_isi" required>{{ old('paragraf_isi', $pengajuan->isiSurat?->paragraf_isi ?? 'Mahasiswa tersebut di atas benar tercatat sebagai mahasiswa aktif pada Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Riau.') }}</textarea>
              </div>
              <button type="submit" class="btn btn-primary btn-block">Setujui &amp; Teruskan ke Pimpinan</button>
            </form>

            <hr class="my-4 border-fog">
            <button class="btn btn-danger-outline btn-block" data-open-modal="modal-tolak">Tolak dengan Alasan</button>
          @else
            <p class="mt-3 fs-7 text-slate">Pengajuan ini sudah berstatus <b>{{ $pengajuan->status->label() }}</b>.</p>
          @endif
        </div>
      </aside>
    </div>
  </div>

  <!-- Modal tolak -->
  <div class="modal" id="modal-tolak" role="dialog" aria-modal="true" aria-labelledby="judul-modal-tolak" data-locked>
    <div class="modal__overlay"></div>
    <div class="modal__panel">
      <div class="d-flex align-items-start justify-content-between gap-4">
        <h2 id="judul-modal-tolak" class="text-title">Tolak pengajuan</h2>
        <button class="modal__close" data-close-modal aria-label="Tutup dialog">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>
      <p class="mt-3 fs-7 leading-relaxed">Alasan ditampilkan kepada mahasiswa. Penolakan bersifat final dan mahasiswa perlu mengajukan baru.</p>
      <form method="POST" action="{{ route('staf.tolak', $pengajuan) }}" class="mt-4 d-grid gap-4">
        @csrf
        <div class="field">
          <label class="field-label" for="alasan_ditolak">Alasan penolakan <span class="req" aria-hidden="true">*</span></label>
          <textarea class="input input-textarea" id="alasan_ditolak" name="alasan_ditolak" required placeholder="Contoh: Bukti KRS bukan semester berjalan."></textarea>
        </div>
        <div class="d-flex flex-column-reverse gap-3 flex-sm-row justify-content-sm-end">
          <button class="btn btn-secondary" data-close-modal type="button">Batal</button>
          <button class="btn btn-danger" type="submit">Kirim Penolakan</button>
        </div>
      </form>
    </div>
  </div>
@endsection