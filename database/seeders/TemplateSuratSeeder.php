<?php

namespace Database\Seeders;

use App\Models\JenisLayanan;
use App\Models\TemplateSurat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TemplateSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Salin file template dokumen jika folder ada (sebagai opsi cadangan/arsip)
        $sourceDir = base_path('template_surat');
        $targetDir = storage_path('app/public/template_surat');
        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        if (File::exists($sourceDir)) {
            $files = File::files($sourceDir);
            foreach ($files as $file) {
                $targetFile = $targetDir . '/' . $file->getFilename();
                if (!File::exists($targetFile)) {
                    File::copy($file->getPathname(), $targetFile);
                }
            }
        }

        $dasarHukumUmum = [
            'Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 3 Tahun 2018 tentang Penerbitan Surat Keterangan Penelitian;',
            'Peraturan Bupati Bogor Nomor 56 Tahun 2020 tentang Kedudukan, Susunan Organisasi, Tugas dan Fungsi, serta Tata Kerja Badan Kesatuan Bangsa dan Politik sebagaimana telah diubah dengan Peraturan Bupati Bogor Nomor 27 Tahun 2022 tentang Kedudukan, Susunan Organisasi, Tugas dan Fungsi serta Tata Kerja Badan Kesatuan Bangsa dan Politik;',
            'Peraturan Bupati Bogor Nomor 65 Tahun 2023 tentang Sistem Kerja Aparatur Sipil Negara Untuk Penyederhanaan Birokrasi Di Lingkungan Pemerintah Daerah.'
        ];

        $templates = [
            [
                'slug' => 'penelitian_pt',
                'nama' => 'Template Surat Rekomendasi Penelitian / Survei / Data (Perguruan Tinggi)',
                'file_nama' => 'NEW PENELITIAN.docx',
                'nomor_prefix' => '000.9.2 / ',
                'sifat_surat' => 'Penting',
                'lampiran_surat' => '-',
                'perihal' => 'Rekomendasi Surat Izin Penelitian',
                'keterangan' => 'Struktur template resmi rekomendasi penelitian mahasiswa perguruan tinggi (HTML kodingan identik format Word)',
                'label_nama' => 'Nama Peneliti',
                'include_judul_penelitian' => true,
                'include_nama_kegiatan' => false,
                'dasar_hukum' => $dasarHukumUmum,
                'ketentuan' => [
                    'Menaati ketentuan peraturan perundang-undangan;',
                    'Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi Penelitian;',
                    'Berkoordinasi dan mengikuti petunjuk dan arahan dari Pimpinan Instansi tempat pelaksanaan penelitian;',
                    'Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas;',
                    'Setelah selesai melaksanakan kegiatan, agar Mahasiswa yang bersangkutan melaporkan hasilnya kepada Bupati Bogor melalui Kepala Badan Kesatuan Bangsa dan Politik Kabupaten Bogor.'
                ],
                'tembusan' => [
                    'Yth. Bupati Bogor;',
                    'Yth. Wakil Bupati Bogor;',
                    'Yth. Sekretaris Daerah Kabupaten Bogor;',
                    'Yth. Kepala Badan Perencanaan Pembangunan, Riset dan Inovasi Daerah;',
                    'Yth. Dekan / Pimpinan Perguruan Tinggi.'
                ]
            ],
            [
                'slug' => 'penelitian_instansi',
                'nama' => 'Template Surat Rekomendasi Penelitian Lembaga / Instansi',
                'file_nama' => 'PENELITIAN LEMBAGA ATAU INSTANSI.docx',
                'nomor_prefix' => '000.9.2 / ',
                'sifat_surat' => 'Penting',
                'lampiran_surat' => '-',
                'perihal' => 'Rekomendasi Surat Izin Penelitian (Lembaga / Instansi)',
                'keterangan' => 'Struktur template resmi rekomendasi penelitian lembaga / instansi',
                'label_nama' => 'Nama Peneliti',
                'include_judul_penelitian' => true,
                'include_nama_kegiatan' => false,
                'dasar_hukum' => $dasarHukumUmum,
                'ketentuan' => [
                    'Menaati ketentuan peraturan perundang-undangan;',
                    'Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi Penelitian;',
                    'Berkoordinasi dan mengikuti petunjuk dan arahan dari Pimpinan Instansi tempat pelaksanaan penelitian;',
                    'Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas;',
                    'Setelah selesai melaksanakan kegiatan, agar yang bersangkutan melaporkan hasilnya kepada Bupati Bogor melalui Kepala Badan Kesatuan Bangsa dan Politik Kabupaten Bogor.'
                ],
                'tembusan' => [
                    'Yth. Bupati Bogor;',
                    'Yth. Wakil Bupati Bogor;',
                    'Yth. Sekretaris Daerah Kabupaten Bogor;',
                    'Yth. Pimpinan Lembaga / Instansi.'
                ]
            ],
            [
                'slug' => 'kkn_mahasiswa',
                'nama' => 'Template Surat Rekomendasi Kuliah Kerja Nyata (KKN)',
                'file_nama' => 'NEW KKN.docx',
                'nomor_prefix' => '000.9.2 / ',
                'sifat_surat' => 'Penting',
                'lampiran_surat' => '-',
                'perihal' => 'Rekomendasi Kuliah Kerja Nyata (KKN)',
                'keterangan' => 'Struktur template resmi rekomendasi KKN mahasiswa',
                'label_nama' => 'Nama',
                'include_judul_penelitian' => false,
                'include_nama_kegiatan' => false,
                'dasar_hukum' => $dasarHukumUmum,
                'ketentuan' => [
                    'Mentaati ketentuan Peraturan Perundang-undangan;',
                    'Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi KKN;',
                    'Senantiasa berkoordinasi dan mengikuti petunjuk dan arahan dari Forum Koordinasi Pimpinan Kecamatan (Forkopimcam) setempat;',
                    'Mematuhi aturan yang berlaku di lokasi KKN;',
                    'Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas;',
                    'Setelah selesai melaksanakan kegiatan, agar Mahasiswa yang bersangkutan melaporkan hasilnya kepada Bupati Bogor melalui Kepala Badan Kesatuan Bangsa dan Politik Kabupaten Bogor.'
                ],
                'tembusan' => [
                    'Yth. Bupati Bogor;',
                    'Yth. Wakil Bupati Bogor;',
                    'Yth. Sekretaris Daerah Kabupaten Bogor;',
                    'Yth. Kepala Badan Perencanaan Pembangunan, Riset dan Inovasi Daerah;',
                    'Yth. Rektor / Dekan Perguruan Tinggi.'
                ]
            ],
            [
                'slug' => 'kkl_siswa',
                'nama' => 'Template Surat Rekomendasi PKL Siswa Sekolah',
                'file_nama' => 'NEW PKL ANAK SEKOLAH.docx',
                'nomor_prefix' => '400.14.5.4 / ',
                'sifat_surat' => 'Penting',
                'lampiran_surat' => '-',
                'perihal' => 'Rekomendasi Praktik Kerja Lapangan (PKL Siswa)',
                'keterangan' => 'Struktur template resmi rekomendasi PKL siswa SMK / SMA',
                'label_nama' => 'Nama',
                'include_judul_penelitian' => false,
                'include_nama_kegiatan' => false,
                'dasar_hukum' => $dasarHukumUmum,
                'ketentuan' => [
                    'Mentaati ketentuan peraturan perundang-undangan;',
                    'Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi PKL/Magang;',
                    'Berkoordinasi dan mengikuti petunjuk dan arahan dari Pimpinan Instansi tempat pelaksanaan PKL/Magang;',
                    'Mematuhi aturan dan jam kerja yang berlaku di lokasi PKL/Magang;',
                    'Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas.'
                ],
                'tembusan' => [
                    'Yth. Bupati Bogor;',
                    'Yth. Wakil Bupati Bogor;',
                    'Yth. Sekretaris Daerah Kabupaten Bogor;',
                    'Yth. Kepala Sekolah.'
                ]
            ],
            [
                'slug' => 'kkl_mahasiswa',
                'nama' => 'Template Surat Rekomendasi KKL / PKL Mahasiswa',
                'file_nama' => 'NEW PKL MAHASISWA.docx',
                'nomor_prefix' => '400.14.5.4 / ',
                'sifat_surat' => 'Penting',
                'lampiran_surat' => '-',
                'perihal' => 'Rekomendasi Praktik Kerja Lapangan (PKL / KKL Mahasiswa)',
                'keterangan' => 'Struktur template resmi rekomendasi PKL mahasiswa',
                'label_nama' => 'Nama',
                'include_judul_penelitian' => false,
                'include_nama_kegiatan' => false,
                'dasar_hukum' => $dasarHukumUmum,
                'ketentuan' => [
                    'Mentaati ketentuan peraturan perundang-undangan;',
                    'Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi PKL/Magang;',
                    'Berkoordinasi dan mengikuti petunjuk dan arahan dari Pimpinan Instansi tempat pelaksanaan PKL/Magang;',
                    'Mematuhi aturan dan jam kerja yang berlaku di lokasi PKL/Magang;',
                    'Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas.'
                ],
                'tembusan' => [
                    'Yth. Bupati Bogor;',
                    'Yth. Wakil Bupati Bogor;',
                    'Yth. Sekretaris Daerah Kabupaten Bogor;',
                    'Yth. Dekan / Pimpinan Perguruan Tinggi.'
                ]
            ],
            [
                'slug' => 'pelaksanaan_kegiatan',
                'nama' => 'Template Surat Rekomendasi Pelaksanaan Kegiatan',
                'file_nama' => 'Pelaksanaan Kegiatan.docx',
                'nomor_prefix' => '000.6.9.3 / ',
                'sifat_surat' => 'Penting',
                'lampiran_surat' => '-',
                'perihal' => 'Rekomendasi Pelaksanaan Kegiatan',
                'keterangan' => 'Struktur template resmi rekomendasi pelaksanaan kegiatan organisasi',
                'label_nama' => 'Nama',
                'include_judul_penelitian' => false,
                'include_nama_kegiatan' => true,
                'dasar_hukum' => $dasarHukumUmum,
                'ketentuan' => [
                    'Mentaati ketentuan Peraturan Perundang-undangan;',
                    'Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi Pelaksanaan Kegiatan;',
                    'Senantiasa berkoordinasi dan mengikuti petunjuk dan arahan dari Forum Koordinasi Pimpinan Kecamatan (Forkopimcam) setempat;',
                    'Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas;',
                    'Mengadakan koordinasi dengan stakeholder terkait;',
                    'Mematuhi aturan yang berlaku di lokasi Pelaksanaan Kegiatan;',
                    'Setelah selesai melaksanakan kegiatan, agar yang bersangkutan melaporkan hasilnya kepada Bupati Bogor melalui Kepala Badan Kesatuan Bangsa dan Politik Kabupaten Bogor.'
                ],
                'tembusan' => [
                    'Yth. Bupati Bogor;',
                    'Yth. Wakil Bupati Bogor;',
                    'Yth. Sekretaris Daerah Kabupaten Bogor;',
                    'Yth. Pimpinan Organisasi / Lembaga.'
                ]
            ],
            [
                'slug' => 'perpanjangan',
                'nama' => 'Template Surat Perpanjangan Rekomendasi',
                'file_nama' => 'NEW PKL MAHASISWA.docx',
                'nomor_prefix' => '400.14.5.4 / ',
                'sifat_surat' => 'Penting',
                'lampiran_surat' => '-',
                'perihal' => 'Perpanjangan Surat Rekomendasi',
                'keterangan' => 'Struktur template resmi perpanjangan surat rekomendasi',
                'label_nama' => 'Nama',
                'include_judul_penelitian' => false,
                'include_nama_kegiatan' => false,
                'dasar_hukum' => $dasarHukumUmum,
                'ketentuan' => [
                    'Mentaati ketentuan peraturan perundang-undangan;',
                    'Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi PKL/Magang;',
                    'Berkoordinasi dan mengikuti petunjuk dan arahan dari Pimpinan Instansi tempat pelaksanaan PKL/Magang;',
                    'Mematuhi aturan dan jam kerja yang berlaku di lokasi PKL/Magang;',
                    'Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas.'
                ],
                'tembusan' => [
                    'Yth. Bupati Bogor;',
                    'Yth. Wakil Bupati Bogor;',
                    'Yth. Sekretaris Daerah Kabupaten Bogor;',
                    'Yth. Dekan / Pimpinan Instansi Asal.'
                ]
            ],
        ];

        foreach ($templates as $item) {
            $jenisLayanan = JenisLayanan::where('slug', $item['slug'])->first();

            $htmlContent = self::buildHtmlTemplate(
                $item['label_nama'],
                $item['include_judul_penelitian'],
                $item['include_nama_kegiatan']
            );

            TemplateSurat::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'jenis_layanan_id' => $jenisLayanan?->id,
                    'nama' => $item['nama'],
                    'file_nama' => $item['file_nama'],
                    'file_path' => 'template_surat/' . $item['file_nama'],
                    'nomor_prefix' => $item['nomor_prefix'],
                    'sifat_surat' => $item['sifat_surat'],
                    'lampiran_surat' => $item['lampiran_surat'],
                    'perihal' => $item['perihal'],
                    'pejabat_nama' => 'Drs. BAMBANG WIDJANARKO, M.Si',
                    'pejabat_nip' => '19680512 199303 1 008',
                    'pejabat_pangkat' => 'Pembina Utama Muda (IV/c)',
                    'pejabat_jabatan' => 'Kepala Badan Kesatuan Bangsa dan Politik Kabupaten Bogor',
                    'keterangan' => $item['keterangan'],
                    'konten_html' => $htmlContent,
                    'dasar_hukum' => $item['dasar_hukum'],
                    'ketentuan' => $item['ketentuan'],
                    'tembusan' => $item['tembusan'],
                    'is_active' => true,
                ]
            );
        }
    }

    /**
     * Susun kodingan template HTML yang identik dengan tata letak dokumen Word resmi
     */
    public static function buildHtmlTemplate(string $labelNama, bool $includeJudulPenelitian, bool $includeNamaKegiatan): string
    {
        $judulField = $includeJudulPenelitian ? '
            <tr>
                <td class="data-label">Judul Penelitian</td>
                <td class="data-colon">:</td>
                <td>{{ $judulKegiatan }}</td>
            </tr>' : '';

        $kegiatanField = $includeNamaKegiatan ? '
            <tr>
                <td class="data-label">Nama Kegiatan</td>
                <td class="data-colon">:</td>
                <td>{{ $judulKegiatan }}</td>
            </tr>' : '';

        return <<<'HTML'
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
        .kop-table { width: 100%; border-collapse: collapse; margin-bottom: 2px; }
        .kop-logo { width: 75px; vertical-align: middle; text-align: center; }
        .kop-logo img { width: 68px; height: auto; }
        .kop-text { text-align: center; vertical-align: middle; padding-right: 15px; }
        .kop-text h2 { font-size: 13.5pt; font-weight: bold; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }
        .kop-text h1 { font-size: 15.5pt; font-weight: bold; margin: 2px 0 3px 0; text-transform: uppercase; letter-spacing: 0.5px; }
        .kop-text p { font-size: 8.5pt; margin: 1px 0; line-height: 1.3; }
        .kop-divider { border-top: 2.5px solid #000; border-bottom: 1px solid #000; height: 2px; margin-top: 4px; margin-bottom: 14px; }
        .page-break { page-break-before: always; }
        .tgl-surat { text-align: right; font-size: 10pt; margin-bottom: 12px; }
        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 10pt; }
        .meta-table td { vertical-align: top; padding: 1px 0; }
        .meta-label { width: 75px; }
        .meta-colon { width: 15px; text-align: center; }
        .tujuan-block { margin-bottom: 14px; font-size: 10pt; line-height: 1.4; }
        .list-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; font-size: 10pt; }
        .list-table td { vertical-align: top; padding: 2px 0; line-height: 1.4; }
        .list-no { width: 22px; text-align: left; }
        .list-content { text-align: justify; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 4px; margin-bottom: 10px; font-size: 10pt; }
        .data-table td { vertical-align: top; padding: 2px 0; line-height: 1.4; }
        .data-label { width: 155px; }
        .data-colon { width: 15px; text-align: center; }
        .signature-table { width: 100%; border-collapse: collapse; margin-top: 35px; }
        .signature-table td { vertical-align: top; }
        .tembusan-box { font-size: 9.5pt; line-height: 1.4; padding-top: 10px; }
        .manual-esign-placeholder { width: 100%; height: 125px; }
    </style>
