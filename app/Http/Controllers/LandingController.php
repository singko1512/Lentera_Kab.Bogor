<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekrutmen;
use App\Models\Dinas;
use App\Models\MagangApplication;
use App\Models\PermohonanLayanan;
use Illuminate\Support\Facades\DB;

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

        // 2. Ambil Instansi (Prioritaskan yang memiliki kuota, dibatasi tepat 1 row / 3 kartu)
        $allInstansis = Dinas::with([
                'rekrutmens' => function($q) {
                    $q->where('is_active', true);
                },
                'rekrutmens.magangApplications'
            ])
            ->get();

        foreach ($allInstansis as $dinas) {
            $dinas->slot_tersedia = $dinas->sisa_kuota;
        }

        // Pisahkan instansi yang memiliki kuota dan yang belum ada kuota
        $withKuota = $allInstansis->filter(function ($dinas) {
            return $dinas->sisa_kuota > 0;
        })->sortByDesc('sisa_kuota')->values();

        $withoutKuota = $allInstansis->filter(function ($dinas) {
            return $dinas->sisa_kuota <= 0;
        })->sortBy('name')->values();

        // Gabungkan: utamakan yang memiliki kuota, jika kurang/kosong diisi instansi lainnya (1 row = 3 card)
        $featuredInstansis = $withKuota->concat($withoutKuota)->take(3)->values();

        // 3. Ambil Peserta Magang (Diterima, Aktif, Selesai)
        $pesertas = MagangApplication::with(['user', 'rekrutmen.dinas', 'rekrutmen.bidang'])
            ->whereIn('status', ['diterima', 'aktif', 'selesai'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
        
        // Mapping properti agar sesuai dengan view (dulu pakai struktur dummy)
        foreach ($pesertas as $p) {
            $p->jurusan = $p->user?->program_studi ?: ($p->permohonanLayanan?->jenisLayanan?->nama ?? 'Umum');
            $p->instansi_asal = $p->user?->asal_instansi ?: ($p->permohonanLayanan?->asal_instansi ?? 'Instansi/Kampus');
            $p->dinas = $p->rekrutmen?->dinas;
            $p->bidang = $p->rekrutmen?->bidang;
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
            'dinas_tersedia' => Dinas::count()
        ];

        $pastDayNames = [];
        for ($i = 6; $i >= 0; $i--) {
            $pastDayNames[] = now()->subDays($i)->locale('id')->minDayName;
        }

        $servicesList = [
            'Semua Layanan' => 'all',
            'Penelitian (Perguruan Tinggi)' => 'penelitian_pt',
            'Penelitian (Instansi / Lembaga Lainnya)' => 'penelitian_instansi',
            'KKL / PKL / Magang (Mahasiswa)' => 'kkl_mahasiswa',
            'KKN Mahasiswa' => 'kkn_mahasiswa',
            'KKL / PKL / Magang (Siswa Sekolah)' => 'kkl_siswa',
            'Pelaksanaan Kegiatan' => 'pelaksanaan_kegiatan',
            'Perpanjangan Izin Rekomendasi' => 'perpanjangan',
        ];

        // Global top 10 dinas sebagai referensi/fallback
        $globalTopDinasRaw = PermohonanLayanan::whereNotNull('dinas_id')
            ->select('dinas_id', DB::raw('count(*) as total'))
            ->groupBy('dinas_id')
            ->orderByDesc('total')
            ->take(10)
            ->with('dinas')
            ->get();

        $chartData = [];

        foreach ($servicesList as $groupKey => $slug) {
            $baseQuery = PermohonanLayanan::query();
            if ($slug !== 'all') {
                $baseQuery->whereHas('jenisLayanan', function($q) use ($slug) {
                    $q->where('slug', $slug);
                });
            }

            $registeredCount = (clone $baseQuery)->count();

            $acceptedCount = (clone $baseQuery)->whereHas('statusMaster', function($q) {
                $q->whereIn('kode', ['disetujui', 'selesai']);
            })->count();

            $todayCount = (clone $baseQuery)->whereDate('created_at', now()->format('Y-m-d'))->count();

            $weeklySeries = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $weeklySeries[] = (clone $baseQuery)->whereDate('created_at', $date->format('Y-m-d'))->count();
            }

            // Query 10 Dinas Terbanyak Pendaftarnya untuk layanan ini
            $topDinasRaw = (clone $baseQuery)
                ->whereNotNull('dinas_id')
                ->select('dinas_id', DB::raw('count(*) as total'))
                ->groupBy('dinas_id')
                ->orderByDesc('total')
                ->take(10)
                ->with('dinas')
                ->get();

            $dinasDataToUse = $topDinasRaw->isNotEmpty() ? $topDinasRaw : $globalTopDinasRaw;

            $topDinasCategories = [];
            $topDinasFullNames = [];
            $topDinasSeries = [];

            foreach ($dinasDataToUse as $row) {
                $dName = $row->dinas?->name ?? 'Dinas';
                $topDinasCategories[] = Dinas::formatSingkatan($dName);
                $topDinasFullNames[] = $dName;
                $topDinasSeries[] = (int) $row->total;
            }

            $firstItem = $dinasDataToUse->first();
            $topDinasBadge = $firstItem
                ? (Dinas::formatSingkatan($firstItem->dinas?->name ?? 'Diskominfo') . ' (' . $firstItem->total . ' Orang)')
                : '-';

            $chartData[$groupKey] = [
                'registered' => $registeredCount,
                'accepted' => $acceptedCount,
                'today' => $todayCount,
                'weekly_series' => $weeklySeries,
                'top_dinas' => [
                    'categories' => $topDinasCategories,
                    'full_names' => $topDinasFullNames,
                    'series' => $topDinasSeries,
                    'top_badge' => $topDinasBadge,
                ]
            ];
        }

        $userApplications = auth()->check() 
            ? PermohonanLayanan::with(['jenisLayanan', 'statusMaster', 'dinas', 'magangApplication.rekrutmen.bidang', 'magangApplication.bidang'])
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
            $bidangId = $userMagang->bidang_id ?? ($userMagang->rekrutmen?->bidang_id ?? null);
            $dinasId = $userMagang->dinas_id ?? ($userMagang->rekrutmen?->dinas_id ?? null);

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

        $isApproaching3Months = false;
        $daysRemaining3Months = null;
        $batas3Bulan = null;

        if ($userMagang && $userMagang->tanggal_mulai) {
            $tglMulai = \Carbon\Carbon::parse($userMagang->tanggal_mulai);
            $batas3Bulan = $tglMulai->copy()->addMonths(3);
            $now = \Carbon\Carbon::now();
            $daysRemaining3Months = (int) $now->diffInDays($batas3Bulan, false);

            // Jika durasi magang sudah berjalan mendekati batas 3 bulan (sisa <= 14 hari) atau sudah melampaui 3 bulan
            if ($daysRemaining3Months <= 14) {
                $isApproaching3Months = true;

                // Buat notifikasi di lonceng/drawer jika belum ada notifikasi pengingat perpanjangan dalam 7 hari terakhir
                if (auth()->check()) {
                    $recentNotif = \App\Models\Notification::where('user_id', auth()->id())
                        ->where('judul', 'like', '%Perpanjangan Surat Rekomendasi%')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->first();

                    if (!$recentNotif) {
                        $dinasName = $userMagang->dinas->name ?? ($userMagang->rekrutmen->dinas->name ?? 'Instansi Penempatan');
                        \App\Models\Notification::create([
                            'user_id' => auth()->id(),
                            'judul' => 'Pengingat Wajib Perpanjangan Surat Rekomendasi',
                            'pesan' => 'Masa berlaku Surat Rekomendasi kegiatan magang Anda di ' . $dinasName . ' mendekati batas waktu 3 bulan. Anda wajib mengajukan perpanjangan izin agar masa magang dan absensi tetap berlaku sah.',
                            'link' => route('layanan.create', 'perpanjangan'),
                            'dibaca' => false,
                        ]);
                    }
                }
            }
        }

        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $yearExpr = $isSqlite ? "strftime('%Y', created_at)" : "YEAR(created_at)";
        $dbYears = PermohonanLayanan::selectRaw("{$yearExpr} as year")->distinct()->pluck('year')->filter()->map(fn($y) => (int)$y)->toArray();
        $availableYears = array_values(array_unique(array_merge([now()->year, now()->year - 1], $dbYears)));
        rsort($availableYears);

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
            'bidangParticipants',
            'isApproaching3Months',
            'daysRemaining3Months',
            'batas3Bulan'
        ));
    }

    /**
     * AJAX Endpoint untuk data analitik filter Jenis Layanan
     */
    public function getChartData(Request $request)
    {
        $serviceName = $request->input('service', 'Semua Layanan');

        $servicesList = [
            'Semua Layanan' => 'all',
            'Penelitian (Perguruan Tinggi)' => 'penelitian_pt',
            'Penelitian (Instansi / Lembaga Lainnya)' => 'penelitian_instansi',
            'KKL / PKL / Magang (Mahasiswa)' => 'kkl_mahasiswa',
            'KKN Mahasiswa' => 'kkn_mahasiswa',
            'KKL / PKL / Magang (Siswa Sekolah)' => 'kkl_siswa',
            'Pelaksanaan Kegiatan' => 'pelaksanaan_kegiatan',
            'Perpanjangan Izin Rekomendasi' => 'perpanjangan',
        ];

        $slug = $servicesList[$serviceName] ?? 'all';

        $baseQuery = PermohonanLayanan::query();

        if ($slug !== 'all') {
            $baseQuery->whereHas('jenisLayanan', function($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        $registeredCount = (clone $baseQuery)->count();

        $acceptedCount = (clone $baseQuery)->whereHas('statusMaster', function($q) {
            $q->whereIn('kode', ['disetujui', 'selesai', 'diterima', 'aktif']);
        })->count();

        // Top 10 Dinas Terbanyak Pendaftarnya
        $topDinasRaw = (clone $baseQuery)
            ->whereNotNull('dinas_id')
            ->select('dinas_id', DB::raw('count(*) as total'))
            ->groupBy('dinas_id')
            ->orderByDesc('total')
            ->take(10)
            ->with('dinas')
            ->get();

        $topDinasCategories = [];
        $topDinasFullNames = [];
        $topDinasSeries = [];

        foreach ($topDinasRaw as $row) {
            $dName = $row->dinas?->name ?? 'Dinas';
            $topDinasCategories[] = Dinas::formatSingkatan($dName);
            $topDinasFullNames[] = $dName;
            $topDinasSeries[] = (int) $row->total;
        }

        $firstItem = $topDinasRaw->first();
        $topDinasBadge = $firstItem
            ? (Dinas::formatSingkatan($firstItem->dinas?->name ?? 'Diskominfo') . ' (' . $firstItem->total . ' Orang)')
            : '-';

        return response()->json([
            'status' => 'success',
            'data' => [
                'registered' => $registeredCount,
                'accepted' => $acceptedCount,
                'top_dinas' => [
                    'categories' => $topDinasCategories,
                    'full_names' => $topDinasFullNames,
                    'series' => $topDinasSeries,
                    'top_badge' => $topDinasBadge,
                ]
            ]
        ]);
    }

    /**
     * AJAX Endpoint untuk data chart tren aktivitas pendaftaran (Tahun & Bulan)
     */
    public function getTrendChartData(Request $request)
    {
        $yearInput = $request->input('year', now()->year);
        $year = is_numeric($yearInput) ? (int) $yearInput : now()->year;
        $month = $request->input('month', 'all');

        $trendData = $this->buildTrendData($year, $month);

        return response()->json([
            'status' => 'success',
            'data' => $trendData
        ]);
    }

    /**
     * Helper untuk mengolah series tren pendaftaran per periode tahun / bulan
     */
    protected function buildTrendData(int $year, $month = 'all'): array
    {
        $query = PermohonanLayanan::query()
            ->whereYear('created_at', $year)
            ->with('statusMaster');

        if ($month !== 'all' && is_numeric($month)) {
            $month = (int) $month;
            $query->whereMonth('created_at', $month);
        }

        $records = $query->get(['id', 'status_master_id', 'created_at']);

        $monthNamesShort = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $monthNamesLong = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $categories = [];
        $registeredSeries = [];
        $acceptedSeries = [];

        $isAccepted = function($record) {
            $kode = strtolower($record->statusMaster?->kode ?? '');
            return in_array($kode, ['disetujui', 'selesai', 'diterima', 'aktif']);
        };

        if ($month === 'all') {
            for ($m = 1; $m <= 12; $m++) {
                $categories[] = $monthNamesShort[$m];
                $monthRecords = $records->filter(function($r) use ($m) {
                    return (int) $r->created_at->format('n') === $m;
                });
                $registeredSeries[] = $monthRecords->count();
                $acceptedSeries[] = $monthRecords->filter($isAccepted)->count();
            }
            $periodLabel = "Tahun {$year}";
            $periodType = 'year';
        } else {
            $daysInMonth = \Carbon\Carbon::createFromDate($year, (int)$month, 1)->daysInMonth;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $categories[] = (string) $d;
                $dayRecords = $records->filter(function($r) use ($d) {
                    return (int) $r->created_at->format('j') === $d;
                });
                $registeredSeries[] = $dayRecords->count();
                $acceptedSeries[] = $dayRecords->filter($isAccepted)->count();
            }
            $mName = $monthNamesLong[(int)$month] ?? "Bulan {$month}";
            $periodLabel = "{$mName} {$year}";
            $periodType = 'month';
        }

        $totalRegistered = array_sum($registeredSeries);
        $totalAccepted = array_sum($acceptedSeries);
        $approvalRate = $totalRegistered > 0 ? round(($totalAccepted / $totalRegistered) * 100, 1) : 0;
        $countUnits = count($registeredSeries);
        $avgPerUnit = $countUnits > 0 ? round($totalRegistered / $countUnits, 1) : 0;

        // Query pendaftar per instansi pada periode tahun & bulan ini (dibatasi Top 10)
        $instansiQuery = PermohonanLayanan::query()
            ->whereYear('created_at', $year)
            ->when($month !== 'all' && is_numeric($month), function($q) use ($month) {
                $q->whereMonth('created_at', (int) $month);
            })
            ->whereNotNull('dinas_id');

        $totalInstansiCount = (clone $instansiQuery)->distinct('dinas_id')->count('dinas_id');

        $instansiRaw = (clone $instansiQuery)
            ->select('dinas_id', DB::raw('count(*) as total'))
            ->groupBy('dinas_id')
            ->orderByDesc('total')
            ->take(10)
            ->with('dinas')
            ->get();

        $topDinasIds = $instansiRaw->pluck('dinas_id');

        $instansiAcceptedMap = (clone $instansiQuery)
            ->whereIn('dinas_id', $topDinasIds)
            ->whereHas('statusMaster', function($q) {
                $q->whereIn('kode', ['disetujui', 'selesai', 'diterima', 'aktif']);
            })
            ->select('dinas_id', DB::raw('count(*) as total'))
            ->groupBy('dinas_id')
            ->pluck('total', 'dinas_id');

        $instansiCategories = [];
        $instansiFullNames = [];
        $instansiRegistered = [];
        $instansiAcceptedSeries = [];

        foreach ($instansiRaw as $row) {
            $fullName = $row->dinas?->name ?? 'Dinas';
            $shortName = Dinas::formatSingkatan($fullName);
            $instansiCategories[] = $shortName;
            $instansiFullNames[] = $fullName;
            $instansiRegistered[] = (int) $row->total;
            $instansiAcceptedSeries[] = (int) ($instansiAcceptedMap[$row->dinas_id] ?? 0);
        }

        $topInstansiBadge = count($instansiCategories) > 0 
            ? "{$instansiCategories[0]} ({$instansiRegistered[0]} Pendaftar)" 
            : '-';

        return [
            'year' => $year,
            'month' => $month,
            'period_label' => $periodLabel,
            'period_type' => $periodType,
            'categories' => $categories,
            'series' => [
                [
                    'name' => 'Total Pendaftar',
                    'data' => $registeredSeries
                ]
            ],
            'instansi_data' => [
                'categories' => $instansiCategories,
                'full_names' => $instansiFullNames,
                'series' => [
                    [
                        'name' => 'Total Pendaftar',
                        'data' => $instansiRegistered
                    ]
                ],
                'registered' => $instansiRegistered,
                'top_badge' => $topInstansiBadge,
                'total_instansi' => $totalInstansiCount,
            ],
            'summary' => [
                'total_registered' => $totalRegistered,
                'total_accepted' => $totalAccepted,
                'approval_rate' => $approvalRate,
                'average' => $avgPerUnit,
                'top_instansi' => $topInstansiBadge,
                'total_instansi' => $totalInstansiCount,
            ]
        ];
    }

    public function peserta(Request $request)
    {
        // Validasi ketat query string untuk mitigasi SQL Injection & time-based blind injection
        if ($request->has('page')) {
            $rawPage = $request->query('page');
            if (!is_numeric($rawPage) || (int) $rawPage < 1 || filter_var($rawPage, FILTER_VALIDATE_INT) === false) {
                abort(404);
            }
        }
        $page = (int) $request->query('page', 1);

        $search = $request->input('search');
        $instansiAsal = $request->input('instansi_asal');

        $dinasId = $request->input('dinas_id');
        if ($dinasId !== null && $dinasId !== '') {
            if (!is_numeric($dinasId) || (int) $dinasId <= 0 || filter_var($dinasId, FILTER_VALIDATE_INT) === false) {
                abort(404);
            }
            $dinasId = (int) $dinasId;
        } else {
            $dinasId = null;
        }

        $year = $request->input('year', 'all');
        if ($year !== 'all' && $year !== null && $year !== '') {
            if (!is_numeric($year) || (int) $year < 2000 || (int) $year > 2100 || filter_var($year, FILTER_VALIDATE_INT) === false) {
                abort(404);
            }
            $year = (int) $year;
        } else {
            $year = 'all';
        }

        $month = $request->input('month', 'all');
        if ($month !== 'all' && $month !== null && $month !== '') {
            if (!is_numeric($month) || (int) $month < 1 || (int) $month > 12 || filter_var($month, FILTER_VALIDATE_INT) === false) {
                abort(404);
            }
            $month = (int) $month;
        } else {
            $month = 'all';
        }

        $query = MagangApplication::with(['user', 'rekrutmen.dinas', 'rekrutmen.bidang', 'permohonanLayanan.jenisLayanan', 'dinas', 'bidang'])
            ->whereIn('status', ['diterima', 'aktif', 'selesai']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('program_studi', 'like', "%{$search}%")
                       ->orWhere('asal_instansi', 'like', "%{$search}%");
                })->orWhereHas('permohonanLayanan', function($pq) use ($search) {
                    $pq->where('asal_instansi', 'like', "%{$search}%");
                });
            });
        }

        if ($instansiAsal) {
            $query->where(function($q) use ($instansiAsal) {
                $q->whereHas('permohonanLayanan', function($pq) use ($instansiAsal) {
                    $pq->where('asal_instansi', $instansiAsal);
                })->orWhereHas('user', function($uq) use ($instansiAsal) {
                    $uq->where('asal_instansi', $instansiAsal);
                });
            });
        }

        if ($dinasId) {
            $query->where(function($q) use ($dinasId) {
                $q->where('dinas_id', $dinasId)
                  ->orWhereHas('rekrutmen', function($rq) use ($dinasId) {
                      $rq->where('dinas_id', $dinasId);
                  });
            });
        }

        if ($year && $year !== 'all') {
            $query->whereYear('created_at', $year);
        }

        if ($month && $month !== 'all') {
            $query->whereMonth('created_at', $month);
        }

        $pesertas = $query->orderBy('updated_at', 'desc')->paginate(12, ['*'], 'page', $page)->withQueryString();

        // Ambil semua tahun unik yang tersedia dari peserta magang
        $availableYears = MagangApplication::whereIn('status', ['diterima', 'aktif', 'selesai'])
            ->whereNotNull('created_at')
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        $currentYear = (int) date('Y');
        if (!in_array($currentYear, $availableYears)) {
            $availableYears[] = $currentYear;
            rsort($availableYears);
        }

        // Ambil semua instansi unik dari peserta magang yang diterima / aktif / selesai
        $instansiFromPermohonan = \App\Models\PermohonanLayanan::whereIn('id', function($q) {
            $q->select('permohonan_layanan_id')
              ->from('magang_applications')
              ->whereIn('status', ['diterima', 'aktif', 'selesai']);
        })->pluck('asal_instansi')->filter();

        $instansiFromUser = \App\Models\User::whereIn('id', function($q) {
            $q->select('user_id')
              ->from('magang_applications')
              ->whereIn('status', ['diterima', 'aktif', 'selesai']);
        })->pluck('asal_instansi')->filter();

        $semuaInstansi = $instansiFromPermohonan->merge($instansiFromUser)
            ->map(fn($item) => trim($item))
            ->filter()
            ->unique()
            ->sort(SORT_NATURAL | SORT_FLAG_CASE)
            ->values();

        // Ambil semua dinas untuk filter
        $semuaDinas = \App\Models\Dinas::orderBy('name')->get();

        $selectedDinas = $dinasId ? \App\Models\Dinas::find($dinasId) : null;
        $selectedDinasName = $selectedDinas ? \App\Models\Dinas::formatTitleCaseKbbi($selectedDinas->name) : null;

        foreach ($pesertas as $p) {
            $p->jurusan = $p->user?->program_studi ?: ($p->permohonanLayanan?->jenisLayanan?->nama ?? 'Umum');
            $p->instansi_asal = $p->user?->asal_instansi ?: ($p->permohonanLayanan?->asal_instansi ?? 'Instansi/Kampus');
            $p->dinas = $p->dinas ?: $p->rekrutmen?->dinas;
            $p->bidang = $p->bidang ?: $p->rekrutmen?->bidang;
        }

        return view('pelayanan.landing.peserta', compact(
            'pesertas', 
            'search', 
            'instansiAsal', 
            'dinasId', 
            'selectedDinasName',
            'year',
            'month',
            'availableYears',
            'semuaInstansi', 
            'semuaDinas'
        ));
    }

    public function instansiList(Request $request)
    {
        if ($request->has('page')) {
            $rawPage = $request->query('page');
            if (!is_numeric($rawPage) || (int) $rawPage < 1 || filter_var($rawPage, FILTER_VALIDATE_INT) === false) {
                abort(404);
            }
        }
        $page = (int) $request->query('page', 1);

        $query = Dinas::with(['rekrutmens' => function ($q) {
            $q->where('is_active', true);
        }, 'rekrutmens.magangApplications']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('filter') && $request->filter != 'semua') {
            if ($request->filter == 'tersedia') {
                $query->where(function ($q) {
                    $q->where('status_magang', 'tersedia')
                      ->orWhere(function ($subQ) {
                          $subQ->where(function ($s) {
                              $s->where('status_magang', 'otomatis')->orWhereNull('status_magang');
                          })->whereHas('rekrutmens', function ($q2) {
                              $q2->where('is_active', true)
                                 ->whereRaw('kuota > (SELECT COUNT(*) FROM magang_applications WHERE magang_applications.rekrutmen_id = rekrutmens.id AND magang_applications.status IN (?, ?, ?))', ['menunggu', 'diterima', 'aktif']);
                          });
                      });
                });
            } elseif ($request->filter == 'penuh') {
                $query->where(function ($q) {
                    $q->where('status_magang', 'penuh')
                      ->orWhere(function ($subQ) {
                          $subQ->where(function ($s) {
                              $s->where('status_magang', 'otomatis')->orWhereNull('status_magang');
                          })->whereHas('rekrutmens', function ($q2) {
                              $q2->where('is_active', true);
                          })->whereDoesntHave('rekrutmens', function ($q3) {
                              $q3->where('is_active', true)
                                 ->whereRaw('kuota > (SELECT COUNT(*) FROM magang_applications WHERE magang_applications.rekrutmen_id = rekrutmens.id AND magang_applications.status IN (?, ?, ?))', ['menunggu', 'diterima', 'aktif']);
                          });
                      });
                });
            } elseif ($request->filter == 'tidak_tersedia') {
                $query->where(function ($q) {
                    $q->where('status_magang', 'tidak_tersedia')
                      ->orWhere(function ($subQ) {
                          $subQ->where(function ($s) {
                              $s->where('status_magang', 'otomatis')->orWhereNull('status_magang');
                          })->whereDoesntHave('rekrutmens', function ($q2) {
                              $q2->where('is_active', true);
                          });
                      });
                });
            }
        }

        if ($request->filled('sort')) {
            if ($request->sort == 'nama_asc') {
                $query->orderBy('name', 'asc');
            } elseif ($request->sort == 'nama_desc') {
                $query->orderBy('name', 'desc');
            } elseif ($request->sort == 'kuota_terbanyak') {
                $query->withSum(['rekrutmens' => function($q) {
                    $q->where('is_active', true);
                }], 'kuota')->orderBy('rekrutmens_sum_kuota', 'desc');
            }
        } else {
            $query->orderBy('name', 'asc');
        }

        $instansis = $query->paginate(40, ['*'], 'page', $page)->withQueryString();

        return view('pelayanan.landing.instansi', compact('instansis'));
    }

    public function instansiDetail($id)
    {
        // Decode identifier terenkripsi / non-sekuensial untuk mitigasi resource enumeration & IDOR
        $resolvedId = Dinas::decodeId($id);
        if (!$resolvedId) {
            abort(404);
        }

        $instansi = Dinas::with(['bidang', 'rekrutmens' => function ($q) {
            $q->where('is_active', true)->with('magangApplications');
        }])->findOrFail($resolvedId);

        $totalKuota = (int) $instansi->rekrutmens->sum('kuota');
        $slotTersedia = (int) $instansi->rekrutmens->sum('slot_tersedia');
        $totalDiterima = (int) MagangApplication::whereHas('rekrutmen', function ($q) use ($resolvedId) {
            $q->where('dinas_id', '=', $resolvedId);
        })->whereIn('status', ['diterima', 'aktif'])->count();

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
            'nik' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $tglLahir = $request->input('tanggal_lahir');
                    if (!$tglLahir) return;
                    try {
                        $age = \Carbon\Carbon::parse($tglLahir)->age;
                        if ($age >= 17 && empty($value)) {
                            $fail('NIK wajib diisi untuk pemohon berusia 17 tahun atau lebih.');
                        }
                    } catch (\Exception $e) {}
                }
            ],
            'no_hp' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'asal_instansi' => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            'nim' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only([
            'nik',
            'no_hp',
            'tempat_lahir',
            'tanggal_lahir',
            'asal_instansi',
            'program_studi',
            'nim',
            'alamat',
        ]);

        $data['name'] = $request->input('nama');
        $data['email'] = $request->input('email');

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success_swal', 'Profil berhasil diperbarui!');
    }

    /**
     * Verifikasi Publik Surat Rekomendasi via Token QR Code
     * Menampilkan data terbatas dengan masking privasi pemohon.
     */
    public function verifikasiSurat($token)
    {
        $surat = \App\Models\SuratRekomendasi::with(['permohonanLayanan.dinas', 'permohonanLayanan.user', 'permohonanLayanan.statusMaster'])
            ->where('verification_token', $token)
            ->first();

        if (!$surat || !$surat->permohonanLayanan) {
            return view('pelayanan.landing.verifikasi_surat', [
                'isValid' => false,
                'statusLabel' => 'TIDAK DITEMUKAN / TIDAK VALID',
                'nomorSurat' => '-',
                'namaPemohon' => '-',
                'instansiTujuan' => '-',
                'tanggalTerbit' => '-',
                'catatan' => 'Dokumen Surat Rekomendasi dengan tanda verifikasi digital ini tidak terdaftar dalam sistem resmi Bangga Online Kabupaten Bogor.',
            ]);
        }

        $permohonan = $surat->permohonanLayanan;
        $namaAsli = $permohonan->atas_nama ?: ($permohonan->user->name ?? '-');

        // Masking nama pemohon demi privasi (misal: B*** S******)
        $maskedName = collect(explode(' ', trim($namaAsli)))->map(function ($part) {
            $len = mb_strlen($part);
            if ($len <= 2) {
                return $part;
            }
            return mb_substr($part, 0, 1) . str_repeat('*', min(6, $len - 1));
        })->implode(' ');

        $instansiTujuan = $permohonan->dinas?->name ?: ($permohonan->tempat_kegiatan ?: 'Pemerintah Kabupaten Bogor');
        $tanggalTerbit = $surat->tanggal_surat 
            ? \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y')
            : \Carbon\Carbon::parse($surat->created_at)->translatedFormat('d F Y');

        $statusKode = strtolower($permohonan->statusMaster->kode ?? '');
        $isDisetujui = in_array($statusKode, ['disetujui', 'selesai'], true);

        // Cek kedaluwarsa
        $isExpired = false;
        if ($permohonan->tanggal_selesai) {
            $isExpired = \Carbon\Carbon::parse($permohonan->tanggal_selesai)->endOfDay()->isPast();
        }

        if (!$isDisetujui) {
            $isValid = false;
            $statusLabel = 'TIDAK AKTIF / DIBATALKAN';
            $catatan = 'Status surat rekomendasi ini belum disetujui atau telah dibatalkan.';
        } elseif ($isExpired) {
            $isValid = false;
            $statusLabel = 'KEDALUWARSA';
            $catatan = 'Masa berlaku kegiatan/rekomendasi pada surat ini telah berakhir (' . \Carbon\Carbon::parse($permohonan->tanggal_selesai)->translatedFormat('d F Y') . ').';
        } else {
            $isValid = true;
            $statusLabel = 'VALID';
            $catatan = 'Surat Rekomendasi ini sah dan terverifikasi secara elektronik oleh Bakesbangpol Kabupaten Bogor.';
        }

        return view('pelayanan.landing.verifikasi_surat', [
            'isValid' => $isValid,
            'statusLabel' => $statusLabel,
            'nomorSurat' => $surat->nomor_surat ?: ('000.1.5/' . $permohonan->id . '/Bakesbangpol/' . date('Y')),
            'namaPemohon' => $maskedName,
            'instansiTujuan' => $instansiTujuan,
            'tanggalTerbit' => $tanggalTerbit,
            'catatan' => $catatan,
        ]);
    }

    /**
     * Halaman Statistik dan Rekapitulasi Pendaftaran Seluruh Instansi (Kedinasan & Kecamatan)
     */
    public function statistikInstansi(Request $request)
    {
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $yearExpr = $isSqlite ? "strftime('%Y', created_at)" : "YEAR(created_at)";
        $dbYears = PermohonanLayanan::selectRaw("{$yearExpr} as year")
            ->whereNotNull('created_at')
            ->distinct()
            ->pluck('year')
            ->filter()
            ->map(fn($y) => (int)$y)
            ->toArray();
        $availableYears = array_values(array_unique(array_merge([now()->year], $dbYears)));
        rsort($availableYears);

        $defaultYear = !empty($availableYears) ? $availableYears[0] : (int)date('Y');
        $year = $request->input('year', (string) $defaultYear);
        $month = $request->input('month', 'all');

        $query = PermohonanLayanan::whereNotNull('dinas_id');
        if ($year !== 'all' && is_numeric($year)) {
            $query->whereYear('created_at', (int) $year);
        }
        if ($month !== 'all' && is_numeric($month)) {
            $query->whereMonth('created_at', (int) $month);
        }

        // 1. Agregasi pendaftar per dinas
        $aggregates = (clone $query)
            ->select('dinas_id', DB::raw('count(*) as total'))
            ->groupBy('dinas_id')
            ->pluck('total', 'dinas_id');

        $acceptedAggregates = (clone $query)
            ->whereHas('statusMaster', function($q) {
                $q->whereIn('kode', ['disetujui', 'selesai', 'diterima', 'aktif']);
            })
            ->select('dinas_id', DB::raw('count(*) as total'))
            ->groupBy('dinas_id')
            ->pluck('total', 'dinas_id');

        // 2. Ambil semua dinas beserta rekrutmen aktif untuk kuota
        $allDinas = Dinas::with(['rekrutmens' => function($q) {
            $q->where('is_active', true);
        }])->get();

        $dinasList = [];
        $kecamatanList = [];
        $tableData = [];

        foreach ($allDinas as $dinas) {
            $nameClean = trim($dinas->name);
            $isKecamatan = str_starts_with(strtoupper($nameClean), 'KECAMATAN');
            
            $total = (int) ($aggregates[$dinas->id] ?? 0);
            $diterima = (int) ($acceptedAggregates[$dinas->id] ?? 0);
            $proses = max(0, $total - $diterima);
            $singkatan = Dinas::formatSingkatan($nameClean);

            $item = [
                'id' => $dinas->id,
                'hashid' => $dinas->hashid,
                'name' => $nameClean,
                'singkatan' => $singkatan,
                'tipe' => $isKecamatan ? 'Kecamatan' : 'Perangkat Daerah',
                'is_kecamatan' => $isKecamatan,
                'total_pendaftar' => $total,
                'diterima' => $diterima,
                'proses' => $proses,
                'status_magang' => $dinas->status_magang ?? 'otomatis',
                'sisa_kuota' => $dinas->sisa_kuota,
                'email' => $dinas->email ?: '-',
                'telepon' => $dinas->telepon ?: '-',
                'updated_at' => $dinas->updated_at ? $dinas->updated_at->format('Y-m-d') : '-',
            ];

            if ($isKecamatan) {
                $kecamatanList[] = $item;
            } else {
                $dinasList[] = $item;
            }

            $tableData[] = $item;
        }

        // Urutkan dinas & kecamatan berdasarkan total_pendaftar terbanyak
        usort($dinasList, fn($a, $b) => $b['total_pendaftar'] <=> $a['total_pendaftar'] ?: strcmp($a['name'], $b['name']));
        usort($kecamatanList, fn($a, $b) => $b['total_pendaftar'] <=> $a['total_pendaftar'] ?: strcmp($a['name'], $b['name']));
        usort($tableData, fn($a, $b) => $b['total_pendaftar'] <=> $a['total_pendaftar'] ?: strcmp($a['name'], $b['name']));

        // Data chart Kedinasan (horizontal bar)
        $chartDinas = [
            'categories' => array_column($dinasList, 'singkatan'),
            'full_names' => array_column($dinasList, 'name'),
            'series' => array_column($dinasList, 'total_pendaftar'),
        ];

        // Data chart Kecamatan (horizontal bar)
        $chartKecamatan = [
            'categories' => array_column($kecamatanList, 'singkatan'),
            'full_names' => array_column($kecamatanList, 'name'),
            'series' => array_column($kecamatanList, 'total_pendaftar'),
        ];

        $summary = [
            'total_instansi' => count($allDinas),
            'total_kedinasan' => count($dinasList),
            'total_kecamatan' => count($kecamatanList),
            'total_pendaftar' => array_sum(array_column($tableData, 'total_pendaftar')),
            'total_diterima' => array_sum(array_column($tableData, 'diterima')),
            'total_proses' => array_sum(array_column($tableData, 'proses')),
        ];

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        if ($year === 'all') {
            $periodLabel = 'Semua Periode';
        } elseif ($month === 'all') {
            $periodLabel = "Tahun {$year}";
        } else {
            $mName = $monthNames[(int)$month] ?? "Bulan {$month}";
            $periodLabel = "{$mName} {$year}";
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'chartDinas' => $chartDinas,
                'chartKecamatan' => $chartKecamatan,
                'tableData' => $tableData,
                'summary' => $summary,
                'year' => $year,
                'month' => $month,
                'periodLabel' => $periodLabel,
                'totalDinasCount' => count($dinasList),
                'totalKecamatanCount' => count($kecamatanList),
            ]);
        }

        return view('pelayanan.landing.statistik_instansi', compact(
            'dinasList',
            'kecamatanList',
            'chartDinas',
            'chartKecamatan',
            'tableData',
            'summary',
            'availableYears',
            'year',
            'month',
            'periodLabel'
        ));
    }
}
