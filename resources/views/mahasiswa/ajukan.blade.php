@extends('mahasiswa.layouts.app')

@section('title', 'Ajukan Surat')
@section('page-title', 'Ajukan Surat')

@section('content')

  @php
    $user = auth()->user();
    $jenisSurat = [
      'Surat Aktif Kuliah',
      'Surat Keterangan Berkelakuan Baik',
      'Keterangan Tidak Menerima Beasiswa dari Pihak Lain',
      'Keterangan Tunjangan Gaji/Taspen/Jamsostek/Askes',
      'Surat Kehilangan KTM',
      'Surat Kehilangan KHS',
    ];
    $jenisTerpilih = request('jenis_surat', '');
  @endphp

  <style>
    .application-page {
      max-width: 900px;
      margin: 0 auto;
      color: #243329;
    }

    .application-intro {
      margin-bottom: 25px;
    }

    .application-intro h1 {
      margin: 0 0 7px;
      color: #163300;
      font-size: 23px;
      font-weight: 750;
    }

    .application-intro p {
      margin: 0;
      color: #718071;
      font-size: 13px;
      line-height: 1.7;
    }

    .application-section {
      padding: 23px;
      margin-bottom: 20px;
      background: #fff;
      border: 1px solid #e6ecdf;
      border-radius: 11px;
    }

    .section-heading {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding-bottom: 17px;
      margin-bottom: 19px;
      border-bottom: 1px solid #e6ecdf;
    }

    .section-number {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 29px;
      height: 29px;
      flex-shrink: 0;
      border-radius: 50%;
      background: #e2f6d5;
      color: #054d28;
      font-size: 12px;
      font-weight: 800;
    }

    .section-heading h2 {
      margin: 1px 0 5px;
      color: #163300;
      font-size: 16px;
      font-weight: 750;
    }

    .section-heading p {
      margin: 0;
      color: #718071;
      font-size: 12px;
      line-height: 1.6;
    }

    .letter-options {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 10px;
    }

    .letter-option {
      position: relative;
      display: flex;
      align-items: flex-start;
      gap: 11px;
      min-height: 66px;
      padding: 14px;
      border: 1px solid #e6ecdf;
      border-radius: 8px;
      cursor: pointer;
      transition: .18s ease;
    }

    .letter-option:hover {
      border-color: #8ebd70;
      background: #fbfef9;
    }

    .letter-option:has(input:checked) {
      border-color: #639b43;
      background: #f3f9ef;
    }

    .letter-option input {
      margin: 3px 0 0;
      accent-color: #287443;
      flex-shrink: 0;
    }

    .letter-option span {
      color: #344536;
      font-size: 12px;
      font-weight: 600;
      line-height: 1.6;
    }

    .student-data-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 20px 28px;
    }

    .student-data label {
      display: block;
      margin-bottom: 6px;
      color: #718071;
      font-size: 11px;
    }

    .student-data div {
      min-height: 20px;
      color: #243329;
      font-size: 13px;
      font-weight: 600;
      overflow-wrap: anywhere;
    }

    .data-placeholder {
      color: #9aa598 !important;
      font-weight: 400 !important;
      font-style: italic;
    }

    .profile-reminder {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 15px;
      padding: 13px 15px;
      margin-top: 22px;
      background: #f7faf4;
      border-radius: 7px;
    }

    .profile-reminder p {
      margin: 0;
      color: #61705e;
      font-size: 12px;
      line-height: 1.6;
    }

    .profile-reminder a {
      flex-shrink: 0;
      color: #287443;
      font-size: 12px;
      font-weight: 700;
      text-decoration: none;
    }

    .profile-reminder a:hover {
      text-decoration: underline;
    }

    .temporary-notice {
      padding: 12px 14px;
      margin-bottom: 17px;
      border-left: 3px solid #c58b26;
      border-radius: 5px;
      background: #fffaf0;
      color: #795b24;
      font-size: 12px;
      line-height: 1.7;
    }

    .upload-list {
      display: grid;
      gap: 15px;
    }

    .upload-item label {
      display: block;
      margin-bottom: 7px;
      color: #344536;
      font-size: 12px;
      font-weight: 700;
    }

    .upload-item input {
      display: block;
      width: 100%;
      padding: 11px;
      border: 1px dashed #cbd8c3;
      border-radius: 7px;
      background: #fbfdf9;
      color: #61705e;
      font-family: inherit;
      font-size: 12px;
    }

    .upload-item small {
      display: block;
      margin-top: 6px;
      color: #8a9586;
      font-size: 11px;
    }

    .upload-feedback {
      margin-top: 5px;
      color: #287443;
      font-size: 11px;
    }

    .summary-list {
      display: grid;
      gap: 13px;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      gap: 20px;
      padding-bottom: 12px;
      border-bottom: 1px solid #edf0e9;
    }

    .summary-row:last-child {
      padding-bottom: 0;
      border-bottom: 0;
    }

    .summary-row span {
      color: #718071;
      font-size: 12px;
    }

    .summary-row strong {
      max-width: 60%;
      color: #243329;
      font-size: 12px;
      font-weight: 700;
      text-align: right;
      line-height: 1.6;
    }

    .application-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin: 22px 0 35px;
    }

    .application-button {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-height: 39px;
      padding: 10px 17px;
      border: 1px solid transparent;
      border-radius: 7px;
      font-family: inherit;
      font-size: 12px;
      font-weight: 700;
      text-decoration: none;
      cursor: pointer;
      transition: .18s ease;
    }

    .application-button-primary {
      background: #163300;
      color: #fff;
    }

    .application-button-primary:hover {
      background: #054d28;
    }

    .application-button-secondary {
      border-color: #dce5d6;
      background: #fff;
      color: #52634e;
    }

    .application-button-secondary:hover {
      background: #f7faf4;
    }

    .application-feedback {
      display: none;
      padding: 13px 15px;
      margin-top: 15px;
      border-radius: 7px;
      background: #fffaf0;
      color: #795b24;
      font-size: 12px;
      line-height: 1.7;
    }

    @media (max-width: 650px) {
      .application-section {
        padding: 18px;
      }

      .letter-options,
      .student-data-grid {
        grid-template-columns: 1fr;
      }

      .profile-reminder {
        align-items: flex-start;
        flex-direction: column;
      }

      .summary-row {
        flex-direction: column;
        gap: 5px;
      }

      .summary-row strong {
        max-width: 100%;
        text-align: left;
      }

      .application-actions {
        flex-direction: column-reverse;
      }

      .application-button {
        width: 100%;
      }
    }
  </style>

  <div class="application-page">

    <div class="application-intro">
      <h1>Ajukan Surat</h1>
      <p>
        Satu pengajuan untuk satu jenis surat. Periksa data mahasiswa
        dan lengkapi persyaratan sebelum melanjutkan.
      </p>
    </div>

    {{-- 1. Pilih jenis surat --}}
    <section class="application-section" id="pilih-surat">
      <div class="section-heading">
        <div class="section-number">1</div>
        <div>
          <h2>Pilih Jenis Surat</h2>
          <p>Pilih satu jenis surat yang ingin kamu ajukan.</p>
        </div>
      </div>

      <div class="letter-options">
        @foreach ($jenisSurat as $index => $jenis)
          <label class="letter-option">
            <input type="radio" name="jenis_surat" value="{{ $jenis }}" {{ $jenisTerpilih === $jenis ? 'checked' : '' }}>
            <span>{{ $jenis }}</span>
          </label>
        @endforeach
      </div>
    </section>

    {{-- 2. Periksa data mahasiswa --}}
    <section class="application-section" id="data-mahasiswa">
      <div class="section-heading">
        <div class="section-number">2</div>
        <div>
          <h2>Periksa Data Mahasiswa</h2>
          <p>Pastikan informasi berikut sudah sesuai sebelum mengajukan surat.</p>
        </div>
      </div>

      <div class="student-data-grid">
        <div class="student-data">
          <label>Nama Lengkap</label>
          <div>{{ $user->name ?? 'Belum tersedia' }}</div>
        </div>

        <div class="student-data">
          <label>Nomor Induk Mahasiswa (NIM)</label>
          <div class="data-placeholder">Belum tersedia</div>
        </div>

        <div class="student-data">
          <label>Program Studi</label>
          <div class="data-placeholder">Belum tersedia</div>
        </div>

        <div class="student-data">
          <label>Fakultas</label>
          <div>Fakultas Matematika dan Ilmu Pengetahuan Alam</div>
        </div>

        <div class="student-data">
          <label>Tempat, Tanggal Lahir</label>
          <div class="data-placeholder">Belum tersedia</div>
        </div>

        <div class="student-data">
          <label>Nama Orang Tua/Wali</label>
          <div class="data-placeholder">Belum tersedia</div>
        </div>

        <div class="student-data">
          <label>Nomor WhatsApp</label>
          <div class="data-placeholder">Belum tersedia</div>
        </div>

        <div class="student-data">
          <label>Alamat Domisili</label>
          <div class="data-placeholder">Belum tersedia</div>
        </div>
      </div>

      <div class="profile-reminder">
        <p>
          Jika ada informasi yang tidak sesuai, periksa kembali halaman profil.
          Data akademik belum terhubung dengan database.
        </p>

        <a id="profile-link" href="{{ route('mahasiswa.profil') }}?from=ajukan">
          Periksa Profil &rarr;
        </a>
      </div>
    </section>

    {{-- 3. Unggah persyaratan --}}
    <section class="application-section" id="unggah-persyaratan">
      <div class="section-heading">
        <div class="section-number">3</div>
        <div>
          <h2>Unggah Persyaratan</h2>
          <p>Pilih berkas yang diperlukan untuk pengajuan surat.</p>
        </div>
      </div>

      <div class="temporary-notice">
        <strong>Persyaratan sementara — belum terverifikasi.</strong>
        Daftar berkas berikut diambil dari prototipe awal dan masih perlu
        dikonfirmasi kepada pihak kemahasiswaan.
      </div>

      <div class="upload-list">
        <div class="upload-item">
          <label for="bukti_ukt">Bukti Pembayaran UKT</label>
          <input type="file" id="bukti_ukt" accept=".pdf,.jpg,.jpeg,.png">
          <small>Format PDF atau gambar. Ukuran maksimal 10 MB.</small>
          <div class="upload-feedback" id="feedback-ukt"></div>
        </div>

        <div class="upload-item">
          <label for="krs">Kartu Rencana Studi (KRS) Terbaru</label>
          <input type="file" id="krs" accept=".pdf,.jpg,.jpeg,.png">
          <small>Format PDF atau gambar. Ukuran maksimal 10 MB.</small>
          <div class="upload-feedback" id="feedback-krs"></div>
        </div>
      </div>
    </section>

    {{-- 4. Ringkasan dan konfirmasi --}}
    <section class="application-section" id="ringkasan">
      <div class="section-heading">
        <div class="section-number">4</div>
        <div>
          <h2>Ringkasan Pengajuan</h2>
          <p>Periksa kembali informasi sebelum mengirim pengajuan.</p>
        </div>
      </div>

      <div class="summary-list">
        <div class="summary-row">
          <span>Nama Mahasiswa</span>
          <strong>{{ $user->name ?? 'Belum tersedia' }}</strong>
        </div>

        <div class="summary-row">
          <span>Jenis Surat</span>
          <strong id="summary-letter">Belum dipilih</strong>
        </div>

        <div class="summary-row">
          <span>Bukti Pembayaran UKT</span>
          <strong id="summary-ukt">Belum dipilih</strong>
        </div>

        <div class="summary-row">
          <span>KRS Terbaru</span>
          <strong id="summary-krs">Belum dipilih</strong>
        </div>

        <div class="summary-row">
          <span>Status Awal</span>
          <strong>Menunggu Validasi (rancangan)</strong>
        </div>
      </div>

      <div class="application-feedback" id="application-feedback"></div>
    </section>

    <div class="application-actions">
      <a href="{{ route('mahasiswa.dashboard') }}" class="application-button application-button-secondary">
        Batal
      </a>

      <button type="button" class="application-button application-button-primary" id="submit-application">
        Kirim Pengajuan
      </button>
    </div>

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const letterInputs = document.querySelectorAll('input[name="jenis_surat"]');
      const summaryLetter = document.getElementById('summary-letter');
      const profileLink = document.getElementById('profile-link');
      const feedback = document.getElementById('application-feedback');

      const fileFields = [
        {
          input: document.getElementById('bukti_ukt'),
          summary: document.getElementById('summary-ukt'),
          feedback: document.getElementById('feedback-ukt')
        },
        {
          input: document.getElementById('krs'),
          summary: document.getElementById('summary-krs'),
          feedback: document.getElementById('feedback-krs')
        }
      ];

      function getSelectedLetter() {
        const selected = document.querySelector('input[name="jenis_surat"]:checked');
        return selected ? selected.value : '';
      }

      function updateLetter() {
        const selected = getSelectedLetter();

        summaryLetter.textContent = selected || 'Belum dipilih';

        const profileUrl = new URL(profileLink.href);
        profileUrl.searchParams.set('from', 'ajukan');

        if (selected) {
          profileUrl.searchParams.set('jenis_surat', selected);
        } else {
          profileUrl.searchParams.delete('jenis_surat');
        }

        profileLink.href = profileUrl.toString();

        const currentUrl = new URL(window.location.href);

        if (selected) {
          currentUrl.searchParams.set('jenis_surat', selected);
        } else {
          currentUrl.searchParams.delete('jenis_surat');
        }

        window.history.replaceState({}, '', currentUrl.toString());
      }

      letterInputs.forEach(function (input) {
        input.addEventListener('change', updateLetter);
      });

      fileFields.forEach(function (field) {
        field.input.addEventListener('change', function () {
          const file = field.input.files[0];

          field.feedback.textContent = '';
          field.summary.textContent = 'Belum dipilih';

          if (!file) {
            return;
          }

          const allowedTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png'
          ];

          const maxSize = 10 * 1024 * 1024;

          if (!allowedTypes.includes(file.type)) {
            field.input.value = '';
            field.feedback.textContent = 'Format berkas tidak didukung.';
            return;
          }

          if (file.size > maxSize) {
            field.input.value = '';
            field.feedback.textContent = 'Ukuran berkas melebihi 10 MB.';
            return;
          }

          field.summary.textContent = file.name;
          field.feedback.textContent = 'Berkas dipilih: ' + file.name;
        });
      });

      document.getElementById('submit-application').addEventListener('click', function () {
        feedback.style.display = 'block';

        const selectedLetter = getSelectedLetter();
        const ukt = document.getElementById('bukti_ukt').files[0];
        const krs = document.getElementById('krs').files[0];

        if (!selectedLetter) {
          feedback.textContent = 'Silakan pilih jenis surat terlebih dahulu.';
          document.getElementById('pilih-surat').scrollIntoView({ behavior: 'smooth' });
          return;
        }

        if (!ukt || !krs) {
          feedback.textContent = 'Silakan pilih kedua berkas persyaratan terlebih dahulu.';
          document.getElementById('unggah-persyaratan').scrollIntoView({ behavior: 'smooth' });
          return;
        }

        feedback.textContent =
          'Pemeriksaan tampilan berhasil. Pengajuan belum dikirim karena halaman ini ' +
          'masih berupa prototipe dan belum terhubung dengan database.';
      });

      updateLetter();
    });
  </script>

@endsection