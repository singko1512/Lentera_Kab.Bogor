<?php

namespace App\Http\Controllers\Simalam;

use App\Http\Controllers\Controller;

use App\Models\Simalam\Absensi;
use App\Models\Simalam\ActivityLog;
use App\Models\Bidang;
use App\Models\Simalam\MasterData;
use App\Models\Simalam\Project;
use App\Models\Simalam\ProjectModule;
use App\Models\Simalam\ProjectTask;
use App\Models\Simalam\ProjectTaskParticipant;
use App\Models\User;
use App\Support\CertificatePayload;
use App\Support\CertificateTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\MagangApplication;
use App\Models\Jurnal;
use App\Models\Simalam\Pengaturan;


class AttendanceController extends Controller
{
    private const OFFICIAL_BIDANG_NAMES = [
        'Bidang Pengelolaan Informasi dan Komunikasi Publik',
        'Bidang Aplikasi Informatika',
        'Bidang Infrastruktur Teknologi',
        'Bidang Persandian dan Statistik',
        'Kepala UPT Radio dan Televisi',
    ];

    /**
     * Display the landing page.
     */
    public function home()
    {
        $users = User::with(['jadwalMingguan', 'bidang'])->where('role', 'user')->orderBy('name', 'asc')->get();
        $bidangsByName = Bidang::whereIn('name', self::OFFICIAL_BIDANG_NAMES)->get()->keyBy('name');
        $scheduleBidangGroups = collect(self::OFFICIAL_BIDANG_NAMES)->map(function (string $bidangName) use ($users, $bidangsByName) {
            $bidang = $bidangsByName->get($bidangName);
            $members = $users
                ->filter(function (User $user) use ($bidang, $bidangName): bool {
                    return ($bidang && (int) ($user->bidang_id ?? 0) === (int) $bidang->id)
                        || $user->bidang_magang === $bidangName;
                })
                ->sortBy('name')
                ->values();

            return [
                'id' => $bidang?->id,
                'nama' => $bidangName,
                'users' => $members,
                'landing_view' => AdminController::getLandingScheduleView($bidang?->id),
            ];
        });

        $landingModes = $scheduleBidangGroups->pluck('landing_view')->unique()->values();
        $jadwalLandingView = $landingModes->count() === 1 ? $landingModes->first() : 'mixed';
        $availableTeams = AdminController::getAvailableTeams();

        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->addDays(4);
        $todayKey = match (Carbon::now()->dayOfWeekIso) {
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            default => null,
        };
        $dayMap = [
            'senin' => $weekStart->copy(),
            'selasa' => $weekStart->copy()->addDay(),
            'rabu' => $weekStart->copy()->addDays(2),
            'kamis' => $weekStart->copy()->addDays(3),
            'jumat' => $weekStart->copy()->addDays(4),
        ];

        return view('absensi.home', compact('users', 'scheduleBidangGroups', 'weekStart', 'weekEnd', 'todayKey', 'dayMap', 'jadwalLandingView', 'availableTeams'));
    }

