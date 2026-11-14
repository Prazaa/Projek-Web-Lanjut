@extends('mahasiswa.layouts.app')

@section('content')
  @php
    // Data sementara untuk contoh tampilan.
    // Nantinya akan diganti dengan data dari database.
    $pengajuan = [
      [
        'nomor' => 'SIMKA-2026-001',
        'jenis' => 'Surat Aktif Kuliah',
        'tanggal' => '10 November 2026',
        'diperbarui' => '12 November 2026',
        'status' => 'Selesai',
        'keterangan' => 'Surat telah selesai diproses.',
      ],
      [
        'nomor' => 'SIMKA-2026-002',
        'jenis' => 'Surat Keterangan Berkelakuan Baik',
        'tanggal' => '12 November 2026',
        'diperbarui' => '13 November 2026',
        'status' => 'Sedang Diproses',
        'keterangan' => 'Pengajuan sedang diperiksa oleh petugas.',
      ],
      [
        'nomor' => 'SIMKA-2026-003',
        'jenis' => 'Surat Kehilangan KTM',
        'tanggal' => '14 November 2026',
        'diperbarui' => '14 November 2026',
        'status' => 'Menunggu Validasi',
        'keterangan' => 'Pengajuan menunggu pemeriksaan awal.',
      ],
      [
        'nomor' => 'SIMKA-2026-004',
        'jenis' => 'Surat Kehilangan KHS',
        'tanggal' => '14 November 2026',
        'diperbarui' => '14 November 2026',
        'status' => 'Perlu Perbaikan',
        'keterangan' => 'Terdapat berkas yang perlu diperbaiki.',
      ],
    ];

    $jumlahPengajuan = count($pengajuan);
    $jumlahSelesai = collect($pengajuan)->where('status', 'Selesai')->count();
    $jumlahDiproses = collect($pengajuan)->whereIn('status', [
      'Menunggu Validasi',
      'Sedang Diproses'
    ])->count();
    $jumlahPerbaikan = collect($pengajuan)->where('status', 'Perlu Perbaikan')->count();
  @endphp

  <style>
    .history-page {
      color: var(--text);
    }

    .history-heading {
      margin-bottom: 24px;
    }

    .history-heading h1 {
      margin: 0 0 7px;
      color: var(--forest);
      font-size: 25px;
      font-weight: 750;
      letter-spacing: -0.5px;
    }

    .history-heading p {
      margin: 0;
      color: var(--muted);
      font-size: 13px;
      line-height: 1.6;
    }

    .history-summary {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 14px;
      margin-bottom: 28px;
    }

    .history-summary-item {
      padding: 17px 18px;
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 12px;
    }

    .history-summary-item span {
      display: block;
      color: var(--muted);
      font-size: 12px;
      margin-bottom: 9px;
    }

    .history-summary-item strong {
      display: block;
      color: var(--forest);
      font-size: 25px;
      font-weight: 750;
    }

    .history-section {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 13px;
      overflow: hidden;
    }

    .history-section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 19px 21px;
      border-bottom: 1px solid var(--border);
    }

    .history-section-header h2 {
      margin: 0 0 5px;
      color: var(--forest);
      font-size: 16px;
      font-weight: 700;
    }

    .history-section-header p {
      margin: 0;
      color: var(--muted);
      font-size: 12px;
    }

    .history-filter {
      display: flex;
      gap: 9px;
      align-items: center;
    }

    .history-search,
    .history-select {
      height: 37px;
      padding: 0 11px;
      color: var(--text);
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 8px;
      font-family: inherit;
      font-size: 12px;
      outline: none;
    }

    .history-search {
      width: 190px;
    }

    .history-search:focus,
    .history-select:focus {
      border-color: #83b66a;
    }

    .history-table-wrap {
      overflow-x: auto;
    }

    .history-table {
      width: 100%;
      border-collapse: collapse;
      min-width: 850px;
    }

    .history-table th {
      padding: 13px 18px;
      color: var(--muted);
      background: #fbfcf9;
      text-align: left;
      font-size: 11px;
      font-weight: 700;
      white-space: nowrap;
    }

    .history-table td {
      padding: 16px 18px;
      border-top: 1px solid #edf0e9;
      font-size: 12px;
      vertical-align: middle;
    }

    .history-table tbody tr:hover {
      background: #fcfdfb;
    }

    .history-number {
      display: block;
      color: var(--forest);
      font-weight: 700;
      margin-bottom: 4px;
    }

    .history-type {
      color: var(--text);
      font-weight: 600;
      line-height: 1.5;
    }

    .history-date {
      color: var(--muted);
      white-space: nowrap;
    }

    .history-status {
      display: inline-block;
      padding: 6px 9px;
      border-radius: 20px;
      font-size: 10px;
      font-weight: 700;
      white-space: nowrap;
    }

    .status-done {
      color: #27633b;
      background: #e8f5e9;
    }

    .status-process {
      color: #87651c;
      background: #fff5d9;
    }

    .status-waiting {
      color: #52677d;
      background: #edf3f8;
    }

    .status-revision {
      color: #a34d35;
      background: #fff0e9;
    }

    .history-download {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      padding: 8px 11px;
      color: #fff;
      background: var(--spruce);
      border: none;
      border-radius: 7px;
      font-family: inherit;
      font-size: 11px;
      font-weight: 650;
      white-space: nowrap;
      cursor: pointer;
      transition: background 0.2s ease;
    }

    .history-download:hover {
      background: var(--forest);
    }

    .history-unavailable {
      color: var(--muted);
      font-size: 11px;
      white-space: nowrap;
    }

    .history-empty {
      display: none;
      padding: 35px 20px;
      color: var(--muted);
      text-align: center;
      font-size: 13px;
    }

    .history-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      padding: 14px 20px;
      border-top: 1px solid var(--border);
      color: var(--muted);
      font-size: 11px;
    }

    .history-create-link {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 10px 14px;
      color: white;
      background: var(--spruce);
      border-radius: 8px;
      text-decoration: none;
      font-size: 12px;
      font-weight: 650;
      white-space: nowrap;
    }

    .history-create-link:hover {
      background: var(--forest);
    }

    .history-note {
      margin-top: 15px;
      padding: 12px 14px;
      color: #68765e;
      background: #f2f7ed;
      border-left: 3px solid #9fc77f;
      border-radius: 5px;
      font-size: 11px;
      line-height: 1.6;
    }

    @media (max-width: 900px) {
      .history-summary {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .history-section-header {
        align-items: flex-start;
        flex-direction: column;
      }

      .history-filter {
        width: 100%;
      }

      .history-search {
        flex: 1;
        min-width: 0;
      }
    }

    @media (max-width: 520px) {
      .history-heading h1 {
        font-size: 21px;
      }

      .history-summary {
        gap: 9px;
      }

      .history-summary-item {
        padding: 14px;
      }

      .history-summary-item strong {
        font-size: 21px;
      }

      .history-filter {
        align-items: stretch;
        flex-direction: column;
      }

      .history-search,
      .history-select {
        width: 100%;
      }

      .history-footer {
        align-items: flex-start;
        flex-direction: column;
      }
    }
  </style>

  <div class="history-page">
    <div class="history-heading">
      <h1>Riwayat Pengajuan</h1>
      <p>Lihat daftar pengajuan surat dan perkembangan prosesnya.</p>
    </div>

    <div class="history-summary">
      <div class="history-summary-item">
        <span>Total Pengajuan</span>
        <strong>{{ $jumlahPengajuan }}</strong>
      </div>

      <div class="history-summary-item">
        <span>Selesai</span>
        <strong>{{ $jumlahSelesai }}</strong>
      </div>

      <div class="history-summary-item">
        <span>Sedang Diproses</span>
        <strong>{{ $jumlahDiproses }}</strong>
      </div>

      <div class="history-summary-item">
        <span>Perlu Perbaikan</span>
        <strong>{{ $jumlahPerbaikan }}</strong>
      </div>
    </div>

    <section class="history-section">
      <div class="history-section-header">
        <div>
          <h2>Daftar Pengajuan Surat</h2>
          <p>Daftar pengajuan yang pernah kamu kirimkan.</p>
        </div>

        <div class="history-filter">
          <input type="search" id="historySearch" class="history-search" placeholder="Cari nomor atau jenis surat...">

          <select id="historyStatus" class="history-select">
            <option value="">Semua Status</option>
            <option value="Selesai">Selesai</option>
            <option value="Sedang Diproses">Sedang Diproses</option>
            <option value="Menunggu Validasi">Menunggu Validasi</option>
            <option value="Perlu Perbaikan">Perlu Perbaikan</option>
          </select>
        </div>
      </div>

      <div class="history-table-wrap">
        <table class="history-table">
          <thead>
            <tr>
              <th>Nomor Pengajuan</th>
              <th>Jenis Surat</th>
              <th>Tanggal Pengajuan</th>
              <th>Status</th>
              <th>Pembaruan Terakhir</th>
              <th>Aksi</th>
            </tr>
          </thead>

          <tbody id="historyTableBody">
            @foreach ($pengajuan as $item)
              @php
                $statusClass = match ($item['status']) {
                  'Selesai' => 'status-done',
                  'Sedang Diproses' => 'status-process',
                  'Menunggu Validasi' => 'status-waiting',
                  'Perlu Perbaikan' => 'status-revision',
                  default => '',
                };
              @endphp

              <tr class="history-row" data-status="{{ $item['status'] }}">
                <td>
                  <span class="history-number">
                    {{ $item['nomor'] }}
                  </span>
                </td>

                <td>
                  <span class="history-type">
                    {{ $item['jenis'] }}
                  </span>
                </td>

                <td>
                  <span class="history-date">
                    {{ $item['tanggal'] }}
                  </span>
                </td>

                <td>
                  <span class="history-status {{ $statusClass }}">
                    {{ $item['status'] }}
                  </span>
                </td>

                <td>
                  <span class="history-date">
                    {{ $item['diperbarui'] }}
                  </span>
                </td>

                <td>
                  @if ($item['status'] === 'Selesai')
                    <button type="button" class="history-download" onclick="showDownloadNotice()">
                      <span>↓</span>
                      Download
                    </button>
                  @else
                    <span class="history-unavailable">
                      Belum tersedia
                    </span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="history-empty" id="historyEmpty">
        Tidak ada pengajuan yang sesuai dengan pencarian.
      </div>

      <div class="history-footer">
        <span id="historyCount">
          Menampilkan {{ $jumlahPengajuan }} pengajuan
        </span>

        <a href="{{ url('/mahasiswa/ajukan') }}" class="history-create-link">
          + Ajukan Surat Baru
        </a>
      </div>
    </section>

    <div class="history-note">
      <strong>Catatan:</strong> Data dan status pada halaman ini masih berupa
      contoh tampilan. Tombol download juga belum terhubung dengan file surat
      sebenarnya. Fitur unduhan akan diaktifkan setelah proses penerbitan
      surat dan penyimpanan file terintegrasi dengan backend.
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const searchInput = document.getElementById('historySearch');
      const statusSelect = document.getElementById('historyStatus');
      const rows = document.querySelectorAll('.history-row');
      const emptyMessage = document.getElementById('historyEmpty');
      const countText = document.getElementById('historyCount');

      function filterHistory() {
        const keyword = searchInput.value.toLowerCase().trim();
        const selectedStatus = statusSelect.value;

        let visibleCount = 0;

        rows.forEach(function (row) {
          const rowText = row.textContent.toLowerCase();
          const rowStatus = row.dataset.status;

          const matchesKeyword = rowText.includes(keyword);
          const matchesStatus =
            selectedStatus === '' || rowStatus === selectedStatus;

          if (matchesKeyword && matchesStatus) {
            row.style.display = '';
            visibleCount++;
          } else {
            row.style.display = 'none';
          }
        });

        emptyMessage.style.display =
          visibleCount === 0 ? 'block' : 'none';

        countText.textContent =
          'Menampilkan ' + visibleCount + ' pengajuan';
      }

      searchInput.addEventListener('input', filterHistory);
      statusSelect.addEventListener('change', filterHistory);
    });

    function showDownloadNotice() {
      alert(
        'File surat belum tersedia. Fitur download akan aktif setelah surat diterbitkan dan terhubung dengan sistem.'
      );
    }
  </script>
@endsection