<footer class="mt-16 bg-forest text-paper">
  <div class="mx-auto max-w-1200 px-5 pb-10 pt-12">
    <div class="d-grid gap-10 grid-cols-sm-2 grid-cols-lg-4">
      <div>
        <p class="fs-7 fw-black tracking-20 text-lime">SIMKA</p>
        <p class="mt-3 max-w-32ch fs-7 leading-relaxed text-paper-70">Pengajuan, validasi, penandatanganan, dan verifikasi surat kemahasiswaan FMIPA dalam satu alur.</p>
      </div>
      <nav aria-label="Tautan publik">
        <p class="fs-8 fw-semibold tracking-12 text-paper-60">Publik</p>
        <ul class="mt-3 d-grid gap-2 fs-7">
          <li><a class="text-paper-70 hover-text-paper" href="{{ route('verifikasi.index') }}">Cek keaslian surat</a></li>
          <li><a class="text-paper-70 hover-text-paper" href="{{ route('login') }}">Masuk layanan</a></li>
        </ul>
      </nav>
      <nav aria-label="Tautan mahasiswa">
        <p class="fs-8 fw-semibold tracking-12 text-paper-60">Mahasiswa</p>
        <ul class="mt-3 d-grid gap-2 fs-7">
          <li><a class="text-paper-70 hover-text-paper" href="{{ route('mahasiswa.dashboard') }}">Dashboard</a></li>
          <li><a class="text-paper-70 hover-text-paper" href="{{ route('mahasiswa.ajukan.create') }}">Ajukan surat</a></li>
          <li><a class="text-paper-70 hover-text-paper" href="{{ route('mahasiswa.riwayat.index') }}">Riwayat</a></li>
          <li><a class="text-paper-70 hover-text-paper" href="{{ route('mahasiswa.profil.edit') }}">Profil</a></li>
        </ul>
      </nav>
      <nav aria-label="Tautan staf dan pimpinan">
        <p class="fs-8 fw-semibold tracking-12 text-paper-60">Staf &amp; Pimpinan</p>
        <ul class="mt-3 d-grid gap-2 fs-7">
          <li><a class="text-paper-70 hover-text-paper" href="{{ route('staf.index') }}">Panel staf</a></li>
          <li><a class="text-paper-70 hover-text-paper" href="{{ route('pimpinan.index') }}">Panel pimpinan</a></li>
        </ul>
      </nav>
    </div>
    <div class="mt-12 d-flex flex-column gap-2 border-top border-paper-15 pt-6 fs-8 text-paper-50 flex-sm-row align-items-sm-center justify-content-sm-between">
      <p>© {{ date('Y') }} Kelompok Pengembangan Aplikasi Web — FMIPA Universitas Riau.</p>
      <p class="num-tab">Sistem Informasi Manajemen Surat Kemahasiswaan <span aria-hidden="true">•</span> Waktu dalam WIB.</p>
    </div>
  </div>
</footer>