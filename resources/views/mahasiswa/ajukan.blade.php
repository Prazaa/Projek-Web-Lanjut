@extends('layouts.app')

@section('title', 'Ajukan Surat — SIMKA')

@section('content')
  <div class="mx-auto max-w-720 px-5 pb-16 pt-10 pt-md-14">
    <a href="{{ route('mahasiswa.dashboard') }}" class="d-inline-flex align-items-center gap-1 fs-7 fw-medium text-forest hover-text-primary">
      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
      Kembali ke dashboard
    </a>
    <h1 class="page-title">Ajukan Surat</h1>
    <p class="page-lead">Setiap pengajuan hanya diperuntukkan untuk satu jenis surat.</p>

    <form id="form-ajukan" method="POST" action="{{ route('mahasiswa.ajukan.store') }}" enctype="multipart/form-data" class="d-grid gap-8 pt-10" novalidate>
      @csrf

      @if ($errors->any())
        <div class="error-summary is-visible" role="alert" tabindex="-1">
          <p class="fs-7 fw-semibold">Pengajuan belum bisa dikirim. Perbaiki bagian berikut:</p>
          <ul class="mt-2 list-disc pl-5 fs-7">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Section 1: jenis surat -->
      <fieldset id="field-jenis-surat">
        <legend class="text-title">1. Pilih jenis surat <span class="req text-alarm" aria-hidden="true">*</span></legend>
        <p class="mt-1 fs-7">Pilih jenis surat yang dibutuhkan.</p>
        <div class="mt-4 d-grid gap-3 grid-cols-sm-2">
          @foreach ($daftarJenisSurat as $jenis)
            <label class="radio-card">
              <input class="visually-hidden js-jenis-surat" type="radio" name="jenis_surat_id" value="{{ $jenis->id }}"
                     data-url="{{ route('api.jenis-surat.syarat', $jenis) }}"
                     @checked(old('jenis_surat_id') == $jenis->id) required>
              <span class="d-block">
                <span>
                  <span class="radio-card__title d-block fs-6 fw-semibold text-obs">{{ $jenis->nama }}</span>
                  <span class="d-block fs-8">{{ Str::limit($jenis->deskripsi, 75) }}</span>
                </span>
              </span>
              <span class="radio-card__mark"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg></span>
            </label>
          @endforeach
        </div>
      </fieldset>

      <!-- Section 2: ringkasan data otomatis & keperluan -->
      <section aria-labelledby="judul-data">
        <div class="d-flex align-items-baseline justify-content-between gap-4">
          <h2 id="judul-data" class="text-title">2. Periksa data &amp; keperluan</h2>
          <a class="fs-7 fw-medium text-decoration-underline" href="{{ route('mahasiswa.profil.edit') }}">Perbarui di Profil</a>
        </div>
        <p class="mt-1 fs-7">Terisi otomatis dari profil dan disalin tetap saat pengajuan — surat yang telah terbit tidak berubah bila profil diperbarui kemudian.</p>
        <dl class="mt-4 d-grid gap-x-8 gap-y-3 rounded-10 border border-fog bg-fog-40 p-5 fs-7 grid-cols-sm-2">
          <div class="col-span-sm-2"><dt class="fs-8 fw-medium text-slate">Nama &amp; NIM</dt><dd class="num-tab mt-0-5 fw-medium text-obs">{{ auth()->user()->nama }} — {{ $mahasiswa?->nim ?? '-' }}</dd></div>
          <div><dt class="fs-8 fw-medium text-slate">Program studi</dt><dd class="mt-0-5 fw-medium text-obs">{{ $mahasiswa?->program_studi ?? '-' }}</dd></div>
          <div><dt class="fs-8 fw-medium text-slate">Semester aktif</dt><dd class="num-tab mt-0-5 fw-medium text-obs">Semester {{ $mahasiswa?->semester ?? '-' }}</dd></div>
          <div><dt class="fs-8 fw-medium text-slate">Tempat/tanggal lahir</dt><dd class="num-tab mt-0-5 fw-medium text-obs">{{ $mahasiswa?->tempat_lahir ?? '-' }}, {{ $mahasiswa?->tanggal_lahir?->translatedFormat('d M Y') ?? '-' }}</dd></div>
          <div><dt class="fs-8 fw-medium text-slate">WhatsApp aktif</dt><dd class="num-tab mt-0-5 fw-medium text-obs">{{ $mahasiswa?->no_hp ?? '-' }}</dd></div>
          <div class="col-span-sm-2"><dt class="fs-8 fw-medium text-slate">Alamat saat ini</dt><dd class="mt-0-5 fw-medium text-obs">{{ $mahasiswa?->alamat ?? '-' }}</dd></div>
        </dl>

        <div class="field mt-5">
          <label class="field-label" for="keperluan">Keperluan pengajuan surat <span class="req" aria-hidden="true">*</span></label>
          <input class="input" id="keperluan" name="keperluan" type="text" value="{{ old('keperluan') }}" placeholder="Contoh: Syarat pendaftaran Beasiswa YBM BRILiaN" required>
        </div>
      </section>

      <!-- Section 3: lampiran dinamis via AJAX -->
      <fieldset>
        <legend class="text-title">3. Unggah lampiran <span class="req text-alarm" aria-hidden="true">*</span></legend>
        <div id="wadah-syarat-lampiran" class="mt-4 d-grid gap-4">
          <p class="rounded-10 border border-fog bg-fog-40 p-4 fs-7 text-slate">Pilih jenis surat pada langkah pertama untuk menampilkan berkas persyaratan yang wajib diunggah.</p>
        </div>
      </fieldset>

      <div>
        <div class="mt-2 d-flex flex-column-reverse align-items-stretch gap-3 flex-sm-row align-items-sm-center">
          <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">Batal</a>
          <button class="btn btn-primary" type="submit">Kirim Pengajuan</button>
        </div>
        <p class="mt-3 fs-8 leading-relaxed">Setelah dikirim, data dan lampiran terkunci. Penolakan dari staf bersifat final dan Anda perlu membuat pengajuan baru.</p>
      </div>
    </form>
  </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const radios = document.querySelectorAll('.js-jenis-surat');
  const wadah = document.getElementById('wadah-syarat-lampiran');

  async function muatSyarat(url) {
    wadah.innerHTML = '<p class="fs-7 text-slate">Memuat daftar persyaratan dokumen...</p>';
    try {
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) throw new Error('Gagal memuat syarat');
      const data = await res.json();

      if (!data.syarat || data.syarat.length === 0) {
        wadah.innerHTML = '<p class="rounded-10 border border-fog bg-fog-40 p-4 fs-7 text-slate">Tidak ada berkas lampiran wajib untuk jenis surat ini.</p>';
        return;
      }

      wadah.innerHTML = data.syarat.map((item) => `
        <div class="field">
          <label class="field-label" for="syarat_${item.id}">
            ${item.nama_dokumen} ${item.wajib ? '<span class="req" aria-hidden="true">*</span>' : '<span class="fs-8 text-slate">(Opsional)</span>'}
          </label>
          <label class="upload-box" for="syarat_${item.id}">
            <svg class="h-6 w-6 text-forest" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4m0 0L8 8m4-4 4 4"/><path d="M20 16v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2"/></svg>
            <span class="min-w-0 flex-1">
              <span class="d-block fs-6 fw-medium" data-file-name>Tap untuk pilih berkas (${item.format_diizinkan})</span>
              <span class="d-block fs-8" data-file-meta>${item.keterangan ?? ''} • maksimal ${Math.round(item.maks_ukuran_kb / 1024)} MB</span>
            </span>
            <input class="visually-hidden js-input-berkas" type="file" id="syarat_${item.id}" name="lampiran[${item.id}]" accept=".pdf,.jpg,.jpeg,.png" ${item.wajib ? 'required' : ''}>
          </label>
        </div>
      `).join('');

      wadah.querySelectorAll('.js-input-berkas').forEach((input) => {
        input.addEventListener('change', (e) => {
          const file = e.target.files[0];
          const box = e.target.closest('.upload-box');
          if (file && box) {
            box.querySelector('[data-file-name]').textContent = file.name;
            box.querySelector('[data-file-meta]').textContent = `${(file.size / 1048576).toFixed(2)} MB • Siap diunggah`;
          }
        });
      });
    } catch (err) {
      wadah.innerHTML = '<p class="fs-7 text-alarm">Terjadi kendala saat memuat daftar syarat. Silakan pilih ulang jenis surat.</p>';
    }
  }

  radios.forEach((radio) => {
    radio.addEventListener('change', () => {
      if (radio.checked) muatSyarat(radio.dataset.url);
    });
    if (radio.checked) muatSyarat(radio.dataset.url);
  });
});
</script>
@endpush