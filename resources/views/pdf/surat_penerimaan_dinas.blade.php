<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Penerimaan Magang - #{{ $application->id }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin-top: 1.2cm;
            margin-bottom: 2.2cm;
            margin-left: 2cm;
            margin-right: 2cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            line-height: 1.35;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* Fixed Footer on all pages */
        .fixed-footer {
            position: fixed;
            bottom: -1.7cm;
            left: 0;
            right: 0;
            height: 1.4cm;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            vertical-align: middle;
            padding: 0;
        }

        .footer-qr {
            width: 48px;
        }

        .footer-qr img {
            width: 46px;
            height: 46px;
            display: block;
        }

        .footer-text {
            padding-left: 10px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7pt;
            color: #222;
            line-height: 1.3;
        }

        /* Kop Surat */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }

        .kop-logo {
            width: 75px;
            vertical-align: middle;
            text-align: center;
        }

        .kop-logo img {
            width: 68px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
            padding-right: 15px;
        }

        .kop-text h2 {
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-text h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-text p {
            font-size: 8pt;
            margin: 1px 0;
            color: #111;
        }

        /* Garis Ganda Kop */
        .garis-kop-tebal {
            border-top: 2.5px solid #000;
            margin-top: 3px;
        }

        .garis-kop-tipis {
            border-top: 1px solid #000;
            margin-top: 1.5px;
            margin-bottom: 8px;
        }

        /* Tanggal */
        .tgl-surat {
            text-align: right;
            font-size: 9.5pt;
            margin-bottom: 4px;
        }

        /* Tujuan Surat */
        .tujuan-surat {
            margin-bottom: 12px;
            font-size: 9.5pt;
            line-height: 1.35;
        }

        /* List & Paragraf */
        .section-title {
            font-weight: normal;
            margin-bottom: 2px;
        }

        .content-body {
            text-align: justify;
            margin-bottom: 8px;
        }

        /* Data Pemohon */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            line-height: 1.35;
            margin-bottom: 10px;
            margin-left: 20px;
        }

        .data-table td {
            vertical-align: top;
            padding: 1.5px 0;
        }

        .data-label {
            width: 140px;
        }

        .data-colon {
            width: 15px;
            text-align: center;
        }

        /* TTE & Tembusan Container */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        .signature-table td {
            vertical-align: top;
        }

        .tembusan-box {
            font-size: 9pt;
            line-height: 1.35;
        }

        .tte-box {
            font-size: 8.5pt;
            line-height: 1.25;
        }

        .tte-badge-table {
            border-collapse: collapse;
        }

        .tte-badge-table td {
            vertical-align: middle;
            padding: 0;
        }
    </style>
</head>

