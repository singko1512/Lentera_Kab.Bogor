<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MagangApplication;
use App\Models\Absensi;
use App\Models\Jurnal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $magang = \App\Models\MagangApplication::where('user_id', $user->id)
            ->whereIn('status', ['diterima', 'aktif'])
            ->first();

        $absensiHariIni = $magang ? \App\Models\Absensi::where('magang_application_id', $magang->id)
            ->where('tanggal', \Carbon\Carbon::today()->format('Y-m-d'))
            ->first() : null;
            
        $jurnals = $magang ? \App\Models\Jurnal::where('magang_application_id', $magang->id)
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get() : collect([]);

        $projects = \App\Models\Simalam\Project::whereHas('members', function($q) use ($user) {
            $q->where('users.id', $user->id);
        })->orWhere('user_id', $user->id)
        ->with(['modules' => function($q) {
            $q->orderBy('urutan', 'asc');
        }, 'timelines', 'tasks'])
        ->get();

        return view('pelayanan.peserta.dashboard', compact('magang', 'absensiHariIni', 'jurnals', 'projects'));
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $magang = \App\Models\MagangApplication::where('user_id', $user->id)
            ->whereIn('status', ['diterima', 'aktif'])
            ->firstOrFail();

        $request->validate([
            'foto' => 'required|image|max:5120', // Maks 5MB
            'lokasi' => 'nullable|string'
        ]);

        $path = $request->file('foto')->store('absensi/masuk', 'public');
        $waktuMasuk = \Carbon\Carbon::now()->format('H:i:s');
        $today = \Carbon\Carbon::today()->format('Y-m-d');

        $projects = \App\Models\Simalam\Project::whereHas('members', function($q) use ($user) {
            $q->where('users.id', $user->id);
        })->orWhere('user_id', $user->id)->get();
        $assignedProject = $projects->first();
        $projectName = $assignedProject ? $assignedProject->nama : 'Website absensi';

        \App\Models\Absensi::updateOrCreate(
            [
                'magang_application_id' => $magang->id,
                'tanggal' => $today
            ],
            [
                'waktu_masuk' => $waktuMasuk,
                'status' => 'hadir',
                'foto_masuk' => $path,
                'lokasi_masuk' => $request->lokasi,
                'catatan' => $projectName,
            ]
        );

        $statusId = \App\Models\Simalam\MasterData::idFor(\App\Models\Simalam\MasterData::ABSENSI_STATUS, 'hadir');
        \App\Models\Simalam\Absensi::updateOrCreate(
            [
                'user_id' => $user->id,
                'tanggal' => $today,
            ],
            [
                'jam_masuk' => $waktuMasuk,
                'status' => 'hadir',
                'status_id' => $statusId,
                'foto_kamera' => $path,
                'foto_masuk' => $path,
                'laporan' => $projectName,
            ]
        );

        return redirect()->back()->with('success', 'Berhasil Check In hari ini.');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $magang = \App\Models\MagangApplication::where('user_id', $user->id)
            ->whereIn('status', ['diterima', 'aktif'])
            ->firstOrFail();

        $request->validate([
            'foto' => 'required|image|max:5120',
            'lokasi' => 'nullable|string'
        ]);

        $today = \Carbon\Carbon::today()->format('Y-m-d');
        $absensi = \App\Models\Absensi::where('magang_application_id', $magang->id)
            ->where('tanggal', $today)
            ->firstOrFail();

        $path = $request->file('foto')->store('absensi/pulang', 'public');
        $waktuPulang = \Carbon\Carbon::now()->format('H:i:s');

        $absensi->update([
            'waktu_pulang' => $waktuPulang,
            'foto_pulang' => $path,
            'lokasi_pulang' => $request->lokasi
        ]);

        \App\Models\Simalam\Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->update([
                'jam_pulang' => $waktuPulang,
                'foto_pulang' => $path,
            ]);

        return redirect()->back()->with('success', 'Berhasil Check Out hari ini.');
    }

    public function storeJurnal(Request $request)
    {
        $user = Auth::user();
        $magang = \App\Models\MagangApplication::where('user_id', $user->id)
            ->whereIn('status', ['diterima', 'aktif'])
            ->firstOrFail();

        $request->validate([
            'kegiatan' => 'required|string|max:1000'
        ]);

        \App\Models\Jurnal::create([
            'magang_application_id' => $magang->id,
            'tanggal' => \Carbon\Carbon::today()->format('Y-m-d'),
            'kegiatan' => $request->kegiatan,
            'status_verifikasi' => false
        ]);

        return redirect()->back()->with('success', 'Jurnal harian berhasil disimpan.');
    }
}
