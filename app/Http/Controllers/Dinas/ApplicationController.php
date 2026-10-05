<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MagangApplication;
use App\Models\Bidang;
use App\Models\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Support\CurrentDinas;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $dinasId = CurrentDinas::id();
        $dinas = CurrentDinas::model();

        $query = MagangApplication::with(['user', 'bidang', 'rekrutmen.bidang', 'permohonanLayanan.statusMaster'])
            ->where('dinas_id', $dinasId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($uq) use ($search) {
                $uq->where('nama', 'like', "%{$search}%")
                   ->orWhere('name', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%")
                   ->orWhere('asal_instansi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('pelayanan.dinas.applications.index', compact('applications', 'dinas'));
    }

    public function show($id)
    {
        $dinasId = CurrentDinas::id();
        $dinas = CurrentDinas::model();

        $application = MagangApplication::with(['user', 'bidang', 'rekrutmen.bidang', 'permohonanLayanan.statusMaster', 'permohonanLayanan.suratRekomendasi'])
            ->where('dinas_id', $dinasId)
            ->findOrFail($id);

        $bidangs = Bidang::where('dinas_id', $dinasId)->orderBy('name')->get();

        return view('pelayanan.dinas.applications.show', compact('application', 'dinas', 'bidangs'));
    }

    public function verify(Request $request, $id)
    {
        $dinasId = CurrentDinas::id();
        $dinas = CurrentDinas::model();

        if (!$dinasId) {
            abort(403, 'Akses ditolak. Anda tidak terhubung ke instansi manapun.');
        }

        return DB::transaction(function () use ($request, $id, $dinasId, $dinas) {
            $application = MagangApplication::with(['permohonanLayanan.statusMaster', 'permohonanLayanan.suratRekomendasi', 'user'])
                ->where('dinas_id', $dinasId)
                ->lockForUpdate()
                ->findOrFail($id);

            // 1. Validasi transisi status (tidak boleh dari ditolak/expired/selesai)
            if (in_array($application->status, ['ditolak', 'expired', 'selesai'])) {
                if ($request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Status pengajuan sudah ' . strtoupper($application->status) . ' dan tidak dapat diubah lagi.'], 422);
                }
                return redirect()->back()->with('error', 'Status pengajuan sudah ' . strtoupper($application->status) . ' dan tidak dapat diubah lagi.');
            }

            // 2. Aksi terima/tolak hanya jika status permohonan Kesbangpol = disetujui
            $permohonan = $application->permohonanLayanan;
            $kbKode = $permohonan?->statusMaster?->kode;
            if ($kbKode !== 'disetujui' && $kbKode !== 'selesai') {
                $pesan = 'Keputusan tidak dapat diproses: Surat Rekomendasi Kesbangpol belum disetujui (status saat ini: ' . ($permohonan?->statusMaster?->nama ?? 'belum diverifikasi') . ').';
                if ($request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => $pesan], 422);
                }
                return redirect()->back()->with('error', $pesan);
            }

            // 3. Surat belum expired
            $surat = $permohonan?->suratRekomendasi;
            if ($surat && !empty($surat->berlaku_sampai)) {
                if (\Carbon\Carbon::parse($surat->berlaku_sampai)->isPast() && !\Carbon\Carbon::parse($surat->berlaku_sampai)->isToday()) {
                    $pesan = 'Keputusan tidak dapat diproses: Masa berlaku Surat Rekomendasi telah kedaluwarsa pada ' . \Carbon\Carbon::parse($surat->berlaku_sampai)->format('d/m/Y') . '.';
                    if ($request->wantsJson()) {
                        return response()->json(['status' => 'error', 'message' => $pesan], 422);
                    }
                    return redirect()->back()->with('error', $pesan);
                }
            }
            if ($application->expired_at && \Carbon\Carbon::parse($application->expired_at)->isPast()) {
                $pesan = 'Keputusan tidak dapat diproses: Pengajuan magang telah kedaluwarsa.';
                if ($request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => $pesan], 422);
                }
                return redirect()->back()->with('error', $pesan);
            }

            // 4. Validasi request & bidang_id milik dinas login
            $request->validate([
                'status' => 'required|in:diterima,perlu_revisi,ditolak',
                'bidang_id' => [
                    'required_if:status,diterima',
                    'nullable',
                    Rule::exists('bidang', 'id')->where('dinas_id', $dinasId),
                ],
                'file_surat_penerimaan' => 'nullable|file|mimes:pdf|max:5120',
                'catatan_admin' => 'nullable|string',
            ], [
                'bidang_id.exists' => 'Bidang penempatan yang dipilih tidak valid atau bukan milik instansi Anda.',
                'bidang_id.required_if' => 'Bidang penempatan wajib dipilih saat menerima pemohon.',
            ]);

            // 5. Lock Rekrutmen terkait
            if ($application->rekrutmen_id) {
                \App\Models\Rekrutmen::where('id', $application->rekrutmen_id)->lockForUpdate()->first();
            }

            $application->status = $request->status;
            $application->catatan_admin = $request->catatan_admin;

            if ($request->bidang_id) {
                $application->bidang_id = $request->bidang_id;
            }

            if ($request->hasFile('file_surat_penerimaan')) {
                $path = $request->file('file_surat_penerimaan')->store('permohonan/penerimaan_dinas', 'public');
                $application->file_surat_penerimaan = $path;
            } elseif ($request->status === 'diterima') {
                // Auto generate Surat Penerimaan Dinas
                try {
                    $bidangObj = Bidang::find($request->bidang_id);
                    $bidangNama = $bidangObj ? $bidangObj->name : 'Bidang Tujuan';

                    $dinasName = $dinas ? $dinas->name : 'dinas';
                    $pdfFileName = 'Surat_Penerimaan_' . \Illuminate\Support\Str::slug($dinasName) . '_' . $application->id . '.pdf';
                    $relativePdfPath = 'permohonan/penerimaan_dinas/' . $pdfFileName;
                    $outputPdfPath = storage_path('app/public/' . $relativePdfPath);

                    if (!file_exists(dirname($outputPdfPath))) {
                        mkdir(dirname($outputPdfPath), 0755, true);
                    }

                    $pdf = Pdf::loadView('pdf.surat_penerimaan_dinas', compact('application', 'dinas', 'bidangNama'))
                        ->setPaper('a4', 'portrait');
                    $pdf->save($outputPdfPath);

                    $application->file_surat_penerimaan = $relativePdfPath;
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal generate PDF penerimaan dinas: ' . $e->getMessage());
                }
            }

            $application->save();

            $user = $application->user;

            if ($request->status === 'diterima' && $user) {
                // Guard: jangan pernah menurunkan role admin/dinas bila user_id ternyata akun staf
                if (!in_array($user->role, ['admin', 'superadmin', 'dinas', 'kesbangpol', 'bidang'])) {
                    if ($user->role !== 'peserta') {
                        $user->role = 'peserta';
                    }
                }
                $user->dinas_id = $dinasId;
                if ($request->bidang_id) {
                    $user->bidang_id = $request->bidang_id;
                }
                $user->status_akun = 'aktif';
                if ($application->tanggal_mulai) {
                    $user->tanggal_mulai_magang = $application->tanggal_mulai;
                }
                if ($application->tanggal_selesai) {
                    $user->tanggal_selesai_magang = $application->tanggal_selesai;
                }
                $user->save();

                $bidangObj = Bidang::find($request->bidang_id);
                $bidangNama = $bidangObj ? $bidangObj->name : 'Bidang Tujuan';

                $dinasNama = $dinas ? $dinas->name : 'Instansi';

                Notification::create([
                    'user_id' => $user->id,
                    'judul' => 'Permohonan Magang Diterima!',
                    'pesan' => 'Selamat! Permohonan magang Anda di ' . $dinasNama . ' telah DISETUJUI dan ditempatkan pada ' . $bidangNama . '. Akun Anda sudah aktif dan Anda sudah dapat melakukan presensi (absen).',
                    'link' => route('peserta.dashboard'),
                ]);
            } elseif (in_array($request->status, ['ditolak', 'perlu_revisi']) && $user) {
                $dinasNama = $dinas ? $dinas->name : 'Instansi';
                Notification::create([
                    'user_id' => $user->id,
                    'judul' => $request->status === 'ditolak' ? 'Permohonan Magang Ditolak' : 'Permohonan Magang Perlu Revisi',
                    'pesan' => 'Permohonan magang Anda di ' . $dinasNama . ' ' . ($request->status === 'ditolak' ? 'ditolak.' : 'memerlukan revisi: ') . ($request->catatan_admin ?? '-'),
                    'link' => route('landing.profile'),
                ]);
            }

            return redirect()->route('dinas.applications.show', $id)
                ->with('success', 'Keputusan pendaftaran magang berhasil disimpan.');
        });
    }
}
