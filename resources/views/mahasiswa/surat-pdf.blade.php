
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat {{ $pengajuan->kode_pengajuan }}</title>
    <style>
        @page {
            margin: 2.5cm 2.5cm;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #222;
        }

        .kop {
            text-align: center;
            border-bottom: 3px double #222;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }

        .kop h2 {
            font-size: 15pt;
            margin: 0;
        }

        .kop p {
            margin: 3px 0;
            font-size: 10pt;
        }

        .judul {
            text-align: center;
            margin: 25px 0;
        }

        .judul h3 {
            text-decoration: underline;
            margin-bottom: 3px;
        }

        .nomor {
            text-align: center;
            margin: 0;
        }

        .isi {
            text-align: justify;
            margin-top: 25px;
        }

        .ttd {
            width: 45%;
            margin-left: auto;
            margin-top: 45px;
            text-align: left;
        }

        .nama {
            margin-top: 65px;
            font-weight: bold;
            text-decoration: underline;
        }

        .kode {
            margin-top: 35px;
            font-size: 9pt;
            color: #555;
        }
    </style>
</head>
<body>

    <div class="kop">
        <h2>UNIVERSITAS RIAU</h2>
        <h2>FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM</h2>
        <p>Universitas Riau</p>
    </div>

    <div class="judul">
        <h3>{{ $pengajuan->isiSurat->perihal }}</h3>
        <p class="nomor">
            Nomor: {{ $pengajuan->tandaTangan->nomor_surat }}
        </p>
    </div>

    <p>Kepada Yth.</p>
    <p>{{ $pengajuan->isiSurat->tujuan_surat ?? 'Pihak yang berkepentingan' }}</p>

    <div class="isi">
        <p>{{ $pengajuan->isiSurat->paragraf_pembuka }}</p>

        <p>
            Nama: {{ $pengajuan->nama_snapshot }}<br>
            NIM: {{ $pengajuan->nim_snapshot }}<br>
            Program Studi: {{ $pengajuan->prodi_snapshot }}<br>
            Semester: {{ $pengajuan->semester_snapshot }}
        </p>

        <p>{{ $pengajuan->isiSurat->paragraf_isi }}</p>

        <p>{{ $pengajuan->isiSurat->paragraf_penutup }}</p>
    </div>

    <div class="ttd">
        <p>
            Pekanbaru,
            {{ $pengajuan->tandaTangan->ditandatangani_pada
                ? \Carbon\Carbon::parse($pengajuan->tandaTangan->ditandatangani_pada)->translatedFormat('d F Y')
                : '-' }}
        </p>

        <p>{{ $pengajuan->tandaTangan->jabatan_penanda_tangan }}</p>

        <p class="nama">
            {{ $pengajuan->tandaTangan->nama_penanda_tangan }}
        </p>

        <p>
            NIP. {{ $pengajuan->tandaTangan->nip_penanda_tangan }}
        </p>
    </div>

    <div class="kode">
        Kode pengajuan: {{ $pengajuan->kode_pengajuan }}<br>
        Kode verifikasi: {{ $pengajuan->tandaTangan->kode_verifikasi }}
    </div>

</body>
</html>
