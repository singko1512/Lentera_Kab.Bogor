<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Rekomendasi Kesbangpol #{{ $layanan->id }}</title>
    <style>
        @page {
            margin: 2cm 2cm 2cm 2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000;
            margin-bottom: 15px;
            padding-bottom: 5px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo {
            width: 80px;
            height: auto;
        }
        .header-text {
            text-align: center;
        }
        .header-text h3 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-text h2 {
            margin: 0;
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-text p {
            margin: 2px 0 0 0;
            font-size: 9.5pt;
            font-style: italic;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .meta-table td {
            vertical-align: top;
            font-size: 11pt;
        }
        .content {
            text-align: justify;
            margin-bottom: 15px;
            font-size: 11pt;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 15px 0;
            font-size: 11pt;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 5px 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .footer-table {
            width: 100%;
            margin-top: 30px;
            font-size: 11pt;
        }
        .footer-table td {
            vertical-align: top;
        }
        .qr-section {
            text-align: center;
            padding-top: 10px;
        }
        .qr-img {
            width: 100px;
            height: 100px;
            border: 1px solid #ddd;
            padding: 3px;
            background: #fff;
        }
        .qr-text {
            font-size: 8pt;
            color: #555;
            margin-top: 3px;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <table class="header-table">
        <tr>
            <td style="width: 15%; text-align: center;">
                <img src="{{ public_path('assets/certificate/lambang_kabupaten_bogor.png') }}" class="logo" alt="Logo Pemkab Bogor">
            </td>
            <td style="width: 85%;" class="header-text">
                <h3>PEMERINTAH KABUPATEN BOGOR</h3>
                <h2>BADAN KESATUAN BANGSA DAN POLITIK</h2>
                <p>Jl. Tegar Beriman No. 1 Komplek Perkantoran Pemda Cibinong Telp. (021) 8754101 Kode Pos 16914</p>
            </td>
        </tr>
    </table>

    <!-- Nomor & Tujuan Surat -->
    <table class="meta-table">
        <tr>
            <td style="width: 12%;">Nomor</td>
            <td style="width: 2%;">:</td>
            <td style="width: 46%;">070/{{ $layanan->id }}/Bakesbangpol/{{ date('Y') }}</td>
            <td style="width: 40%; text-align: right;">Cibinong, {{ \Carbon\Carbon::parse($layanan->updated_at ?? now())->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Sifat</td>
            <td>:</td>
            <td>Biasa</td>
            <td rowspan="3" style="vertical-align: top; text-align: left; padding-left: 30px;">
                Kepada Yth.<br>
                <strong>Kepala {{ $layanan->tempat_kegiatan ?? 'Dinas Tujuan' }}</strong><br>
                Kabupaten Bogor<br>
                di -<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<u>CIBINONG</u>
            </td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>:</td>
            <td><strong><u>Rekomendasi {{ $layanan->jenisLayanan->nama ?? 'Izin Kegiatan' }}</u></strong></td>
        </tr>
    </table>

    <!-- Isi Surat -->
    <div class="content">
        <p style="text-indent: 30px; margin-bottom: 10px;">
            Menindaklanjuti Surat Pengantar dari <strong>{{ $layanan->asal_instansi ?? 'Instansi/Perguruan Tinggi' }}</strong> Nomor: <strong>{{ $layanan->nomor_surat_pengantar ?: ('SRT/' . $layanan->id . '/' . date('Y')) }}</strong> tanggal {{ \Carbon\Carbon::parse($layanan->created_at ?? now())->translatedFormat('d F Y') }}, perihal Rekomendasi Pelaksanaan Kegiatan {{ $layanan->jenisLayanan->nama ?? 'Magang / Penelitian' }}.
        </p>

        <p style="text-indent: 30px; margin-bottom: 10px;">
            Kepala Badan Kesatuan Bangsa dan Politik Kabupaten Bogor memberikan Rekomendasi Izin Pelaksanaan Kegiatan kepada:
        </p>

        <!-- Tabel Data Pemohon -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 35%;">Nama Pemohon / Penanggung Jawab</th>
                    <th style="width: 35%;">Asal Instansi / Perguruan Tinggi</th>
                    <th style="width: 25%;">Jumlah Peserta</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td><strong>{{ $layanan->atas_nama ?? '-' }}</strong></td>
                    <td>{{ $layanan->asal_instansi ?? '-' }}</td>
                    <td style="text-align: center;">{{ $layanan->jumlah_anggota ?? 1 }} Orang</td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%; font-size: 11pt; margin-bottom: 10px;">
            <tr>
                <td style="width: 25%; font-weight: bold;">Judul / Tema Kegiatan</td>
                <td style="width: 2%;">:</td>
                <td style="width: 73%;">{{ $layanan->judul_kegiatan ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Lokasi / Tempat</td>
                <td>:</td>
                <td>{{ $layanan->tempat_kegiatan ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Waktu Pelaksanaan</td>
                <td>:</td>
                <td>
                    {{ \Carbon\Carbon::parse($layanan->tanggal_mulai)->translatedFormat('d F Y') }}
                    s.d.
                    {{ \Carbon\Carbon::parse($layanan->tanggal_selesai)->translatedFormat('d F Y') }}
                </td>
            </tr>
        </table>

        <p style="text-indent: 30px; margin-bottom: 10px;">
            Dengan ketentuan bahwa pemohon/peserta wajib menaati dan mematuhi peraturan perundang-undangan yang berlaku, tidak melakukan kegiatan politik praktis, serta melaporkan hasil kegiatan kepada Badan Kesatuan Bangsa dan Politik Kabupaten Bogor setelah kegiatan selesai.
        </p>

        <p style="text-indent: 30px;">
            Demikian rekomendasi ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <!-- Tanda Tangan & QR Code -->
    <table class="footer-table">
        <tr>
            <!-- Kolom QR Code Validator -->
            <td style="width: 45%; text-align: center; vertical-align: middle;">
                <div class="qr-section">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode($qrUrl) }}" class="qr-img" alt="QR Code Keabsahan Surat">
                    <div class="qr-text">
                        <strong>Scan QR Code</strong><br>
                        Untuk memverifikasi keabsahan dokumen PDF ini
                    </div>
                </div>
            </td>
            <!-- Kolom Pejabat TTD -->
            <td style="width: 55%; text-align: center;">
                <strong>a.n. KEPALA BADAN KESATUAN BANGSA DAN POLITIK<br>KABUPATEN BOGOR</strong><br>
                <div style="height: 60px;"></div>
                <strong><u>Drs. BAMBANG WIDODO TAWEKAL, M.Si</u></strong><br>
                Pembina Utama Muda, IV/c<br>
                NIP. 19680512 199003 1 005
            </td>
        </tr>
    </table>

</body>
</html>
