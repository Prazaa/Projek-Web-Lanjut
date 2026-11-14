@extends('mahasiswa.layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page-title', 'Dashboard Mahasiswa')

@section('content')

  @php
    // Data sementara, nantinya diambil dari database.

    $statistics = [
      ['label' => 'Total pengajuan', 'value' => 5],
      ['label' => 'Sedang diproses', 'value' => 3],
      ['label' => 'Selesai', 'value' => 1],
    ];

    $activeRequests = [
      [
        'number' => 'SIMKA-001',
        'type' => 'Surat Aktif Kuliah',
        'date' => '12 November 2026',
        'status' => 'Menunggu validasi',
        'status_class' => 'waiting',
      ],
      [
        'number' => 'SIMKA-002',
        'type' => 'Surat Kehilangan KTM',
        'date' => '10 November 2026',
        'status' => 'Menunggu tanda tangan',
        'status_class' => 'signing',
      ],
      [
        'number' => 'SIMKA-003',
        'type' => 'Surat Keterangan Aktif Kuliah',
        'date' => '08 November 2026',
        'status' => 'Menunggu validasi',
        'status_class' => 'waiting',
      ],
    ];
  @endphp

  <style>
    /* Kartu sapaan - dipertahankan */
    .welcome-box {
      background: linear-gradient(120deg, #163300, #054d28);
      color: white;
      padding: 30px;
      border-radius: 14px;
      margin-bottom: 30px;
    }

    .welcome-box h1 {
      font-size: 25px;
      font-weight: 800;
      margin: 0 0 10px;
      color: #ffffff;
    }

    .welcome-box p {
      color: #e2f6d5;
      font-size: 14px;
      margin: 0;
      line-height: 1.8;
      max-width: 550px;
    }

    .welcome-button {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-top: 20px;
      padding: 10px 17px;
      border-radius: 8px;
      background: #9fe870;
      color: #163300;
      font-size: 13px;
      font-weight: 700;
      text-decoration: none;
      transition: .2s;
    }

    .welcome-button:hover {
      background: #b5f18f;
      transform: translateY(-2px);
    }

    /* Ringkasan */
    .summary-section {
      margin-bottom: 32px;
    }

    .section-heading {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 15px;
    }

    .section-heading h2 {
      font-size: 17px;
      font-weight: 750;
      color: #163300;
      margin: 0;
    }

    .section-caption {
      font-size: 12px;
      color: #718071;
    }

    .statistics-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 0;
      background: #ffffff;
      border: 1px solid #e6ecdf;
      border-radius: 11px;
      overflow: hidden;
    }

    .stat-item {
      padding: 19px 22px;
      border-right: 1px solid #e6ecdf;
    }

    .stat-item:last-child {
      border-right: none;
    }

    .stat-value {
      font-size: 28px;
      font-weight: 800;
      color: #163300;
      margin-bottom: 5px;
    }

    .stat-label {
      font-size: 13px;
      color: #718071;
    }

    /* Susunan utama */
    .dashboard-content {
      display: grid;
      grid-template-columns: minmax(0, 1.7fr) minmax(250px, .8fr);
      gap: 28px;
      align-items: start;
    }

    .main-column,
    .side-column {
      min-width: 0;
    }

    .main-column {
      display: flex;
      flex-direction: column;
      gap: 30px;
    }

    .side-column {
      display: flex;
      flex-direction: column;
      gap: 25px;
    }

    /* Daftar pengajuan */
    .request-list {
      border-top: 1px solid #e6ecdf;
    }

    .request-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 15px;
      padding: 17px 2px;
      border-bottom: 1px solid #e6ecdf;
    }

    .request-detail {
      min-width: 0;
    }

    .request-detail h3 {
      font-size: 14px;
      font-weight: 700;
      color: #243329;
      margin: 0 0 6px;
    }

    .request-detail p {
      font-size: 12px;
      color: #718071;
      margin: 0;
      line-height: 1.6;
    }

    .request-status {
      font-size: 11px;
      font-weight: 650;
      white-space: nowrap;
      padding: 6px 9px;
      border-radius: 5px;
    }

    .request-status.waiting {
      color: #946313;
      background: #fff4d9;
    }

    .request-status.signing {
      color: #3156a3;
      background: #e8efff;
    }

    /* Dokumen */
    .document-row {
      display: flex;
      align-items: center;
      gap: 13px;
      padding: 16px 0;
      border-top: 1px solid #e6ecdf;
      border-bottom: 1px solid #e6ecdf;
    }

    .document-symbol {
      width: 38px;
      height: 43px;
      border: 1px solid #cfe5c2;
      border-radius: 5px;
      color: #054d28;
      background: #f3f9ef;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 19px;
      flex-shrink: 0;
    }

    .document-detail {
      min-width: 0;
    }

    .document-detail h3 {
      font-size: 14px;
      font-weight: 700;
      color: #243329;
      margin: 0 0 6px;
    }

    .document-detail p {
      font-size: 12px;
      color: #718071;
      margin: 0;
      line-height: 1.6;
    }

    .document-label {
      margin-left: auto;
      color: #287443;
      font-size: 11px;
      font-weight: 700;
      white-space: nowrap;
    }

    /* Bagian samping */
    .side-section {
      padding-bottom: 23px;
      border-bottom: 1px solid #e6ecdf;
    }

    .side-section:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }

    .side-section h2 {
      color: #163300;
      font-size: 15px;
      font-weight: 750;
      margin: 0 0 12px;
    }

    .attention-content {
      padding: 13px 14px;
      background: #fff9ed;
      border-left: 3px solid #d99a2b;
    }

    .attention-content strong {
      display: block;
      color: #805b16;
      font-size: 12px;
      margin-bottom: 6px;
    }

    .attention-content p {
      font-size: 12px;
      line-height: 1.7;
      color: #8b7448;
      margin: 0 0 9px;
    }

    .side-link {
      color: #315d31;
      font-size: 12px;
      font-weight: 700;
      text-decoration: none;
    }

    .side-link:hover {
      text-decoration: underline;
    }

    .profile-completion {
      font-size: 12px;
      color: #718071;
      line-height: 1.8;
      margin: 0 0 10px;
    }

    .profile-progress {
      width: 100%;
      height: 5px;
      background: #e9eee5;
      border-radius: 10px;
      overflow: hidden;
      margin: 10px 0;
    }

    .profile-progress span {
      display: block;
      width: 80%;
      height: 100%;
      background: #639b43;
      border-radius: 10px;
    }

    /* Responsif */
    @media (max-width: 850px) {
      .dashboard-content {
        grid-template-columns: 1fr;
        gap: 28px;
      }

      .side-column {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 600px) {
      .welcome-box {
        padding: 22px;
      }

      .welcome-box h1 {
        font-size: 19px;
      }

      .statistics-grid {
        grid-template-columns: 1fr;
      }

      .stat-item {
        border-right: none;
        border-bottom: 1px solid #e6ecdf;
        padding: 14px 18px;
      }

      .stat-item:last-child {
        border-bottom: none;
      }

      .side-column {
        grid-template-columns: 1fr;
      }

      .request-row {
        align-items: flex-start;
        flex-direction: column;
        gap: 9px;
      }
    }
  </style>

  {{-- Sapaan --}}
  <div class="welcome-box">
    <h1>Selamat Datang, {{ auth()->user()->name }}!</h1>

    <p>
      Selamat datang di SIMKA FMIPA Universitas Riau.
      Melalui halaman ini, kamu dapat mengajukan surat kemahasiswaan
      dan memantau perkembangan pengajuanmu.
    </p>

    <a href="{{ url('/mahasiswa/ajukan') }}" class="welcome-button">
      Ajukan Surat <span aria-hidden="true">→</span>
    </a>
  </div>

  {{-- Ringkasan --}}
  <section class="summary-section">
    <div class="section-heading">
      <h2>Ringkasan Aktivitas</h2>
      <span class="section-caption">Data pengajuan</span>
    </div>

    <div class="statistics-grid">
      @foreach ($statistics as $stat)
        <div class="stat-item">
          <div class="stat-value">
            {{ str_pad($stat['value'], 2, '0', STR_PAD_LEFT) }}
          </div>

          <div class="stat-label">
            {{ $stat['label'] }}
          </div>
        </div>
      @endforeach
    </div>
  </section>

  {{-- Konten dashboard --}}
  <div class="dashboard-content">

    <div class="main-column">

      {{-- Pengajuan terbaru --}}
      <section>
        <div class="section-heading">
          <h2>Pengajuan Berjalan</h2>

          <a href="{{ url('/mahasiswa/riwayat') }}" class="side-link">
            Lihat riwayat →
          </a>
        </div>

        <div class="request-list">
          @foreach ($activeRequests as $request)
            <div class="request-row">
              <div class="request-detail">
                <h3>{{ $request['type'] }}</h3>

                <p>
                  {{ $request['number'] }}
                  &nbsp;·&nbsp;
                  {{ $request['date'] }}
                </p>
              </div>

              <span class="request-status {{ $request['status_class'] }}">
                {{ $request['status'] }}
              </span>
            </div>
          @endforeach
        </div>
      </section>

      {{-- Dokumen selesai --}}
      <section>
        <div class="section-heading">
          <h2>Surat Siap Diunduh</h2>
        </div>

        <div class="document-row">
          <div class="document-symbol">▤</div>

          <div class="document-detail">
            <h3>Surat Keterangan Tidak Menerima Beasiswa</h3>

            <p>
              No. 012/FMIPA/2026
              <br>
              30 Oktober 2026
            </p>
          </div>

          <span class="document-label">Selesai</span>
        </div>

        <p class="section-caption" style="margin-top: 10px; line-height: 1.7;">
          Fitur unduh dokumen akan tersedia setelah proses
          penyimpanan dan pembuatan PDF diimplementasikan.
        </p>
      </section>

    </div>

    {{-- Kolom informasi --}}
    <aside class="side-column">

      {{-- Perlu perhatian --}}
      <section class="side-section">
        <h2>Perlu Perhatian</h2>

        <div class="attention-content">
          <strong>1 pengajuan perlu diperiksa</strong>

          <p>
            Periksa kembali pengajuan yang membutuhkan
            tindakan atau perbaikan dari kamu.
          </p>

          <a href="{{ url('/mahasiswa/riwayat') }}" class="side-link">
            Periksa pengajuan →
          </a>
        </div>
      </section>

      {{-- Kelengkapan profil --}}
      <section class="side-section">
        <h2>Kelengkapan Profil</h2>

        <p class="profile-completion">
          Pastikan informasi mahasiswa sudah lengkap
          agar proses pengajuan surat berjalan lancar.
        </p>

        <div class="profile-progress">
          <span></span>
        </div>

        <a href="{{ url('/mahasiswa/profil') }}" class="side-link">
          Periksa profil →
        </a>
      </section>

    </aside>

  </div>

@endsection