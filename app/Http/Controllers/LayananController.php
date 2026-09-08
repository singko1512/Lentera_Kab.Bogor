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

    public function create($slug)
    {
        $jenisLayanan = JenisLayanan::where('slug', $slug)->firstOrFail();
        
        // Render view berdasarkan slug
        return view('pelayanan.landing.forms.' . $slug, compact('jenisLayanan'));
    }

    public function submit(Request $request)
    {
        try {
            $request->validate([
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            ], [
                'tanggal_selesai.after_or_equal' => 'Tanggal Selesai tidak boleh lebih awal dari Tanggal Mulai.'
            ]);

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
            $permohonan->tempat_kegiatan = $request->tempat_kegiatan ?? $request->tempat_pkl ?? $request->tempat_kkn;
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
        $permohonan = PermohonanLayanan::where('user_id', Auth::id())->findOrFail($id);

        // Hanya bisa update jika statusnya perlu_revisi
        if ($permohonan->statusMaster->kode !== 'perlu_revisi') {
            return redirect()->back()->with('error', 'Permohonan tidak dapat direvisi pada status saat ini.');
        }

        $fileFields = [
            'file_ktp', 'file_ktm', 'file_surat_permohonan', 'file_surat_pengantar',
            'file_surat_lokasi', 'file_proposal', 'file_surat_kesbangpol_jabar',
            'file_surat_kemendagri', 'file_surat_rekomendasi_lama', 'file_pendukung',
            'file_daftar_peserta', 'file_id_card', 'file_kartu_pelajar'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($permohonan->{$field}) {
                    Storage::disk('public')->delete($permohonan->{$field});
                }
                $path = $request->file($field)->store('permohonan/' . $field, 'public');
                $permohonan->{$field} = $path;
            }
        }

        // Kembalikan status ke menunggu_verifikasi setelah revisi
        $statusMenunggu = StatusMaster::where('kode', 'menunggu_verifikasi')->firstOrFail();
        $permohonan->status_master_id = $statusMenunggu->id;
        
        $permohonan->save();

        return redirect()->route('layanan.show', $id)->with('success', 'Revisi permohonan berhasil dikirim!');
    }
}