</head>
<body>
    <!-- HALAMAN 1 -->
    <div class="page-container">
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

        <div class="tgl-surat">
            Cibinong, {{ $tglSurat }}
        </div>

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
                <td>{{ $sifatSurat ?? 'Penting' }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="meta-label">Lampiran</td>
                <td class="meta-colon">:</td>
                <td>{{ $lampiranSurat ?? '-' }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="meta-label">Hal</td>
                <td class="meta-colon">:</td>
                <td><strong>{{ $halSurat }}</strong></td>
                <td></td>
            </tr>
        </table>

        <div class="tujuan-block">
            Yth. {{ $tujuanSurat }}<br>
            <span style="margin-left: 20px;">di</span><br>
            <span style="margin-left: 20px;">{{ $tempatTujuan }}</span>
        </div>

        <div style="font-size: 10pt; line-height: 1.4; margin-bottom: 4px;">
            Dasar :
        </div>
        <table class="list-table">
            @foreach($dasarHukum as $dIdx => $dItem)
                <tr>
                    <td class="list-no">{{ $dIdx + 1 }}.</td>
                    <td class="list-content">{{ $dItem }}</td>
                </tr>
            @endforeach
        </table>

        <div style="font-size: 10pt; line-height: 1.4; margin-bottom: 6px; text-align: justify;">
            Memperhatikan :<br>
            Surat dari {{ $asalInstansi }}, Nomor : {{ $nomorAsal }}, tanggal {{ $tglAsal }}, Perihal {{ $perihalSurat }}.
        </div>

        <div style="font-size: 10pt; line-height: 1.4; margin-bottom: 6px; text-align: justify;">
            Berdasarkan hal tersebut diatas, dengan ini kami memberikan {{ $halSurat }} kepada :
        </div>

        <table class="data-table">
            <tr>
                <td class="data-label">__LABEL_NAMA__</td>
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
            __FIELD_JUDUL__
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
            __FIELD_KEGIATAN__
        </table>

        <div style="text-align: right; margin-top: 14px; font-size: 10pt;">
            Dengan …
        </div>
    </div>

    <!-- HALAMAN 2 -->
    <div class="page-break"></div>

    <div class="page-container">
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

        <div style="text-align: center; margin-top: 4px; margin-bottom: 12px; font-weight: bold; font-size: 10pt;">
            - 2 -
        </div>

        <div style="font-size: 10pt; line-height: 1.4; margin-bottom: 6px;">
            Dengan ketentuan sebagai berikut :
        </div>
        <table class="list-table" style="margin-bottom: 10px;">
            @foreach($ketentuanList as $kIdx => $kItem)
                <tr>
                    <td class="list-no">{{ $kIdx + 1 }}.</td>
                    <td class="list-content">{{ $kItem }}</td>
                </tr>
            @endforeach
        </table>

        <div style="font-size: 10pt; line-height: 1.4; margin-bottom: 25px; text-align: justify;">
            Demikian disampaikan, atas perhatian dan kerja samanya diucapkan terima kasih.
        </div>

        <table class="signature-table">
            <tr>
                <td style="width: 52%; vertical-align: top;">
                    <div class="tembusan-box">
                        <div style="font-weight: bold; margin-bottom: 4px;">Tembusan :</div>
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
                    <div class="manual-esign-placeholder">
                        <!-- Ruang tanda tangan / TTE pejabat Bakesbangpol -->
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
HTML;
        $html = str_replace('__LABEL_NAMA__', $labelNama, $html);
        $html = str_replace('__FIELD_JUDUL__', $judulField, $html);
        return str_replace('__FIELD_KEGIATAN__', $kegiatanField, $html);
    }
}
