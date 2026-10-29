<?php

namespace App\Services;

use App\Models\PermohonanLayanan;
use App\Models\TemplateSurat;
use App\Models\SuratRekomendasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use ZipArchive;

class SuratTemplateService
{
    /**
     * Dapatkan file template DOCX dari database template_surats (atau fallback ke direktori filesystem).
     */
    public static function getTemplateFile(PermohonanLayanan $layanan): string
    {
        $slug = $layanan->jenisLayanan->slug ?? '';

        // 1. Cari template dari database template_surats
        $dbTemplate = null;
        if (!empty($layanan->jenis_layanan_id)) {
            $dbTemplate = TemplateSurat::where('jenis_layanan_id', $layanan->jenis_layanan_id)
                ->where('is_active', true)
                ->first();
        }

        if (!$dbTemplate && !empty($slug)) {
            $dbTemplate = TemplateSurat::where('slug', $slug)
                ->where('is_active', true)
                ->first();
        }

        if ($dbTemplate && file_exists($dbTemplate->absolute_path)) {
            return $dbTemplate->absolute_path;
        }

        // 2. Fallback jika belum tersinkronisasi di database
        $baseDir = base_path('template_surat');

        return match ($slug) {
            'penelitian_pt' => $baseDir . '/NEW PENELITIAN.docx',
            'penelitian_instansi' => $baseDir . '/PENELITIAN LEMBAGA ATAU INSTANSI.docx',
            'kkn_mahasiswa' => $baseDir . '/NEW KKN.docx',
            'kkl_siswa' => $baseDir . '/NEW PKL ANAK SEKOLAH.docx',
            'pelaksanaan_kegiatan' => $baseDir . '/Pelaksanaan Kegiatan.docx',
            'kkl_mahasiswa' => $baseDir . '/NEW PKL MAHASISWA.docx',
            'perpanjangan' => $baseDir . '/NEW PKL MAHASISWA.docx',
            default => $baseDir . '/NEW PKL MAHASISWA.docx',
        };
    }

    /**
     * Render string HTML surat dari database template_surats (bebas ketergantungan file docx).
     */
    public static function renderHtml(PermohonanLayanan $layanan, ?TemplateSurat $template = null): string
    {
        $slug = $layanan->jenisLayanan->slug ?? 'kkl_mahasiswa';
        if (!$template) {
            $template = TemplateSurat::where('jenis_layanan_id', $layanan->jenis_layanan_id)
                ->orWhere('slug', $slug)
                ->first();
        }

        $data = self::prepareTemplateData($layanan, $template);

        if ($template && !empty($template->konten_html)) {
            try {
                return \Illuminate\Support\Facades\Blade::render($template->konten_html, $data);
            } catch (\Throwable $e) {
                Log::warning("Gagal render Blade string dari database template_surats: " . $e->getMessage());
            }
        }

        // Fallback ke blade template jika database template kosong
        return view('pdf.surat_kesbangpol', array_merge($data, [
            'layanan' => $layanan,
            'qrUrl' => route('surat.pdf', $layanan->id)
        ]))->render();
    }

