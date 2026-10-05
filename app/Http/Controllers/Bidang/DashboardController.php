<?php

namespace App\Http\Controllers\Bidang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MagangApplication;
use App\Models\Jurnal;
use App\Support\CurrentDinas;

class DashboardController extends Controller
{
    public function index()
    {
        return redirect()->route('absensi.admin.dashboard');
    }

    /**
     * Dapatkan query MagangApplication yang telah di-scope ke dinas / bidang akun login.
     */
    private function getScopedPesertaQuery()
    {
        $user = Auth::user();
        $query = MagangApplication::query();

        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->isAdmin()) {
            $dinasId = CurrentDinas::id();
            if ($dinasId) {
                $query->where(function ($q) use ($dinasId) {
                    $q->where('dinas_id', $dinasId)
                      ->orWhereHas('rekrutmen', function ($sq) use ($dinasId) {
                          $sq->where('dinas_id', $dinasId);
                      });
                });
            }
            return $query;
        }

        if ($user->isBidang()) {
            if ($user->bidang_id) {
                $query->where('bidang_id', $user->bidang_id);
            }
            if ($user->dinas_id) {
                $query->where('dinas_id', $user->dinas_id);
            }
            return $query;
        }

        if ($user->isDinas()) {
            $dinasId = CurrentDinas::id();
            if ($dinasId) {
                $query->where(function ($q) use ($dinasId) {
                    $q->where('dinas_id', $dinasId)
                      ->orWhereHas('rekrutmen', function ($sq) use ($dinasId) {
                          $sq->where('dinas_id', $dinasId);
                      });
                });
            }
            return $query;
        }

        return $query->whereRaw('1 = 0');
    }

    public function showPeserta($id)
    {
        $peserta = $this->getScopedPesertaQuery()
            ->with(['user', 'absensis', 'jurnals', 'rekrutmen.dinas', 'rekrutmen.bidang', 'permohonanLayanan'])
            ->findOrFail($id);

        return view('pelayanan.bidang.peserta_detail', compact('peserta'));
    }

    public function updateJadwal(Request $request, $id)
    {
        $peserta = $this->getScopedPesertaQuery()->findOrFail($id);

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
        $pesertaIds = $this->getScopedPesertaQuery()->pluck('id');
        $jurnal = Jurnal::whereIn('magang_application_id', $pesertaIds)->findOrFail($id);
        $jurnal->update(['status_verifikasi' => true]);

        return redirect()->back()->with('success', 'Jurnal berhasil diverifikasi.');
    }
}
