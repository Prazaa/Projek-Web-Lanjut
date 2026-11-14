@extends('mahasiswa.layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page-title', 'Dashboard Mahasiswa')

@section('content')

  @php
    // Data sementara untuk contoh tampilan dashboard.
    // Nantinya akan diganti dengan data dari database.
    $statistics = [
      [
        'label' => 'Total Pengajuan',
        'value' => 5,
        'icon' => '▤',
        'color' => '#eaf2ff',
        'iconColor' => '#2563eb',
      ],
      [
        'label' => 'Sedang Diproses',
        'value' => 2,
        'icon' => '◷',
        'color' => '#fff7e6',
        'iconColor' => '#d97706',
      ],
      [
        'label' => 'Selesai',
        'value' => 2,
        'icon' => '✓',
        'color' => '#e8f8ef',
        'iconColor' => '#16a34a',
      ],
      [
        'label' => 'Ditolak',
        'value' => 1,
        'icon' => '×',
        'color' => '#fff0f0',
        'iconColor' => '#dc2626',
      ],
    ];

    $recentRequests = [
      [
        'number' => 'SIMKA-001',
        'type' => 'Surat Aktif Kuliah',
        'date' => '12 November 2026',
        'status' => 'Diproses',
      ],
      [
        'number' => 'SIMKA-002',
        'type' => 'Surat Keterangan Tidak Menerima Beasiswa',
        'date' => '08 November 2026',
        'status' => 'Selesai',
      ],
      [
        'number' => 'SIMKA-003',
        'type' => 'Surat Kehilangan KTM',
        'date' => '05 November 2026',
        'status' => 'Ditolak',
      ],
    ];
  @endphp

  <style>
    .welcome-box {
      background: linear-gradient(120deg, #174b87, #2563a6);
      color: white;
      padding: 27px 30px;
      border-radius: 14px;
      margin-bottom: 25px;
      position: relative;
      overflow: hidden;
    }

    .welcome-box h1 {
      font-size: 22px;
      font-weight: 700;
      margin: 0 0 9px;
    }

    .welcome-box p {
      color: #dceaff;
      font-size: 13px;
      margin: 0;
      line-height: 1.7;
      max-width: 550px;
    }

    .welcome-tag {
      display: inline-block;
      margin-top: 17px;
      padding: 7px 12px;
      border-radius: 7px;
      background: rgba(255, 255, 255, .15);
      font-size: 11px;
    }

    .section-title {
      font-size: 15px;
      font-weight: 700;
      color: #243b5a;
      margin: 0 0 16px;
    }

    .statistics-grid {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 16px;
      margin-bottom: 28px;
    }

    .stat-card {
      background: white;
      padding: 20px;
      border: 1px solid #e9eef5;
      border-radius: 12px;
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .stat-icon {
      width: 45px;
      height: 45px;
      border-radius: 11px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      font-weight: 700;
      flex-shrink: 0;
    }

    .stat-label {
      font-size: 11px;
      color: #8996a8;
      margin-bottom: 6px;
    }

    .stat-value {
      font-size: 23px;
      font-weight: 800;
      color: #203653;
    }

    .table-wrapper {
      width: 100%;
      overflow-x: auto;
    }

    .request-table {
      width: 100%;
      border-collapse: collapse;
      min-width: 600px;
    }

    .request-table th {
      text-align: left;
      padding: 13px 14px;
      background: #f8fafc;
      color: #8290a3;
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: .5px;
      font-weight: 700;
    }

    .request-table td {
      padding: 16px 14px;
      border-bottom: 1px solid #edf1f5;
      font-size: 12px;
      color: #475569;
    }

    .request-table tr:last-child td {
      border-bottom: none;
    }

    .request-number {
      font-weight: 700;
      color: #24558e;
    }

    .status {
      display: inline-block;
      padding: 6px 10px;
      border-radius: 20px;
      font-size: 10px;
      font-weight: 700;
    }

    .status-proses {
      background: #fff4d9;
      color: #a16207;
    }

    .status-selesai {
      background: #dcfce7;
      color: #15803d;
    }

    .status-ditolak {
      background: #fee2e2;
      color: #b91c1c;
    }

    .view-link {
      color: #2563a6;
      text-decoration: none;
      font-weight: 600;
      font-size: 11px;
    }

    .view-link:hover {
      text-decoration: underline;
    }

    .table-heading {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px;
    }

    .table-heading a {
      color: #2563a6;
      text-decoration: none;
      font-size: 11px;
      font-weight: 600;
    }

    @media (max-width: 1000px) {
      .statistics-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 500px) {
      .statistics-grid {
        grid-template-columns: 1fr;
      }

      .welcome-box {
        padding: 22px;
      }

      .welcome-box h1 {
        font-size: 19px;
      }
    }
  </style>

  <div class="welcome-box">
    <h1>Selamat Datang, {{ auth()->user()->name }}!</h1>

    <p>
      Selamat datang di SIMKA FMIPA Universitas Riau.
      Melalui halaman ini, kamu dapat mengajukan surat kemahasiswaan
      dan memantau perkembangan pengajuanmu.
    </p>

    <span class="welcome-tag">Portal Mahasiswa</span>
  </div>

  <h2 class="section-title">Ringkasan Pengajuan</h2>

  <div class="statistics-grid">
    @foreach ($statistics as $stat)
      <div class="stat-card">
        <div class="stat-icon" style="background: {{ $stat['color'] }}; color: {{ $stat['iconColor'] }}">
          {{ $stat['icon'] }}
        </div>

        <div>
          <div class="stat-label">{{ $stat['label'] }}</div>
          <div class="stat-value">{{ $stat['value'] }}</div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="content-card">
    <div class="table-heading">
      <h2 class="section-title" style="margin: 0;">
        Pengajuan Terbaru
      </h2>

      <a href="{{ url('/mahasiswa/riwayat') }}">
        Lihat Semua →
      </a>
    </div>

    <div class="table-wrapper">
      <table class="request-table">
        <thead>
          <tr>
            <th>Nomor Pengajuan</th>
            <th>Jenis Surat</th>
            <th>Tanggal Pengajuan</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          @foreach ($recentRequests as $request)
            <tr>
              <td class="request-number">
                {{ $request['number'] }}
              </td>

              <td>{{ $request['type'] }}</td>

              <td>{{ $request['date'] }}</td>

              <td>
                @if ($request['status'] === 'Diproses')
                  <span class="status status-proses">
                    Diproses
                  </span>
                @elseif ($request['status'] === 'Selesai')
                  <span class="status status-selesai">
                    Selesai
                  </span>
                @else
                  <span class="status status-ditolak">
                    Ditolak
                  </span>
                @endif
              </td>

              <td>
                <a href="#" class="view-link">
                  Lihat Detail
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

@endsection