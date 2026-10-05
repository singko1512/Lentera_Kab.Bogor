<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanLayanan;
use App\Models\JenisLayanan;
use App\Models\StatusMaster;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = PermohonanLayanan::with(['jenisLayanan', 'statusMaster'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pelayanan.layanan.index', compact('layanans'));
    }

    public function show($id)
    {
        $layanan = PermohonanLayanan::with(['jenisLayanan', 'statusMaster'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pelayanan.layanan.show', compact('layanan'));
    }

    private function checkActiveLayanan($slug = null)
    {
        if ($slug === 'perpanjangan') {
            return null; // Perpanjangan selalu boleh diajukan meski kegiatan belum selesai
        }

        $now = now()->toDateString();

        // Cek apakah ada permohonan layanan yang masih aktif/berjalan
        $activeLayanan = \App\Models\PermohonanLayanan::where('user_id', Auth::id())
            ->whereHas('statusMaster', function($q) {
                $q->whereIn('kode', ['menunggu_verifikasi', 'disetujui']);
            })
            ->whereDate('tanggal_selesai', '>=', $now)
            ->first();

        if ($activeLayanan) {
            return 'Anda masih memiliki permohonan atau kegiatan yang aktif (belum berakhir). Anda tidak dapat mendaftar layanan baru kecuali "Perpanjangan Izin Rekomendasi".';
        }

        // Cek juga di magang application
        $activeMagang = \App\Models\MagangApplication::where('user_id', Auth::id())
            ->whereIn('status', ['menunggu', 'diterima', 'aktif'])
            ->whereDate('tanggal_selesai', '>=', $now)
            ->first();

        if ($activeMagang) {
            return 'Anda masih berstatus aktif sebagai peserta/pendaftar di sebuah Instansi. Anda tidak dapat mendaftar layanan baru kecuali "Perpanjangan Izin Rekomendasi".';
        }

        return null;
    }

    public function create($slug)
    {
        $errorMsg = $this->checkActiveLayanan($slug);
        if ($errorMsg) {
            return redirect()->back()->with('error', $errorMsg);
        }

        $jenisLayanan = JenisLayanan::where('slug', $slug)->firstOrFail();
        
        // Hanya tampilkan instansi yang sedang membuka rekrutmen aktif dan masih memiliki sisa kuota (sisa_kuota > 0)
        $dinasList = \App\Models\Dinas::where(function ($q) {
                $q->whereNull('status_magang')
                  ->orWhere('status_magang', 'otomatis')
                  ->orWhere('status_magang', 'tersedia');
            })
            ->whereHas('rekrutmens', function ($q) {
                $q->where('is_active', true);
            })
            ->with(['rekrutmens' => function ($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('name')
            ->get()
            ->filter(function ($dinas) {
                return $dinas->sisa_kuota > 0;
            })
            ->values();
        
        // Render view berdasarkan slug
        return view('pelayanan.landing.forms.' . $slug, compact('jenisLayanan', 'dinasList'));
    }

    public function submit(Request $request)
    {
        $errorMsg = $this->checkActiveLayanan($request->jenis_layanan_slug);
        if ($errorMsg) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $errorMsg,
                ], 422);
            }
            return redirect()->back()->with('error', $errorMsg);
        }

        $validationRules = array_merge([
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ], $this->getFileUploadRules());

        $validationMessages = array_merge([
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ], $this->getFileUploadMessages());

        $request->validate($validationRules, $validationMessages, $this->getFileUploadAttributes());

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $jenisLayanan = JenisLayanan::where('slug', $request->jenis_layanan_slug)->firstOrFail();
            $statusMenunggu = StatusMaster::where('kode', 'menunggu_verifikasi')->firstOrFail();

            // Validasi ketersediaan kuota instansi tujuan jika dinas_id dipilih
            $targetDinas = null;
            $chosenRekrutmen = null;
            if ($request->filled('dinas_id')) {
                $targetDinas = \App\Models\Dinas::find($request->dinas_id);
                if (!$targetDinas) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Instansi tujuan tidak ditemukan.'
                    ], 422);
                }

                if (in_array($targetDinas->status_magang, ['tidak_tersedia', 'penuh'])) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Instansi ' . $targetDinas->name . ' saat ini tidak menerima pengajuan magang/PKL (Status: ' . strtoupper($targetDinas->status_magang) . ').'
                    ], 422);
                }

                // Ambil semua rekrutmen aktif dengan lockForUpdate untuk mencegah race condition
                $activeRekrutmens = \App\Models\Rekrutmen::where('dinas_id', $targetDinas->id)
                    ->where('is_active', true)
                    ->orderBy('created_at', 'asc')
                    ->lockForUpdate()
                    ->get();

                if ($activeRekrutmens->isEmpty()) {
                    // Buat rekrutmen default jika belum ada
                    $defaultRek = \App\Models\Rekrutmen::create([
                        'dinas_id' => $targetDinas->id,
                        'judul' => 'Penerimaan Magang / PKL ' . $targetDinas->name,
                        'kuota' => config('lentera.default_quota', 10),
                        'is_active' => true,
                    ]);
                    $activeRekrutmens = \App\Models\Rekrutmen::where('id', $defaultRek->id)->lockForUpdate()->get();
                }

                // Pilih rekrutmen yang kuotanya masih tersedia (urutan FIFO berdasarkan created_at)
                $statusMemakai = config('lentera.status_memakai_kuota', ['menunggu', 'diterima', 'aktif']);
                foreach ($activeRekrutmens as $rek) {
                    $terisi = \App\Models\MagangApplication::where('rekrutmen_id', $rek->id)
                        ->whereIn('status', $statusMemakai)
                        ->count();

                    if (($rek->kuota - $terisi) > 0) {
                        $chosenRekrutmen = $rek;
                        break;
                    }
                }

                if (!$chosenRekrutmen) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Instansi ' . $targetDinas->name . ' saat ini kuotanya sudah penuh. Silakan pilih instansi yang tersedia.'
                    ], 422);
                }
            }

            $permohonan = new PermohonanLayanan();
            $permohonan->user_id = Auth::id();
            $permohonan->jenis_layanan_id = $jenisLayanan->id;
            $permohonan->status_master_id = $statusMenunggu->id;

            // Mapping Text Fields
            $permohonan->jenis_permohonan = $request->jenis_permohonan;
            $permohonan->atas_nama = $request->atas_nama;
            $permohonan->no_hp = $request->no_hp ?: (Auth::user()->no_hp ?? '-');
            $permohonan->asal_instansi = $request->asal_instansi;
            $permohonan->judul_kegiatan = $request->judul_kegiatan;
            $permohonan->dinas_id = $request->dinas_id;
            if ($targetDinas) {
                $permohonan->tempat_kegiatan = $targetDinas->name;
            } else {
                $permohonan->tempat_kegiatan = $request->tempat_kegiatan ?? $request->tempat_pkl ?? $request->tempat_kkn;
            }
            $permohonan->tanggal_mulai = $request->tanggal_mulai;
            $permohonan->tanggal_selesai = $request->tanggal_selesai;
            $permohonan->keterangan = $request->keterangan;

            // Handle File Uploads (All possible files)
            $fileFields = [
                'file_ktp', 'file_ktm', 'file_surat_permohonan', 'file_surat_pengantar',
                'file_surat_lokasi', 'file_proposal', 'file_surat_kesbangpol_jabar',
                'file_surat_kemendagri', 'file_surat_rekomendasi_lama', 'file_pendukung',
                'file_daftar_peserta', 'file_id_card', 'file_kartu_pelajar'
            ];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $path = $request->file($field)->store('permohonan/' . $field, 'public');
                    $permohonan->{$field} = $path;
                }
            }

            $permohonan->save();

            // OTOMATIS MEMOTONG KUOTA INSTANSI TUJUAN & MENGIRIMKAN NOTIFIKASI KE INSTANSI TUJUAN SEJAD AWAL
            if ($targetDinas && $chosenRekrutmen) {
                $masaPengajuanHari = config('lentera.masa_berlaku_pengajuan_hari', 30);
                $expiredAt = now()->addDays($masaPengajuanHari);

                // Tautkan langsung ke MagangApplication dengan status 'menunggu' sehingga kuota langsung terpotong oleh sistem
                $magangApp = \App\Models\MagangApplication::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'permohonan_layanan_id' => $permohonan->id,
                    ],
                    [
                        'dinas_id' => $targetDinas->id,
                        'rekrutmen_id' => $chosenRekrutmen->id,
                        'status' => 'menunggu',
                        'pesan_lamaran' => $permohonan->judul_kegiatan,
                        'tanggal_mulai' => $permohonan->tanggal_mulai,
                        'tanggal_selesai' => $permohonan->tanggal_selesai,
                        'berlaku_sampai' => $expiredAt->toDateString(),
                        'expired_at' => $expiredAt,
                    ]
                );

                // Kirim notifikasi masuk langsung ke akun instansi tujuan sejak awal
                $dinasUsers = \App\Models\User::where('dinas_id', $targetDinas->id)->get();
                $namaPemohon = $permohonan->atas_nama ?: (Auth::user()->name ?? 'Pemohon');
                $asalInstansi = $permohonan->asal_instansi ?: (Auth::user()->asal_instansi ?? '-');
                $layananNama = $jenisLayanan->nama ?? 'Surat Rekomendasi';

                foreach ($dinasUsers as $dUser) {
                    \App\Models\Notification::create([
                        'user_id' => $dUser->id,
                        'judul' => 'Pengajuan Baru Masuk (' . $layananNama . ')',
                        'pesan' => 'Permohonan baru atas nama ' . $namaPemohon . ' (' . $asalInstansi . ') telah diajukan ke instansi Anda melalui verifikasi Kesbangpol.',
                        'link' => route('dinas.applications.show', $magangApp->id),
                        'dibaca' => false,
                    ]);
                }
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Permohonan berhasil dikirim!'
                ]);
            }

            return redirect()->route('landing.profile')->with('success', 'Permohonan berhasil dikirim!');
        });
    }

    public function update(Request $request, $id)
    {
        $validationRules = array_merge([
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ], $this->getFileUploadRules());

        $validationMessages = array_merge([
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ], $this->getFileUploadMessages());

        $request->validate($validationRules, $validationMessages, $this->getFileUploadAttributes());

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $id) {
            $permohonan = PermohonanLayanan::where('user_id', Auth::id())->lockForUpdate()->findOrFail($id);

            $kodeStatus = optional($permohonan->statusMaster)->kode;
            // Hanya bisa edit jika statusnya perlu_revisi atau menunggu_verifikasi
            if (!in_array($kodeStatus, ['perlu_revisi', 'menunggu_verifikasi'])) {
                if ($request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Permohonan tidak dapat diubah pada status saat ini.'], 403);
                }
                return redirect()->back()->with('error', 'Permohonan tidak dapat diubah pada status saat ini.');
            }

            // Update text fields jika ada dalam request dan tidak bernilai kosong
            if ($request->filled('atas_nama')) $permohonan->atas_nama = $request->atas_nama;
            if ($request->filled('no_hp')) $permohonan->no_hp = $request->no_hp;
            if ($request->filled('asal_instansi')) $permohonan->asal_instansi = $request->asal_instansi;
            if ($request->filled('judul_kegiatan')) $permohonan->judul_kegiatan = $request->judul_kegiatan;
            
            if ($request->filled('dinas_id') && $request->dinas_id != $permohonan->dinas_id) {
                $newDinas = \App\Models\Dinas::find($request->dinas_id);
                if (!$newDinas || in_array($newDinas->status_magang, ['tidak_tersedia', 'penuh'])) {
                    if ($request->wantsJson()) {
                        return response()->json(['status' => 'error', 'message' => 'Instansi tujuan tidak tersedia atau kuotanya penuh.'], 422);
                    }
                    return redirect()->back()->with('error', 'Instansi tujuan tidak tersedia atau kuotanya penuh.');
                }

                // Lock rekrutmen aktif dinas baru
                $activeRekrutmens = \App\Models\Rekrutmen::where('dinas_id', $newDinas->id)
                    ->where('is_active', true)
                    ->orderBy('created_at', 'asc')
                    ->lockForUpdate()
                    ->get();

                if ($activeRekrutmens->isEmpty()) {
                    $defaultRek = \App\Models\Rekrutmen::create([
                        'dinas_id' => $newDinas->id,
                        'judul' => 'Penerimaan Magang / PKL ' . $newDinas->name,
                        'kuota' => config('lentera.default_quota', 10),
                        'is_active' => true,
                    ]);
                    $activeRekrutmens = \App\Models\Rekrutmen::where('id', $defaultRek->id)->lockForUpdate()->get();
                }

                $statusMemakai = config('lentera.status_memakai_kuota', ['menunggu', 'diterima', 'aktif']);
                $chosenRekrutmen = null;
                foreach ($activeRekrutmens as $rek) {
                    $terisi = \App\Models\MagangApplication::where('rekrutmen_id', $rek->id)
                        ->whereIn('status', $statusMemakai)
                        ->count();

                    if (($rek->kuota - $terisi) > 0) {
                        $chosenRekrutmen = $rek;
                        break;
                    }
                }

                if (!$chosenRekrutmen) {
                    if ($request->wantsJson()) {
                        return response()->json(['status' => 'error', 'message' => 'Kuota penerimaan di ' . $newDinas->name . ' saat ini sudah penuh.'], 422);
                    }
                    return redirect()->back()->with('error', 'Kuota penerimaan di ' . $newDinas->name . ' saat ini sudah penuh.');
                }

                $permohonan->dinas_id = $newDinas->id;
                $permohonan->tempat_kegiatan = $newDinas->name;

                // Update pada MagangApplication: lepaskan booking lama dan buat booking baru pada satu transaksi
                $masaPengajuanHari = config('lentera.masa_berlaku_pengajuan_hari', 30);
                $expiredAt = now()->addDays($masaPengajuanHari);

                $magangApp = \App\Models\MagangApplication::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'permohonan_layanan_id' => $permohonan->id,
                    ],
                    [
                        'dinas_id' => $newDinas->id,
                        'rekrutmen_id' => $chosenRekrutmen->id,
                        'status' => 'menunggu',
                        'tanggal_mulai' => $permohonan->tanggal_mulai,
                        'tanggal_selesai' => $permohonan->tanggal_selesai,
                        'berlaku_sampai' => $expiredAt->toDateString(),
                        'expired_at' => $expiredAt,
                    ]
                );

                // Beritahu instansi tujuan baru
                $dinasUsers = \App\Models\User::where('dinas_id', $newDinas->id)->get();
                foreach ($dinasUsers as $dUser) {
                    \App\Models\Notification::create([
                        'user_id' => $dUser->id,
                        'judul' => 'Pengajuan Baru Dialihkan ke Instansi Anda',
                        'pesan' => 'Permohonan atas nama ' . ($permohonan->atas_nama ?: Auth::user()->name) . ' telah dialihkan ke instansi Anda.',
                        'link' => route('dinas.applications.show', $magangApp->id),
                        'dibaca' => false,
                    ]);
                }
            } elseif ($request->filled('tempat_kegiatan')) {
                $permohonan->tempat_kegiatan = $request->tempat_kegiatan;
            }

            if ($request->filled('tanggal_mulai')) $permohonan->tanggal_mulai = $request->tanggal_mulai;
            if ($request->filled('tanggal_selesai')) $permohonan->tanggal_selesai = $request->tanggal_selesai;

            // Sinkronisasi tanggal pada MagangApplication jika ada
            \App\Models\MagangApplication::where('permohonan_layanan_id', $permohonan->id)->update([
                'tanggal_mulai' => $permohonan->tanggal_mulai,
                'tanggal_selesai' => $permohonan->tanggal_selesai,
            ]);

            $fileFields = [
                'file_ktp', 'file_ktm', 'file_surat_permohonan', 'file_surat_pengantar',
                'file_surat_lokasi', 'file_proposal', 'file_surat_kesbangpol_jabar',
                'file_surat_kemendagri', 'file_surat_rekomendasi_lama', 'file_pendukung',
                'file_daftar_peserta', 'file_id_card', 'file_kartu_pelajar'
            ];

            $updatedFiles = [];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    // Delete old file if exists
                    if ($permohonan->{$field}) {
                        Storage::disk('public')->delete($permohonan->{$field});
                    }
                    $path = $request->file($field)->store('permohonan/' . $field, 'public');
                    $permohonan->{$field} = $path;
                    $updatedFiles[] = $field;
                }
            }

            // Catat metadata revisi agar Admin Kesbangpol langsung mengetahui update ini
            $permohonan->status_revisi = 'sudah_direvisi';
            $permohonan->tanggal_revisi = now();
            if (!empty($updatedFiles)) {
                $permohonan->dokumen_direvisi = $updatedFiles;
            }
            if ($request->filled('catatan_pemohon')) {
                $permohonan->catatan_pemohon = $request->catatan_pemohon;
            }

            // Kembalikan status ke menunggu_verifikasi setelah revisi/edit
            $statusMenunggu = StatusMaster::where('kode', 'menunggu_verifikasi')->first();
            if ($statusMenunggu) {
                $permohonan->status_master_id = $statusMenunggu->id;
            }
            
            $permohonan->save();

            // Kirim notifikasi ke admin/kesbangpol
            try {
                $adminUsers = \App\Models\User::whereIn('role', ['admin', 'superadmin', 'kesbangpol'])->get();
                foreach ($adminUsers as $admin) {
                    \App\Models\Notification::create([
                        'user_id' => $admin->id,
                        'judul' => 'Revisi Dokumen Masuk',
                        'pesan' => 'Pemohon ' . ($permohonan->atas_nama ?? 'Peserta') . ' telah memperbarui dokumen revisi untuk permohonan #' . $permohonan->id . '.',
                        'link' => route('kesbangpol.layanan.show', $permohonan->id),
                        'dibaca' => false,
                    ]);
                }
            } catch (\Throwable $e) {
                // Non-blocking notification fail
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Permohonan berhasil diperbarui!'
                ]);
            }

            return redirect()->back()->with('success', 'Pembaruan permohonan berhasil dikirim!');
        });
    }

    public function destroy(Request $request, $id)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $id) {
            $permohonan = PermohonanLayanan::where('user_id', Auth::id())->lockForUpdate()->findOrFail($id);

            $kodeStatus = optional($permohonan->statusMaster)->kode;
            // Hapus cuma bisa kalau status pengajuannya baru terkirim (menunggu_verifikasi)
            if ($kodeStatus !== 'menunggu_verifikasi') {
                if ($request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Permohonan hanya dapat dihapus saat berstatus baru terkirim / menunggu verifikasi.'], 403);
                }
                return redirect()->back()->with('error', 'Permohonan hanya dapat dihapus saat berstatus baru terkirim / menunggu verifikasi.');
            }

            // Hapus file-file terlampir
            $fileFields = [
                'file_ktp', 'file_ktm', 'file_surat_permohonan', 'file_surat_pengantar',
                'file_surat_lokasi', 'file_proposal', 'file_surat_kesbangpol_jabar',
                'file_surat_kemendagri', 'file_surat_rekomendasi_lama', 'file_pendukung',
                'file_daftar_peserta', 'file_id_card', 'file_kartu_pelajar', 'file_surat_keluaran', 'file_surat_final'
            ];

            foreach ($fileFields as $field) {
                if ($permohonan->{$field}) {
                    Storage::disk('public')->delete($permohonan->{$field});
                }
            }

            // Hapus juga MagangApplication terkait agar kuota instansi tujuan kembali
            \App\Models\MagangApplication::where('permohonan_layanan_id', $permohonan->id)->delete();

            $permohonan->delete();

            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Permohonan berhasil dihapus!']);
            }

            return redirect()->back()->with('success', 'Permohonan berhasil dihapus!');
        });
    }

    /**
     * Preview / download dokumen permohonan dengan otorisasi ketat.
     */
    public function previewBerkas($permohonanId, $field)
    {
        $allowedFields = [
            'file_ktp', 'file_ktm', 'file_surat_permohonan', 'file_surat_pengantar',
            'file_surat_lokasi', 'file_proposal', 'file_surat_kesbangpol_jabar',
            'file_surat_kemendagri', 'file_surat_rekomendasi_lama', 'file_pendukung',
            'file_daftar_peserta', 'file_id_card', 'file_kartu_pelajar', 'file_surat_keluaran',
            'file_surat_penerimaan'
        ];

        if (!in_array($field, $allowedFields)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        $permohonan = PermohonanLayanan::findOrFail($permohonanId);
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $isOwner = ($permohonan->user_id === $user->id);
        $isAdmin = $user->isAdmin();
        $isKesbangpol = $user->isKesbangpol();
        $currentDinasId = \App\Support\CurrentDinas::id();
        $isDestinationDinas = ($currentDinasId && $permohonan->dinas_id && (int)$permohonan->dinas_id === (int)$currentDinasId);

        if (!$isOwner && !$isAdmin && !$isKesbangpol && !$isDestinationDinas) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat berkas ini.');
        }

        if ($field === 'file_surat_penerimaan') {
            $magangApp = \App\Models\MagangApplication::where('permohonan_layanan_id', $permohonan->id)->latest()->first();
            $filePath = $magangApp?->file_surat_penerimaan;
        } else {
            $filePath = $permohonan->{$field};
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'Berkas tidak ditemukan di server.');
        }

        $fullPath = Storage::disk('public')->path($filePath);
        return response()->file($fullPath);
    }

    private function getFileUploadRules(): array
    {
        $maxSize = config('lentera.max_upload_size', 2048);
        return [
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:' . $maxSize,
            'file_ktm' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:' . $maxSize,
            'file_id_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:' . $maxSize,
            'file_kartu_pelajar' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:' . $maxSize,
            'file_surat_permohonan' => 'nullable|file|mimes:pdf|max:' . $maxSize,
            'file_surat_pengantar' => 'nullable|file|mimes:pdf|max:' . $maxSize,
            'file_surat_lokasi' => 'nullable|file|mimes:pdf|max:' . $maxSize,
            'file_proposal' => 'nullable|file|mimes:pdf|max:' . $maxSize,
            'file_surat_kesbangpol_jabar' => 'nullable|file|mimes:pdf|max:' . $maxSize,
            'file_surat_kemendagri' => 'nullable|file|mimes:pdf|max:' . $maxSize,
            'file_surat_rekomendasi_lama' => 'nullable|file|mimes:pdf|max:' . $maxSize,
            'file_pendukung' => 'nullable|file|mimes:pdf|max:' . $maxSize,
            'file_daftar_peserta' => 'nullable|file|mimes:pdf|max:' . $maxSize,
        ];
    }

    private function getFileUploadMessages(): array
    {
        return [
            'file' => ':attribute harus berupa berkas yang valid.',
            'mimes' => ':attribute harus berupa dokumen dengan format: :values.',
            'max' => ':attribute tidak boleh berukuran lebih dari :max kilobita (KB).',
        ];
    }

    private function getFileUploadAttributes(): array
    {
        return [
            'file_ktp' => 'File KTP',
            'file_ktm' => 'File KTM',
            'file_id_card' => 'File ID Card',
            'file_kartu_pelajar' => 'File Kartu Pelajar',
            'file_surat_permohonan' => 'Surat Permohonan',
            'file_surat_pengantar' => 'Surat Pengantar',
            'file_surat_lokasi' => 'Surat Lokasi',
            'file_proposal' => 'Proposal Kegiatan',
            'file_surat_kesbangpol_jabar' => 'Surat Rekomendasi Kesbangpol Provinsi Jabar',
            'file_surat_kemendagri' => 'Surat Rekomendasi Kemendagri',
            'file_surat_rekomendasi_lama' => 'Surat Rekomendasi Lama',
            'file_pendukung' => 'Dokumen Pendukung',
            'file_daftar_peserta' => 'Daftar Anggota/Peserta',
        ];
    }
}
