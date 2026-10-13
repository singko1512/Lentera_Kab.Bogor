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

        $todayStr = \Carbon\Carbon::today()->format('Y-m-d');
        $absensiHariIni = $magang ? \App\Models\Absensi::where('magang_application_id', $magang->id)
            ->where('tanggal', $todayStr)
            ->first() : null;

        // Fallback to Simalam Absensi so state is always synced between dashboard and /absensi
        $simalamAbsensiHariIni = \App\Models\Simalam\Absensi::where('user_id', $user->id)
            ->where('tanggal', $todayStr)
            ->first();

        if (!$absensiHariIni && $simalamAbsensiHariIni) {
            $absensiHariIni = (object) [
                'status' => $simalamAbsensiHariIni->status,
                'waktu_masuk' => $simalamAbsensiHariIni->jam_masuk,
                'waktu_pulang' => $simalamAbsensiHariIni->jam_pulang,
                'foto_masuk' => $simalamAbsensiHariIni->foto_masuk ?: $simalamAbsensiHariIni->foto_kamera,
                'foto_pulang' => $simalamAbsensiHariIni->foto_pulang,
                'lokasi_masuk' => $simalamAbsensiHariIni->lokasi_masuk_latitude ? ($simalamAbsensiHariIni->lokasi_masuk_latitude . ', ' . $simalamAbsensiHariIni->lokasi_masuk_longitude) : null,
                'lokasi_pulang' => $simalamAbsensiHariIni->lokasi_pulang_latitude ? ($simalamAbsensiHariIni->lokasi_pulang_latitude . ', ' . $simalamAbsensiHariIni->lokasi_pulang_longitude) : null,
            ];
        }

        // Compute monthly stats & history like /absensi view
        $startDate = \Carbon\Carbon::now()->startOfMonth();
        $endDate = \Carbon\Carbon::now()->endOfMonth();
        $riwayatAbsensi = \App\Models\Simalam\Absensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalWorkdays = 0;
        $tempDate = $startDate->copy();
        $maxCalcDate = $endDate->gt(\Carbon\Carbon::today()) ? \Carbon\Carbon::today() : $endDate;
        while ($tempDate->lte($maxCalcDate)) {
            if (!$tempDate->isWeekend()) {
                $totalWorkdays++;
            }
            $tempDate->addDay();
        }
        if ($totalWorkdays === 0) {
            $totalWorkdays = 1;
        }

        $stats = [
            'hadir' => $riwayatAbsensi->where('status', 'hadir')->count(),
            'wfh' => $riwayatAbsensi->where('status', 'wfh')->count(),
            'sakit' => $riwayatAbsensi->where('status', 'sakit')->count(),
            'izin' => $riwayatAbsensi->where('status', 'izin')->count(),
            'total_hari_kerja' => $totalWorkdays,
            'persentase' => round((($riwayatAbsensi->whereIn('status', ['hadir', 'wfh'])->count()) / $totalWorkdays) * 100, 1),
        ];
            
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

        $userApplications = \App\Models\PermohonanLayanan::with(['jenisLayanan', 'statusMaster', 'dinas', 'magangApplication.bidang', 'magangApplication.rekrutmen.bidang'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pelayanan.peserta.dashboard', compact('magang', 'absensiHariIni', 'jurnals', 'projects', 'userApplications', 'stats', 'riwayatAbsensi'));
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $magang = \App\Models\MagangApplication::where('user_id', $user->id)
            ->whereIn('status', ['diterima', 'aktif'])
            ->firstOrFail();

        $status = $request->input('status', 'hadir');
        if (!in_array($status, ['hadir', 'wfh', 'sakit', 'izin'])) {
            $status = 'hadir';
        }

        $request->validate([
            'foto' => 'nullable|file|max:5120',
            'foto_kamera' => 'nullable|file|max:5120',
            'surat_keterangan' => 'nullable|file|max:5120',
            'lokasi_latitude' => 'nullable|numeric',
            'lokasi_longitude' => 'nullable|numeric',
            'lokasi_akurasi' => 'nullable|numeric',
            'lokasi' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('foto_kamera')) {
            $path = $request->file('foto_kamera')->store('absensi/masuk', 'public');
        } elseif ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('absensi/masuk', 'public');
        }

        $suratPath = null;
        if ($request->hasFile('surat_keterangan')) {
            $suratPath = $request->file('surat_keterangan')->store('absensi/surat', 'public');
        }

        $waktuMasuk = \Carbon\Carbon::now()->format('H:i:s');
        $today = \Carbon\Carbon::today()->format('Y-m-d');

        $lat = $request->input('lokasi_latitude');
        $lng = $request->input('lokasi_longitude');
        $acc = $request->input('lokasi_akurasi');
        $lokasiStr = $request->input('lokasi') ?: ($lat && $lng ? "{$lat}, {$lng}" : null);

        // Catatan/Laporan hanya diisi saat checkIn jika sakit atau izin
        // Untuk hadir dan wfh, laporan harian baru diisi saat absen pulang (check-out)
        $catatanMasuk = in_array($status, ['sakit', 'izin'])
            ? ($request->input('keterangan') ?: ($request->input('laporan') ?: ucfirst($status)))
            : null;

        \App\Models\Absensi::updateOrCreate(
            [
                'magang_application_id' => $magang->id,
                'tanggal' => $today
            ],
            [
                'waktu_masuk' => $waktuMasuk,
                'status' => $status,
                'foto_masuk' => $path,
                'lokasi_masuk' => $lokasiStr,
                'catatan' => $catatanMasuk,
            ]
        );

        $statusId = \App\Models\Simalam\MasterData::idFor(\App\Models\Simalam\MasterData::ABSENSI_STATUS, $status);
        \App\Models\Simalam\Absensi::updateOrCreate(
            [
                'user_id' => $user->id,
                'tanggal' => $today,
            ],
            [
                'jam_masuk' => $waktuMasuk,
                'status' => $status,
                'status_id' => $statusId,
                'status_masuk_id' => $statusId,
                'foto_kamera' => $path,
                'foto_masuk' => $path,
                'surat_izin' => $suratPath,
                'lokasi_latitude' => $lat,
                'lokasi_longitude' => $lng,
                'lokasi_akurasi' => $acc,
                'lokasi_masuk_latitude' => $lat,
                'lokasi_masuk_longitude' => $lng,
                'lokasi_masuk_akurasi' => $acc,
                'lokasi_diambil_pada' => now(),
                'lokasi_masuk_diambil_pada' => now(),
                'laporan' => $catatanMasuk,
            ]
        );

        return redirect()->back()->with('success', 'Berhasil melakukan absensi masuk (' . ucfirst($status) . ').');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $magang = \App\Models\MagangApplication::where('user_id', $user->id)
            ->whereIn('status', ['diterima', 'aktif'])
            ->firstOrFail();

        $request->validate([
            'foto' => 'nullable|file|max:5120',
            'foto_kamera' => 'nullable|file|max:5120',
            'lokasi_latitude' => 'nullable|numeric',
            'lokasi_longitude' => 'nullable|numeric',
            'lokasi_akurasi' => 'nullable|numeric',
            'lokasi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'laporan' => 'nullable|string',
        ]);

        $today = \Carbon\Carbon::today()->format('Y-m-d');
        $absensi = \App\Models\Absensi::where('magang_application_id', $magang->id)
            ->where('tanggal', $today)
            ->first();

        $path = null;
        if ($request->hasFile('foto_kamera')) {
            $path = $request->file('foto_kamera')->store('absensi/pulang', 'public');
        } elseif ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('absensi/pulang', 'public');
        }

        $waktuPulang = \Carbon\Carbon::now()->format('H:i:s');
        $lat = $request->input('lokasi_latitude');
        $lng = $request->input('lokasi_longitude');
        $acc = $request->input('lokasi_akurasi');
        $lokasiStr = $request->input('lokasi') ?: ($lat && $lng ? "{$lat}, {$lng}" : null);

        $laporan = $request->input('keterangan') ?: $request->input('laporan');

        if ($absensi) {
            $absensi->update([
                'waktu_pulang' => $waktuPulang,
                'foto_pulang' => $path ?: $absensi->foto_pulang,
                'lokasi_pulang' => $lokasiStr ?: $absensi->lokasi_pulang,
                'catatan' => $laporan ?: $absensi->catatan,
            ]);
        }

        \App\Models\Simalam\Absensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->update([
                'jam_pulang' => $waktuPulang,
                'foto_pulang' => $path,
                'lokasi_pulang_latitude' => $lat,
                'lokasi_pulang_longitude' => $lng,
                'lokasi_pulang_akurasi' => $acc,
                'lokasi_pulang_diambil_pada' => now(),
                'laporan' => $laporan,
            ]);

        if ($laporan) {
            \App\Models\Jurnal::updateOrCreate(
                [
                    'magang_application_id' => $magang->id,
                    'tanggal' => $today,
                ],
                [
                    'kegiatan' => $laporan,
                    'hasil_pekerjaan' => $laporan,
                    'file_lampiran' => $path,
                    'status_verifikasi' => 0,
                ]
            );
        }

        return redirect()->back()->with('success', 'Berhasil Check Out dan menyimpan jurnal kegiatan hari ini.');
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