    /**
     * Combined absensi page: form + rekap in one view.
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $users = User::where('role', 'user')->orderBy('name', 'asc')->get();
        $absensiStatuses = MasterData::options(MasterData::ABSENSI_STATUS);

        $bidangList = Bidang::orderBy('name', 'asc')->pluck('name');

        // Determine active tab
        $activeTab = $request->input('tab', 'form');
        if (! in_array($activeTab, ['form', 'timeline'], true)) {
            return redirect()
                ->route('absensi.index', ['tab' => 'form'])
                ->with('error_swal', 'Peserta magang hanya dapat mengakses absensi dan timeline proyek.');
        }

        // Rekap data
        $selectedUser = null;
        $absensi = collect();
        $stats = [
            'hadir' => 0, 'wfh' => 0, 'sakit' => 0, 'izin' => 0,
            'persentase' => 0, 'total_hari_kerja' => 0,
        ];

        $filterType = $request->input('filter_type', 'all');
        $userId = $currentUser->id;
        $bidangMagang = $request->input('bidang_magang');
        if ($userId && ! $bidangMagang) {
            $tempUser = User::find($userId);
            if ($tempUser) {
                $bidangMagang = $tempUser->bidang_magang;
                $request->merge(['bidang_magang' => $bidangMagang]);
            }
        }
        $timelineUser = null;
        $timelineProjects = collect();

        if (true) {
            if ($filterType === 'date' && $request->filled('date')) {
                $startDate = Carbon::parse($request->input('date'))->startOfDay();
                $endDate = Carbon::parse($request->input('date'))->endOfDay();
            } elseif ($filterType === 'month' && $request->filled('month_filter')) {
                $parts = explode('-', $request->input('month_filter'));
                $startDate = Carbon::createFromDate((int) $parts[0], (int) $parts[1], 1)->startOfMonth();
                $endDate = Carbon::createFromDate((int) $parts[0], (int) $parts[1], 1)->endOfMonth();
            } else {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            }

            $absensiQuery = Absensi::with(['user', 'statusMaster'])
                ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

            if ($userId) {
                $selectedUser = User::findOrFail($userId);
                $absensiQuery->where('user_id', $userId);
            } elseif ($bidangMagang) {
                $absensiQuery->whereHas('user', function ($query) use ($bidangMagang) {
                    $query->where('bidang_magang', $bidangMagang);
                });
            }

            $absensi = $absensiQuery->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();

            $totalWorkdays = 0;
            $tempDate = $startDate->copy();
            $maxCalcDate = $endDate->gt(Carbon::today()) ? Carbon::today() : $endDate;
            while ($tempDate->lte($maxCalcDate)) {
                if (! $tempDate->isWeekend()) {
                    $totalWorkdays++;
                }
                $tempDate->addDay();
            }
            if ($totalWorkdays === 0) {
                $totalWorkdays = 1;
            }

            $stats['hadir'] = $absensi->where('status', 'hadir')->count();
            $stats['wfh'] = $absensi->where('status', 'wfh')->count();
            $stats['sakit'] = $absensi->where('status', 'sakit')->count();
            $stats['izin'] = $absensi->where('status', 'izin')->count();
            $stats['total_hari_kerja'] = $totalWorkdays;
            $stats['persentase'] = round((($stats['hadir'] + $stats['wfh']) / $totalWorkdays) * 100, 1);
        }

        $today = Carbon::today(config('app.timezone'))->toDateString();
        $todayAttendance = Absensi::where('user_id', $userId)
            ->where('tanggal', $today)
            ->first();

        // 1. Dapatkan config batas_absen_masuk
        $batasWaktuConfig = Pengaturan::where('kunci', 'batas_absen_masuk')->value('nilai') ?? '12:00';
        $nowTime = Carbon::now(config('app.timezone'))->format('H:i');
        
        // Mode Form: Jika sekarang >= batas waktu, maka mode Pulang, sebaliknya mode Masuk
        $is_checkout_mode = $nowTime >= $batasWaktuConfig;

        // 2. Fetch MagangApplication and Jurnal
        $magangApp = MagangApplication::where('user_id', $userId)->latest()->first();
        $todayJurnal = null;
        if ($magangApp) {
            $todayJurnal = Jurnal::where('magang_application_id', $magangApp->id)
                ->where('tanggal', $today)
                ->first();
        }

        // Empty dummy collections for timeline/project views to prevent blade errors
        $myActiveTasks = collect();
        $myRevisionTasks = collect();
        $myReviewTasks = collect();
        $myCompletedTasks = collect();
        $myTodayTasks = collect();
        $hasActiveTask = false;
        $allActiveProjects = collect();
        $timelineProjects = collect();
        $selectedProject = null;
        if ($hasActiveTask) {
            $firstActive = $myTodayTasks->first() ?: $myReviewTasks->first();
            $selectedProject = $firstActive ? $firstActive->project : null;
            if ($selectedProject) {
                session(['active_project_id' => $selectedProject->id]);
            }
            if ($request->has('project_id') || $request->has('reset_project')) {
                session()->flash('warning_swal', 'Anda masih memiliki tugas yang sedang dikerjakan. Silakan klik "Batal Pilih" pada tugas di atas jika ingin mengganti proyek.');
            }
        } elseif ($request->has('reset_project')) {
            session()->forget('active_project_id');
            $selectedProject = null;
        } elseif ($request->filled('project_id')) {
            $selectedProjectId = (int) $request->input('project_id');
            $selectedProject = $allActiveProjects->firstWhere('id', $selectedProjectId) ?? Project::with([
                'statusMaster',
                'members',
                'modules.tasks.user',
                'tasks.module',
                'tasks.user',
            ])->find($selectedProjectId);
            if ($selectedProject) {
                session(['active_project_id' => $selectedProject->id]);
            }
        } elseif (session('active_project_id')) {
            $selectedProjectId = (int) session('active_project_id');
            $selectedProject = $allActiveProjects->firstWhere('id', $selectedProjectId);
            if (! $selectedProject) {
                session()->forget('active_project_id');
            }
        }

            $availableTasks = collect();
            $allAvailableTasks = collect();
            $availableModules = collect();
            $allAvailableModules = collect();

        $timelineUser = $currentUser;
        $taskParticipants = collect();

        return view('absensi.absensi.index', compact(
            'users',
            'bidangList',
            'activeTab',
            'selectedUser',
            'absensi',
            'stats',
            'filterType',
            'timelineUser',
            'timelineProjects',
            'absensiStatuses',
            'currentUser',
            'todayAttendance',
            'availableTasks',
            'taskParticipants',
            'myActiveTasks',
            'myRevisionTasks',
            'myReviewTasks',
            'myCompletedTasks',
            'myTodayTasks',
            'allAvailableTasks',
            'hasActiveTask',
            'availableModules',
            'allAvailableModules',
            'allActiveProjects',
            'selectedProject',
            'batasWaktuConfig',
            'is_checkout_mode',
            'todayJurnal'
        ));
    }

    /**
     * Store daily attendance submission.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        $today = Carbon::today(config('app.timezone'))->toDateString();
        $now = now(config('app.timezone'));
        
        $absensi = Absensi::where('user_id', $userId)
            ->where('tanggal', $today)
            ->first();

        // Mode Checkout bergantung pada apakah peserta sudah absen masuk atau belum
        $isCheckoutMode = $absensi ? true : false;

        $status = (string) $request->input('status');

        if (!$isCheckoutMode) {
            // MODE ABSEN MASUK
            if ($absensi) {
                return redirect()->back()
                    ->withInput()
                    ->with('error_swal', 'Anda sudah melakukan absensi masuk hari ini.');
            }
            
            // Validasi Input Masuk
            $request->validate([
                'status' => [
                    'required',
                    Rule::exists('md_master_data', 'kode')
                        ->where(fn ($query) => $query->where('jenis', MasterData::ABSENSI_STATUS)->where('is_active', true)),
                ],
                'foto_kamera' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
                'lokasi_latitude' => Rule::requiredIf(in_array($status, ['izin', 'sakit'])),
                'lokasi_longitude' => Rule::requiredIf(in_array($status, ['izin', 'sakit'])),
                'surat_keterangan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ], [
                'status.required' => 'Pilih status absensi.',
                'foto_kamera.required' => 'Foto kamera wajib diambil.',
                'lokasi_latitude.required' => 'Lokasi GPS wajib diizinkan untuk status Izin/Sakit.',
                'lokasi_longitude.required' => 'Lokasi GPS wajib diizinkan untuk status Izin/Sakit.',
            ]);

            $fotoKameraPath = $this->storeAbsensiFile($request->file('foto_kamera'), $userId, 'kamera');
            $suratIzinPath = null;
            if ($request->hasFile('surat_keterangan')) {
                $suratIzinPath = $this->storeAbsensiFile($request->file('surat_keterangan'), $userId, 'surat');
            }

            // Validasi Geofencing untuk WFO (Hadir)
            if ($status === 'hadir') {
                $lat = $request->input('lokasi_latitude');
                $lng = $request->input('lokasi_longitude');
                
                if (!$lat || !$lng) {
                    return redirect()->back()->withInput()->with('error_swal', 'Koordinat lokasi tidak ditemukan. Pastikan Anda telah mengunci lokasi.');
                }

                $officeLat = -6.4829;
                $officeLng = 106.8285;
                $maxRadius = 100;

                // Haversine formula
                $earthRadius = 6371000; // Radius in meters
                $dLat = deg2rad($lat - $officeLat);
                $dLng = deg2rad($lng - $officeLng);
                $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($officeLat)) * cos(deg2rad($lat)) * sin($dLng/2) * sin($dLng/2);
                $c = 2 * atan2(sqrt($a), sqrt(1-$a));
                $distance = $earthRadius * $c;

                if ($distance > $maxRadius) {
                    return redirect()->back()->withInput()->with('error_swal', 'Jarak Anda terlalu jauh dari kantor (' . round($distance) . ' meter). Jarak maksimal adalah 100 meter.');
                }
            }

            $statusId = MasterData::idFor(MasterData::ABSENSI_STATUS, $status);

            Absensi::create([
                'user_id' => $userId,
                'task_id' => null,
                'tanggal' => $today,
                'jam_masuk' => $now->format('H:i:s'),
                'status' => $status,
                'status_id' => $statusId,
                'status_masuk_id' => $statusId,
                'foto_kamera' => $fotoKameraPath,
                'foto_masuk' => $fotoKameraPath,
                'lokasi_latitude' => $request->input('lokasi_latitude'),
                'lokasi_longitude' => $request->input('lokasi_longitude'),
                'lokasi_akurasi' => $request->input('lokasi_akurasi'),
                'lokasi_diambil_pada' => $now,
                'lokasi_masuk_latitude' => $request->input('lokasi_latitude'),
                'lokasi_masuk_longitude' => $request->input('lokasi_longitude'),
                'lokasi_masuk_akurasi' => $request->input('lokasi_akurasi'),
                'lokasi_masuk_diambil_pada' => $now,
                'surat_izin' => $suratIzinPath,
            ]);

            return redirect()->route('absensi.index')->with('success_swal', 'Absensi masuk berhasil disimpan.');
        } else {
            // MODE ABSEN PULANG
            if (!$absensi) {
                return redirect()->back()->with('error_swal', 'Anda belum melakukan absensi masuk hari ini.');
            }

            if ($absensi->jam_pulang) {
                return redirect()->back()->with('error_swal', 'Anda sudah melakukan absensi pulang hari ini.');
            }

            if (in_array($absensi->status, ['izin', 'sakit'])) {
                return redirect()->back()->with('error_swal', 'Status Anda hari ini adalah Izin/Sakit, tidak memerlukan absen pulang.');
            }

            $magangApp = MagangApplication::where('user_id', $userId)->latest()->first();
            if (!$magangApp) {
                return redirect()->back()->with('error_swal', 'Data magang tidak ditemukan.');
            }
            
            // Cek apakah mode absen hadir/wfh atau sakit/izin
            $isHadirWfh = in_array($absensi->status, ['hadir', 'wfh']);
            
            $rules = [];
            $messages = [];

            if ($isHadirWfh) {
                $rules['foto'] = 'required|image|mimes:jpg,jpeg,png,webp|max:5120';
                $rules['keterangan'] = 'required|string';
                $messages['foto.required'] = 'Gambar bukti pengerjaan wajib diunggah.';
                $messages['keterangan.required'] = 'Laporan hasil pekerjaan wajib diisi.';
            }

            $request->validate($rules, $messages);

            // Buat atau Update Jurnal
            if ($isHadirWfh) {
                $todayJurnal = Jurnal::firstOrNew([
                    'magang_application_id' => $magangApp->id,
                    'tanggal' => $today,
                ]);
                $todayJurnal->kegiatan = 'Melanjutkan pekerjaan magang (Absensi Pulang)';
                $todayJurnal->hasil_pekerjaan = $request->input('keterangan');

                if ($request->hasFile('foto')) {
                    $file = $request->file('foto');
                    $filename = date('Ymd_His') . '_' . $userId . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    // Simpan di storage public atau uploads
                    $file->move(public_path('uploads/jurnal'), $filename);
                    $todayJurnal->file_lampiran = 'uploads/jurnal/' . $filename;
                }
                $todayJurnal->save();
            }

            $statusId = MasterData::idFor(MasterData::ABSENSI_STATUS, $absensi->status);
            $fotoPulangPath = isset($todayJurnal) && $todayJurnal->file_lampiran ? $todayJurnal->file_lampiran : null;

            $absensi->update([
                'jam_pulang' => $now->format('H:i:s'),
                'status_pulang_id' => $statusId,
                'foto_pulang' => $fotoPulangPath,
                'lokasi_pulang_latitude' => $request->input('lokasi_latitude'),
                'lokasi_pulang_longitude' => $request->input('lokasi_longitude'),
                'lokasi_pulang_akurasi' => $request->input('lokasi_akurasi'),
                'lokasi_pulang_diambil_pada' => $now,
            ]);

            return redirect()->route('absensi.index')->with('success_swal', 'Absensi pulang berhasil disimpan. Terima kasih atas kerja keras Anda hari ini!');
        }
    }

    /**
     * Legacy routes redirect to combined page.
     */
    public function showForm()
    {
        return redirect()->route('absensi.index', ['tab' => 'form']);
    }

