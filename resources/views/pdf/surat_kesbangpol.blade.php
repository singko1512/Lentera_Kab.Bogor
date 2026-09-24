<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Rekomendasi Kesbangpol - #{{ $layanan->id }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin-top: 1.2cm;
            margin-bottom: 2.2cm;
            margin-left: 2.2cm;
            margin-right: 2cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* Fixed Footer on all pages */
        .fixed-footer {
            position: fixed;
            bottom: -1.8cm;
            left: 0;
            right: 0;
            height: 1.5cm;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            vertical-align: middle;
            padding: 0;
        }

        /* Dikosongkan sesuai permintaan: untuk ditempel QR code manual oleh Kesbangpol */
        .footer-qr-placeholder {
            width: 52px;
            height: 52px;
        }

        .footer-text {
            padding-left: 12px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7.5pt;
            color: #222;
            line-height: 1.35;
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
            font-size: 13.5pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-text h1 {
            font-size: 15.5pt;
            font-weight: bold;
            margin: 2px 0 3px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-text p {
            font-size: 8.5pt;
            margin: 1px 0;
            line-height: 1.3;
        }

        .kop-divider {
            border-top: 2.5px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-top: 4px;
            margin-bottom: 14px;
        }

        .page-break {
            page-break-before: always;
        }

        /* Layout Elements */
        .tgl-surat {
            text-align: right;
            font-size: 10pt;
            margin-bottom: 12px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10pt;
        }

        .meta-table td {
            vertical-align: top;
            padding: 1px 0;
        }

        .meta-label {
            width: 75px;
        }

        .meta-colon {
            width: 15px;
            text-align: center;
        }

        .tujuan-block {
            margin-bottom: 14px;
            font-size: 10pt;
            line-height: 1.4;
        }

        .list-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 10pt;
        }

        .list-table td {
            vertical-align: top;
            padding: 2px 0;
            line-height: 1.4;
        }

        .list-no {
            width: 22px;
            text-align: left;
        }

        .list-content {
            text-align: justify;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            margin-bottom: 10px;
            font-size: 10pt;
        }

        .data-table td {
            vertical-align: top;
            padding: 2px 0;
            line-height: 1.4;
        }

        .data-label {
            width: 155px;
        }

        .data-colon {
            width: 15px;
            text-align: center;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .signature-table td {
            vertical-align: top;
        }

        .tembusan-box {
            font-size: 9.5pt;
            line-height: 1.4;
        }

        /* Ruang kosong untuk ditempel E-Sign manual oleh Kesbangpol */
        .manual-esign-placeholder {
            width: 100%;
            height: 125px;
        }
    </style>
</head>

<body>
    @php
        // Logo Kabupaten Bogor
        $logoPath = public_path('assets/certificate/lambang_kabupaten_bogor.png');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('assets/images/logo_lentera.png');
        }
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

        $nomorSurat = $layanan->suratRekomendasi->nomor_surat ?? ('400.14.5.4 / ' . $layanan->id . ' - Wasnas');

        $jenisLayananNama = $layanan->jenisLayanan->nama ?? 'Praktik Kerja Lapangan (PKL)';
        
        // Sesuaikan nama perihal agar baku
        $halSurat = 'Rekomendasi ' . $jenisLayananNama;
        if (str_contains(strtolower($jenisLayananNama), 'kkl') || str_contains(strtolower($jenisLayananNama), 'pkl')) {
            $halSurat = 'Rekomendasi Praktik Kerja Lapangan (PKL)';
        } elseif (str_contains(strtolower($jenisLayananNama), 'kkn')) {
            $halSurat = 'Rekomendasi Kuliah Kerja Nyata (KKN)';
        } elseif (str_contains(strtolower($jenisLayananNama), 'penelitian')) {
            $halSurat = 'Rekomendasi Surat Izin Penelitian';
        }

        $singkatanLayanan = 'PKL/Magang';
        if (str_contains(strtolower($jenisLayananNama), 'penelitian')) {
            $singkatanLayanan = 'Penelitian';
        } elseif (str_contains(strtolower($jenisLayananNama), 'kkn')) {
            $singkatanLayanan = 'KKN';
        } elseif (str_contains(strtolower($jenisLayananNama), 'kkl')) {
            $singkatanLayanan = 'KKL/PKL';
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
        $terbilang = $terbilangMap[$jumlahAnggota] ?? (string) $jumlahAnggota;
        $jumlahPesertaFormatted = $jumlahAnggota . ' (' . $terbilang . ') Orang';

        $alamatPemohon = $layanan->user->alamat ?? ($layanan->alamat ?? 'Jl. Pakuan P.O. Box 452');
        $penanggungJawab = $layanan->user->pembimbing_magang ?: ($layanan->atas_nama ?: ($layanan->user->name ?? '-'));

        $pimpinanAsalInstansi = 'Dekan / Pimpinan ' . $asalInstansi;
        if (str_contains(strtolower($asalInstansi), 'universitas') || str_contains(strtolower($asalInstansi), 'fakultas')) {
            $pimpinanAsalInstansi = 'Dekan / Rektor ' . $asalInstansi;
        } elseif (str_contains(strtolower($asalInstansi), 'sekolah') || str_contains(strtolower($asalInstansi), 'smk')) {
            $pimpinanAsalInstansi = 'Kepala ' . $asalInstansi;
        }

        $perihalSurat = $layanan->judul_kegiatan ?: ($jenisLayananNama);
    @endphp

    <!-- FIXED FOOTER PADA SEMUA HALAMAN (AREA QR CODE DIKOSONGKAN SESUAI INSTRUKSI) -->
    <div class="fixed-footer">
        <table class="footer-table">
            <tr>
                <td style="width: 55px;">
                    <!-- Area kosong reserved untuk QR Code BSrE manual -->
                    <div class="footer-qr-placeholder"></div>
                </td>
                <td class="footer-text">
                    Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan oleh<br>
                    <strong>Balai Sertifikasi Elektronik (BSrE) Badan Siber dan Sandi Negara</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- ========================================== -->
    <!-- HALAMAN 1                                  -->
    <!-- ========================================== -->
    <div class="page-container">
        <!-- KOP SURAT HALAMAN 1 -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    @if(!empty($logoBase64))
                        <img src="{{ $logoBase64 }}" alt="Logo Kab Bogor">
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
        <div class="kop-divider"></div>

        <!-- TANGGAL SURAT -->
        <div class="tgl-surat">
            Cibinong, {{ $tglSurat }}
        </div>

        <!-- NOMOR, SIFAT, LAMPIRAN, HAL -->
        <table class="meta-table">
            <tr>
                <td class="meta-label">Nomor</td>
                <td class="meta-colon">:</td>
                <td style="width: 320px;">{{ $nomorSurat }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="meta-label">Sifat</td>
                <td class="meta-colon">:</td>
                <td>Penting</td>
                <td></td>
            </tr>
            <tr>
                <td class="meta-label">Lampiran</td>
                <td class="meta-colon">:</td>
                <td>-</td>
                <td></td>
            </tr>
            <tr>
                <td class="meta-label">Hal</td>
                <td class="meta-colon">:</td>
                <td><strong>{{ $halSurat }}</strong></td>
                <td></td>
            </tr>
        </table>

        <!-- TUJUAN SURAT -->
        <div class="tujuan-block">
            Yth. Kepala {{ $dinasNameFormatted }}<br>
            <span style="margin-left: 20px;">di</span><br>
            <span style="margin-left: 20px;">Cibinong</span>
        </div>

        <!-- DASAR HUKUM -->
        <div style="font-size: 10pt; line-height: 1.4; margin-bottom: 4px;">
            Dasar :
        </div>
        <table class="list-table">
            <tr>
                <td class="list-no">1.</td>
                <td class="list-content">Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 3 Tahun 2018 tentang Penerbitan Surat Keterangan Penelitian;</td>
            </tr>
            <tr>
                <td class="list-no">2.</td>
                <td class="list-content">Peraturan Bupati Bogor Nomor 56 Tahun 2020 tentang Kedudukan, Susunan Organisasi, Tugas dan Fungsi, serta Tata Kerja Badan Kesatuan Bangsa dan Politik sebagaimana telah diubah dengan Peraturan Bupati Bogor Nomor 27 Tahun 2022 tentang Kedudukan, Susunan Organisasi, Tugas dan Fungsi serta Tata Kerja Badan Kesatuan Bangsa dan Politik;</td>
            </tr>
            <tr>
                <td class="list-no">3.</td>
                <td class="list-content">Peraturan Bupati Bogor Nomor 65 Tahun 2023 tentang Sistem Kerja Aparatur Sipil Negara Untuk Penyederhanaan Birokrasi Di Lingkungan Pemerintah Daerah.</td>
            </tr>
        </table>

        <!-- MEMPERHATIKAN -->
        <div style="font-size: 10pt; line-height: 1.4; margin-bottom: 6px; text-align: justify;">
            Memperhatikan :<br>
            Surat dari {{ $asalInstansi }}, Nomor : {{ $nomorAsal }}, tanggal {{ $tglAsal }}, Perihal {{ $perihalSurat }}.
        </div>

        <!-- PENGANTAR -->
        <div style="font-size: 10pt; line-height: 1.4; margin-bottom: 6px; text-align: justify;">
            Berdasarkan hal tersebut diatas, dengan ini kami memberikan {{ $halSurat }} kepada :
        </div>

        <!-- TABEL DATA PEMOHON -->
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
                <td class="data-label">Alamat</td>
                <td class="data-colon">:</td>
                <td>{{ $alamatPemohon }}</td>
            </tr>
            <tr>
                <td class="data-label">Penanggung Jawab</td>
                <td class="data-colon">:</td>
                <td>{{ $penanggungJawab }}</td>
            </tr>
            <tr>
                <td class="data-label">Jumlah Peserta</td>
                <td class="data-colon">:</td>
                <td>{{ $jumlahPesertaFormatted }}</td>
            </tr>
            <tr>
                <td class="data-label">Tenggang Waktu</td>
                <td class="data-colon">:</td>
                <td>{{ $tglMulai }} s.d {{ $tglSelesai }}</td>
            </tr>
            <tr>
                <td class="data-label">Tempat</td>
                <td class="data-colon">:</td>
                <td>{{ $tempatKegiatan }}</td>
            </tr>
        </table>

        <!-- KATA SAMBUNG DI KANAN BAWAH HALAMAN 1 -->
        <div style="text-align: right; margin-top: 14px; font-size: 10pt;">
            Dengan …
        </div>
    </div>

    <!-- ========================================== -->
    <!-- HALAMAN 2                                  -->
    <!-- ========================================== -->
    <div class="page-break"></div>

    <div class="page-container">
        <!-- KOP SURAT HALAMAN 2 -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    @if(!empty($logoBase64))
                        <img src="{{ $logoBase64 }}" alt="Logo Kab Bogor">
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
        <div class="kop-divider"></div>

        <!-- PENOMORAN HALAMAN -->
        <div style="text-align: center; margin-top: 4px; margin-bottom: 12px; font-weight: bold; font-size: 10pt;">
            - 2 -
        </div>

        <!-- KETENTUAN -->
        <div style="font-size: 10pt; line-height: 1.4; margin-bottom: 6px;">
            Dengan ketentuan sebagai berikut :
        </div>
        <table class="list-table" style="margin-bottom: 10px;">
            <tr>
                <td class="list-no">1.</td>
                <td class="list-content">Mentaati ketentuan peraturan perundang-undangan;</td>
            </tr>
            <tr>
                <td class="list-no">2.</td>
                <td class="list-content">Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi {{ $singkatanLayanan }};</td>
            </tr>
            <tr>
                <td class="list-no">3.</td>
                <td class="list-content">Berkoordinasi dan mengikuti petunjuk dan arahan dari Pimpinan Instansi tempat pelaksanaan {{ $singkatanLayanan }};</td>
            </tr>
            <tr>
                <td class="list-no">4.</td>
                <td class="list-content">Mematuhi aturan dan jam kerja yang berlaku di lokasi {{ $singkatanLayanan }};</td>
            </tr>
            <tr>
                <td class="list-no">5.</td>
                <td class="list-content">Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas.</td>
            </tr>
        </table>

        <!-- PENUTUP -->
        <div style="font-size: 10pt; line-height: 1.4; margin-bottom: 25px; text-align: justify;">
            Demikian disampaikan, atas perhatian dan kerja samanya diucapkan terima kasih.
        </div>

        <!-- AREA TEMBUSAN (KIRI) & AREA TTE KOSONG (KANAN) -->
        <table class="signature-table">
            <tr>
                <td style="width: 52%; vertical-align: top;">
                    <!-- TEMBUSAN -->
                    <div class="tembusan-box">
                        <strong>Tembusan :</strong>
                        <table style="width: 100%; border-collapse: collapse; margin-top: 3px; font-size: 9.5pt;">
                            <tr>
                                <td style="width: 18px; vertical-align: top;">1.</td>
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
                <td style="width: 6%;"></td>
                <td style="width: 42%; vertical-align: top;">
                    <!-- AREA TTE DIKOSONGKAN SESUAI PERMINTAAN KESBANGPOL (UNTUK DITEMPEL MANUAL) -->
                    <div class="manual-esign-placeholder">
                        <!-- Area kosong untuk manual e-sign / stempel Kesbangpol -->
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