<body>
    @php
        $logoBase64 = null;
        $logoPath = public_path('images/logo_bogor.png');
        if(file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        
        $qrUrl = route('surat.pdf', $application->permohonan_layanan_id ?? 0);
        $qrBase64 = null;
        try {
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(200)->margin(0)->generate($qrUrl);
            $qrBase64 = 'data:image/png;base64,' . base64_encode($qrCode);
        } catch(\Exception $e) {
            $qrBase64 = null;
        }
    @endphp

    <!-- FIXED FOOTER -->
    <div class="fixed-footer">
        <table class="footer-table">
            <tr>
                <td class="footer-qr">
                    @if($qrBase64)
                        <img src="{{ $qrBase64 }}" alt="QR Code">
                    @endif
                </td>
                <td class="footer-text">
                    <i>Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang
                        diterbitkan oleh Balai Sertifikasi<br>
                        Elektronik (BSrE), BSSN. Untuk memastikan keaslian tanda tangan elektronik, silakan pindai QR
                        Code<br>
                        dan pastikan diarahkan ke alamat <b>https://lentera.bogorkab.go.id</b></i>
                </td>
            </tr>
        </table>
    </div>

    <!-- CONTENT -->
    <div>
        <!-- KOP SURAT RESMI -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo Kab. Bogor">
                    @endif
                </td>
                <td class="kop-text">
                    <h2>PEMERINTAH KABUPATEN BOGOR</h2>
                    <h1>{{ $dinas->name }}</h1>
                    <p>{{ !empty($dinas->alamat) ? $dinas->alamat : 'Jl. Tegar Beriman, Cibinong - Bogor' }}</p>
                    <p>Telp: {{ !empty($dinas->telepon) ? $dinas->telepon : '-' }}, Email: {{ $dinas->email }}</p>
                </td>
            </tr>
        </table>
        <div class="garis-kop-tebal"></div>
        <div class="garis-kop-tipis"></div>

        <!-- TANGGAL -->
        <div class="tgl-surat">
            Cibinong, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>

        <div class="tujuan-surat">
            Yth.<br>
            <strong>{{ $application->user->name }}</strong><br>
            di Tempat
        </div>

        <div class="content-body">
            Dengan hormat,<br><br>
            Berdasarkan permohonan pelaksanaan Praktik Kerja Lapangan (PKL)/Magang yang telah diajukan oleh:
        </div>

        <table class="data-table">
            <tr>
                <td class="data-label">Nama</td>
                <td class="data-colon">:</td>
                <td><strong>{{ $application->user->name }}</strong></td>
            </tr>
            <tr>
                <td class="data-label" style="padding-top: 3px;">NIS/NIM</td>
                <td class="data-colon" style="padding-top: 3px;">:</td>
                <td style="padding-top: 3px;">{{ $application->user->nim ?? '-' }}</td>
            </tr>
            <tr>
                <td class="data-label" style="padding-top: 3px;">Asal Sekolah/Universitas</td>
                <td class="data-colon" style="padding-top: 3px;">:</td>
                <td style="padding-top: 3px;">{{ $application->user->asal_instansi ?? ($application->permohonanLayanan->asal_instansi ?? '-') }}</td>
            </tr>
            <tr>
                <td class="data-label" style="padding-top: 3px;">Program Studi/Jurusan</td>
                <td class="data-colon" style="padding-top: 3px;">:</td>
                <td style="padding-top: 3px;">{{ $application->user->program_studi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="data-label" style="padding-top: 3px;">Periode</td>
                <td class="data-colon" style="padding-top: 3px;">:</td>
                <td style="padding-top: 3px;">{{ \Carbon\Carbon::parse($application->tanggal_mulai)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($application->tanggal_selesai)->translatedFormat('d F Y') }}</td>
            </tr>
        </table>

        <div class="content-body">
            dengan ini kami sampaikan bahwa {{ mb_convert_case($dinas->name, MB_CASE_TITLE, "UTF-8") }} menyetujui dan menerima yang bersangkutan untuk melaksanakan kegiatan PKL/Magang di lingkungan {{ mb_convert_case($dinas->name, MB_CASE_TITLE, "UTF-8") }} Kabupaten Bogor.<br><br>
            Pelaksanaan kegiatan akan ditempatkan pada <strong>{{ strtoupper($bidangNama) }}</strong> dan dilaksanakan sesuai dengan ketentuan serta tata tertib yang berlaku di lingkungan {{ mb_convert_case($dinas->name, MB_CASE_TITLE, "UTF-8") }}.<br><br>
            Demikian surat pemberitahuan ini disampaikan untuk dapat dipergunakan sebagaimana mestinya. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.
        </div>

        <!-- TTE & TEMBUSAN -->
        <table class="signature-table">
            <tr>
                <td style="width: 48%;">
                    <!-- TEMBUSAN JIKA DIPERLUKAN -->
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 48%;">
                    <!-- BLOK TTE KEPALA DINAS -->
                    <div class="tte-box">
                        <table class="tte-badge-table">
                            <tr>
                                <td style="width: 50px; vertical-align: top; text-align: center; padding-top: 4px;">
                                    <div style="border-left: 1px solid #aaa; height: 55px; margin: 0 auto; width: 1px;"></div>
                                    <div style="font-size: 6pt; color: #777; line-height: 1.1; margin-top: 4px;">
                                        e-sign<br>Kabupaten<br>Bogor
                                    </div>
                                </td>
                                <td style="width: 44px; vertical-align: top; text-align: center; padding-left: 6px; padding-top: 10px;">
                                    @if($logoBase64)
                                        <img src="{{ $logoBase64 }}" style="width: 36px; height: auto;" alt="Logo Bogor">
                                    @endif
                                </td>
                                <td style="padding-left: 6px; vertical-align: top; text-align: left; font-family: Helvetica, Arial, sans-serif;">
                                    <div style="font-size: 7.5pt; color: #444; margin-bottom: 2px;">Ditandatangani secara elektronik oleh:</div>
                                    <div style="font-size: 8.5pt; font-weight: bold; line-height: 1.2; color: #222;">
                                        {!! nl2br(e("KEPALA " . strtoupper($dinas->name) . "\nKABUPATEN BOGOR")) !!}
                                    </div>
                                    <div style="font-size: 9pt; font-weight: bold; margin-top: 10px; color: #222;">
                                        {{ !empty($dinas->nama_kepala) ? $dinas->nama_kepala : '........................................' }}
                                    </div>
                                    <div style="font-size: 8.5pt; color: #333; margin-top: 1px;">
                                        NIP. {{ !empty($dinas->nip_kepala) ? $dinas->nip_kepala : '........................................' }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>