<?php

namespace App\Http\Controllers\Kesbangpol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermohonanLayanan;
use App\Models\StatusMaster;
use App\Models\MagangApplication;
use App\Models\Rekrutmen;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class LayananController extends Controller
{
    public function dashboard()
    {
        $totalPermohonan = PermohonanLayanan::count();
        $sedangDiproses = PermohonanLayanan::whereHas('statusMaster', function ($q) {
            $q->whereIn('kode', ['menunggu_verifikasi', 'perlu_revisi']);
        })->count();
        $selesai = PermohonanLayanan::whereHas('statusMaster', function ($q) {
            $q->whereIn('kode', ['disetujui', 'selesai']);
        })->count();
        $ditolak = PermohonanLayanan::whereHas('statusMaster', function ($q) {
            $q->where('kode', 'ditolak');
        })->count();

        $pengajuanTerbarus = PermohonanLayanan::with(['jenisLayanan', 'statusMaster'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Data Internal Kesbangpol (Sebagai Dinas)
        $user = Auth::user();
        $dinas = $user->dinas;
        
        $totalKuota = 0;
        $slotTersedia = 0;
        $pesertaAktif = 0;
        
        if ($dinas) {
            $rekrutmens = Rekrutmen::where('dinas_id', $dinas->id)->get();
            $totalKuota = $rekrutmens->sum('kuota');
            $slotTersedia = $rekrutmens->sum('slot_tersedia');

            $pesertaAktif = MagangApplication::whereHas('rekrutmen', function ($q) use ($dinas) {
                $q->where('dinas_id', $dinas->id);
            })->where('status', 'diterima')->count();
        }

        return view('pelayanan.kesbangpol.dashboard', compact(
            'totalPermohonan', 'sedangDiproses', 'selesai', 'ditolak', 'pengajuanTerbarus',
            'dinas', 'totalKuota', 'slotTersedia', 'pesertaAktif'
        ));
    }

    public function index()
    {
        $layanans = PermohonanLayanan::with(['jenisLayanan', 'statusMaster'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('pelayanan.kesbangpol.layanan.index', compact('layanans'));
    }

    public function show($id)
    {
        $layanan = PermohonanLayanan::with(['jenisLayanan', 'statusMaster'])->findOrFail($id);
        $allDinas = \App\Models\Dinas::orderBy('name')->get();
        return view('pelayanan.kesbangpol.layanan.show', compact('layanan', 'allDinas'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'dinas_id' => 'nullable|exists:dinas,id',
        ]);

        $layanan = PermohonanLayanan::findOrFail($id);
        $statusMaster = StatusMaster::where('kode', $request->status)->firstOrFail();

        $layanan->status_master_id = $statusMaster->id;

        if ($request->status === 'perlu_revisi') {
            $layanan->keterangan = $request->keterangan;
            $layanan->status_revisi = 'menunggu_user';
            $layanan->dokumen_direvisi = null;
            $layanan->catatan_pemohon = null;
            $layanan->tanggal_revisi = null;
        } elseif ($request->status === 'ditolak') {
            $layanan->keterangan = $request->keterangan;
            $layanan->status_revisi = null;
        } elseif ($request->status === 'disetujui') {
            $layanan->status_revisi = null;
        }

        if ($request->status === 'disetujui') {
            if ($request->filled('dinas_id')) {
                $layanan->dinas_id = $request->dinas_id;
            }
            
            $surat = \App\Models\SuratRekomendasi::updateOrCreate(
                ['permohonan_layanan_id' => $layanan->id],
                [
                    'nomor_surat' => $request->nomor_surat ?: ('070/' . $layanan->id . '/Bakesbangpol/' . date('Y')),
                    'tanggal_surat' => now(),
                    'sifat_surat' => 'Biasa',
                    'lampiran_surat' => '-',
                    'pejabat_nama' => 'FERDINANDO SELMI PARDEDE, S.IP, M.AP',
                    'pejabat_nip' => '196805121990031005',
                    'pejabat_pangkat' => 'Pembina Tk. I',
                    'pejabat_jabatan' => 'KEPALA BADAN KESATUAN BANGSA DAN POLITIK KABUPATEN BOGOR'
                ]
            );

            if ($request->hasFile('file_surat_keluaran')) {
                $path = $request->file('file_surat_keluaran')->store('permohonan/keluaran', 'public');
                $layanan->file_surat_keluaran = $path;
            } else {
                // 1. Generate PDF Resmi otomatis dengan Kop Surat, TTE, dan QR Code
                try {
                    $pdfFileName = 'Surat_Rekomendasi_Kesbangpol_' . $layanan->id . '_' . \Illuminate\Support\Str::slug($layanan->atas_nama ?? 'pemohon') . '.pdf';
                    $relativePdfPath = 'permohonan/keluaran/' . $pdfFileName;
                    $outputPdfPath = storage_path('app/public/' . $relativePdfPath);

                    if (!file_exists(dirname($outputPdfPath))) {
                        mkdir(dirname($outputPdfPath), 0755, true);
                    }

                    $qrUrl = route('surat.pdf', $layanan->id);
                    $pdf = Pdf::loadView('pdf.surat_kesbangpol', compact('layanan', 'qrUrl'))
                        ->setPaper('a4', 'portrait');
                    $pdf->save($outputPdfPath);

                    // Simpan path PDF resmi ke database
                    $layanan->file_surat_keluaran = $relativePdfPath;
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal generate PDF rekomendasi: ' . $e->getMessage());
                }

                // 2. Generate juga draf DOCX cadangan jika template tersedia
                try {
                    $templatePath = resource_path('templates/template_kesbangpol.docx');
                    if (!file_exists($templatePath)) {
                        $templatePath = base_path('template_kesbangpol.docx');
                    }

                    if (file_exists($templatePath)) {
                        $docxFileName = 'Surat_Rekomendasi_Kesbangpol_' . $layanan->id . '_' . \Illuminate\Support\Str::slug($layanan->atas_nama ?? 'pemohon') . '.docx';
                        $relativeDocxPath = 'permohonan/keluaran/' . $docxFileName;
                        $outputDocxPath = storage_path('app/public/' . $relativeDocxPath);

                        if (!file_exists(dirname($outputDocxPath))) {
                            mkdir(dirname($outputDocxPath), 0755, true);
                        }
                        copy($templatePath, $outputDocxPath);

                        $zip = new \ZipArchive();
                        if ($zip->open($outputDocxPath) === TRUE) {
                            $xml = $zip->getFromName('word/document.xml');
                            $dinasName = $layanan->tempat_kegiatan ?? 'Dinas Tujuan';

                            $tglSurat = \Carbon\Carbon::parse(now())->translatedFormat('d F Y');
                            $tglAsal = \Carbon\Carbon::parse($layanan->created_at ?? now())->translatedFormat('d F Y');
                            $tglMulai = \Carbon\Carbon::parse($layanan->tanggal_mulai ?? now())->translatedFormat('d F Y');
                            $tglSelesai = \Carbon\Carbon::parse($layanan->tanggal_selesai ?? now())->translatedFormat('d F Y');

                            $replacements = [
                                '${tanggal_surat}' => $tglSurat,
                                '${nomor_surat}' => $surat->nomor_surat,
                                '${sifat_surat}' => $surat->sifat_surat,
                                '${lampiran_surat}' => $surat->lampiran_surat,
                                '${tujuan_surat}' => 'Kepala ' . $dinasName,
                                '${tempat_tujuan}' => 'Kabupaten Bogor',
                                '${asal_surat}' => $layanan->asal_instansi ?? 'Perguruan Tinggi / Sekolah',
                                '${nomor_asal_surat}' => $layanan->nomor_surat_pengantar ?: ('SRT/' . $layanan->id . '/' . date('Y')),
                                '${tanggal_asal_surat}' => $tglAsal,
                                '${no_mhs}' => '1.',
                                '${nama_mahasiswa}' => $layanan->atas_nama ?? ($layanan->user->name ?? '-'),
                                '${alamat_pemohon}' => $layanan->user->alamat ?? $layanan->alamat ?? 'Kabupaten Bogor',
                                '${nama_penanggung_jawab}' => $layanan->atas_nama ?? ($layanan->user->name ?? '-'),
                                '${jumlah_peserta}' => ($layanan->jumlah_anggota ?? 1) . ' Orang',
                                '${tenggang_waktu}' => $tglMulai . ' s.d ' . $tglSelesai,
                                '${tempat_pkl}' => $dinasName,
                                '${nama_pejabat}' => $surat->pejabat_nama,
                                '${pangkat_pejabat}' => $surat->pejabat_pangkat,
                                '${nip_pejabat}' => $surat->pejabat_nip,
                            ];

                            $xml = str_replace(array_keys($replacements), array_values($replacements), $xml);
                            $zip->addFromString('word/document.xml', $xml);
                            $zip->close();
                        }
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal generate draf docx cadangan: ' . $e->getMessage());
                }
            }
        }

        $layanan->save();

        if ($request->status === 'disetujui' || $request->status === 'selesai') {
            \App\Models\Notification::create([
                'user_id' => $layanan->user_id,
                'judul' => 'Rekomendasi Kesbangpol Disetujui',
                'pesan' => 'Permohonan Rekomendasi Kesbangpol Anda (#' . $layanan->id . ') telah disetujui dan diteruskan ke Dinas tujuan Anda.',
                'link' => route('landing.profile'),
            ]);

            // AUTO-FORWARD KE DINAS JIKA DINAS ID ADA
            if ($layanan->dinas_id && (!$layanan->jenisLayanan || $layanan->jenisLayanan->slug !== 'perpanjangan')) {
                \App\Models\MagangApplication::updateOrCreate(
                    [
                        'user_id' => $layanan->user_id,
                        'permohonan_layanan_id' => $layanan->id,
                    ],
                    [
                        'dinas_id' => $layanan->dinas_id,
                        'status' => 'menunggu',
                        'tanggal_mulai' => $layanan->tanggal_mulai,
                        'tanggal_selesai' => $layanan->tanggal_selesai,
                    ]
                );
            }

            // Update untuk perpanjangan
            if ($layanan->jenisLayanan && $layanan->jenisLayanan->slug === 'perpanjangan') {
                $magangApplication = \App\Models\MagangApplication::where('user_id', $layanan->user_id)
                    ->whereIn('status', ['menunggu', 'diterima', 'aktif'])
                    ->latest()
                    ->first();
                    
                if ($magangApplication) {
                    $magangApplication->tanggal_mulai = $layanan->tanggal_mulai;
                    $magangApplication->tanggal_selesai = $layanan->tanggal_selesai;
                    $magangApplication->save();
                }
            }
        } elseif ($request->status === 'ditolak' || $request->status === 'perlu_revisi') {
            \App\Models\Notification::create([
                'user_id' => $layanan->user_id,
                'judul' => $request->status === 'ditolak' ? 'Permohonan Kesbangpol Ditolak' : 'Permohonan Kesbangpol Perlu Revisi',
                'pesan' => 'Permohonan Rekomendasi Kesbangpol Anda (#' . $layanan->id . ') ' . ($request->status === 'ditolak' ? 'ditolak.' : 'memerlukan revisi: ') . ($request->keterangan ?? ''),
                'link' => route('landing.profile'),
            ]);
        }

        return redirect()->route('kesbangpol.layanan.show', $id)
            ->with('success', 'Status permohonan berhasil diperbarui dan Surat Rekomendasi telah diterbitkan!');
    }

    public function updatePemohon(Request $request, $id)
    {
        $layanan = PermohonanLayanan::with('user')->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:255',
            'institusi' => 'required|string|max:255',
            'alamat' => 'required|string',
        ]);

        if ($layanan->user) {
            $layanan->user->update([
                'name' => $request->name,
                'nim' => $request->nim,
                'asal_instansi' => $request->institusi,
                'alamat' => $request->alamat,
            ]);
        }

        return redirect()->back()->with('success', 'Data pemohon berhasil diperbarui.');
    }

    /**
     * Generate & Download Surat Rekomendasi Kesbangpol (.docx).
     * Membuat DOCX dari nol (tanpa template) agar format selalu valid dan konsisten dengan PDF.
     */
    public function generateDocx($id)
    {
        $layanan = PermohonanLayanan::with(['jenisLayanan', 'user', 'statusMaster', 'suratRekomendasi'])->findOrFail($id);

        // === DATA PREPARATION (identik dengan PDF blade) ===
        $tglSurat = \Carbon\Carbon::parse($layanan->updated_at ?? now())->translatedFormat('d F Y');
        $tglAsal = \Carbon\Carbon::parse($layanan->created_at ?? now())->translatedFormat('d F Y');
        $tglMulai = \Carbon\Carbon::parse($layanan->tanggal_mulai ?? now())->translatedFormat('d F Y');
        $tglSelesai = \Carbon\Carbon::parse($layanan->tanggal_selesai ?? now())->translatedFormat('d F Y');

        $surat = $layanan->suratRekomendasi;
        $nomorSurat = $surat->nomor_surat ?? ('400.14.5.4 / ' . $layanan->id . ' - Wasnas');

        $jenisLayananNama = $layanan->jenisLayanan->nama ?? 'Praktik Kerja Lapangan (PKL)';
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

        $rawNama = $layanan->atas_nama ?: ($layanan->user->name ?? 'PEMOHON');
        if (preg_match_all('/(?:\d+[\.\\)]\s*)?([^\r\n,]+)/', $rawNama, $matches)) {
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

        $esc = function ($text) {
            return htmlspecialchars((string) $text, ENT_XML1, 'UTF-8');
        };

        // === BUILD DOCX FROM SCRATCH ===
        $fileName = 'Surat_Rekomendasi_Kesbangpol_' . $layanan->id . '_' . \Illuminate\Support\Str::slug($layanan->atas_nama ?? 'pemohon') . '.docx';
        $tempOutput = storage_path('app/public/permohonan/keluaran/' . $fileName);
        if (!file_exists(dirname($tempOutput))) {
            mkdir(dirname($tempOutput), 0755, true);
        }

        // Embed logo
        $logoPath = public_path('assets/certificate/lambang_kabupaten_bogor.png');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('assets/images/logo_lentera.png');
        }

        // Helper: buat baris tabel 3-kolom (label : value)
        $makeRow3 = function ($label, $value, $bold = false) use ($esc) {
            $bTag = $bold ? '<w:b/>' : '';
            return '<w:tr>'
                . '<w:tc><w:tcPr><w:tcW w:w="1125" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="276" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>' . $esc($label) . '</w:t></w:r></w:p></w:tc>'
                . '<w:tc><w:tcPr><w:tcW w:w="225" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="276" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>:</w:t></w:r></w:p></w:tc>'
                . '<w:tc><w:tcPr><w:tcW w:w="8290" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="276" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>' . $bTag . '<w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>' . $esc($value) . '</w:t></w:r></w:p></w:tc>'
                . '</w:tr>';
        };

        // Helper: buat baris data (label lebih lebar)
        $makeDataRow = function ($label, $value, $bold = false) use ($esc) {
            $bTag = $bold ? '<w:b/>' : '';
            return '<w:tr>'
                . '<w:tc><w:tcPr><w:tcW w:w="2325" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="276" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>' . $esc($label) . '</w:t></w:r></w:p></w:tc>'
                . '<w:tc><w:tcPr><w:tcW w:w="225" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="276" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>:</w:t></w:r></w:p></w:tc>'
                . '<w:tc><w:tcPr><w:tcW w:w="7090" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="276" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>' . $bTag . '<w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>' . $esc($value) . '</w:t></w:r></w:p></w:tc>'
                . '</w:tr>';
        };

        // Helper: baris data Nama (multi-baris)
        $namaDataRow = '<w:tr>'
            . '<w:tc><w:tcPr><w:tcW w:w="2325" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="276" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>Nama</w:t></w:r></w:p></w:tc>'
            . '<w:tc><w:tcPr><w:tcW w:w="225" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="276" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>:</w:t></w:r></w:p></w:tc>'
            . '<w:tc><w:tcPr><w:tcW w:w="7090" w:type="dxa"/></w:tcPr>';
        foreach ($namaList as $idx => $namaItem) {
            $namaDataRow .= '<w:p><w:pPr><w:spacing w:after="20" w:line="276" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:b/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>' . $esc(($idx + 1) . '. ' . strtoupper($namaItem)) . '</w:t></w:r></w:p>';
        }
        $namaDataRow .= '</w:tc></w:tr>';

        // Helper: buat paragraf biasa
        $p = function ($text, $opts = []) use ($esc) {
            $bold = !empty($opts['bold']) ? '<w:b/>' : '';
            $jc = !empty($opts['align']) ? '<w:jc w:val="' . $opts['align'] . '"/>' : '';
            $sz = $opts['size'] ?? '20';
            $indent = !empty($opts['indent']) ? '<w:ind w:left="' . $opts['indent'] . '"/>' : '';
            $spacing = '<w:spacing w:after="' . ($opts['after'] ?? '60') . '" w:line="276" w:lineRule="auto"/>';
            return '<w:p><w:pPr>' . $jc . $indent . $spacing . '</w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>' . $bold . '<w:sz w:val="' . $sz . '"/><w:szCs w:val="' . $sz . '"/></w:rPr><w:t xml:space="preserve">' . $esc($text) . '</w:t></w:r></w:p>';
        };

        // Helper: buat baris daftar nomor
        $listItem = function ($no, $text, $justify = true) use ($esc) {
            $jc = $justify ? '<w:jc w:val="both"/>' : '';
            return '<w:tr>'
                . '<w:tc><w:tcPr><w:tcW w:w="340" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="40" w:line="276" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>' . $esc($no) . '</w:t></w:r></w:p></w:tc>'
                . '<w:tc><w:tcPr><w:tcW w:w="9300" w:type="dxa"/></w:tcPr><w:p><w:pPr>' . $jc . '<w:spacing w:after="40" w:line="276" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t xml:space="preserve">' . $esc($text) . '</w:t></w:r></w:p></w:tc>'
                . '</w:tr>';
        };

        // Invisible table properties (no borders)
        $noBorderTbl = '<w:tblPr><w:tblW w:w="9640" w:type="dxa"/><w:tblLayout w:type="fixed"/><w:tblBorders><w:top w:val="none" w:sz="0" w:space="0" w:color="auto"/><w:left w:val="none" w:sz="0" w:space="0" w:color="auto"/><w:bottom w:val="none" w:sz="0" w:space="0" w:color="auto"/><w:right w:val="none" w:sz="0" w:space="0" w:color="auto"/><w:insideH w:val="none" w:sz="0" w:space="0" w:color="auto"/><w:insideV w:val="none" w:sz="0" w:space="0" w:color="auto"/></w:tblBorders></w:tblPr>';

        // Helper: KOP SURAT (logo + text) dengan ID DrawingML unik & skema OpenXML valid
        $getKopSurat = function ($docPrId, $picId, $imgRelId = 'rId6') use ($noBorderTbl) {
            return '<w:tbl>' . $noBorderTbl
                . '<w:tblGrid><w:gridCol w:w="1134"/><w:gridCol w:w="8506"/></w:tblGrid>'
                . '<w:tr>'
                // Logo cell
                . '<w:tc><w:tcPr><w:tcW w:w="1134" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr>'
                . '<w:p><w:pPr><w:jc w:val="center"/><w:spacing w:after="0"/></w:pPr>'
                . '<w:r><w:rPr><w:noProof/></w:rPr>'
                . '<w:drawing><wp:inline distT="0" distB="0" distL="0" distR="0">'
                . '<wp:extent cx="647700" cy="820420"/>'
                . '<wp:effectExtent l="0" t="0" r="0" b="0"/>'
                . '<wp:docPr id="' . $docPrId . '" name="Picture ' . $docPrId . '"/>'
                . '<wp:cNvGraphicFramePr><a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" noChangeAspect="1"/></wp:cNvGraphicFramePr>'
                . '<a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">'
                . '<a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">'
                . '<pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">'
                . '<pic:nvPicPr>'
                . '<pic:cNvPr id="' . $picId . '" name="Picture ' . $picId . '"/>'
                . '<pic:cNvPicPr/>'
                . '</pic:nvPicPr>'
                . '<pic:blipFill><a:blip r:embed="' . $imgRelId . '"/><a:stretch><a:fillRect/></a:stretch></pic:blipFill>'
                . '<pic:spPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="647700" cy="820420"/></a:xfrm><a:prstGeom prst="rect"><a:avLst/></a:prstGeom></pic:spPr>'
                . '</pic:pic></a:graphicData></a:graphic></wp:inline></w:drawing>'
                . '</w:r></w:p></w:tc>'
                // Text cell
                . '<w:tc><w:tcPr><w:tcW w:w="8506" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr>'
                . '<w:p><w:pPr><w:jc w:val="center"/><w:spacing w:after="0" w:line="276" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:b/><w:sz w:val="27"/><w:szCs w:val="27"/></w:rPr><w:t>PEMERINTAH KABUPATEN BOGOR</w:t></w:r></w:p>'
                . '<w:p><w:pPr><w:jc w:val="center"/><w:spacing w:after="0" w:line="276" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:b/><w:sz w:val="31"/><w:szCs w:val="31"/></w:rPr><w:t>BADAN KESATUAN BANGSA DAN POLITIK</w:t></w:r></w:p>'
                . '<w:p><w:pPr><w:jc w:val="center"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="17"/><w:szCs w:val="17"/></w:rPr><w:t xml:space="preserve">Jl. KSR Dadi Kusmayadi Komplek Pemda Kel. Tengah Cibinong &#x2013; Bogor 16914</w:t></w:r></w:p>'
                . '<w:p><w:pPr><w:jc w:val="center"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="17"/><w:szCs w:val="17"/></w:rPr><w:t xml:space="preserve">Telp/Fax. (021) 8758836, Email : kesbangpolbogor09@gmail.com, Web : bakesbangpol.bogorkab.go.id</w:t></w:r></w:p>'
                . '</w:tc>'
                . '</w:tr></w:tbl>';
        };

        // Garis pemisah kop (double line)
        $kopDivider = '<w:p><w:pPr><w:pBdr><w:bottom w:val="thinThickSmallGap" w:sz="18" w:space="1" w:color="000000"/></w:pBdr><w:spacing w:after="200"/></w:pPr></w:p>';

        // Meta table (Nomor, Sifat, Lampiran, Hal) — identik dengan PDF
        $metaTable = '<w:tbl>' . $noBorderTbl
            . '<w:tblGrid><w:gridCol w:w="1125"/><w:gridCol w:w="225"/><w:gridCol w:w="8290"/></w:tblGrid>'
            . $makeRow3('Nomor', $nomorSurat)
            . $makeRow3('Sifat', 'Penting')
            . $makeRow3('Lampiran', '-')
            . $makeRow3('Hal', $halSurat, true)
            . '</w:tbl>';

        // Tujuan surat (blok terpisah persis seperti PDF)
        $tujuanBlock = $p('Yth. Kepala ' . $dinasNameFormatted, ['after' => '20'])
            . $p('di', ['indent' => '400', 'after' => '20'])
            . $p('Cibinong', ['indent' => '400', 'after' => '200']);

        // Tanggal surat
        $tglPara = $p('Cibinong, ' . $tglSurat, ['align' => 'right', 'after' => '120']);

        // Dasar hukum list
        $dasarHukum = '<w:tbl>' . $noBorderTbl
            . '<w:tblGrid><w:gridCol w:w="340"/><w:gridCol w:w="9300"/></w:tblGrid>'
            . $listItem('1.', 'Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 3 Tahun 2018 tentang Penerbitan Surat Keterangan Penelitian;')
            . $listItem('2.', 'Peraturan Bupati Bogor Nomor 56 Tahun 2020 tentang Kedudukan, Susunan Organisasi, Tugas dan Fungsi, serta Tata Kerja Badan Kesatuan Bangsa dan Politik sebagaimana telah diubah dengan Peraturan Bupati Bogor Nomor 27 Tahun 2022 tentang Kedudukan, Susunan Organisasi, Tugas dan Fungsi serta Tata Kerja Badan Kesatuan Bangsa dan Politik;')
            . $listItem('3.', 'Peraturan Bupati Bogor Nomor 65 Tahun 2023 tentang Sistem Kerja Aparatur Sipil Negara Untuk Penyederhanaan Birokrasi Di Lingkungan Pemerintah Daerah.')
            . '</w:tbl>';

        // Data pemohon table
        $dataPemohon = '<w:tbl>' . $noBorderTbl
            . '<w:tblGrid><w:gridCol w:w="2325"/><w:gridCol w:w="225"/><w:gridCol w:w="7090"/></w:tblGrid>'
            . $namaDataRow
            . $makeDataRow('Alamat', $alamatPemohon)
            . $makeDataRow('Penanggung Jawab', $penanggungJawab)
            . $makeDataRow('Jumlah Peserta', $jumlahPesertaFormatted)
            . $makeDataRow('Tenggang Waktu', $tglMulai . ' s.d ' . $tglSelesai)
            . $makeDataRow('Tempat', $tempatKegiatan)
            . '</w:tbl>';

        // Ketentuan list (halaman 2)
        $ketentuanList = '<w:tbl>' . $noBorderTbl
            . '<w:tblGrid><w:gridCol w:w="340"/><w:gridCol w:w="9300"/></w:tblGrid>'
            . $listItem('1.', 'Mentaati ketentuan peraturan perundang-undangan;')
            . $listItem('2.', 'Ikut menjaga situasi, stabilitas kerukunan, ketentraman dan ketertiban di lokasi ' . $singkatanLayanan . ';')
            . $listItem('3.', 'Berkoordinasi dan mengikuti petunjuk dan arahan dari Pimpinan Instansi tempat pelaksanaan ' . $singkatanLayanan . ';')
            . $listItem('4.', 'Mematuhi aturan dan jam kerja yang berlaku di lokasi ' . $singkatanLayanan . ';')
            . $listItem('5.', 'Tidak diperkenankan melaksanakan kegiatan di luar ketentuan yang ditetapkan di atas.')
            . '</w:tbl>';

        // Tembusan (halaman 2 kiri bawah)
        $tembusanList = '<w:tbl>' . str_replace('w:w="9640"', 'w:w="4800"', $noBorderTbl)
            . '<w:tblGrid><w:gridCol w:w="340"/><w:gridCol w:w="4460"/></w:tblGrid>'
            . '<w:tr><w:tc><w:tcPr><w:tcW w:w="340" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="240" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t>1.</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="4460" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="240" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t>Yth. Bupati Bogor;</w:t></w:r></w:p></w:tc></w:tr>'
            . '<w:tr><w:tc><w:tcPr><w:tcW w:w="340" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="240" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t>2.</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="4460" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="240" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t>Yth. Wakil Bupati Bogor;</w:t></w:r></w:p></w:tc></w:tr>'
            . '<w:tr><w:tc><w:tcPr><w:tcW w:w="340" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="240" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t>3.</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="4460" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="240" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t>Yth. Sekretaris Daerah Kabupaten Bogor;</w:t></w:r></w:p></w:tc></w:tr>'
            . '<w:tr><w:tc><w:tcPr><w:tcW w:w="340" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="240" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t>4.</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="4460" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:spacing w:after="20" w:line="240" w:lineRule="auto"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t>Yth. ' . $esc($pimpinanAsalInstansi) . '.</w:t></w:r></w:p></w:tc></w:tr>'
            . '</w:tbl>';

        // Tembusan + TTE wrapper table (selalu diakhiri w:p agar valid di Word)
        $signatureTable = '<w:tbl>' . $noBorderTbl
            . '<w:tblGrid><w:gridCol w:w="5200"/><w:gridCol w:w="4440"/></w:tblGrid>'
            . '<w:tr>'
            . '<w:tc><w:tcPr><w:tcW w:w="5200" w:type="dxa"/><w:vAlign w:val="top"/></w:tcPr>'
            . $p('Tembusan :', ['bold' => true, 'size' => '19', 'after' => '40'])
            . $tembusanList
            . '<w:p><w:pPr><w:spacing w:after="0"/></w:pPr></w:p>'
            . '</w:tc>'
            . '<w:tc><w:tcPr><w:tcW w:w="4440" w:type="dxa"/><w:vAlign w:val="top"/></w:tcPr>'
            . '<w:p><w:pPr><w:spacing w:after="0"/></w:pPr></w:p>'
            . '</w:tc>'
            . '</w:tr></w:tbl>';

        // Template path & rel IDs
        $templatePath = resource_path('templates/template_kesbangpol.docx');
        $useTemplate = file_exists($templatePath);
        $imgRelId = $useTemplate ? 'rId6' : 'rId7';
        $footerRelId = $useTemplate ? 'rIdFooter1' : 'rId8';

        // === ASSEMBLE DOCUMENT.XML ===
        $documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<w:document xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com:office:word" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml" xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup" xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk" xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml" xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14">'
            . '<w:body>'
            // === HALAMAN 1 ===
            . $getKopSurat(101, 1, $imgRelId)
            . $kopDivider
            . $tglPara
            . $metaTable
            . $tujuanBlock
            . $p('Dasar :', ['after' => '40'])
            . $dasarHukum
            . $p('Memperhatikan :', ['after' => '40'])
            . $p('Surat dari ' . $asalInstansi . ', Nomor : ' . $nomorAsal . ', tanggal ' . $tglAsal . ', Perihal ' . $perihalSurat . '.', ['after' => '80'])
            . $p('Berdasarkan hal tersebut diatas, dengan ini kami memberikan ' . $halSurat . ' kepada :', ['after' => '60'])
            . $dataPemohon
            . $p('Dengan …', ['align' => 'right', 'after' => '80'])
            // Page break
            . '<w:p><w:r><w:br w:type="page"/></w:r></w:p>'
            // === HALAMAN 2 ===
            . $getKopSurat(102, 2, $imgRelId)
            . $kopDivider
            . $p('- 2 -', ['align' => 'center', 'bold' => true, 'after' => '120'])
            . $p('Dengan ketentuan sebagai berikut :', ['after' => '60'])
            . $ketentuanList
            . $p('Demikian disampaikan, atas perhatian dan kerja samanya diucapkan terima kasih.', ['after' => '200'])
            . $signatureTable
            // Footer reference + section properties
            . '<w:sectPr>'
            . '<w:footerReference w:type="default" r:id="' . $footerRelId . '"/>'
            . '<w:pgSz w:w="11906" w:h="16838"/>'
            . '<w:pgMar w:top="680" w:right="1134" w:bottom="1247" w:left="1247" w:header="360" w:footer="720" w:gutter="0"/>'
            . '<w:cols w:space="720"/>'
            . '<w:docGrid w:linePitch="360"/>'
            . '</w:sectPr>'
            . '</w:body></w:document>';

        // Footer XML
        $footerXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<w:ftr xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">'
            . '<w:tbl>'
            . '<w:tblPr><w:tblW w:w="9550" w:type="dxa"/><w:tblLayout w:type="fixed"/><w:tblBorders><w:top w:val="none"/><w:left w:val="none"/><w:bottom w:val="none"/><w:right w:val="none"/><w:insideH w:val="none"/><w:insideV w:val="none"/></w:tblBorders></w:tblPr>'
            . '<w:tblGrid><w:gridCol w:w="600"/><w:gridCol w:w="8950"/></w:tblGrid>'
            . '<w:tr>'
            . '<w:tc><w:tcPr><w:tcW w:w="600" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p><w:pPr><w:spacing w:before="0" w:after="0"/></w:pPr></w:p></w:tc>'
            . '<w:tc><w:tcPr><w:tcW w:w="8950" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr>'
            . '<w:p><w:pPr><w:spacing w:before="0" w:after="0" w:line="200" w:lineRule="auto"/></w:pPr>'
            . '<w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial"/><w:sz w:val="15"/><w:szCs w:val="15"/><w:color w:val="333333"/></w:rPr><w:t>Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan oleh</w:t></w:r>'
            . '<w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial"/><w:b/><w:sz w:val="15"/><w:szCs w:val="15"/><w:color w:val="222222"/></w:rPr><w:br/><w:t>Balai Sertifikasi Elektronik (BSrE) Badan Siber dan Sandi Negara</w:t></w:r>'
            . '</w:p></w:tc>'
            . '</w:tr></w:tbl>'
            . '</w:ftr>';

        if (file_exists($tempOutput)) {
            unlink($tempOutput);
        }

        if ($useTemplate) {
            // METODE UTAMA: Gunakan kontainer template resmi Word agar seluruh part OOXML lengkap 100%
            copy($templatePath, $tempOutput);
            $zip = new \ZipArchive();
            if ($zip->open($tempOutput) !== TRUE) {
                abort(500, 'Gagal membuka file template DOCX.');
            }
            $zip->addFromString('word/document.xml', $documentXml);
            $zip->addFromString('word/footer1.xml', $footerXml);
            if (file_exists($logoPath)) {
                $zip->addFile($logoPath, 'word/media/image1.png');
            }
            $zip->close();
        } else {
            // FALLBACK: Buat dari awal jika template tidak ditemukan
            $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
                . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
                . '<Default Extension="xml" ContentType="application/xml"/>'
                . '<Default Extension="png" ContentType="image/png"/>'
                . '<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>'
                . '<Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>'
                . '<Override PartName="/word/settings.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.settings+xml"/>'
                . '<Override PartName="/word/footer1.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.footer+xml"/>'
                . '</Types>';

            $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>'
                . '</Relationships>';

            $wordRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
                . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/settings" Target="settings.xml"/>'
                . '<Relationship Id="rId7" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image1.png"/>'
                . '<Relationship Id="rId8" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/footer" Target="footer1.xml"/>'
                . '</Relationships>';

            $stylesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">'
                . '<w:docDefaults><w:rPrDefault><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:rPrDefault></w:docDefaults>'
                . '<w:style w:type="paragraph" w:default="1" w:styleId="Normal"><w:name w:val="Normal"/><w:pPr><w:spacing w:after="60" w:line="276" w:lineRule="auto"/></w:pPr></w:style>'
                . '</w:styles>';

            $settingsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<w:settings xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:compat><w:compatSetting w:name="compatibilityMode" w:uri="http://schemas.microsoft.com/office/word" w:val="15"/></w:compat></w:settings>';

            $zip = new \ZipArchive();
            if ($zip->open($tempOutput, \ZipArchive::CREATE) !== TRUE) {
                abort(500, 'Gagal membuat file DOCX.');
            }
            $zip->addFromString('[Content_Types].xml', $contentTypes);
            $zip->addFromString('_rels/.rels', $rels);
            $zip->addFromString('word/_rels/document.xml.rels', $wordRels);
            $zip->addFromString('word/document.xml', $documentXml);
            $zip->addFromString('word/styles.xml', $stylesXml);
            $zip->addFromString('word/settings.xml', $settingsXml);
            $zip->addFromString('word/footer1.xml', $footerXml);
            if (file_exists($logoPath)) {
                $zip->addFile($logoPath, 'word/media/image1.png');
            }
            $zip->close();
        }

        return response()->download($tempOutput, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    /**
     * Generate & Download / View Surat Rekomendasi Kesbangpol (.pdf) dengan QR Code.
     */
    public function downloadPdf($id)
    {
        $layanan = PermohonanLayanan::with(['jenisLayanan', 'user', 'statusMaster'])->findOrFail($id);

        $qrUrl = route('surat.pdf', $id);

        $pdf = Pdf::loadView('pdf.surat_kesbangpol', compact('layanan', 'qrUrl'))
            ->setPaper('a4', 'portrait');

        $fileName = 'Surat_Rekomendasi_Kesbangpol_' . $layanan->id . '_' . \Illuminate\Support\Str::slug($layanan->atas_nama ?? 'pemohon') . '.pdf';

        if (request()->has('download')) {
            return $pdf->download($fileName);
        }

        return $pdf->stream($fileName);
    }
}
