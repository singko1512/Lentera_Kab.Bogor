<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\MagangApplication;
use App\Models\Absensi;
use App\Models\Jurnal;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        return redirect()->route('absensi.form');
    }

    public function form()
    {
        $user = Auth::user();
        // Hanya sediakan nama sendiri
        $users = User::where('id', $user->id)->get();
        return view('absensi.absensi.form', compact('users'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'status' => 'required|in:hadir,wfh,sakit,izin',
            'laporan' => 'required|string',
            'foto' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
            'foto_kamera' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $magang = MagangApplication::where('user_id', $user->id)
            ->whereIn('status', ['diterima', 'aktif'])
            ->first();

        if (!$magang) {
            return redirect()->back()->withErrors(['Anda tidak memiliki status magang aktif untuk absensi.']);
        }

        $hariIni = Carbon::today()->format('Y-m-d');
        
        // Simpan file
        $path = null;
        if ($request->hasFile('foto_kamera')) {
            $path = $request->file('foto_kamera')->store('absensi/masuk', 'public');
        } elseif ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('absensi/lampiran', 'public');
        }

        // Simpan Absensi (masuk)
        $waktuSekarang = Carbon::now()->format('H:i:s');
        $absensi = Absensi::firstOrCreate(
            ['magang_application_id' => $magang->id, 'tanggal' => $hariIni],
            [
                'waktu_masuk' => $waktuSekarang, 
                'status' => $request->status,
                'foto_masuk' => $path
            ]
        );

        $statusId = \App\Models\Simalam\MasterData::idFor(\App\Models\Simalam\MasterData::ABSENSI_STATUS, $request->status ?: 'hadir');
        \App\Models\Simalam\Absensi::updateOrCreate(
            [
                'user_id' => $user->id,
                'tanggal' => $hariIni,
            ],
            [
                'jam_masuk' => $waktuSekarang,
                'status' => $request->status ?: 'hadir',
                'status_id' => $statusId,
                'foto_masuk' => $path,
                'laporan' => $request->laporan,
            ]
        );

        // Update jika status berbeda atau pulang
        if (!$absensi->wasRecentlyCreated) {
            if (in_array($request->status, ['hadir', 'wfh']) && !$absensi->waktu_pulang) {
                $absensi->update([
                    'waktu_pulang' => $waktuSekarang,
                    'foto_pulang' => $path
                ]);

                \App\Models\Simalam\Absensi::where('user_id', $user->id)
                    ->where('tanggal', $hariIni)
                    ->update([
                        'jam_pulang' => $waktuSekarang,
                        'foto_pulang' => $path,
                    ]);
            }
        }

        // Simpan Jurnal Laporan
        Jurnal::create([
            'magang_application_id' => $magang->id,
            'tanggal' => $hariIni,
            'kegiatan' => $request->laporan,
            'status_verifikasi' => false
        ]);

        return redirect()->route('absensi.rekap')->with('success', 'Absensi dan Laporan berhasil dikirim.');
    }

    public function rekap(Request $request)
    {
        $user = Auth::user();
        $users = User::where('id', $user->id)->get();
        
        $magang = MagangApplication::where('user_id', $user->id)->first();
        
        $absensi = collect();
        $stats = ['hadir' => 0, 'wfh' => 0, 'sakit' => 0, 'izin' => 0];

        if ($magang) {
            $absensi = Absensi::where('magang_application_id', $magang->id)
                ->orderBy('tanggal', 'desc')
                ->get();

            $stats['hadir'] = $absensi->where('status', 'hadir')->count();
            $stats['wfh'] = $absensi->where('status', 'wfh')->count();
            $stats['sakit'] = $absensi->where('status', 'sakit')->count();
            $stats['izin'] = $absensi->where('status', 'izin')->count();
        }

        return view('absensi.absensi.rekap', compact('users', 'absensi', 'stats'));
    }
}
