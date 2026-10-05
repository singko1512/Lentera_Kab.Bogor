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
        if (!$dinas) {
            $dinasId = \App\Support\CurrentDinas::id() ?? \App\Models\Dinas::where('is_kesbangpol', 1)->value('id') ?? \App\Models\Dinas::first()?->id;
            $dinas = \App\Models\Dinas::find($dinasId);
        }
        
        $totalKuota = 0;
        $slotTersedia = 0;
        $pesertaAktif = 0;
        $totalPengajuanInternal = 0;
        $totalPengajuanLayanan = 0;
        $pengajuanInternalTerbarus = collect();
        $pesertaDiterima = collect();
        
        if ($dinas) {
            $rekrutmens = Rekrutmen::where('dinas_id', $dinas->id)->get();
            $totalKuota = $rekrutmens->sum('kuota');
            $slotTersedia = $rekrutmens->sum('slot_tersedia');

            $pesertaAktif = MagangApplication::where(function($q) use ($dinas) {
                $q->where('dinas_id', $dinas->id)
                  ->orWhereHas('rekrutmen', function($sq) use ($dinas) {
                      $sq->where('dinas_id', $dinas->id);
                  });
            })->whereIn('status', ['diterima', 'aktif'])->count();

            $totalPengajuanInternal = MagangApplication::where(function($q) use ($dinas) {
                $q->where('dinas_id', $dinas->id)
                  ->orWhereHas('rekrutmen', function($sq) use ($dinas) {
                      $sq->where('dinas_id', $dinas->id);
                  });
            })->count();

            $totalPengajuanLayanan = $totalPengajuanInternal;

            $pengajuanInternalTerbarus = MagangApplication::with(['user', 'bidang', 'rekrutmen.bidang'])
                ->where(function($q) use ($dinas) {
                    $q->where('dinas_id', $dinas->id)
                      ->orWhereHas('rekrutmen', function($sq) use ($dinas) {
                          $sq->where('dinas_id', $dinas->id);
                      });
                })
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $pesertaDiterima = MagangApplication::with(['user', 'bidang', 'rekrutmen.bidang'])
                ->where(function($q) use ($dinas) {
                    $q->where('dinas_id', $dinas->id)
                      ->orWhereHas('rekrutmen', function($sq) use ($dinas) {
                          $sq->where('dinas_id', $dinas->id);
                      });
                })
                ->whereIn('status', ['diterima', 'aktif'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('pelayanan.kesbangpol.dashboard', compact(
            'totalPermohonan', 'sedangDiproses', 'selesai', 'ditolak', 'pengajuanTerbarus',
            'dinas', 'totalKuota', 'slotTersedia', 'pesertaAktif', 'totalPengajuanInternal', 'totalPengajuanLayanan', 'pengajuanInternalTerbarus', 'pesertaDiterima'
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

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $id) {
            $layanan = PermohonanLayanan::lockForUpdate()->findOrFail($id);
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
                
                $kesbangpolDinas = \App\Models\Dinas::where('is_kesbangpol', true)->first();
                $pejabatNama = $kesbangpolDinas?->nama_kepala ?: config('lentera.pejabat_kesbangpol.nama', 'FERDINANDO SELMI PARDEDE, S.IP, M.AP');
                $pejabatNip = $kesbangpolDinas?->nip_kepala ?: config('lentera.pejabat_kesbangpol.nip', '196805121990031005');
                $pejabatPangkat = config('lentera.pejabat_kesbangpol.pangkat', 'Pembina Tk. I');
                $pejabatJabatan = config('lentera.pejabat_kesbangpol.jabatan', 'KEPALA BADAN KESATUAN BANGSA DAN POLITIK KABUPATEN BOGOR');

                $existingSurat = \App\Models\SuratRekomendasi::where('permohonan_layanan_id', $layanan->id)->first();
                $nomorSurat = $request->filled('nomor_surat')
                    ? $request->nomor_surat
                    : ($existingSurat?->nomor_surat ?: ('000.1.5/' . $layanan->id . '/Bakesbangpol/' . date('Y')));

                // Hitung masa berlaku surat rekomendasi
                // TODO: konfirmasi ke mentor mengenai definisi persis masa berlaku surat apakah dari tanggal_surat atau tanggal mulai kegiatan
                $masaBerlakuHari = config('lentera.masa_berlaku_surat_hari', 30);
                if ($layanan->dinas_id) {
                    $targetDinasModel = \App\Models\Dinas::find($layanan->dinas_id);
                    if ($targetDinasModel && !empty($targetDinasModel->masa_berlaku_hari)) {
                        $masaBerlakuHari = (int) $targetDinasModel->masa_berlaku_hari;
                    }
                }
                $tanggalSurat = now();
                $berlakuSampai = $tanggalSurat->copy()->addDays($masaBerlakuHari)->toDateString();

                $surat = \App\Models\SuratRekomendasi::updateOrCreate(
                    ['permohonan_layanan_id' => $layanan->id],
                    [
                        'nomor_surat' => $nomorSurat,
                        'tanggal_surat' => $tanggalSurat,
                        'berlaku_sampai' => $berlakuSampai,
                        'sifat_surat' => 'Biasa',
                        'lampiran_surat' => '-',
                        'pejabat_nama' => $pejabatNama,
                        'pejabat_nip' => $pejabatNip,
                        'pejabat_pangkat' => $pejabatPangkat,
                        'pejabat_jabatan' => $pejabatJabatan,
                    ]
                );

                if ($request->hasFile('file_surat_keluaran')) {
                    $path = $request->file('file_surat_keluaran')->store('permohonan/keluaran', 'public');
                    $layanan->file_surat_keluaran = $path;
                } elseif (empty($layanan->file_surat_keluaran)) {
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

                                $tglSuratFormatted = \Carbon\Carbon::parse(now())->translatedFormat('d F Y');
                                $tglAsal = \Carbon\Carbon::parse($layanan->created_at ?? now())->translatedFormat('d F Y');
                                $tglMulai = \Carbon\Carbon::parse($layanan->tanggal_mulai ?? now())->translatedFormat('d F Y');
                                $tglSelesai = \Carbon\Carbon::parse($layanan->tanggal_selesai ?? now())->translatedFormat('d F Y');

                                $replacements = [
                                    '${tanggal_surat}' => $tglSuratFormatted,
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

            $pemohonName = $layanan->atas_nama ?: ($layanan->user->name ?? 'Pemohon');
            $namaLayanan = $layanan->jenisLayanan->nama ?? 'Surat Rekomendasi';

            if ($request->status === 'disetujui' || $request->status === 'selesai') {
                \App\Models\Notification::create([
                    'user_id' => $layanan->user_id,
                    'judul' => 'Rekomendasi Kesbangpol Disetujui',
                    'pesan' => 'Permohonan Rekomendasi Kesbangpol Anda (#' . $layanan->id . ') telah disetujui dan diteruskan ke Dinas tujuan Anda.',
                    'link' => route('landing.profile'),
                ]);

                // AUTO-FORWARD / SYNC KE DINAS JIKA DINAS ID ADA
                if ($layanan->dinas_id && (!$layanan->jenisLayanan || $layanan->jenisLayanan->slug !== 'perpanjangan')) {
                    $targetDinas = \App\Models\Dinas::find($layanan->dinas_id);
                    $activeRekrutmen = $targetDinas ? \App\Models\Rekrutmen::firstOrCreate(
                        ['dinas_id' => $targetDinas->id, 'is_active' => true],
                        [
                            'judul' => 'Penerimaan Magang / PKL ' . $targetDinas->name,
                            'kuota' => config('lentera.default_quota', 10),
                        ]
                    ) : null;

                    $masaBerlakuHari = config('lentera.masa_berlaku_surat_hari', 30);
                    if ($targetDinas && !empty($targetDinas->masa_berlaku_hari)) {
                        $masaBerlakuHari = (int) $targetDinas->masa_berlaku_hari;
                    }
                    $berlakuSampai = now()->addDays($masaBerlakuHari)->toDateString();

                    $magangApp = \App\Models\MagangApplication::updateOrCreate(
                        [
                            'user_id' => $layanan->user_id,
                            'permohonan_layanan_id' => $layanan->id,
                        ],
                        [
                            'dinas_id' => $layanan->dinas_id,
                            'rekrutmen_id' => $activeRekrutmen ? $activeRekrutmen->id : null,
                            'status' => 'menunggu', // Tetap menunggu keputusan dinas
                            'pesan_lamaran' => $layanan->judul_kegiatan,
                            'tanggal_mulai' => $layanan->tanggal_mulai,
                            'tanggal_selesai' => $layanan->tanggal_selesai,
                            'berlaku_sampai' => $berlakuSampai,
                            'expired_at' => \Carbon\Carbon::parse($berlakuSampai)->endOfDay(),
                        ]
                    );

                    // Beritahu instansi tujuan bahwa rekomendasi telah disetujui Kesbangpol
                    $dinasUsers = \App\Models\User::where('dinas_id', $layanan->dinas_id)->get();
                    foreach ($dinasUsers as $dUser) {
                        \App\Models\Notification::create([
                            'user_id' => $dUser->id,
                            'judul' => 'Surat Rekomendasi Kesbangpol Disetujui',
                            'pesan' => 'Surat Rekomendasi untuk pemohon ' . $pemohonName . ' (' . $namaLayanan . ') telah DISETUJUI oleh Kesbangpol dan siap diproses lebih lanjut oleh instansi Anda.',
                            'link' => route('dinas.applications.show', $magangApp->id),
                            'dibaca' => false,
                        ]);
                    }
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
            } elseif ($request->status === 'ditolak') {
                // SINKRONISASI KESBANGPOL DITOLAK -> MagangApplication.status = 'ditolak' (kuota kembali)
                $magangApp = \App\Models\MagangApplication::where('permohonan_layanan_id', $layanan->id)->first();
                if ($magangApp) {
                    $magangApp->status = 'ditolak';
                    $magangApp->catatan_admin = 'Ditolak oleh Kesbangpol: ' . ($request->keterangan ?? '-');
                    $magangApp->save();
                }

                \App\Models\Notification::create([
                    'user_id' => $layanan->user_id,
                    'judul' => 'Permohonan Kesbangpol Ditolak',
                    'pesan' => 'Permohonan Rekomendasi Kesbangpol Anda (#' . $layanan->id . ') ditolak: ' . ($request->keterangan ?? '-'),
                    'link' => route('landing.profile'),
                ]);

                if ($layanan->dinas_id) {
                    $dinasUsers = \App\Models\User::where('dinas_id', $layanan->dinas_id)->get();
                    foreach ($dinasUsers as $dUser) {
                        \App\Models\Notification::create([
                            'user_id' => $dUser->id,
                            'judul' => 'Permohonan Rekomendasi Ditolak Kesbangpol',
                            'pesan' => 'Permohonan Rekomendasi atas nama ' . $pemohonName . ' (' . $namaLayanan . ') telah DITOLAK oleh Kesbangpol. Alasan: ' . ($request->keterangan ?? '-'),
                            'link' => $magangApp ? route('dinas.applications.show', $magangApp->id) : route('dinas.applications.index'),
                            'dibaca' => false,
                        ]);
                    }
                }
            } elseif ($request->status === 'perlu_revisi') {
                // SINKRONISASI PERLU REVISI: booking kuota tetap (status tetap 'menunggu'), tandai catatan
                $magangApp = \App\Models\MagangApplication::where('permohonan_layanan_id', $layanan->id)->first();
                if ($magangApp) {
                    $magangApp->catatan_admin = 'Sedang dalam proses revisi dokumen di Kesbangpol: ' . ($request->keterangan ?? '-');
                    $magangApp->save();
                }

                \App\Models\Notification::create([
                    'user_id' => $layanan->user_id,
                    'judul' => 'Permohonan Kesbangpol Perlu Revisi',
                    'pesan' => 'Permohonan Rekomendasi Kesbangpol Anda (#' . $layanan->id . ') memerlukan revisi: ' . ($request->keterangan ?? '-'),
                    'link' => route('landing.profile'),
                ]);

                if ($layanan->dinas_id) {
                    $dinasUsers = \App\Models\User::where('dinas_id', $layanan->dinas_id)->get();
                    foreach ($dinasUsers as $dUser) {
                        \App\Models\Notification::create([
                            'user_id' => $dUser->id,
                            'judul' => 'Permohonan Rekomendasi Perlu Revisi di Kesbangpol',
                            'pesan' => 'Permohonan Rekomendasi atas nama ' . $pemohonName . ' (' . $namaLayanan . ') memerlukan REVISI dokumen di Kesbangpol. Catatan: ' . ($request->keterangan ?? '-'),
                            'link' => $magangApp ? route('dinas.applications.show', $magangApp->id) : route('dinas.applications.index'),
                            'dibaca' => false,
                        ]);
                    }
                }
            }

            $successMsg = 'Status permohonan berhasil diperbarui!';
            if ($request->hasFile('file_surat_keluaran')) {
                $successMsg = 'Surat Rekomendasi Kesbangpol berhasil diunggah/diperbarui!';
            } elseif ($request->status === 'disetujui') {
                $successMsg = 'Layanan berhasil disetujui dan Surat Rekomendasi siap digunakan.';
            }

            return redirect()->route('kesbangpol.layanan.show', $id)
                ->with('success', $successMsg);
        });
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
            $oldData = [
                'name' => $layanan->user->name,
                'nim' => $layanan->user->nim,
                'asal_instansi' => $layanan->user->asal_instansi,
                'alamat' => $layanan->user->alamat,
            ];

            $layanan->user->update([
                'name' => $request->name,
                'nim' => $request->nim,
                'asal_instansi' => $request->institusi,
                'alamat' => $request->alamat,
            ]);

            \Illuminate\Support\Facades\Log::info('Admin Kesbangpol memperbarui data akun pemohon', [
                'admin_id' => Auth::id(),
                'admin_email' => Auth::user()->email ?? null,
                'target_user_id' => $layanan->user->id,
                'permohonan_id' => $layanan->id,
                'old_data' => $oldData,
                'new_data' => [
                    'name' => $request->name,
                    'nim' => $request->nim,
                    'asal_instansi' => $request->institusi,
                    'alamat' => $request->alamat,
                ],
                'ip' => $request->ip(),
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        $layanan->update([
            'atas_nama' => $request->name,
            'asal_instansi' => $request->institusi,
        ]);

        return redirect()->back()->with('success', 'Data pemohon berhasil diperbarui dan aktivitas telah dicatat.');
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
     * Upload Surat Rekomendasi Final (yang sudah diedit nomor + TTE/tanda tangan).
     * Disimpan ke file_surat_final, mencatat pengunggah dan timestamp.
     */
    public function uploadSuratFinal(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || (!$user->isKesbangpol() && !$user->isAdmin())) {
            abort(403, 'Akses ditolak. Hanya Kesbangpol atau Admin yang berhak mengunggah Surat Rekomendasi Final.');
        }

        $maxSize = config('lentera.max_upload_size', 2048);
        $request->validate([
            'file_surat_final' => 'required|file|mimes:pdf|max:' . $maxSize,
            'nomor_surat' => 'nullable|string|max:255',
        ], [
            'file_surat_final.required' => 'File surat rekomendasi final wajib diunggah.',
            'file_surat_final.mimes' => 'File surat final harus berformat PDF.',
            'file_surat_final.max' => 'Ukuran file surat final tidak boleh melebihi ' . ($maxSize / 1024) . ' MB.',
        ]);

        $layanan = PermohonanLayanan::with(['user', 'dinas', 'suratRekomendasi'])->findOrFail($id);

        // Hapus file lama jika ada penggantian
        if ($layanan->file_surat_final && Storage::disk('public')->exists($layanan->file_surat_final)) {
            Storage::disk('public')->delete($layanan->file_surat_final);
        }

        $storedPath = $request->file('file_surat_final')->store('permohonan/surat_final', 'public');

        $layanan->file_surat_final = $storedPath;
        $layanan->surat_final_diunggah_pada = now();
        $layanan->surat_final_diunggah_oleh = $user->id;

        // Jika nomor surat diisi manual, perbarui model SuratRekomendasi
        if ($request->filled('nomor_surat')) {
            \App\Models\SuratRekomendasi::updateOrCreate(
                ['permohonan_layanan_id' => $layanan->id],
                [
                    'nomor_surat' => $request->nomor_surat,
                    'tanggal_surat' => now(),
                ]
            );
        }

        // Jika status masih menunggu verifikasi, otomatis setujui saat surat final diunggah
        if ($layanan->statusMaster && $layanan->statusMaster->kode === 'menunggu_verifikasi') {
            $disetujuiStatus = \App\Models\StatusMaster::where('kode', 'disetujui')->first();
            if ($disetujuiStatus) {
                $layanan->status_master_id = $disetujuiStatus->id;
            }
        }

        $layanan->save();

        $pemohonName = $layanan->atas_nama ?: ($layanan->user->name ?? 'Pemohon');
        $namaLayanan = $layanan->jenisLayanan->nama ?? 'Surat Rekomendasi';

        // 1. Notifikasi untuk mahasiswa
        \App\Models\Notification::create([
            'user_id' => $layanan->user_id,
            'judul' => 'Surat Rekomendasi Terbit',
            'pesan' => 'Surat rekomendasi Anda sudah terbit dan siap diunduh.',
            'link' => route('landing.profile'),
            'dibaca' => false,
        ]);

        // 2. Notifikasi untuk akun dinas tujuan
        if ($layanan->dinas_id) {
            $magangApp = \App\Models\MagangApplication::where('permohonan_layanan_id', $layanan->id)->first();
            $dinasUsers = \App\Models\User::where('dinas_id', $layanan->dinas_id)->get();
            foreach ($dinasUsers as $dUser) {
                \App\Models\Notification::create([
                    'user_id' => $dUser->id,
                    'judul' => 'Surat Rekomendasi Final Tersedia',
                    'pesan' => 'Surat Rekomendasi final untuk pemohon ' . $pemohonName . ' (' . $namaLayanan . ') telah diunggah oleh Kesbangpol.',
                    'link' => $magangApp ? route('dinas.applications.show', $magangApp->id) : route('dinas.applications.index'),
                    'dibaca' => false,
                ]);
            }
        }

        return redirect()->route('kesbangpol.layanan.show', $id)
            ->with('success', 'Surat Rekomendasi Final berhasil diunggah! Notifikasi telah dikirimkan ke pemohon dan instansi tujuan.');
    }

    /**
     * Download / View Surat Rekomendasi Final (.pdf).
     * Terautentikasi dan terotorisasi: hanya pemilik, kesbangpol, dinas tujuan, atau admin.
     * Mahasiswa tidak pernah mengunduh draft; bila belum ada file final, beri 404/menunggu penandatanganan.
     */
    public function downloadPdf($id)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            abort(401);
        }

        $layanan = PermohonanLayanan::with(['jenisLayanan', 'user', 'statusMaster', 'suratRekomendasi', 'dinas'])->findOrFail($id);

        $isOwner = $layanan->user_id === $user->id;
        $isKesbangpol = $user->isKesbangpol();
        $isAdmin = $user->isAdmin();
        $isTargetDinas = $user->isDinas() && $layanan->dinas_id && ($layanan->dinas_id == \App\Support\CurrentDinas::id());

        if (!$isOwner && !$isKesbangpol && !$isAdmin && !$isTargetDinas) {
            abort(403, 'Akses ditolak. Anda tidak berhak mengakses Surat Rekomendasi ini.');
        }

        // Jika file_surat_final tersedia di storage, sajikan file final tersebut
        if (!empty($layanan->file_surat_final) && Storage::disk('public')->exists($layanan->file_surat_final)) {
            $filePath = Storage::disk('public')->path($layanan->file_surat_final);
            $fileName = 'Surat_Rekomendasi_Final_' . $layanan->id . '_' . \Illuminate\Support\Str::slug($layanan->atas_nama ?? 'pemohon') . '.pdf';

            // Jika secara eksplisit meminta preview / inline (misal modal viewer Dinas atau Kesbangpol)
            if (request()->has('preview') || request()->has('inline')) {
                return response()->file($filePath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                ]);
            }

            // Default: langsung unduh otomatis (download attachment) ke perangkat pengguna
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
        }

        // Jika belum ada file_surat_final:
        // Mahasiswa dan Dinas tujuan TIDAK PERNAH mengunduh draft
        if ($isOwner || $isTargetDinas) {
            abort(404, 'Surat rekomendasi final belum diterbitkan atau sedang menunggu penandatanganan.');
        }

        // Akun Kesbangpol atau Admin dapat melihat draf PDF dari template jika diperlukan
        return $this->generateDraftPdf($id);
    }

    /**
     * Generate & Download Draft Surat Rekomendasi Kesbangpol (.pdf).
     * Khusus untuk akun Kesbangpol dan Admin (tombol "Unduh Draft").
     */
    public function generateDraftPdf($id)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user || (!$user->isKesbangpol() && !$user->isAdmin())) {
            abort(403, 'Akses ditolak. Hanya Kesbangpol atau Admin yang berhak mengunduh draf surat.');
        }

        $layanan = PermohonanLayanan::with(['jenisLayanan', 'user', 'statusMaster', 'suratRekomendasi', 'dinas'])->findOrFail($id);

        $surat = $layanan->suratRekomendasi;
        if (!$surat) {
            $surat = \App\Models\SuratRekomendasi::create([
                'permohonan_layanan_id' => $layanan->id,
                'nomor_surat' => $layanan->nomor_surat ?? ('000.1.5/' . $layanan->id . '/Bakesbangpol/' . date('Y')),
                'tanggal_surat' => now(),
                'verification_token' => \Illuminate\Support\Str::random(40),
            ]);
        } elseif (empty($surat->verification_token)) {
            $surat->update(['verification_token' => \Illuminate\Support\Str::random(40)]);
        }

        $qrUrl = route('surat.verifikasi', $surat->verification_token);

        $pdf = Pdf::loadView('pdf.surat_kesbangpol', compact('layanan', 'qrUrl'))
            ->setPaper('a4', 'portrait');

        $fileName = 'Draf_Surat_Rekomendasi_Kesbangpol_' . $layanan->id . '_' . \Illuminate\Support\Str::slug($layanan->atas_nama ?? 'pemohon') . '.pdf';

        if (request()->has('download')) {
            return $pdf->download($fileName);
        }

        return $pdf->stream($fileName);
    }
}
