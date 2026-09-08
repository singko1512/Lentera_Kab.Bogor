<?php

namespace App\Http\Controllers\Kesbangpol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermohonanLayanan;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $date = $request->input('date');

        $totalRiwayat = PermohonanLayanan::whereHas('statusMaster', function($q) {
            $q->whereIn('kode', ['disetujui', 'selesai', 'ditolak', 'dibatalkan']);
        })->count();

        $totalDiterima = PermohonanLayanan::whereHas('statusMaster', function($q) {
            $q->whereIn('kode', ['disetujui', 'selesai']);
        })->count();

        $totalDitolak = PermohonanLayanan::whereHas('statusMaster', function($q) {
            $q->whereIn('kode', ['ditolak', 'dibatalkan']);
        })->count();

        $totalBerlakuHabis = PermohonanLayanan::whereHas('statusMaster', function($q) {
            $q->where('kode', 'selesai');
        })->count();

        $query = PermohonanLayanan::with(['jenisLayanan', 'statusMaster'])
            ->whereHas('statusMaster', function($q) {
                $q->whereIn('kode', ['disetujui', 'selesai', 'ditolak', 'dibatalkan']);
            });

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('atas_nama', 'like', "%{$search}%")
                  ->orWhere('asal_instansi', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'Semua Status') {
            if (in_array(strtolower($status), ['diterima', 'disetujui', 'selesai'])) {
                $query->whereHas('statusMaster', function($q) { $q->whereIn('kode', ['disetujui', 'selesai']); });
            } elseif (in_array(strtolower($status), ['ditolak', 'dibatalkan'])) {
                $query->whereHas('statusMaster', function($q) { $q->whereIn('kode', ['ditolak', 'dibatalkan']); });
            }
        }

        if ($date) {
            $query->whereDate('updated_at', $date);
        }

        $historyPengajuan = $query->orderBy('updated_at', 'desc')->paginate(10)->withQueryString();

        return view('pelayanan.kesbangpol.applications.history', compact(
            'historyPengajuan', 'totalRiwayat', 'totalDiterima', 'totalDitolak', 'totalBerlakuHabis',
            'search', 'status', 'date'
        ));
    }
}
