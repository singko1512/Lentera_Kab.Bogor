<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MagangApplication;
use App\Models\Bidang;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $dinas = Auth::user()->dinas;
        $dinasId = $dinas->id;

        $applications = MagangApplication::with(['user', 'bidang', 'rekrutmen.bidang', 'permohonanLayanan'])
            ->where(function($q) use ($dinasId) {
                $q->where('dinas_id', $dinasId)
                  ->orWhereHas('rekrutmen', function($sq) use ($dinasId) {
                      $sq->where('dinas_id', $dinasId);
                  });
            })
            ->orderBy('created_at', 'desc')->paginate(15);

        return view('pelayanan.dinas.applications.index', compact('applications', 'dinas'));
    }

    public function show($id)
    {
        $dinas = Auth::user()->dinas;
        $dinasId = $dinas->id;

        $application = MagangApplication::with(['user', 'bidang', 'rekrutmen.bidang', 'permohonanLayanan'])
            ->where(function($q) use ($dinasId) {
                $q->where('dinas_id', $dinasId)
                  ->orWhereHas('rekrutmen', function($sq) use ($dinasId) {
                      $sq->where('dinas_id', $dinasId);
                  });
            })->findOrFail($id);

        $bidangs = Bidang::where('dinas_id', $dinasId)->orderBy('name')->get();

        return view('pelayanan.dinas.applications.show', compact('application', 'dinas', 'bidangs'));
    }

    public function verify(Request $request, $id)
    {
        $dinas = Auth::user()->dinas;
        $dinasId = $dinas->id;

        $application = MagangApplication::where(function($q) use ($dinasId) {
            $q->where('dinas_id', $dinasId)
              ->orWhereHas('rekrutmen', function($sq) use ($dinasId) {
                  $sq->where('dinas_id', $dinasId);
              });
        })->findOrFail($id);

        $request->validate([
            'status' => 'required|in:diterima,perlu_revisi,ditolak',
            'bidang_id' => 'required_if:status,diterima|nullable|exists:bidang,id',
            'file_surat_penerimaan' => 'nullable|file|mimes:pdf|max:5120',
            'catatan_admin' => 'nullable|string',
        ]);

        $application->status = $request->status;
        $application->catatan_admin = $request->catatan_admin;

        if ($request->bidang_id) {
            $application->bidang_id = $request->bidang_id;
        }

        if ($request->hasFile('file_surat_penerimaan')) {
            $path = $request->file('file_surat_penerimaan')->store('permohonan/penerimaan_dinas', 'public');
            $application->file_surat_penerimaan = $path;
        }

        $application->save();

        $user = $application->user;

        if ($request->status === 'diterima' && $user) {
            $user->role = 'peserta';
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

            Notification::create([
                'user_id' => $user->id,
                'judul' => 'Permohonan Magang Diterima!',
                'pesan' => 'Selamat! Permohonan magang Anda di ' . $dinas->name . ' telah DISETUJUI dan ditempatkan pada ' . $bidangNama . '. Akun Anda sudah aktif dan Anda sudah dapat melakukan presensi (absen).',
                'link' => route('peserta.dashboard'),
            ]);
        } elseif (in_array($request->status, ['ditolak', 'perlu_revisi']) && $user) {
            Notification::create([
                'user_id' => $user->id,
                'judul' => $request->status === 'ditolak' ? 'Permohonan Magang Ditolak' : 'Permohonan Magang Perlu Revisi',
                'pesan' => 'Permohonan magang Anda di ' . $dinas->name . ' ' . ($request->status === 'ditolak' ? 'ditolak.' : 'memerlukan revisi: ') . ($request->catatan_admin ?? '-'),
                'link' => route('landing.profile'),
            ]);
        }

        return redirect()->route('dinas.applications.show', $id)
            ->with('success', 'Keputusan pendaftaran magang berhasil disimpan.');
    }
}
