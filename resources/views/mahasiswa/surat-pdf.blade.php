<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat {{ $pengajuan->kode_pengajuan }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.25cm 2cm 1.25cm 2cm;
        }

        body {
            font-family: "Times-Roman", "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.35;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2.5px solid #000;
            padding-bottom: 4px;
        }


        .kop-logo {
            width: 95pt;
            vertical-align: middle;
            text-align: left;
            padding: 0 4pt 4pt 0;
        }


        .kop-teks {
            text-align: center;
            vertical-align: middle;
            padding: 0 4px 4px 0;
        }

        .kop-teks .kementerian {
            font-size: 11.5pt;
            font-weight: bold;
            line-height: 1.15;
            margin: 0;
        }

        .kop-teks .universitas {
            font-size: 11.5pt;
            font-weight: bold;
            line-height: 1.15;
            margin: 1px 0;
        }

        .kop-teks .fakultas {
            font-size: 11.5pt;
            font-weight: bold;
            line-height: 1.15;
            margin: 1px 0 2px;
        }

        .kop-teks .alamat {
            font-size: 9pt;
            line-height: 1.2;
            margin: 0;
        }

        .judul-surat {
            text-align: center;
            margin: 12px 0 14px;
        }

        .judul-surat h3 {
            margin: 0 0 2px;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .judul-surat .nomor {
            margin: 0;
            font-size: 12pt;
        }

        .pembuka {
            margin: 0 0 4px;
        }

        .tabel-data {
            width: 100%;
            border-collapse: collapse;
            margin: 2px 0 10px 18px;
        }

        .tabel-data td {
            padding: 1px 3px;
            vertical-align: top;
            line-height: 1.3;
        }

        .tabel-data td.label {
            width: 175px;
            white-space: nowrap;
        }

        .tabel-data td.colon {
            width: 12px;
        }

        .paragraf {
            text-align: justify;
            margin: 8px 0;
            line-height: 1.38;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
            page-break-inside: avoid;
        }

        .footer-table td {
            vertical-align: top;
        }

        .qr-kolom {
            width: 48%;
            padding-top: 10px;
            font-size: 8.5pt;
            line-height: 1.25;
            color: #222;
        }

        .qr-kolom img {
            width: 72px;
            height: 72px;
            display: block;
            margin-bottom: 4px;
        }

        .ttd-kolom {
            width: 52%;
            padding-left: 16px;
            text-align: left;
        }

        .ttd-kolom p {
            margin: 0;
            line-height: 1.3;
        }

        .ttd-ruang {
            height: 54px;
        }

        .ttd-nama {
            font-weight: bold;
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('assets/img/logo-unri.png');
        $logoBase64 = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        $tanggalLahirTeks = $pengajuan->mahasiswa?->tanggal_lahir
            ? \Carbon\Carbon::parse($pengajuan->mahasiswa->tanggal_lahir)->translatedFormat('d F Y')
            : '-';

        $ttlGabungan = trim(($pengajuan->mahasiswa?->tempat_lahir ?? '-') . ', ' . $tanggalLahirTeks, ', ');
    @endphp

    <!-- KOP SURAT RESMI FMIPA UNRI -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" style="width: 88pt; height: auto;" alt="Logo Universitas Riau">
                @endif
            </td>
            <td class="kop-teks">
                <p class="kementerian">KEMENTERIAN PENDIDIKAN TINGGI, SAINS,<br>DAN TEKNOLOGI</p>
                <p class="universitas">UNIVERSITAS RIAU</p>
                <p class="fakultas">FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM</p>
                <p class="alamat">
                    Kampus Bina Widya Km. 12,5 Simpang Baru Pekanbaru 28293<br>
                    Telepon (0761) 63273 Faksimile (0761) 63273<br>
                    Laman: <span style="text-decoration: underline; color: #0000EE;">www.fmipa.unri.ac.id</span> Email: fmipa@unri.ac.id
                </p>
            </td>
        </tr>
    </table>

    <!-- JUDUL & NOMOR SURAT -->
    <div class="judul-surat">
        <h3>{{ $pengajuan->jenisSurat->nama ?? $pengajuan->isiSurat->perihal }}</h3>
        <p class="nomor">Nomor : {{ $pengajuan->tandaTangan->nomor_surat }}</p>
    </div>

    <!-- BLOK PEJABAT PENANDA TANGAN -->
    <p class="pembuka">Yang bertanda tangan dibawah ini,</p>
    <table class="tabel-data">
        <tr>
            <td class="label">Nama</td>
            <td class="colon">:</td>
            <td>{{ $pengajuan->tandaTangan->nama_penanda_tangan }}</td>
        </tr>
        <tr>
            <td class="label">NIP</td>
            <td class="colon">:</td>
            <td>{{ $pengajuan->tandaTangan->nip_penanda_tangan }}</td>
        </tr>
        <tr>
            <td class="label">Pangkat</td>
            <td class="colon">:</td>
            <td>Penata Tk.I/IIId</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="colon">:</td>
            <td>{{ $pengajuan->tandaTangan->jabatan_penanda_tangan }} Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Riau</td>
        </tr>
    </table>

    <!-- BLOK IDENTITAS MAHASISWA -->
    <p class="pembuka">dengan ini menerangkan bahwa,</p>
    <table class="tabel-data">
        <tr>
            <td class="label">Nama</td>
            <td class="colon">:</td>
            <td>{{ $pengajuan->nama_snapshot }}</td>
        </tr>
        <tr>
            <td class="label">NIM</td>
            <td class="colon">:</td>
            <td>{{ $pengajuan->nim_snapshot }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tanggal Lahir</td>
            <td class="colon">:</td>
            <td>{{ $ttlGabungan }}</td>
        </tr>
        <tr>
            <td class="label">Jurusan/Prodi</td>
            <td class="colon">:</td>
            <td>{{ $pengajuan->prodi_snapshot }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td class="colon">:</td>
            <td>{{ $pengajuan->mahasiswa?->alamat ?? '-' }}</td>
        </tr>
    </table>

    <!-- ISI & PENUTUP SURAT -->
    <p class="paragraf">
        {{ $pengajuan->isiSurat->paragraf_isi }}
    </p>

    <p class="paragraf">
        {{ $pengajuan->isiSurat->paragraf_penutup ?: 'Demikian surat keterangan ini dibuat untuk digunakan sebagaimana mestinya.' }}
    </p>

    <!-- BLOK TANDA TANGAN ELEKTRONIK (QR CODE) -->
    <table class="footer-table">
        <tr>
            <td style="width: 48%;"></td>
            <td class="ttd-kolom" style="width: 52%; padding-left: 16px; text-align: left; vertical-align: top;">
                <p style="margin: 0; line-height: 1.3;">
                    Pekanbaru,
                    {{ $pengajuan->tandaTangan->ditandatangani_pada
                        ? \Carbon\Carbon::parse($pengajuan->tandaTangan->ditandatangani_pada)->translatedFormat('d F Y')
                        : '-' }}
                </p>
                <p style="margin: 0; line-height: 1.3;">{{ $pengajuan->tandaTangan->jabatan_penanda_tangan }},</p>

                <div style="margin: 8px 0;">
                    @if (!empty($qrCodeDataUri))
                        <img src="{{ $qrCodeDataUri }}" alt="QR Tanda Tangan Elektronik" style="width: 76px; height: 76px; display: block;">
                    @else
                        <div style="height: 60px;"></div>
                    @endif
                </div>

                <p class="ttd-nama" style="margin: 0; font-weight: bold;">{{ $pengajuan->tandaTangan->nama_penanda_tangan }}</p>
                <p style="margin: 0;">NIP {{ $pengajuan->tandaTangan->nip_penanda_tangan }}</p>
            </td>
        </tr>
    </table>
</body>
</html>