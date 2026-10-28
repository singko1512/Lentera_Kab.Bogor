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
            margin-bottom: 2cm;
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
            margin-top: 35px;
        }

        .signature-table td {
            vertical-align: top;
        }

        .tembusan-box {
            font-size: 9.5pt;
            line-height: 1.4;
            padding-top: 10px;
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
            $logoPath = public_path('assets/images/logo_bakesbangpol_2026.png');
        }
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        // Slug Layanan
        $slug = $layanan->jenisLayanan->slug ?? 'kkl_mahasiswa';
        $jenisLayananNama = $layanan->jenisLayanan->nama ?? 'Praktik Kerja Lapangan (PKL)';

        // Variabel Tanggal
        $tglSurat = \Carbon\Carbon::parse($layanan->updated_at ?? now())->translatedFormat('d F Y');
        $tglAsal = \Carbon\Carbon::parse($layanan->created_at ?? now())->translatedFormat('d F Y');
        $tglMulai = \Carbon\Carbon::parse($layanan->tanggal_mulai ?? now())->translatedFormat('d F Y');
        $tglSelesai = \Carbon\Carbon::parse($layanan->tanggal_selesai ?? now())->translatedFormat('d F Y');

        // Nomor Surat & Hal Sesuai Template
        $prefixNomor = match($slug) {
            'penelitian_pt', 'penelitian_instansi', 'kkn_mahasiswa' => '000.9.2 / ',
            'pelaksanaan_kegiatan' => '000.6.9.3 / ',
            default => '400.14.5.4 / ',
        };

        if ($layanan->suratRekomendasi && !empty($layanan->suratRekomendasi->nomor_surat)) {
            $rawNomor = trim($layanan->suratRekomendasi->nomor_surat);
            if (preg_match('/^(?:000\.9\.2|400\.14\.5\.4|000\.6\.9\.3)\s*\//i', $rawNomor)) {
                $nomorSurat = $rawNomor;
            } else {
                $nomorSurat = $prefixNomor . $rawNomor;
            }
            if (!str_contains(strtolower($nomorSurat), 'wasnas')) {
                $nomorSurat .= ' - Wasnas';
            }
        } else {
            $nomorSurat = $prefixNomor . $layanan->id . ' - Wasnas';
        }

        $halSurat = match($slug) {
            'penelitian_pt', 'penelitian_instansi' => 'Rekomendasi Surat Izin Penelitian',
            'kkn_mahasiswa' => 'Rekomendasi Kuliah Kerja Nyata (KKN)',
            'pelaksanaan_kegiatan' => 'Rekomendasi Pelaksanaan Kegiatan',
            default => 'Rekomendasi Praktik Kerja Lapangan (PKL)',
        };

        // Tujuan & Tempat Tujuan
        $dinasNameRaw = $layanan->tempat_kegiatan ?: ($layanan->dinas->name ?? 'Dinas Komunikasi dan Informatika');
        $dinasFormatted = ucwords(strtolower(trim($dinasNameRaw)));
        if (!str_contains(strtolower($dinasFormatted), 'kabupaten') && !str_contains(strtolower($dinasFormatted), 'kab.')) {
            $dinasFormatted .= ' Kab. Bogor';
        }

        $tujuanSurat = 'Kepala ' . $dinasFormatted;
        $tempatTujuan = 'Cibinong';
        if (str_contains(strtolower($dinasNameRaw), 'kecamatan')) {
            $kecClean = preg_replace('/^kantor\s+/i', '', $dinasFormatted);
            if (!str_starts_with(strtolower($kecClean), 'camat')) {
                $tujuanSurat = 'Camat ' . preg_replace('/^kecamatan\s+/i', '', $kecClean);
            } else {
                $tujuanSurat = $kecClean;
            }
            if (preg_match('/kecamatan\s+([a-zA-Z\s]+)/i', $dinasNameRaw, $mKec)) {
                $tempatTujuan = ucwords(strtolower(trim($mKec[1])));
            }
        } elseif ($slug === 'kkn_mahasiswa' && str_contains(strtolower($dinasFormatted), 'kecamatan')) {
            $tujuanSurat = 'Camat ' . preg_replace('/^kecamatan\s+/i', '', $dinasFormatted);
        }

        $tempatKegiatan = 'Kantor ' . ucwords(strtolower(trim($dinasNameRaw)));
        if (!str_contains(strtolower($tempatKegiatan), 'kabupaten') && !str_contains(strtolower($tempatKegiatan), 'kab.')) {
            $tempatKegiatan .= ' Kabupaten Bogor';
        }

        // Asal Instansi
        $asalInstansi = $layanan->asal_instansi ?? ($layanan->user->asal_instansi ?? 'Perguruan Tinggi / Sekolah');
        $nomorAsal = $layanan->nomor_surat_pengantar ?: ('1832/D/' . strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $asalInstansi), 0, 6)) . '/' . date('Y'));

        // Nama Pemohon (Mendukung Multi-nama)
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

        $alamatPemohon = $layanan->user->alamat ?? ($layanan->alamat ?? 'Kabupaten Bogor');
        $penanggungJawab = $layanan->user->pembimbing_magang ?: ($layanan->atas_nama ?: ($layanan->user->name ?? '-'));

        $pimpinanAsalInstansi = 'Dekan / Pimpinan ' . $asalInstansi;
        if (str_contains(strtolower($asalInstansi), 'universitas') || str_contains(strtolower($asalInstansi), 'fakultas')) {
            $pimpinanAsalInstansi = 'Dekan / Rektor ' . $asalInstansi;
        } elseif (str_contains(strtolower($asalInstansi), 'sekolah') || str_contains(strtolower($asalInstansi), 'smk')) {
            $pimpinanAsalInstansi = 'Kepala ' . $asalInstansi;
        }

        $perihalSurat = $layanan->judul_kegiatan ?: $jenisLayananNama;
    @endphp

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
            Yth. {{ $tujuanSurat }}<br>
            <span style="margin-left: 20px;">di</span><br>
            <span style="margin-left: 20px;">{{ $tempatTujuan }}</span>
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
                <td class="data-label">{{ in_array($slug, ['penelitian_pt', 'penelitian_instansi']) ? 'Nama Peneliti' : 'Nama' }}</td>
                <td class="data-colon">:</td>
                <td>
                    @if(count($namaList) === 1)
                        <strong>{{ strtoupper($namaList[0]) }}</strong>
                    @else
                        @foreach($namaList as $idx => $namaItem)
                            <div><strong>{{ $idx + 1 }}. {{ strtoupper($namaItem) }}</strong></div>
                        @endforeach
                    @endif
                </td>
            </tr>
            <tr>
                <td class="data-label">Alamat</td>
                <td class="data-colon">:</td>
                <td>{{ $alamatPemohon }}</td>
            </tr>
            @if(in_array($slug, ['penelitian_pt', 'penelitian_instansi']))
            <tr>
                <td class="data-label">Judul Penelitian</td>
                <td class="data-colon">:</td>
                <td>{{ $layanan->judul_kegiatan ?: $jenisLayananNama }}</td>
            </tr>
            @endif
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
            @if($slug === 'pelaksanaan_kegiatan')
            <tr>
                <td class="data-label">Nama Kegiatan</td>
                <td class="data-colon">:</td>
                <td>{{ $layanan->judul_kegiatan ?: $jenisLayananNama }}</td>
            </tr>
            @endif
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

        <!-- KETENTUAN RESMI SESUAI TEMPLATE SURAT -->
        <div style="font-size: 10pt; line-height: 1.4; margin-bottom: 6px;">
            Dengan ketentuan sebagai berikut :
        </div>
        @php
            $ketentuanList = match($slug) {
                'penelitian_pt' => [
                    'Menaati ketentuan peraturan perundang-undangan;',
                    'Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi Penelitian;',
                    'Berkoordinasi dan mengikuti petunjuk dan arahan dari Pimpinan Instansi tempat pelaksanaan penelitian;',
                    'Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas;',
                    'Setelah selesai melaksanakan kegiatan, agar Mahasiswa yang bersangkutan melaporkan hasilnya kepada Bupati Bogor melalui Kepala Badan Kesatuan Bangsa dan Politik Kabupaten Bogor.',
                ],
                'penelitian_instansi' => [
                    'Menaati ketentuan peraturan perundang-undangan;',
                    'Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi Penelitian;',
                    'Berkoordinasi dan mengikuti petunjuk dan arahan dari Pimpinan Instansi tempat pelaksanaan penelitian;',
                    'Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas;',
                    'Setelah selesai melaksanakan kegiatan, agar yang bersangkutan melaporkan hasilnya kepada Bupati Bogor melalui Kepala Badan Kesatuan Bangsa dan Politik Kabupaten Bogor.',
                ],
                'kkn_mahasiswa' => [
                    'Mentaati ketentuan Peraturan Perundang-undangan;',
                    'Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi KKN;',
                    'Senantiasa berkoordinasi dan mengikuti petunjuk dan arahan dari Forum Koordinasi Pimpinan Kecamatan (Forkopimcam) setempat;',
                    'Mematuhi aturan yang berlaku di lokasi KKN;',
                    'Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas;',
                    'Setelah selesai melaksanakan kegiatan, agar Mahasiswa yang bersangkutan melaporkan hasilnya kepada Bupati Bogor melalui Kepala Badan Kesatuan Bangsa dan Politik Kabupaten Bogor.',
                ],
                'pelaksanaan_kegiatan' => [
                    'Mentaati ketentuan Peraturan Perundang-undangan;',
                    'Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi Pelaksanaan Kegiatan;',
                    'Senantiasa berkoordinasi dan mengikuti petunjuk dan arahan dari Forum Koordinasi Pimpinan Kecamatan (Forkopimcam) setempat;',
                    'Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas;',
                    'Mengadakan koordinasi dengan stakeholder terkait;',
                    'Mematuhi aturan yang berlaku di lokasi Pelaksanaan Kegiatan;',
                    'Setelah selesai melaksanakan kegiatan, agar yang bersangkutan melaporkan hasilnya kepada Bupati Bogor melalui Kepala Badan Kesatuan Bangsa dan Politik Kabupaten Bogor.',
                ],
                default => [
                    'Mentaati ketentuan peraturan perundang-undangan;',
                    'Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi PKL/Magang;',
                    'Berkoordinasi dan mengikuti petunjuk dan arahan dari Pimpinan Instansi tempat pelaksanaan PKL/Magang;',
                    'Mematuhi aturan dan jam kerja yang berlaku di lokasi PKL/Magang;',
                    'Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas.',
                ],
            };
        @endphp
        <table class="list-table" style="margin-bottom: 10px;">
            @foreach($ketentuanList as $kIdx => $kItem)
                <tr>
                    <td class="list-no">{{ $kIdx + 1 }}.</td>
                    <td class="list-content">{{ $kItem }}</td>
                </tr>
            @endforeach
        </table>

        <!-- PENUTUP -->
        <div style="font-size: 10pt; line-height: 1.4; margin-bottom: 25px; text-align: justify;">
            Demikian disampaikan, atas perhatian dan kerja samanya diucapkan terima kasih.
        </div>

        <!-- AREA TEMBUSAN (KIRI) & AREA TTE KOSONG (KANAN) -->
        <table class="signature-table">
            <tr>
                <td style="width: 52%; vertical-align: top;">
                    <!-- TEMBUSAN RESMI -->
                    <div class="tembusan-box">
                        <div style="font-weight: bold; margin-bottom: 4px;">Tembusan :</div>
                        @php
                            $tembusanList = [
                                'Yth. Bupati Bogor;',
                                'Yth. Wakil Bupati Bogor;',
                                'Yth. Sekretaris Daerah Kabupaten Bogor;',
                            ];
                            if (in_array($slug, ['penelitian_pt', 'kkn_mahasiswa'])) {
                                $tembusanList[] = 'Yth. Kepala Badan Perencanaan Pembangunan, Riset dan Inovasi Daerah;';
                            }
                            $tembusanList[] = 'Yth. ' . $pimpinanAsalInstansi . '.';
                        @endphp
                        <table style="width: 100%; border-collapse: collapse; font-size: 9.5pt; line-height: 1.35;">
                            @foreach($tembusanList as $tIdx => $tItem)
                                <tr>
                                    <td style="width: 18px; vertical-align: top; padding: 2px 0;">{{ $tIdx + 1 }}.</td>
                                    <td style="vertical-align: top; padding: 2px 0;">{{ $tItem }}</td>
                                </tr>
                            @endforeach
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