    public function rekap(Request $request)
    {
        return redirect()->route('absensi.index', ['tab' => 'form']);
    }

    public function lampiran(Absensi $absensi)
    {
        return $this->serveAbsensiFile($absensi->foto);
    }

    public function kamera(Request $request, Absensi $absensi)
    {
        $tipe = $request->query('tipe');
        if ($tipe === 'pulang') {
            $photo = $absensi->foto_pulang ?: $absensi->foto_kamera ?: $absensi->foto_masuk;
        } elseif ($tipe === 'masuk') {
            $photo = $absensi->foto_masuk ?: $absensi->foto_kamera ?: $absensi->foto_pulang;
        } else {
            $photo = $absensi->foto_kamera ?: $absensi->foto_masuk ?: $absensi->foto_pulang;
        }

        return $this->serveAbsensiFile($photo);
    }

    public function sertifikat(string $slug)
    {
        $user = User::all()->first(function (User $user) use ($slug) {
            return Str::slug($user->name) === $slug;
        });

        if (! $user || ! $user->tanggal_selesai_magang || $user->tanggal_selesai_magang->isFuture()) {
            abort(404);
        }

        $uploadedTemplate = CertificateTemplate::renderUploaded($user);
        if ($uploadedTemplate) {
            return response($uploadedTemplate);
        }

        return view('sertifikat.show', [
            'user' => $user,
            'certificate' => CertificatePayload::forUser($user),
            'assets' => CertificatePayload::assets(),
            'pdfMode' => false,
        ]);
    }