    /**
     * Buat otomatis file draf (PDF dan DOCX) langsung saat pemohon mengajukan formulir.
     * Tidak terpaku pada file docs; PDF dibuat langsung dari struktur HTML di database.
     */
    public static function generateInitialFiles(PermohonanLayanan $layanan): array
    {
        // 1. Pastikan record SuratRekomendasi terbuat
        $surat = $layanan->suratRekomendasi;
        $dbTemplate = TemplateSurat::where('jenis_layanan_id', $layanan->jenis_layanan_id)
            ->orWhere('slug', $layanan->jenisLayanan->slug ?? '')
            ->first();

        $prefix = $dbTemplate->nomor_prefix ?? '000.9.2 / ';
        $nomorAwal = trim($prefix) . ' ' . $layanan->id . ' - Wasnas';

        if (!$surat) {
            $surat = SuratRekomendasi::create([
                'permohonan_layanan_id' => $layanan->id,
                'nomor_surat' => $nomorAwal,
                'tanggal_surat' => now(),
                'sifat_surat' => $dbTemplate->sifat_surat ?? 'Biasa',
                'lampiran_surat' => $dbTemplate->lampiran_surat ?? '-',
                'pejabat_nama' => $dbTemplate->pejabat_nama ?? 'Drs. BAMBANG WIDJANARKO, M.Si',
                'pejabat_nip' => $dbTemplate->pejabat_nip ?? '19680512 199303 1 008',
                'pejabat_pangkat' => $dbTemplate->pejabat_pangkat ?? 'Pembina Utama Muda (IV/c)',
                'pejabat_jabatan' => $dbTemplate->pejabat_jabatan ?? 'Kepala Badan Kesatuan Bangsa dan Politik Kabupaten Bogor',
                'verification_token' => Str::random(40),
            ]);
            $layanan->setRelation('suratRekomendasi', $surat);
        } elseif (empty($surat->verification_token)) {
            $surat->update(['verification_token' => Str::random(40)]);
        }

        $outputDir = storage_path('app/public/permohonan/keluaran');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $slugPemohon = Str::slug($layanan->atas_nama ?: ($layanan->user->name ?? 'pemohon'));
        $baseFileName = 'Surat_Rekomendasi_Kesbangpol_' . $layanan->id . '_' . $slugPemohon;

        $results = ['pdf' => null, 'docx' => null];

        // 2. Generate PDF Draf Otomatis langsung dari template HTML kodingan di database
        try {
            $pdfFileName = $baseFileName . '.pdf';
            $relativePdfPath = 'permohonan/keluaran/' . $pdfFileName;
            $outputPdfPath = storage_path('app/public/' . $relativePdfPath);

            $htmlContent = self::renderHtml($layanan, $dbTemplate);
            $pdf = Pdf::loadHTML($htmlContent)->setPaper('a4', 'portrait');
            $pdf->save($outputPdfPath);

            $layanan->file_surat_keluaran = $relativePdfPath;
            $results['pdf'] = $relativePdfPath;
        } catch (\Throwable $e) {
            Log::error("Gagal generate draf PDF saat pengajuan #{$layanan->id}: " . $e->getMessage());
        }

        // 3. Generate DOCX Draf (opsional/arsip tambahan, tidak blocking jika template fisik tidak ada)
        try {
            $docxFileName = $baseFileName . '.docx';
            $relativeDocxPath = 'permohonan/keluaran/' . $docxFileName;
            $outputDocxPath = storage_path('app/public/' . $relativeDocxPath);

            self::generateDocx($layanan, $outputDocxPath);
            $results['docx'] = $relativeDocxPath;
        } catch (\Throwable $e) {
            // DOCX adalah opsional sehingga kegagalan tidak membatalkan pengajuan
            Log::info("DOCX draf dilewati atau gagal: " . $e->getMessage());
        }

        $layanan->save();

        return $results;
    }

