<?php

namespace App\Http\Controllers\Bidang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MagangApplication;
use App\Models\Absensi;
use App\Models\Jurnal;

class DashboardController extends Controller
{
    public function index()
    {
        return redirect()->route('absensi.admin.dashboard');
    }

    public function showPeserta($id)
    {
        $peserta = MagangApplication::with(['user', 'absensis', 'jurnals', 'rekrutmen.dinas', 'rekrutmen.bidang', 'permohonanLayanan'])
            ->findOrFail($id);

        return view('pelayanan.bidang.peserta_detail', compact('peserta'));
    }

    public function updateJadwal(Request $request, $id)
    {
        $peserta = MagangApplication::findOrFail($id);

        $request->validate([
            'jadwal' => 'required|array',
            'jadwal.senin' => 'required|in:wfo,wfh',
            'jadwal.selasa' => 'required|in:wfo,wfh',
            'jadwal.rabu' => 'required|in:wfo,wfh',
            'jadwal.kamis' => 'required|in:wfo,wfh',
            'jadwal.jumat' => 'required|in:wfo,wfh',
        ]);

        $peserta->update([
            'jadwal_wfh_wfo' => $request->input('jadwal')
        ]);

        return redirect()->back()->with('success', 'Jadwal WFO / WFH peserta ' . ($peserta->user->name ?? '') . ' berhasil diperbarui.');
    }

    public function verifyJurnal(Request $request, $id)
    {
        $jurnal = Jurnal::findOrFail($id);
        $jurnal->update(['status_verifikasi' => true]);

        return redirect()->back()->with('success', 'Jurnal berhasil diverifikasi.');
    }
}
