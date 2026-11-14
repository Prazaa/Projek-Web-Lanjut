@extends('mahasiswa.layouts.app')

@section('content')
  @php
    // Data sementara. Nantinya akan diganti dengan data dari database.
    $daftarPengajuan = [
      'SIMKA-2026-001' => [
        'nomor' => 'SIMKA-2026-001',
        'jenis' => 'Surat Aktif Kuliah',
        'tanggal' => '10 November 2026',
        'diperbarui' => '12 November 2026',
        'status' => 'Selesai',
        'keterangan' => 'Pengajuan telah selesai diproses. Surat sudah dapat diunduh setelah file diterbitkan oleh petugas.',
        'berkas' => [
          ['nama' => 'Bukti Pembayaran UKT', 'status' => 'Diterima'],
          ['nama' => 'Kartu Rencana Studi (KRS)', 'status' => 'Diterima'],
        ],
        'timeline' => [
          [
            'judul' => 'Pengajuan dikirim',
            'tanggal' => '10 November 2026',
            'keterangan' => 'Mahasiswa mengirimkan pengajuan surat.',
            'selesai' => true,
          ],
          [
            'judul' => 'Validasi berkas',
            'tanggal' => '11 November 2026',
            'keterangan' => 'Berkas persyaratan telah diperiksa.',
            'selesai' => true,
          ],
          [
            'judul' => 'Pengajuan diproses',
            'tanggal' => '11 November 2026',
            'keterangan' => 'Pengajuan diteruskan untuk diproses.',
            'selesai' => true,
          ],
          [
            'judul' => 'Pengajuan selesai',
            'tanggal' => '12 November 2026',
            'keterangan' => 'Proses pengajuan telah diselesaikan.',
            'selesai' => true,
          ],
        ],
      ],

      'SIMKA-2026-002' => [
        'nomor' => 'SIMKA-2026-002',
        'jenis' => 'Surat Keterangan Berkelakuan Baik',
        'tanggal' => '12 November 2026',
        'diperbarui' => '13 November 2026',
        'status' => 'Sedang Diproses',
        'keterangan' => 'Pengajuan sedang diperiksa oleh petugas. Silakan menunggu informasi selanjutnya.',
        'berkas' => [
          ['nama' => 'Bukti Pembayaran UKT', 'status' => 'Diterima'],
          ['nama' => 'Kartu Rencana Studi (KRS)', 'status' => 'Diterima'],
        ],
        'timeline' => [
          [
            'judul' => 'Pengajuan dikirim',
            'tanggal' => '12 November 2026',
            'keterangan' => 'Mahasiswa mengirimkan pengajuan surat.',
            'selesai' => true,
          ],
          [
            'judul' => 'Validasi berkas',
            'tanggal' => '13 November 2026',
            'keterangan' => 'Berkas persyaratan telah diperiksa.',
            'selesai' => true,
          ],
          [
            'judul' => 'Pengajuan diproses',
            'tanggal' => 'Sedang berlangsung',
            'keterangan' => 'Pengajuan sedang ditangani oleh petugas.',
            'selesai' => false,
          ],
          [
            'judul' => 'Pengajuan selesai',
            'tanggal' => 'Belum selesai',
            'keterangan' => 'Menunggu proses sebelumnya selesai.',
            'selesai' => false,
          ],
        ],
      ],

      'SIMKA-2026-003' => [
        'nomor' => 'SIMKA-2026-003',
        'jenis' => 'Surat Kehilangan KTM',
        'tanggal' => '14 November 2026',
        'diperbarui' => '14 November 2026',
        'status' => 'Menunggu Validasi',
        'keterangan' => 'Pengajuan telah dikirim dan sedang menunggu pemeriksaan awal oleh petugas.',
        'berkas' => [
          ['nama' => 'Bukti Pembayaran UKT', 'status' => 'Menunggu pemeriksaan'],
          ['nama' => 'Kartu Rencana Studi (KRS)', 'status' => 'Menunggu pemeriksaan'],
        ],
        'timeline' => [
          [
            'judul' => 'Pengajuan dikirim',
            'tanggal' => '14 November 2026',
            'keterangan' => 'Mahasiswa mengirimkan pengajuan surat.',
            'selesai' => true,
          ],
          [
            'judul' => 'Validasi berkas',
            'tanggal' => 'Menunggu validasi',
            'keterangan' => 'Petugas belum menyelesaikan pemeriksaan berkas.',
            'selesai' => false,
          ],
          [
            'judul' => 'Pengajuan diproses',
            'tanggal' => 'Belum dimulai',
            'keterangan' => 'Menunggu hasil validasi berkas.',
            'selesai' => false,
          ],
          [
            'judul' => 'Pengajuan selesai',
            'tanggal' => 'Belum selesai',
            'keterangan' => 'Menunggu tahapan sebelumnya.',
            'selesai' => false,
          ],
        ],
      ],

      'SIMKA-2026-004' => [
        'nomor' => 'SIMKA-2026-004',
        'jenis' => 'Surat Kehilangan KHS',
        'tanggal' => '14 November 2026',
        'diperbarui' => '14 November 2026',
        'status' => 'Perlu Perbaikan',
        'keterangan' => 'Terdapat berkas yang perlu diperbaiki. Silakan periksa kembali persyaratan pengajuan.',
        'berkas' => [
          ['nama' => 'Bukti Pembayaran UKT', 'status' => 'Diterima'],
          ['nama' => 'Kartu Rencana Studi (KRS)', 'status' => 'Perlu diperbaiki'],
        ],
        'timeline' => [
          [
            'judul' => 'Pengajuan dikirim',
            'tanggal' => '14 November 2026',
            'keterangan' => 'Mahasiswa mengirimkan pengajuan surat.',
            'selesai' => true,
          ],
          [
            'judul' => 'Validasi berkas',
            'tanggal' => '14 November 2026',
            'keterangan' => 'Petugas menemukan berkas yang perlu diperbaiki.',
            'selesai' => true,
          ],
          [
            'judul' => 'Perbaikan berkas',
            'tanggal' => 'Menunggu perbaikan',
            'keterangan' => 'Mahasiswa perlu memperbaiki berkas persyaratan.',
            'selesai' => false,
          ],
          [
            'judul' => 'Pengajuan selesai',
            'tanggal' => 'Belum selesai',
            'keterangan' => 'Menunggu berkas diperbaiki dan diproses kembali.',
            'selesai' => false,
          ],
        ],
      ],
    ];

    abort_unless(isset($daftarPengajuan[$nomor]), 404);

    $item = $daftarPengajuan[$nomor];

    $statusClass = match ($item['status']) {
      'Selesai' => 'detail-status-done',
      'Sedang Diproses' => 'detail-status-process',
      'Menunggu Validasi' => 'detail-status-waiting',
      'Perlu Perbaikan' => 'detail-status-revision',
      default => '',
    };
  @endphp

  <style>
    .detail-page {
      color: var(--text);
    }

    .detail-back {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      margin-bottom: 19px;
      color: var(--spruce);
      font-size: 12px;
      font-weight: 650;
      text-decoration: none;
    }

    .detail-back:hover {
      color: var(--forest);
    }

    .detail-heading {
      margin-bottom: 23px;
    }

    .detail-heading h1 {
      margin: 0 0 7px;
      color: var(--forest);
      font-size: 25px;
      font-weight: 750;
      letter-spacing: -0.5px;
    }

    .detail-heading p {
      margin: 0;
      color: var(--muted);
      font-size: 13px;
      line-height: 1.6;
    }

    .detail-status-box {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 18px;
      padding: 19px 21px;
      margin-bottom: 20px;
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 12px;
    }

    .detail-status-info span {
      display: block;
      margin-bottom: 7px;
      color: var(--muted);
      font-size: 11px;
    }

    .detail-status-info strong {
      color: var(--forest);
      font-size: 16px;
    }

    .detail-status {
      display: inline-block;
      padding: 7px 11px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
      white-space: nowrap;
    }

    .detail-status-done {
      color: #27633b;
      background: #e8f5e9;
    }

    .detail-status-process {
      color: #87651c;
      background: #fff5d9;
    }

    .detail-status-waiting {
      color: #52677d;
      background: #edf3f8;
    }

    .detail-status-revision {
      color: #a34d35;
      background: #fff0e9;
    }

    .detail-content {
      display: grid;
      grid-template-columns: minmax(0, 1.15fr) minmax(280px, 0.85fr);
      gap: 18px;
      align-items: start;
    }

    .detail-main,
    .detail-sidebar {
      display: flex;
      flex-direction: column;
      gap: 18px;
      min-width: 0;
    }

    .detail-card {
      padding: 20px;
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 12px;
    }

    .detail-card h2 {
      margin: 0 0 17px;
      color: var(--forest);
      font-size: 15px;
      font-weight: 700;
    }

    .detail-information {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 19px 15px;
    }

    .detail-field span {
      display: block;
      margin-bottom: 6px;
      color: var(--muted);
      font-size: 11px;
    }

    .detail-field strong {
      display: block;
      color: var(--text);
      font-size: 12px;
      font-weight: 650;
      line-height: 1.5;
      overflow-wrap: anywhere;
    }

    .detail-description {
      padding: 13px 14px;
      color: #68765e;
      background: #f2f7ed;
      border-left: 3px solid #9fc77f;
      border-radius: 5px;
      font-size: 12px;
      line-height: 1.7;
    }

    .detail-file-list {
      display: flex;
      flex-direction: column;
      gap: 0;
    }

    .detail-file {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      padding: 13px 0;
      border-top: 1px solid #edf0e9;
    }

    .detail-file:first-child {
      padding-top: 0;
      border-top: none;
    }

    .detail-file:last-child {
      padding-bottom: 0;
    }

    .detail-file-name {
      display: flex;
      align-items: center;
      gap: 10px;
      color: var(--text);
      font-size: 12px;
      font-weight: 600;
      line-height: 1.5;
    }

    .detail-file-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      width: 34px;
      height: 34px;
      color: var(--spruce);
      background: #eff6eb;
      border-radius: 8px;
      font-size: 15px;
    }

    .detail-file-status {
      color: var(--muted);
      font-size: 10px;
      white-space: nowrap;
    }

    .detail-file-status.accepted {
      color: #27633b;
      font-weight: 700;
    }

    .detail-file-status.revision {
      color: #a34d35;
      font-weight: 700;
    }

    .detail-timeline {
      position: relative;
      display: flex;
      flex-direction: column;
      gap: 0;
    }

    .detail-timeline-item {
      position: relative;
      display: flex;
      gap: 13px;
      padding-bottom: 23px;
    }

    .detail-timeline-item:last-child {
      padding-bottom: 0;
    }

    .detail-timeline-marker {
      position: relative;
      z-index: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      width: 25px;
      height: 25px;
      color: #879285;
      background: #edf0e9;
      border-radius: 50%;
      font-size: 11px;
      font-weight: 700;
    }

    .detail-timeline-marker.completed {
      color: #fff;
      background: #3e8050;
    }

    .detail-timeline-item:not(:last-child)::after {
      position: absolute;
      top: 25px;
      left: 12px;
      width: 1px;
      height: calc(100% - 25px);
      background: #dfe7d9;
      content: '';
    }

    .detail-timeline-body {
      flex: 1;
      min-width: 0;
      padding-top: 2px;
    }

    .detail-timeline-body strong {
      display: block;
      margin-bottom: 4px;
      color: var(--text);
      font-size: 12px;
      font-weight: 700;
    }

    .detail-timeline-body span {
      display: block;
      margin-bottom: 5px;
      color: var(--muted);
      font-size: 10px;
    }

    .detail-timeline-body p {
      margin: 0;
      color: var(--muted);
      font-size: 11px;
      line-height: 1.6;
    }

    .detail-download {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      width: 100%;
      padding: 11px 14px;
      color: #fff;
      background: var(--spruce);
      border: none;
      border-radius: 8px;
      font-family: inherit;
      font-size: 12px;
      font-weight: 650;
      cursor: pointer;
    }

    .detail-download:hover {
      background: var(--forest);
    }

    .detail-download-note {
      margin-top: 10px;
      color: var(--muted);
      font-size: 10px;
      line-height: 1.6;
      text-align: center;
    }

    .detail-prototype-note {
      margin-top: 18px;
      padding: 12px 14px;
      color: #68765e;
      background: #f2f7ed;
      border-left: 3px solid #9fc77f;
      border-radius: 5px;
      font-size: 11px;
      line-height: 1.6;
    }

    @media (max-width: 760px) {
      .detail-content {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 520px) {
      .detail-heading h1 {
        font-size: 21px;
      }

      .detail-status-box {
        align-items: flex-start;
        flex-direction: column;
      }

      .detail-card {
        padding: 16px;
      }

      .detail-information {
        grid-template-columns: 1fr;
        gap: 15px;
      }
    }
  </style>

  <div class="detail-page">
    <a href="{{ route('mahasiswa.riwayat') }}" class="detail-back">
      &larr; Kembali ke Riwayat
    </a>

    <div class="detail-heading">
      <h1>Detail Pengajuan</h1>
      <p>Informasi lengkap mengenai pengajuan surat yang kamu pilih.</p>
    </div>

    <div class="detail-status-box">
      <div class="detail-status-info">
        <span>Nomor Pengajuan</span>
        <strong>{{ $item['nomor'] }}</strong>
      </div>

      <span class="detail-status {{ $statusClass }}">
        {{ $item['status'] }}
      </span>
    </div>

    <div class="detail-content">
      <div class="detail-main">
        <section class="detail-card">
          <h2>Informasi Pengajuan</h2>

          <div class="detail-information">
            <div class="detail-field">
              <span>Jenis Surat</span>
              <strong>{{ $item['jenis'] }}</strong>
            </div>

            <div class="detail-field">
              <span>Nomor Pengajuan</span>
              <strong>{{ $item['nomor'] }}</strong>
            </div>

            <div class="detail-field">
              <span>Tanggal Pengajuan</span>
              <strong>{{ $item['tanggal'] }}</strong>
            </div>

            <div class="detail-field">
              <span>Pembaruan Terakhir</span>
              <strong>{{ $item['diperbarui'] }}</strong>
            </div>
          </div>
        </section>

        <section class="detail-card">
          <h2>Berkas Persyaratan</h2>

          <div class="detail-file-list">
            @foreach ($item['berkas'] as $berkas)
              <div class="detail-file">
                <div class="detail-file-name">
                  <div class="detail-file-icon">▤</div>
                  <span>{{ $berkas['nama'] }}</span>
                </div>

                <span class="detail-file-status
                                    {{ $berkas['status'] === 'Diterima' ? 'accepted' : '' }}
                                    {{ $berkas['status'] === 'Perlu diperbaiki' ? 'revision' : '' }}">
                  {{ $berkas['status'] }}
                </span>
              </div>
            @endforeach
          </div>
        </section>

        <section class="detail-card">
          <h2>Perkembangan Pengajuan</h2>

          <div class="detail-timeline">
            @foreach ($item['timeline'] as $index => $tahap)
              <div class="detail-timeline-item">
                <div class="detail-timeline-marker
                                    {{ $tahap['selesai'] ? 'completed' : '' }}">
                  @if ($tahap['selesai'])
                    ✓
                  @else
                    {{ $index + 1 }}
                  @endif
                </div>

                <div class="detail-timeline-body">
                  <strong>{{ $tahap['judul'] }}</strong>
                  <span>{{ $tahap['tanggal'] }}</span>
                  <p>{{ $tahap['keterangan'] }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </section>
      </div>

      <aside class="detail-sidebar">
        <section class="detail-card">
          <h2>Keterangan Pengajuan</h2>

          <div class="detail-description">
            {{ $item['keterangan'] }}
          </div>
        </section>

        @if ($item['status'] === 'Selesai')
          <section class="detail-card">
            <h2>Surat Selesai Diproses</h2>

            <button type="button" class="detail-download" onclick="showDetailDownloadNotice()">
              <span>↓</span>
              Download Surat
            </button>

            <p class="detail-download-note">
              File surat akan tersedia setelah terhubung dengan sistem penerbitan.
            </p>
          </section>
        @elseif ($item['status'] === 'Perlu Perbaikan')
          <section class="detail-card">
            <h2>Yang Perlu Dilakukan</h2>

            <div class="detail-description">
              Periksa kembali berkas yang berstatus perlu diperbaiki.
              Silakan lakukan perbaikan sesuai arahan petugas.
            </div>
          </section>
        @endif
      </aside>
    </div>

    <div class="detail-prototype-note">
      <strong>Catatan:</strong> Informasi pada halaman ini masih menggunakan
      data contoh. Status, berkas, dan riwayat proses nantinya akan mengikuti
      data sebenarnya dari database dan aktivitas petugas.
    </div>
  </div>

  <script>
    function showDetailDownloadNotice() {
      alert(
        'File surat belum tersedia. Fitur download akan aktif setelah surat diterbitkan dan terhubung dengan sistem.'
      );
    }
  </script>
@endsection