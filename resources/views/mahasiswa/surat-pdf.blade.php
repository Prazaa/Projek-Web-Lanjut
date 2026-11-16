<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat {{ $pengajuan->kode_pengajuan }}</title>
    <style>
        @page {
            margin: 2.2cm 2.5cm;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11pt;
            line-height: 1.55;
            color: #111;
        }

        .kop {
            text-align: center;
            border-bottom: 3px double #111;
            padding-bottom: 10px;
            margin-bottom: 22px;
        }

        .kop h2 {
            font-size: 14pt;
            margin: 0;
            letter-spacing: 0.4px;
        }

        .kop p {
            margin: 3px 0 0;
            font-size: 10pt;
        }

        .judul {
            text-align: center;
            margin: 20px 0 24px;
        }

        .judul h3 {
            text-decoration: underline;
            margin: 0 0 4px;
            font-size: 12pt;
            text-transform: uppercase;
        }

        .nomor {
            margin: 0;
            font-size: 10.5pt;
        }

        .isi {
            text-align: justify;
            margin-top: 18px;
        }

        .biodata {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 14px 20px;
        }

        .biodata td {
            padding: 2px 4px;
            vertical-align: top;
        }

        .biodata td.label {
            width: 150px;
        }

        .biodata td.colon {
            width: 14px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 38px;
        }

        .footer-table td {
            vertical-align: bottom;
        }

        .qr-box {
            width: 52%;
            font-size: 8.5pt;
            color: #444;
        }

        .qr-box img {
            width: 88px;
            height: 88px;
            display: block;
            margin-bottom: 6px;
        }

        .ttd-box {
            width: 48%;
            text-align: left;
            padding-left: 24px;
        }

        .nama {
            margin-top: 62px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="kop">
        <h2>KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h2>
        <h2>UNIVERSITAS RIAU</h2>
        <h2>FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM</h2>
        <p>Kampus Bina Widya Km. 12,5 Simpang Baru Pekanbaru 28293</p>
    </div>

    <div class="judul">
        <h3>{{ $pengajuan->isiSurat->perihal }}</h3>
        <p class="nomor">Nomor: {{ $pengajuan->tandaTangan->nomor_surat }}</p>
    </div>

    <p style="margin-bottom: 2px;">Kepada Yth.</p>
    <p style="margin-top: 0;">{{ $pengajuan->isiSurat->tujuan_surat ?? 'Pihak yang berkepentingan' }}</p>

    <div class="isi">
        <p>{{ $pengajuan->isiSurat->paragraf_pembuka }}</p>

        <table class="biodata">
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
                <td class="label">Program Studi</td>
                <td class="colon">:</td>
                <td>{{ $pengajuan->prodi_snapshot }}</td>
            </tr>
            <tr>
                <td class="label">Semester</td>
                <td class="colon">:</td>
                <td>{{ $pengajuan->semester_snapshot }}</td>
            </tr>
        </table>

        <p>{{ $pengajuan->isiSurat->paragraf_isi }}</p>

        <p>{{ $pengajuan->isiSurat->paragraf_penutup }}</p>
    </div>

    <table class="footer-table">
        <tr>
            <td class="qr-box">
                @if (!empty($qrCodeDataUri))
                    <img src="{{ $qrCodeDataUri }}" alt="QR Verifikasi Surat">
                @endif
                Dokumen ini ditandatangani secara elektronik melalui SIMKA FMIPA UNRI.<br>
                Kode Pengajuan: <strong>{{ $pengajuan->kode_pengajuan }}</strong><br>
                Kode Verifikasi: <strong>{{ $pengajuan->tandaTangan->kode_verifikasi }}</strong>
            </td>
            <td class="ttd-box">
                <p style="margin: 0;">
                    Pekanbaru,
                    {{ $pengajuan->tandaTangan->ditandatangani_pada
                        ? \Carbon\Carbon::parse($pengajuan->tandaTangan->ditandatangani_pada)->translatedFormat('d F Y')
                        : '-' }}
                </p>
                <p style="margin: 2px 0 0;">{{ $pengajuan->tandaTangan->jabatan_penanda_tangan }}</p>

                <p class="nama" style="margin-bottom: 2px;">
                    {{ $pengajuan->tandaTangan->nama_penanda_tangan }}
                </p>
                <p style="margin: 0;">
                    NIP. {{ $pengajuan->tandaTangan->nip_penanda_tangan }}
                </p>
            </td>
        </tr>
    </table>

</body>
</html>