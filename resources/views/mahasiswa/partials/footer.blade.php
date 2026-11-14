<footer class="simka-footer">

  <div class="footer-container">

    {{-- Identitas SIMKA --}}
    <div class="footer-brand">
      <strong class="footer-title">SIMKA</strong>

      <p>
        Pengajuan, validasi, penandatanganan, dan verifikasi
        surat kemahasiswaan FMIPA dalam satu alur.
      </p>
    </div>

    {{-- Navigasi Publik --}}
    <div class="footer-navigation">
      <strong>Publik</strong>

      <a href="{{ url('/cek-keaslian') }}">
        Cek keaslian surat
      </a>

      <a href="{{ url('/login') }}">
        Masuk layanan
      </a>
    </div>

    {{-- Navigasi Mahasiswa --}}
    <div class="footer-navigation">
      <strong>Mahasiswa</strong>

      <a href="{{ url('/mahasiswa/dashboard') }}">
        Dashboard
      </a>

      <a href="{{ url('/mahasiswa/ajukan') }}">
        Ajukan surat
      </a>

      <a href="{{ url('/mahasiswa/riwayat') }}">
        Riwayat
      </a>

      <a href="{{ url('/mahasiswa/profil') }}">
        Profil
      </a>
    </div>

  </div>

  <div class="footer-bottom">
    &copy; {{ date('Y') }} SIMKA FMIPA Universitas Riau.
    Seluruh hak cipta dilindungi.
  </div>

</footer>