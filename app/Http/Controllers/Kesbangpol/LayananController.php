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

                    // 2. Generate juga draf DOCX cadangan menggunakan template resmi template_surat
                    try {
                        $docxFileName = 'Surat_Rekomendasi_Kesbangpol_' . $layanan->id . '_' . \Illuminate\Support\Str::slug($layanan->atas_nama ?? 'pemohon') . '.docx';
                        $relativeDocxPath = 'permohonan/keluaran/' . $docxFileName;
                        $outputDocxPath = storage_path('app/public/' . $relativeDocxPath);

                        \App\Services\SuratTemplateService::generateDocx($layanan, $outputDocxPath);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Gagal generate draf docx template_surat: ' . $e->getMessage());
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
                    $dinasUsers = \App\Models\User::where('dinas_id', $layanan->dinas_id)->where('role', 'dinas')->get();
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
                    $dinasUsers = \App\Models\User::where('dinas_id', $layanan->dinas_id)->where('role', 'dinas')->get();
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
                    $dinasUsers = \App\Models\User::where('dinas_id', $layanan->dinas_id)->where('role', 'dinas')->get();
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
     * Menggunakan template resmi di folder template_surat/ sesuai dengan jenis layanan permohonan.
     */
    public function generateDocx($id)
    {
        $layanan = PermohonanLayanan::with(['jenisLayanan', 'user', 'statusMaster', 'suratRekomendasi'])->findOrFail($id);

        $fileName = 'Surat_Rekomendasi_Kesbangpol_' . $layanan->id . '_' . \Illuminate\Support\Str::slug($layanan->atas_nama ?: ($layanan->user->name ?? 'pemohon')) . '.docx';
        $tempOutput = storage_path('app/public/permohonan/keluaran/' . $fileName);

        \App\Services\SuratTemplateService::generateDocx($layanan, $tempOutput);

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
            $dinasUsers = \App\Models\User::where('dinas_id', $layanan->dinas_id)->where('role', 'dinas')->get();
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
