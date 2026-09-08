<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekrutmen;
use App\Models\Dinas;
use App\Models\MagangApplication;
use App\Models\PermohonanLayanan;

class LandingController extends Controller
{
    public function index()
    {
        // 1. Ambil Lowongan Aktif
        $rekrutmens = Rekrutmen::with(['dinas', 'bidang'])
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereDate('tanggal_berakhir', '>=', now())
                  ->orWhereNull('tanggal_berakhir');
            })
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        
        // Tambahkan dummy jenis_layanan agar tampilan blade tidak error (jika sebelumnya ada properti ini)
        foreach ($rekrutmens as $r) {
            $r->jenis_layanan = $r->bidang ? $r->bidang->nama : 'Umum';
        }

        // 2. Ambil Instansi (Dinas) Populer / Membuka kuota
        $featuredInstansis = Dinas::where('is_kesbangpol', false)
            ->with(['rekrutmens' => function($q) {
                $q->where('is_active', true);
            }])
            ->take(6)
            ->get();
        
        foreach ($featuredInstansis as $dinas) {
            $dinas->slot_tersedia = $dinas->rekrutmens->sum('slot_tersedia');
        }

        // 3. Ambil Peserta Magang Diterima
        $pesertas = MagangApplication::with(['user', 'rekrutmen.dinas', 'rekrutmen.bidang'])
            ->where('status', 'diterima')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
        
        // Mapping properti agar sesuai dengan view (dulu pakai struktur dummy)
        foreach ($pesertas as $p) {
            $p->jurusan = $p->permohonanLayanan->jenisLayanan->nama ?? 'Umum';
            $p->instansi_asal = $p->permohonanLayanan->asal_instansi ?? 'Instansi/Kampus';
            $p->dinas = $p->rekrutmen->dinas;
            $p->bidang = $p->rekrutmen->bidang;
        }

        // 4. Data Statistik Chart & Cards (Real dari DB)
        $totalRegistered = PermohonanLayanan::count();
        $totalAccepted = PermohonanLayanan::whereHas('statusMaster', function($q){
            $q->where('kode', 'disetujui');
        })->count();

        $stats = [
            'total_pendaftar' => $totalRegistered,
            'diterima' => $totalAccepted,
            'aktif' => MagangApplication::where('status', 'diterima')->count(),
            'dinas_tersedia' => Dinas::where('is_kesbangpol', false)->count()
        ];

        $pastDayNames = [];
        for ($i = 6; $i >= 0; $i--) {
            $pastDayNames[] = now()->subDays($i)->locale('id')->minDayName;
        }

        $servicesList = [
            'Penelitian (Perguruan Tinggi)' => 'penelitian_pt',
            'Penelitian (Instansi / Lembaga Lainnya)' => 'penelitian_instansi',
            'KKL / PKL / Magang (Mahasiswa)' => 'kkl_mahasiswa',
            'KKN Mahasiswa' => 'kkn_mahasiswa',
            'KKL / PKL / Magang (Siswa Sekolah)' => 'kkl_siswa',
            'Pelaksanaan Kegiatan' => 'pelaksanaan_kegiatan',
            'Perpanjangan Izin Rekomendasi' => 'perpanjangan',
        ];

        $chartData = [];

        foreach ($servicesList as $groupKey => $slug) {
            $registeredCount = PermohonanLayanan::whereHas('jenisLayanan', function($q) use ($slug) {
                $q->where('slug', $slug);
            })->count();

            $acceptedCount = PermohonanLayanan::whereHas('jenisLayanan', function($q) use ($slug) {
                $q->where('slug', $slug);
            })->whereHas('statusMaster', function($q) {
                $q->whereIn('kode', ['disetujui', 'selesai']);
            })->count();

            $todayCount = PermohonanLayanan::whereDate('created_at', now()->format('Y-m-d'))
                ->whereHas('jenisLayanan', function($q) use ($slug) {
                    $q->where('slug', $slug);
                })->count();

            $weeklySeries = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $weeklySeries[] = PermohonanLayanan::whereDate('created_at', $date->format('Y-m-d'))
                    ->whereHas('jenisLayanan', function($q) use ($slug) {
                        $q->where('slug', $slug);
                    })->count();
            }

            $chartData[$groupKey] = [
                'registered' => $registeredCount,
                'accepted' => $acceptedCount,
                'today' => $todayCount,
                'weekly_series' => $weeklySeries
            ];
        }

        $userApplications = auth()->check() 
            ? PermohonanLayanan::with(['jenisLayanan', 'statusMaster'])
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get()
            : collect();

        $userMagang = auth()->check()
            ? MagangApplication::with(['rekrutmen.dinas', 'rekrutmen.bidang', 'dinas', 'bidang', 'permohonanLayanan'])
                ->where('user_id', auth()->id())
                ->whereIn('status', ['diterima', 'aktif'])
                ->first()
            : null;

        $bidangParticipants = collect();
        if ($userMagang) {
            $bidangId = $userMagang->bidang_id ?? ($userMagang->rekrutmen->bidang_id ?? null);
            $dinasId = $userMagang->dinas_id ?? ($userMagang->rekrutmen->dinas_id ?? null);

            $query = MagangApplication::with(['user', 'absensis' => function($q) {
                $q->where('tanggal', now()->format('Y-m-d'));
            }, 'permohonanLayanan'])
            ->whereIn('status', ['diterima', 'aktif']);

            if ($bidangId) {
                $query->where(function($q) use ($bidangId) {
                    $q->where('bidang_id', $bidangId)
                      ->orWhereHas('rekrutmen', function($rq) use ($bidangId) {
                          $rq->where('bidang_id', $bidangId);
                      });
                });
            } elseif ($dinasId) {
                $query->where(function($q) use ($dinasId) {
                    $q->where('dinas_id', $dinasId)
                      ->orWhereHas('rekrutmen', function($rq) use ($dinasId) {
                          $rq->where('dinas_id', $dinasId);
                      });
                });
            }

            $bidangParticipants = $query->get();
        }

        return view('pelayanan.landing.index', compact(
            'rekrutmens', 
            'featuredInstansis', 
            'pesertas', 
            'chartData', 
            'pastDayNames',
            'totalRegistered',
            'totalAccepted',
            'stats',
            'userApplications',
            'userMagang',
            'bidangParticipants'
        ));
    }

    public function peserta(Request $request)
    {
        $search = $request->input('search');
        $instansiAsal = $request->input('instansi_asal');
        $dinasId = $request->input('dinas_id');

        $query = MagangApplication::with(['user', 'rekrutmen.dinas', 'rekrutmen.bidang', 'permohonanLayanan.jenisLayanan'])
            ->where('status', 'diterima');

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($instansiAsal) {
            $query->whereHas('permohonanLayanan', function($q) use ($instansiAsal) {
                $q->where('asal_instansi', $instansiAsal);
            });
        }

        if ($dinasId) {
            $query->whereHas('rekrutmen', function($q) use ($dinasId) {
                $q->where('dinas_id', $dinasId);
            });
        }

        $pesertas = $query->orderBy('updated_at', 'desc')->paginate(12);

        // Ambil semua instansi unik dari peserta magang yang diterima
        $semuaInstansi = \App\Models\PermohonanLayanan::whereIn('id', function($q) {
            $q->select('permohonan_layanan_id')
              ->from('magang_applications')
              ->where('status', 'diterima');
        })->pluck('asal_instansi')->filter()->unique()->values();

        // Ambil semua dinas untuk filter
        $semuaDinas = \App\Models\Dinas::where('is_kesbangpol', false)->orderBy('name')->get();

        foreach ($pesertas as $p) {
            $p->jurusan = $p->permohonanLayanan->jenisLayanan->nama ?? 'Umum';
            $p->instansi_asal = $p->permohonanLayanan->asal_instansi ?? 'Instansi/Kampus';
            $p->dinas = $p->rekrutmen->dinas;
            $p->bidang = $p->rekrutmen->bidang;
        }

        return view('pelayanan.landing.peserta', compact('pesertas', 'search', 'instansiAsal', 'dinasId', 'semuaInstansi', 'semuaDinas'));
    }

    public function instansiDetail($id)
    {
        $instansi = Dinas::with(['bidang', 'rekrutmens' => function($q) {
            $q->where('is_active', true);
        }])->findOrFail($id);

        $totalKuota = $instansi->rekrutmens->sum('kuota');
        $totalDiterima = MagangApplication::whereHas('rekrutmen', function($q) use ($id) {
            $q->where('dinas_id', $id);
        })->where('status', 'diterima')->count();
        $slotTersedia = $instansi->rekrutmens->sum('slot_tersedia');

        return view('pelayanan.landing.instansi_detail', compact('instansi', 'totalKuota', 'totalDiterima', 'slotTersedia'));
    }
    public function profile()
    {
        return view('pelayanan.landing.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'nik' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'asal_instansi' => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            'nim' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->except(['password', 'password_confirmation']);
        
        if ($request->filled('password')) {
            $data['password'] = \Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success_swal', 'Profil berhasil diperbarui!');
    }
}
