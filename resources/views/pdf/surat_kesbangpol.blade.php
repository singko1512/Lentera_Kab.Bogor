<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Rekomendasi Kesbangpol - #{{ $layanan->id }}</title>
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

        /* Metadata Surat */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9.5pt;
        }

        .meta-table td {
            vertical-align: top;
            padding: 1px 0;
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

        .list-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            line-height: 1.35;
            margin-bottom: 8px;
        }

        .list-table td {
            vertical-align: top;
            padding: 1px 0;
        }

        .list-no {
            width: 20px;
            text-align: left;
        }

        .list-content {
            text-align: justify;
        }

        /* Data Pemohon */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            line-height: 1.35;
            margin-bottom: 10px;
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

        /* Halaman 2 */
        .page-break {
            page-break-before: always;
        }

        .page-number {
            text-align: center;
            font-weight: bold;
            font-size: 9.5pt;
            margin: 4px 0 10px 0;
        }

        /* TTE & Tembusan Container */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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
        // Logo Kabupaten Bogor
        $logoPath = public_path('assets/certificate/lambang_kabupaten_bogor.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        // Variabel Data Dinamis dari Input / Database
        $tglSurat = \Carbon\Carbon::parse($layanan->updated_at ?? now())->translatedFormat('d F Y');
        $tglAsal = \Carbon\Carbon::parse($layanan->created_at ?? now())->translatedFormat('d F Y');
        $tglMulai = \Carbon\Carbon::parse($layanan->tanggal_mulai ?? now())->translatedFormat('d F Y');
        $tglSelesai = \Carbon\Carbon::parse($layanan->tanggal_selesai ?? now())->translatedFormat('d F Y');
        
        $nomorSurat = '400.14.5.4 / ' . $layanan->id . ' - Wasnas';
        
        $jenisLayananNama = $layanan->jenisLayanan->nama ?? 'Praktik Kerja Lapangan (PKL)';
        $halSurat = 'Rekomendasi ' . $jenisLayananNama;
        
        $singkatanLayanan = 'PKL/Magang';
        if (str_contains(strtolower($jenisLayananNama), 'penelitian')) {
            $singkatanLayanan = 'Penelitian';
        } elseif (str_contains(strtolower($jenisLayananNama), 'kkn')) {
            $singkatanLayanan = 'KKN';
        } elseif (str_contains(strtolower($jenisLayananNama), 'kkl')) {
            $singkatanLayanan = 'KKL';
        }

        $dinasNameRaw = $layanan->tempat_kegiatan ?? 'Dinas Komunikasi dan Informatika';
        $dinasNameFormatted = ucwords(strtolower(trim($dinasNameRaw)));
        if (!str_contains(strtolower($dinasNameFormatted), 'kabupaten') && !str_contains(strtolower($dinasNameFormatted), 'kab.')) {
            $dinasNameFormatted .= ' Kab. Bogor';
        }

        $tempatKegiatan = 'Kantor ' . ucwords(strtolower(trim($dinasNameRaw))) . ' Kabupaten Bogor.';

        $asalInstansi = $layanan->asal_instansi ?? ($layanan->user->asal_instansi ?? 'Perguruan Tinggi / Sekolah');
        $nomorAsal = $layanan->nomor_surat_pengantar ?: ('1832/D/' . strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $asalInstansi), 0, 6)) . '/' . date('Y'));

        // Parsing Daftar Nama Pemohon (Mendukung satu atau banyak nama yang diinput pemohon)
        $rawNama = $layanan->atas_nama ?: ($layanan->user->name ?? 'PEMOHON');
        // Pisahkan jika menggunakan format "1. Nama 2. Nama" atau baris baru atau koma
        if (preg_match_all('/(?:\d+[\.\)]\s*)?([^\r\n,]+)/', $rawNama, $matches)) {
            $namaList = array_values(array_filter(array_map('trim', $matches[1])));
        } else {
            $namaList = [trim($rawNama)];
        }
        if (empty($namaList)) {
            $namaList = [trim($rawNama)];
        }

        $jumlahAnggota = count($namaList) > 1 ? count($namaList) : ($layanan->jumlah_anggota ?? 1);
        $terbilangMap = [1 => 'Satu', 2 => 'Dua', 3 => 'Tiga', 4 => 'Empat', 5 => 'Lima', 6 => 'Enam', 7 => 'Tujuh', 8 => 'Delapan', 9 => 'Sembilan', 10 => 'Sepuluh'];
        $terbilang = $terbilangMap[$jumlahAnggota] ?? (string)$jumlahAnggota;
        $jumlahPesertaFormatted = $jumlahAnggota . ' (' . $terbilang . ') Orang';

        $alamatPemohon = $layanan->user->alamat ?? ($layanan->alamat ?? 'Jl. Pakuan P.O. Box 452');
        $penanggungJawab = $layanan->user->pembimbing_magang ?: ($layanan->atas_nama ?: ($layanan->user->name ?? '-'));
        
        $pimpinanAsalInstansi = 'Pimpinan ' . $asalInstansi;
        if (str_contains(strtolower($asalInstansi), 'universitas') || str_contains(strtolower($asalInstansi), 'fakultas')) {
            $pimpinanAsalInstansi = 'Dekan / Rektor ' . $asalInstansi;
        } elseif (str_contains(strtolower($asalInstansi), 'sekolah') || str_contains(strtolower($asalInstansi), 'smk')) {
            $pimpinanAsalInstansi = 'Kepala ' . $asalInstansi;
        }

        $perihalSurat = $layanan->judul_kegiatan ?: ($jenisLayananNama);
    @endphp

    <!-- FIXED FOOTER (QR CODE & BSrE NOTICE ON ALL PAGES) -->
    <div class="fixed-footer">
        <table class="footer-table">
            <tr>
                <td class="footer-qr">
                    @if(!empty($qrBase64))
                        <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" alt="QR Code Verifikasi">
                    @elseif(!empty($qrUrl))
                        @php
                            $svgString = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(90)->errorCorrection('M')->generate($qrUrl);
                            $localBase64 = base64_encode($svgString);
                        @endphp
                        <img src="data:image/svg+xml;base64,{{ $localBase64 }}" alt="QR Code Verifikasi">
                    @endif
                </td>
                <td class="footer-text">
                    Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan oleh<br>
                    <strong>Balai Sertifikasi Elektronik (BSrE) Badan Siber dan Sandi Negara</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- ==================== HALAMAN 1 ==================== -->
    <div class="page-1">
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
                    <h1>BADAN KESATUAN BANGSA DAN POLITIK</h1>
                    <p>Jl. KSR Dadi Kusmayadi Komplek Pemda Kel. Tengah Cibinong – Bogor 16914</p>
                    <p>Telp/Fax. (021) 8758836, Email : kesbangpolbogor09@gmail.com, Web : bakesbangpol.bogorkab.go.id</p>
                </td>
            </tr>
        </table>
        <div class="garis-kop-tebal"></div>
        <div class="garis-kop-tipis"></div>

        <!-- TANGGAL SURAT -->
        <div class="tgl-surat">
            Cibinong, {{ $tglSurat }}
        </div>

        <!-- METADATA SURAT -->
        <table class="meta-table">
            <tr>
                <td style="width: 80px;">Nomor</td>
                <td style="width: 15px;">:</td>
                <td>{{ $nomorSurat }}</td>
            </tr>
            <tr>
                <td>Sifat</td>
                <td>:</td>
                <td>Penting</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Hal</td>
                <td>:</td>
                <td><strong>{{ $halSurat }}</strong></td>
            </tr>
        </table>

        <!-- TUJUAN SURAT -->
        <div class="tujuan-surat">
            Yth. Kepala {{ $dinasNameFormatted }}<br>
            di<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cibinong
        </div>

        <!-- DASAR -->
        <div class="section-title">Dasar :</div>
        <table class="list-table">
            <tr>
                <td class="list-no">1.</td>
                <td class="list-content">Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 3 Tahun 2018 tentang Penerbitan Surat Keterangan Penelitian;</td>
            </tr>
            <tr>
                <td class="list-no" style="padding-top: 3px;">2.</td>
                <td class="list-content" style="padding-top: 3px;">Peraturan Bupati Bogor Nomor 56 Tahun 2020 tentang Kedudukan, Susunan Organisasi, Tugas dan Fungsi, serta Tata Kerja Badan Kesatuan Bangsa dan Politik sebagaimana telah diubah dengan Peraturan Bupati Bogor Nomor 27 Tahun 2022 tentang Kedudukan, Susunan Organisasi, Tugas dan Fungsi serta Tata Kerja Badan Kesatuan Bangsa dan Politik;</td>
            </tr>
            <tr>
                <td class="list-no" style="padding-top: 3px;">3.</td>
                <td class="list-content" style="padding-top: 3px;">Peraturan Bupati Bogor Nomor 65 Tahun 2023 tentang Sistem Kerja Aparatur Sipil Negara Untuk Penyederhanaan Birokrasi Di Lingkungan Pemerintah Daerah.</td>
            </tr>
        </table>

        <!-- MEMPERHATIKAN -->
        <div style="font-size: 9.5pt; line-height: 1.35; margin-bottom: 6px; text-align: justify;">
            Memperhatikan :<br>
            Surat dari {{ $asalInstansi }}, Nomor : {{ $nomorAsal }}, tanggal {{ $tglAsal }}, Perihal {{ $perihalSurat }}.
        </div>

        <div style="font-size: 9.5pt; line-height: 1.35; margin-bottom: 8px; text-indent: 25px; text-align: justify;">
            Berdasarkan hal tersebut diatas, dengan ini kami memberikan {{ $halSurat }} kepada :
        </div>

        <!-- DATA PEMOHON -->
        <table class="data-table">
            <tr>
                <td class="data-label">Nama</td>
                <td class="data-colon">:</td>
                <td>
                    @foreach($namaList as $idx => $namaItem)
                        <div><strong>{{ $idx + 1 }}. {{ strtoupper($namaItem) }}</strong></div>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td class="data-label" style="padding-top: 3px;">Alamat</td>
                <td class="data-colon" style="padding-top: 3px;">:</td>
                <td style="padding-top: 3px;">{{ $alamatPemohon }}</td>
            </tr>
            <tr>
                <td class="data-label" style="padding-top: 3px;">Penanggung Jawab</td>
                <td class="data-colon" style="padding-top: 3px;">:</td>
                <td style="padding-top: 3px;">{{ $penanggungJawab }}</td>
            </tr>
            <tr>
                <td class="data-label" style="padding-top: 3px;">Jumlah Peserta</td>
                <td class="data-colon" style="padding-top: 3px;">:</td>
                <td style="padding-top: 3px;">{{ $jumlahPesertaFormatted }}</td>
            </tr>
            <tr>
                <td class="data-label" style="padding-top: 3px;">Tenggang Waktu</td>
                <td class="data-colon" style="padding-top: 3px;">:</td>
                <td style="padding-top: 3px;">{{ $tglMulai }} s.d {{ $tglSelesai }}</td>
            </tr>
            <tr>
                <td class="data-label" style="padding-top: 3px;">Tempat</td>
                <td class="data-colon" style="padding-top: 3px;">:</td>
                <td style="padding-top: 3px;">{{ $tempatKegiatan }}</td>
            </tr>
        </table>

        <!-- KATA SAMBUNG KE HALAMAN 2 -->
        <div style="text-align: right; font-size: 9.5pt; margin-top: 15px;">
            Dengan ...
        </div>
    </div>

    <!-- ==================== HALAMAN 2 ==================== -->
    <div class="page-break">
        <!-- KOP SURAT RESMI HALAMAN 2 -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo Kab. Bogor">
                    @endif
                </td>
                <td class="kop-text">
                    <h2>PEMERINTAH KABUPATEN BOGOR</h2>
                    <h1>BADAN KESATUAN BANGSA DAN POLITIK</h1>
                    <p>Jl. KSR Dadi Kusmayadi Komplek Pemda Kel. Tengah Cibinong – Bogor 16914</p>
                    <p>Telp/Fax. (021) 8758836, Email : kesbangpolbogor09@gmail.com, Web : bakesbangpol.bogorkab.go.id</p>
                </td>
            </tr>
        </table>
        <div class="garis-kop-tebal"></div>
        <div class="garis-kop-tipis"></div>

        <!-- NOMOR HALAMAN 2 -->
        <div class="page-number">- 2 -</div>

        <!-- KETENTUAN -->
        <div style="font-size: 9.5pt; line-height: 1.35; margin-bottom: 6px;">
            Dengan ketentuan sebagai berikut :
        </div>
        <table class="list-table" style="margin-bottom: 12px;">
            <tr>
                <td class="list-no">1.</td>
                <td class="list-content">Mentaati ketentuan peraturan perundang-undangan;</td>
            </tr>
            <tr>
                <td class="list-no" style="padding-top: 3px;">2.</td>
                <td class="list-content" style="padding-top: 3px;">Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi {{ $singkatanLayanan }};</td>
            </tr>
            <tr>
                <td class="list-no" style="padding-top: 3px;">3.</td>
                <td class="list-content" style="padding-top: 3px;">Berkoordinasi dan mengikuti petunjuk dan arahan dari Pimpinan Instansi tempat pelaksanaan {{ $singkatanLayanan }};</td>
            </tr>
            <tr>
                <td class="list-no" style="padding-top: 3px;">4.</td>
                <td class="list-content" style="padding-top: 3px;">Mematuhi aturan dan jam kerja yang berlaku di lokasi {{ $singkatanLayanan }};</td>
            </tr>
            <tr>
                <td class="list-no" style="padding-top: 3px;">5.</td>
                <td class="list-content" style="padding-top: 3px;">Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas.</td>
            </tr>
        </table>

        <!-- PENUTUP -->
        <div style="font-size: 9.5pt; line-height: 1.35; margin-bottom: 25px; text-indent: 25px; text-align: justify;">
            Demikian disampaikan, atas perhatian dan kerja samanya diucapkan terima kasih.
        </div>

        <!-- TTE & TEMBUSAN -->
        <table class="signature-table">
            <tr>
                <td style="width: 48%;">
                    <!-- TEMBUSAN -->
                    <div class="tembusan-box">
                        <strong>Tembusan :</strong>
                        <table style="width: 100%; border-collapse: collapse; margin-top: 3px;">
                            <tr>
                                <td style="width: 16px; vertical-align: top;">1.</td>
                                <td style="vertical-align: top;">Yth. Bupati Bogor;</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding-top: 2px;">2.</td>
                                <td style="vertical-align: top; padding-top: 2px;">Yth. Wakil Bupati Bogor;</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding-top: 2px;">3.</td>
                                <td style="vertical-align: top; padding-top: 2px;">Yth. Sekretaris Daerah Kabupaten Bogor;</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding-top: 2px;">4.</td>
                                <td style="vertical-align: top; padding-top: 2px;">Yth. {{ $pimpinanAsalInstansi }}.</td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 48%;">
                    <!-- BLOK TTE KEPALA BAKESBANGPOL -->
                    <div class="tte-box">
                        <table class="tte-badge-table">
                            <tr>
                                <td style="width: 52px; vertical-align: middle; text-align: center;">
                                    <!-- Badge e-sign Kabupaten Bogor -->
                                    <svg width="42" height="42" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="46" fill="#3B82F6" />
                                        <path d="M58 26 A 16 16 0 0 0 36 44 L 22 58 L 22 72 L 36 72 L 36 62 L 44 62 L 44 54 L 52 46 A 16 16 0 0 0 58 26 Z M 62 32 A 4.5 4.5 0 1 1 62 41 A 4.5 4.5 0 0 1 62 32 Z" fill="#FFFFFF" />
                                    </svg>
                                    <div style="font-size: 5pt; color: #555; text-align: center; margin-top: 2px;">e-sign Kabupaten Bogor</div>
                                </td>
                                <td style="width: 44px; vertical-align: middle; text-align: center; padding-left: 4px;">
                                    @if($logoBase64)
                                        <img src="{{ $logoBase64 }}" style="width: 36px; height: auto;" alt="Logo Bogor">
                                    @endif
                                </td>
                                <td style="padding-left: 8px; vertical-align: middle;">
                                    <div style="font-size: 7.5pt; color: #222; margin-bottom: 2px;">Ditandatangani secara elektronik oleh:</div>
                                    <div style="font-size: 8.5pt; font-weight: bold; line-height: 1.2;">
                                        KEPALA BADAN KESATUAN BANGSA<br>DAN POLITIK KABUPATEN BOGOR
                                    </div>
                                    <div style="font-size: 9pt; font-weight: bold; text-decoration: underline; margin-top: 10px;">
                                        FERDINANDO SELMI PARDEDE, S.IP, M.AP
                                    </div>
                                    <div style="font-size: 8pt; color: #333; margin-top: 1px;">
                                        Pembina Tk. I
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