    private function storeAbsensiFile($file, int $userId, string $folder): string
    {
        $relativeDir = 'uploads/absensi/'.$folder;
        $uploadDir = public_path($relativeDir);

        File::ensureDirectoryExists($uploadDir, 0755, true);

        $extension = strtolower($file->extension() ?: $file->getClientOriginalExtension() ?: 'jpg');
        $filename = now(config('app.timezone'))->format('Ymd_His')
            .'_'.$userId
            .'_'.Str::random(10)
            .'.'.$extension;

        $file->move($uploadDir, $filename);

        return $relativeDir.'/'.$filename;
    }

    private function serveAbsensiFile(?string $path)
    {
        if (! $path) {
            abort(404);
        }

        // 1. Check in public direct (e.g. uploads/absensi/...)
        $publicTarget = public_path($path);
        if (File::isFile($publicTarget)) {
            return response()->file($publicTarget);
        }

        // 2. Check in Storage public disk (e.g. absensi/masuk/...)
        $cleanStoragePath = Str::after($path, 'storage/');
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($cleanStoragePath)) {
            return response()->file(\Illuminate\Support\Facades\Storage::disk('public')->path($cleanStoragePath));
        }

        // 3. Fallback check raw storage_path
        $rawStorage = storage_path('app/public/' . ltrim($path, '/\\'));
        if (File::isFile($rawStorage)) {
            return response()->file($rawStorage);
        }

