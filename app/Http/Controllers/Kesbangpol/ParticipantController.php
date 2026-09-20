<?php

namespace App\Http\Controllers\Kesbangpol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index()
    {
        $participants = \App\Models\User::with(['magangApplications' => function($q) {
                $q->latest();
            }, 'magangApplications.rekrutmen.dinas'])
            ->where('role', 'peserta')
            ->paginate(10);

        $semuaPeserta = \App\Models\User::where('role', 'peserta')->get();
        $aktif = $semuaPeserta->where('status_akun', 'aktif')->count();
        $dibatasi = $semuaPeserta->where('status_akun', 'dibatasi')->count();
        $diblokir = $semuaPeserta->where('status_akun', 'diblokir')->count();
        $perluTindakan = 0; // Customize if you have specific criteria for this

        return view('pelayanan.kesbangpol.participants.index', compact('aktif', 'perluTindakan', 'dibatasi', 'diblokir', 'participants'));
    }

    public function updateStatusAccount(Request $request, $id)
    {
        $request->validate([
            'status_akun' => 'required|in:aktif,dibatasi,diblokir'
        ]);

        $user = \App\Models\User::findOrFail($id);
        
        // Ensure user is actually a peserta to prevent altering admins
        if ($user->role !== 'peserta') {
            return redirect()->back()->with('error', 'Hanya dapat mengubah status akun peserta.');
        }

        $user->status_akun = $request->status_akun;
        $user->save();

        return redirect()->back()->with('success', 'Status akun peserta berhasil diperbarui menjadi ' . ucfirst($request->status_akun) . '.');
    }

    public function placement()
    {
        $pesertaMenunggu = \App\Models\MagangApplication::with(['user', 'rekrutmen.dinas', 'rekrutmen.bidang', 'permohonanLayanan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $menungguPenempatan = \App\Models\MagangApplication::where('status', 'menunggu')->count();
        $aktif = \App\Models\MagangApplication::where('status', 'diterima')->count();
        $totalPeserta = \App\Models\MagangApplication::count();
        $dinasOptions = \App\Models\Dinas::all();

        return view('pelayanan.kesbangpol.participants.placement', compact('menungguPenempatan', 'aktif', 'totalPeserta', 'dinasOptions', 'pesertaMenunggu'));
    }

    public function extend()
    {
        $menungguPersetujuan = \App\Models\PermohonanLayanan::whereHas('statusMaster', function($q) { $q->where('kode', 'menunggu_verifikasi'); })->count();
        $disetujui = \App\Models\PermohonanLayanan::whereHas('statusMaster', function($q) { $q->whereIn('kode', ['disetujui', 'selesai']); })->count();
        $ditolak = \App\Models\PermohonanLayanan::whereHas('statusMaster', function($q) { $q->where('kode', 'ditolak'); })->count();
        $selesaiPeriode = \App\Models\PermohonanLayanan::whereNotNull('tanggal_selesai')->where('tanggal_selesai', '<', now())->count();

        $pengajuanPerpanjangan = \App\Models\PermohonanLayanan::with(['jenisLayanan', 'statusMaster'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('pelayanan.kesbangpol.participants.extend', compact('menungguPersetujuan', 'disetujui', 'ditolak', 'selesaiPeriode', 'pengajuanPerpanjangan'));
    }

    public function show($id)
    {
        $user = \App\Models\User::with(['magangApplications' => function($q) {
            $q->latest();
        }, 'magangApplications.rekrutmen.dinas', 'magangApplications.rekrutmen.bidang', 'magangApplications.permohonanLayanan'])->findOrFail($id);
        
        $application = $user->magangApplications->first();
        return view('pelayanan.kesbangpol.participants.detail', compact('user', 'application'));
    }

    public function showPlacement($id)
    {
        $application = \App\Models\MagangApplication::with(['user', 'rekrutmen.dinas', 'rekrutmen.bidang'])->findOrFail($id);
        return view('pelayanan.kesbangpol.participants.placement_show', compact('application'));
    }

    public function showExtend($id)
    {
        $permohonan = \App\Models\PermohonanLayanan::with(['jenisLayanan', 'statusMaster', 'user'])->findOrFail($id);
        return view('pelayanan.kesbangpol.participants.extend_show', compact('permohonan'));
    }
}
