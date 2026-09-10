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
            ->whereDoesntHave('magangApplications', function($q) {
                $q->whereIn('status', ['diterima', 'aktif', 'selesai']);
            })
            ->paginate(10);

        $aktif = $participants->total();
        $perluTindakan = 0;
        $dibatasi = 0;
        $diblokir = 0;

        return view('pelayanan.kesbangpol.participants.index', compact('aktif', 'perluTindakan', 'dibatasi', 'diblokir', 'participants'));
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
        $user = \App\Models\User::findOrFail($id);
        return view('pelayanan.kesbangpol.participants.detail', compact('user'));
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