        abort(404);
    }

    private function joinTaskForUser(int $taskId, int $userId): void
    {
        $task = ProjectTask::with('project.members')->findOrFail($taskId);

        if (! $task->project->members->contains('id', $userId)) {
            $task->project->members()->attach($userId);
        }

        $now = now(config('app.timezone'));

        if (! $task->user_id) {
            $hasActiveTask = ProjectTask::where('user_id', $userId)
                ->whereIn('status', ['sedang_dikerjakan', 'review', 'revision'])
                ->exists();
            if ($hasActiveTask) {
                throw new \Exception('Anda hanya dapat mengambil satu tugas dalam satu waktu. Selesaikan tugas aktif Anda terlebih dahulu.');
            }

            $task->update([
                'user_id' => $userId,
                'status' => 'sedang_dikerjakan',
            ]);

            ActivityLog::create([
                'user_id' => $userId,
                'project_id' => $task->project_id,
                'aktivitas' => User::find($userId)->name.' mengambil tugas saat absen masuk: '.$task->judul,
            ]);
        }

        ProjectTaskParticipant::firstOrCreate(
            ['task_id' => $task->id, 'user_id' => $userId],
            [
                'joined_at' => $now,
                'status' => 'joined',
                'contribution_percentage' => 100.00,
            ]
        );

        $task->recalculateModuleProgress();
    }

    private function recalculateTaskContribution(ProjectTask $task): void
    {
        $count = max($task->participants->count(), 1);
        $share = round(100 / $count, 2);

        foreach ($task->participants as $participant) {
            $participant->update(['contribution_percentage' => $share]);
        }
    }

    /**
     * UPSERT Jurnal Harian Peserta
     */
    public function storeJurnal(Request $request)
    {
        $userId = Auth::id();
        $magangApp = MagangApplication::where('user_id', $userId)->latest()->first();

        if (!$magangApp) {
            return redirect()->back()->with('error_swal', 'Data magang tidak ditemukan.');
        }

        $today = Carbon::today(config('app.timezone'))->toDateString();
        $absensi = Absensi::where('user_id', $userId)->where('tanggal', $today)->first();

        if (!$absensi || !in_array($absensi->status, ['hadir', 'wfh'])) {
            return redirect()->back()->with('error_swal', 'Laporan harian hanya dapat diisi jika Anda melakukan absen masuk WFO/WFH.');
        }

        $request->validate([
            'hasil_pekerjaan' => 'required|string',
            'kendala' => 'nullable|string',
            'file_lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:5120',
            'file_pekerjaan' => 'nullable|file|mimes:zip,rar,pdf,docx,xlsx|max:10240',
        ]);

        $jurnal = Jurnal::firstOrNew([
            'magang_application_id' => $magangApp->id,
            'tanggal' => $today,
        ]);

        $jurnal->kegiatan = $request->input('hasil_pekerjaan'); // Legacy fallback
        $jurnal->hasil_pekerjaan = $request->input('hasil_pekerjaan');
        $jurnal->kendala = $request->input('kendala');

        if ($request->hasFile('file_lampiran')) {
            $jurnal->file_lampiran = $this->storeAbsensiFile($request->file('file_lampiran'), $userId, 'jurnal_lampiran');
        }

        if ($request->hasFile('file_pekerjaan')) {
            $jurnal->file_pekerjaan = $this->storeAbsensiFile($request->file('file_pekerjaan'), $userId, 'jurnal_pekerjaan');
        }

        $jurnal->save();

        return redirect()->back()->with('success_swal', 'Laporan harian berhasil disimpan.');
    }
}
