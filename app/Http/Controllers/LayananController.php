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
        $dinasList = \App\Models\Dinas::orderBy('name')->get();
        
        // Render view berdasarkan slug
        return view('pelayanan.landing.forms.' . $slug, compact('jenisLayanan', 'dinasList'));
    }

    public function submit(Request $request)
    {
        $errorMsg = $this->checkActiveLayanan($request->jenis_layanan_slug);
        if ($errorMsg) {
            return redirect()->back()->with('error', $errorMsg);
        }

        $request->validate([
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai'
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.'
        ]);

        try {
            $jenisLayanan = JenisLayanan::where('slug', $request->jenis_layanan_slug)->firstOrFail();
            $statusMenunggu = StatusMaster::where('kode', 'menunggu_verifikasi')->firstOrFail();

            $permohonan = new PermohonanLayanan();
            $permohonan->user_id = Auth::id();
            $permohonan->jenis_layanan_id = $jenisLayanan->id;
            $permohonan->status_master_id = $statusMenunggu->id;

            // Mapping Text Fields
            $permohonan->jenis_permohonan = $request->jenis_permohonan;
            $permohonan->atas_nama = $request->atas_nama;
            $permohonan->no_hp = $request->no_hp;
            $permohonan->asal_instansi = $request->asal_instansi;
            $permohonan->judul_kegiatan = $request->judul_kegiatan;
            $permohonan->dinas_id = $request->dinas_id;
            if ($request->dinas_id) {
                $dinas = \App\Models\Dinas::find($request->dinas_id);
                $permohonan->tempat_kegiatan = $dinas ? $dinas->name : ($request->tempat_kegiatan ?? $request->tempat_pkl ?? $request->tempat_kkn);
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

            return response()->json([
                'status' => 'success',
                'message' => 'Permohonan berhasil dikirim!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai'
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.'
        ]);

        $permohonan = PermohonanLayanan::where('user_id', Auth::id())->findOrFail($id);

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
        
        if ($request->filled('dinas_id')) {
            $permohonan->dinas_id = $request->dinas_id;
            $dinas = \App\Models\Dinas::find($request->dinas_id);
            if ($dinas) {
                $permohonan->tempat_kegiatan = $dinas->name;
            }
        } elseif ($request->filled('tempat_kegiatan')) {
            $permohonan->tempat_kegiatan = $request->tempat_kegiatan;
        }

        if ($request->filled('tanggal_mulai')) $permohonan->tanggal_mulai = $request->tanggal_mulai;
        if ($request->filled('tanggal_selesai')) $permohonan->tanggal_selesai = $request->tanggal_selesai;

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
    }

    public function destroy(Request $request, $id)
    {
        $permohonan = PermohonanLayanan::where('user_id', Auth::id())->findOrFail($id);

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
            'file_daftar_peserta', 'file_id_card', 'file_kartu_pelajar', 'file_surat_keluaran'
        ];

        foreach ($fileFields as $field) {
            if ($permohonan->{$field}) {
                Storage::disk('public')->delete($permohonan->{$field});
            }
        }

        $permohonan->delete();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Permohonan berhasil dihapus!']);
        }

        return redirect()->back()->with('success', 'Permohonan berhasil dihapus!');
    }
}