    /**
     * Ekstraksi dan format data permohonan agar sesuai dengan field template surat.
     */
    /**
     * Ekstraksi dan format data permohonan agar sesuai dengan field template surat (HTML dan DOCX).
     */
    public static function prepareTemplateData(PermohonanLayanan $layanan, ?TemplateSurat $dbTemplate = null): array
    {
        $tglSurat = Carbon::parse($layanan->updated_at ?? now())->translatedFormat('d F Y');
        $tglAsal = Carbon::parse($layanan->created_at ?? now())->translatedFormat('d F Y');
        $tglMulai = Carbon::parse($layanan->tanggal_mulai ?? now())->translatedFormat('d F Y');
        $tglSelesai = Carbon::parse($layanan->tanggal_selesai ?? now())->translatedFormat('d F Y');

        $slug = $layanan->jenisLayanan->slug ?? '';
        if (!$dbTemplate) {
            $dbTemplate = TemplateSurat::where('jenis_layanan_id', $layanan->jenis_layanan_id)
                ->orWhere('slug', $slug)
                ->first();
        }

        $prefixNomor = $dbTemplate->nomor_prefix ?? (match($slug) {
            'penelitian_pt', 'penelitian_instansi', 'kkn_mahasiswa' => '000.9.2 / ',
            'pelaksanaan_kegiatan' => '000.6.9.3 / ',
            default => '400.14.5.4 / ',
        });

        $surat = $layanan->suratRekomendasi;
        if ($surat && !empty($surat->nomor_surat)) {
            $rawNomor = trim($surat->nomor_surat);
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

        $halSurat = $dbTemplate->perihal ?? match($slug) {
            'penelitian_pt', 'penelitian_instansi' => 'Rekomendasi Surat Izin Penelitian',
            'kkn_mahasiswa' => 'Rekomendasi Kuliah Kerja Nyata (KKN)',
            'pelaksanaan_kegiatan' => 'Rekomendasi Pelaksanaan Kegiatan',
            default => 'Rekomendasi Praktik Kerja Lapangan (PKL)',
        };

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

        $asalInstansi = $layanan->asal_instansi ?? ($layanan->user->asal_instansi ?? 'Perguruan Tinggi / Sekolah');
        $nomorAsal = $layanan->nomor_surat_pengantar ?: ('1832/D/' . strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $asalInstansi), 0, 6)) . '/' . date('Y'));

        // Parse nama-nama pemohon
        $rawNama = $layanan->atas_nama ?: ($layanan->user->name ?? 'PEMOHON');
        if (preg_match_all('/(?:\d+[\.\)]\s*)?([^\r\n,]+)/', $rawNama, $matches)) {
            $namaList = array_values(array_filter(array_map('trim', $matches[1])));
        } else {
            $namaList = [trim($rawNama)];
        }
        if (empty($namaList)) {
            $namaList = [trim($rawNama)];
        }

        $namaFormatted = '';
        if (count($namaList) === 1) {
            $namaFormatted = strtoupper($namaList[0]);
        } else {
            $namaLines = [];
            foreach ($namaList as $idx => $nm) {
                $namaLines[] = ($idx + 1) . '. ' . strtoupper($nm);
            }
            $namaFormatted = implode("\n", $namaLines);
        }

        $jumlahAnggota = count($namaList) > 1 ? count($namaList) : ($layanan->jumlah_anggota ?? 1);
        $terbilangMap = [1 => 'Satu', 2 => 'Dua', 3 => 'Tiga', 4 => 'Empat', 5 => 'Lima', 6 => 'Enam', 7 => 'Tujuh', 8 => 'Delapan', 9 => 'Sembilan', 10 => 'Sepuluh'];
        $terbilang = $terbilangMap[$jumlahAnggota] ?? (string) $jumlahAnggota;
        $jumlahPesertaFormatted = $jumlahAnggota . ' (' . $terbilang . ') Orang';

        $alamat = $layanan->user->alamat ?? ($layanan->alamat ?? 'Kabupaten Bogor');
        $penanggungJawab = $layanan->user->pembimbing_magang ?: ($layanan->atas_nama ?: ($layanan->user->name ?? '-'));

        $pimpinanAsal = 'Dekan / Pimpinan ' . $asalInstansi;
        if (str_contains(strtolower($asalInstansi), 'universitas') || str_contains(strtolower($asalInstansi), 'fakultas')) {
            $pimpinanAsal = 'Rektor ' . $asalInstansi;
        } elseif (str_contains(strtolower($asalInstansi), 'sekolah') || str_contains(strtolower($asalInstansi), 'smk')) {
            $pimpinanAsal = 'Kepala ' . $asalInstansi;
        }

        $jenisLayananNama = $layanan->jenisLayanan->nama ?? 'Praktik Kerja Lapangan (PKL)';
        $perihalSurat = $layanan->judul_kegiatan ?: $jenisLayananNama;
        $memperhatikan = "Surat {$pimpinanAsal}, Nomor : {$nomorAsal}, tanggal {$tglAsal}, Perihal {$perihalSurat}.";

        $tempatKegiatan = 'Kantor ' . ucwords(strtolower(trim($dinasNameRaw)));
        if (!str_contains(strtolower($tempatKegiatan), 'kabupaten') && !str_contains(strtolower($tempatKegiatan), 'kab.')) {
            $tempatKegiatan .= ' Kabupaten Bogor';
        }

        // Logo Kabupaten Bogor Base64
        $logoPath = public_path('assets/certificate/lambang_kabupaten_bogor.png');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('assets/images/logo_bakesbangpol_2026.png');
        }
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        $dasarHukum = $dbTemplate->dasar_hukum ?? [
            'Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 3 Tahun 2018 tentang Penerbitan Surat Keterangan Penelitian;',
            'Peraturan Bupati Bogor Nomor 56 Tahun 2020 tentang Kedudukan, Susunan Organisasi, Tugas dan Fungsi, serta Tata Kerja Badan Kesatuan Bangsa dan Politik sebagaimana telah diubah dengan Peraturan Bupati Bogor Nomor 27 Tahun 2022 tentang Kedudukan, Susunan Organisasi, Tugas dan Fungsi serta Tata Kerja Badan Kesatuan Bangsa dan Politik;',
            'Peraturan Bupati Bogor Nomor 65 Tahun 2023 tentang Sistem Kerja Aparatur Sipil Negara Untuk Penyederhanaan Birokrasi Di Lingkungan Pemerintah Daerah.'
        ];

        $ketentuanList = $dbTemplate->ketentuan ?? [
            'Mentaati ketentuan peraturan perundang-undangan;',
            'Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi PKL/Magang;',
            'Berkoordinasi dan mengikuti petunjuk dan arahan dari Pimpinan Instansi tempat pelaksanaan PKL/Magang;',
            'Mematuhi aturan dan jam kerja yang berlaku di lokasi PKL/Magang;',
            'Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas.'
        ];

        $tembusanList = $dbTemplate->tembusan ?? [
            'Yth. Bupati Bogor;',
            'Yth. Wakil Bupati Bogor;',
            'Yth. Sekretaris Daerah Kabupaten Bogor;',
            'Yth. ' . $pimpinanAsal . '.'
        ];

        return [
            // Variabel Format CamelCase (untuk Blade HTML Template)
            'tglSurat' => $tglSurat,
            'nomorSurat' => $nomorSurat,
            'sifatSurat' => $dbTemplate->sifat_surat ?? 'Penting',
            'lampiranSurat' => $dbTemplate->lampiran_surat ?? '-',
            'halSurat' => $halSurat,
            'tujuanSurat' => $tujuanSurat,
            'tempatTujuan' => $tempatTujuan,
            'dasarHukum' => $dasarHukum,
            'asalInstansi' => $asalInstansi,
            'nomorAsal' => $nomorAsal,
            'tglAsal' => $tglAsal,
            'perihalSurat' => $perihalSurat,
            'namaList' => $namaList,
            'namaFormatted' => $namaFormatted,
            'alamatPemohon' => $alamat,
            'judulKegiatan' => $layanan->judul_kegiatan ?: $jenisLayananNama,
            'penanggungJawab' => $penanggungJawab,
            'jumlahPesertaFormatted' => $jumlahPesertaFormatted,
            'tglMulai' => $tglMulai,
            'tglSelesai' => $tglSelesai,
            'tempatKegiatan' => $tempatKegiatan,
            'ketentuanList' => $ketentuanList,
            'tembusanList' => $tembusanList,
            'logoBase64' => $logoBase64,
            'layanan' => $layanan,

            // Variabel Format SnakeCase (kompatibilitas template docx & blade)
            'tanggal_surat' => $tglSurat,
            'nomor_surat' => $nomorSurat,
            'tujuan_surat' => $tujuanSurat,
            'tempat_tujuan' => $tempatTujuan,
            'memperhatikan' => $memperhatikan,
            'nama' => $namaFormatted,
            'alamat' => $alamat,
            'penanggung_jawab' => $penanggungJawab,
            'jumlah_peserta' => $jumlahPesertaFormatted,
            'waktu' => $tglMulai . ' s.d ' . $tglSelesai,
            'tempat' => $tempatKegiatan,
            'judul' => $layanan->judul_kegiatan ?: $jenisLayananNama,
            'tembusan' => 'Yth. ' . $pimpinanAsal . '.'
        ];
    }

    /**
     * Generate file DOCX resmi dari template yang bersangkutan.
     */
    public static function generateDocx(PermohonanLayanan $layanan, ?string $outputPath = null): string
    {
        $templatePath = self::getTemplateFile($layanan);
        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template surat tidak ditemukan: {$templatePath}");
        }

        if (!$outputPath) {
            $slugNama = Str::slug($layanan->atas_nama ?? ($layanan->user->name ?? 'pemohon'));
            $tempDir = storage_path('app/temp_surat');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            $outputPath = $tempDir . '/Surat_Rekomendasi_' . $layanan->id . '_' . $slugNama . '.docx';
        }

        if (!file_exists(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0755, true);
        }

        copy($templatePath, $outputPath);

        $zip = new ZipArchive();
        if ($zip->open($outputPath) === true) {
            $xml = $zip->getFromName('word/document.xml');
            $slug = $layanan->jenisLayanan->slug ?? 'kkl_mahasiswa';
            $data = self::prepareTemplateData($layanan);

            $xml = self::populateXml($xml, $slug, $data);

            // Bersihkan sisa tag warna merah EE0000 agar dokumen menjadi teks hitam formal
            $xml = str_replace('w:val="EE0000"', 'w:val="000000"', $xml);

            // Hilangkan gambar/drawing e-sign dari template agar tempat tanda tangan menjadi ruang kosong siap TTE / stempel manual
            $xml = preg_replace('/<w:drawing\b.*?<\/w:drawing>/s', '', $xml);

            $zip->addFromString('word/document.xml', $xml);
            $zip->close();
        } else {
            throw new \RuntimeException("Gagal membuka output docx: {$outputPath}");
        }

        return $outputPath;
    }

    /**
     * Populate XML template Word berdasarkan slug jenis layanan.
     */
    protected static function populateXml(string $xml, string $slug, array $data): string
    {
        preg_match_all('/<w:p\b[^>]*>(?:(?!<\/w:p>).)*?EE0000(?:(?!<\/w:p>).)*?<\/w:p>/s', $xml, $matches);
        $pList = $matches[0] ?? [];

        $configs = [
            'penelitian_pt' => [
                0 => ['field' => 'tanggal_surat', 'prefix' => 'Cibinong, '],
                1 => ['field' => 'nomor_surat', 'prefix' => '000.9.2 / '],
                2 => ['field' => 'tujuan_surat', 'prefix' => 'Yth.  '],
                4 => ['field' => 'tempat_tujuan'],
                5 => ['field' => 'memperhatikan'],
                6 => ['field' => 'nama', 'bold' => true],
                7 => ['field' => 'alamat'],
                8 => ['field' => 'judul'],
                9 => ['field' => 'penanggung_jawab'],
                10 => ['field' => 'jumlah_peserta'],
                11 => ['field' => 'waktu'],
                12 => ['field' => 'tempat'],
                13 => ['field' => 'tembusan']
            ],
            'penelitian_instansi' => [
                0 => ['field' => 'tanggal_surat', 'prefix' => 'Cibinong, '],
                1 => ['field' => 'tujuan_surat', 'prefix' => 'Yth. '],
                3 => ['field' => 'tempat_tujuan'],
                4 => ['field' => 'memperhatikan'],
                5 => ['field' => 'nama', 'bold' => true],
                6 => ['empty' => true],
                7 => ['empty' => true],
                8 => ['empty' => true],
                9 => ['empty' => true],
                10 => ['field' => 'alamat'],
                11 => ['field' => 'judul'],
                12 => ['field' => 'penanggung_jawab'],
                13 => ['field' => 'jumlah_peserta'],
                14 => ['field' => 'waktu'],
                15 => ['field' => 'tempat'],
                16 => ['field' => 'tembusan']
            ],
            'kkn_mahasiswa' => [
                0 => ['field' => 'tanggal_surat', 'prefix' => 'Cibinong,  '],
                1 => ['field' => 'nomor_surat', 'prefix' => '000.9.2 / '],
                2 => ['field' => 'tujuan_surat', 'prefix' => 'Yth. '],
                4 => ['field' => 'tempat_tujuan'],
                5 => ['field' => 'memperhatikan'],
                6 => ['field' => 'nama', 'bold' => true],
                7 => ['field' => 'alamat'],
                8 => ['field' => 'penanggung_jawab'],
                9 => ['field' => 'jumlah_peserta'],
                10 => ['field' => 'waktu'],
                11 => ['field' => 'tempat'],
                12 => ['field' => 'tembusan']
            ],
            'kkl_siswa' => [
                0 => ['field' => 'tanggal_surat', 'prefix' => 'Cibinong,   '],
                1 => ['field' => 'nomor_surat', 'prefix' => '400.14.5.4 / '],
                2 => ['field' => 'tujuan_surat', 'prefix' => 'Yth. '],
                4 => ['field' => 'tempat_tujuan'],
                5 => ['field' => 'memperhatikan'],
                6 => ['field' => 'nama', 'bold' => true],
                7 => ['empty' => true],
                8 => ['field' => 'alamat'],
                9 => ['field' => 'penanggung_jawab'],
                10 => ['field' => 'jumlah_peserta'],
                11 => ['field' => 'waktu'],
                12 => ['field' => 'tempat'],
                13 => ['field' => 'tembusan']
            ],
            'kkl_mahasiswa' => [
                0 => ['field' => 'tanggal_surat', 'prefix' => 'Cibinong,  '],
                1 => ['field' => 'nomor_surat', 'prefix' => '400.14.5.4 /  '],
                2 => ['field' => 'tujuan_surat', 'prefix' => 'Yth.  '],
                6 => ['field' => 'tempat_tujuan'],
                9 => ['field' => 'memperhatikan'],
                10 => ['field' => 'nama', 'bold' => true],
                11 => ['field' => 'alamat'],
                12 => ['field' => 'penanggung_jawab'],
                13 => ['field' => 'jumlah_peserta'],
                14 => ['field' => 'waktu'],
                15 => ['field' => 'tempat'],
                16 => ['field' => 'tembusan']
            ],
            'pelaksanaan_kegiatan' => [
                0 => ['field' => 'tanggal_surat', 'prefix' => 'Cibinong, '],
                1 => ['field' => 'nomor_surat', 'prefix' => '000.6.9.3 / '],
                2 => ['field' => 'tujuan_surat', 'prefix' => 'Yth. '],
                4 => ['field' => 'tempat_tujuan'],
                5 => ['field' => 'memperhatikan'],
                6 => ['field' => 'nama', 'bold' => true],
                7 => ['empty' => true],
                8 => ['empty' => true],
                9 => ['empty' => true],
                10 => ['field' => 'alamat'],
                11 => ['field' => 'penanggung_jawab'],
                12 => ['field' => 'jumlah_peserta'],
                13 => ['field' => 'waktu'],
                14 => ['field' => 'tempat'],
                15 => ['field' => 'judul'],
                16 => ['field' => 'tembusan']
            ],
        ];

        $map = $configs[$slug] ?? $configs['kkl_mahasiswa'];

        foreach ($map as $idx => $m) {
            if (!isset($pList[$idx])) {
                continue;
            }
            $oldP = $pList[$idx];
            if (!empty($m['empty'])) {
                $newP = self::emptyP($oldP);
            } else {
                $val = $data[$m['field']] ?? '';
                $prefix = !empty($m['prefix']) ? $m['prefix'] : '';
                // Mencegah duplikasi jika teks/nomor surat sudah mengandung prefix
                if (!empty($prefix) && (str_starts_with(trim($val), trim($prefix)) || str_contains($val, trim($prefix)))) {
                    $text = $val;
                } else {
                    $text = $prefix . $val;
                }
                $newP = self::replaceP($oldP, $text, !empty($m['bold']));
            }
            $xml = str_replace($oldP, $newP, $xml);
        }

        // Penyesuaian khusus nomor surat jika template tidak menandai nomor surat dalam merah
        if ($slug === 'penelitian_instansi') {
            $nomorLengkap = '000.9.2 / ' . ($data['nomor_surat'] ?? 'Wasnas');
            $xml = str_replace('000.9.2 / 591 - Wasnas', $nomorLengkap, $xml);
        }

        return $xml;
    }

    /**
     * Format ulang paragraph XML dengan teks baru tanpa merusak struktur style Word
     */
    protected static function replaceP(string $pXml, string $newText, bool $bold = false): string
    {
        $bTag = $bold ? '<w:b/>' : '';
        preg_match('/<w:pPr>.*?<\/w:pPr>/s', $pXml, $pPrMatch);
        $pPr = !empty($pPrMatch[0]) ? $pPrMatch[0] : '<w:pPr/>';
        $pPr = str_replace('<w:color w:val="EE0000"/>', '', $pPr);

        $lines = explode("\n", $newText);
        $runs = '';
        foreach ($lines as $i => $line) {
            if ($i > 0) {
                $runs .= '<w:r><w:br/></w:r>';
            }
            $runs .= '<w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>' . $bTag . '<w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t xml:space="preserve">' . htmlspecialchars($line, ENT_XML1) . '</w:t></w:r>';
        }

        preg_match('/^<w:p\b[^>]*>/', $pXml, $pOpen);
        $openTag = !empty($pOpen[0]) ? $pOpen[0] : '<w:p>';

        return $openTag . $pPr . $runs . '</w:p>';
    }

    /**
     * Kosongkan paragraph XML
     */
    protected static function emptyP(string $pXml): string
    {
        preg_match('/<w:pPr>.*?<\/w:pPr>/s', $pXml, $pPrMatch);
        $pPr = !empty($pPrMatch[0]) ? $pPrMatch[0] : '<w:pPr/>';
        $pPr = str_replace('<w:color w:val="EE0000"/>', '', $pPr);

        preg_match('/^<w:p\b[^>]*>/', $pXml, $pOpen);
        $openTag = !empty($pOpen[0]) ? $pOpen[0] : '<w:p>';

        return $openTag . $pPr . '</w:p>';
    }
}
