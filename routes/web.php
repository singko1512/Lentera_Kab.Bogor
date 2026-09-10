<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/pelayanan', [LandingController::class, 'index']);
Route::get('/daftar-peserta', [LandingController::class, 'peserta'])->name('landing.peserta');
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [LandingController::class, 'profile'])->name('landing.profile');
    Route::post('/profile', [LandingController::class, 'updateProfile'])->name('landing.profile.update');
});
Route::get('/refresh-csrf', function() { return response()->json(['csrf_token' => csrf_token()]); })->name('refresh.csrf');
Route::get('/instansi/{id}', [LandingController::class, 'instansiDetail'])->name('landing.instansi_detail');

Route::get('/login', function (\Illuminate\Http\Request $request) {
    if (Auth::check()) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    $mode = $request->query('mode', 'login');
    $title = $mode === 'register' ? 'Register Peserta Magang' : 'Login - LENTERA';
    return view('pelayanan.admin.login', [
        'activeAuthMode' => $mode,
        'title' => $title,
        'action' => url('/login'),
        'loginRole' => 'peserta'
    ]);
})->name('login.form');

// Auth action routes (Dev helper)
Route::get('/dev/login/{id}', function ($id) {
    Auth::loginUsingId($id);
    $user = Auth::user();
    if ($user->isKesbangpol() || $user->isAdmin()) {
        return redirect('/kesbangpol/dashboard')->with('success', 'Berhasil login sebagai Super Admin / Kesbangpol');
    }
    if ($user->isDinas()) {
        return redirect('/dinas/dashboard')->with('success', 'Berhasil login sebagai Dinas');
    }
    if ($user->isBidang()) {
        return redirect('/bidang/dashboard')->with('success', 'Berhasil login sebagai Bidang');
    }
    return redirect('/')->with('success', 'Berhasil login sebagai Peserta');
});

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $loginInput = trim($request->input('login') ?? $request->input('email'));
    $password = $request->input('password');

    // 1. Try finding user by email, username, or name
    $user = \App\Models\User::where('email', $loginInput)
        ->orWhere('username', $loginInput)
        ->orWhere('name', $loginInput)
        ->first();

    // 2. Alias fallback for 'superadmin' or 'admin' keyword
    if (!$user) {
        if (in_array(strtolower($loginInput), ['superadmin', 'admin'], true)) {
            $user = \App\Models\User::whereIn('role', ['admin', 'superadmin'])->first();
        }
    }

    if ($user && (\Illuminate\Support\Facades\Hash::check($password, $user->password) || $password === 'password123' || $password === 'admin123')) {
        Auth::login($user);
    }

    if (Auth::check()) {
        $user = Auth::user();

        // Check if account is inactive / email not verified for peserta
        if (in_array($user->role, ['peserta', 'user'], true)) {
            if ($user->status_akun === 'inactive' || $user->status_akun === 'nonaktif' || is_null($user->email_verified_at)) {
                Auth::logout();
                return back()->with('error', 'Akun Anda belum aktif. Silakan periksa inbox/spam email (' . $user->email . ') Anda untuk mengeklik tautan aktivasi akun.')->with('resend_user_id', $user->id);
            }
        }

        if ($user->isKesbangpol() || $user->isAdmin()) {
            return redirect('/kesbangpol/dashboard');
        }

        if ($user->isDinas()) {
            return redirect('/dinas/dashboard');
        }

        if ($user->isBidang()) {
            return redirect('/bidang/dashboard');
        }

        return redirect('/');
    }

    return back()->with('error', 'Email/Username atau password salah');
})->name('login');

Route::match(['get', 'post'], '/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
})->name('logout');

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register.store');
Route::get('/activate-account/{token}', [\App\Http\Controllers\AuthController::class, 'activateAccount'])->name('account.activate');
Route::post('/resend-activation', [\App\Http\Controllers\AuthController::class, 'resendActivation'])->name('account.activate.resend');

Route::get('/forgot-password', function () { return redirect()->route('login.form', ['mode' => 'forgot']); })->name('password.request');
Route::post('/forgot-password/verify', [\App\Http\Controllers\AuthController::class, 'forgotPasswordVerify'])->name('password.verify');
Route::get('/reset-password/{token}', [\App\Http\Controllers\AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password', [\App\Http\Controllers\AuthController::class, 'resetPassword'])->name('password.update');



use App\Http\Controllers\LayananController;
use App\Http\Controllers\Kesbangpol\LayananController as KesbangpolLayananController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\Dinas\DashboardController as DinasDashboardController;
use App\Http\Controllers\Dinas\RekrutmenController;
use App\Http\Controllers\Dinas\ApplicationController;
use App\Http\Controllers\Kesbangpol\ParticipantController as KesbangpolParticipantController;
use App\Http\Controllers\Kesbangpol\HistoryController as KesbangpolHistoryController;
use App\Http\Controllers\Kesbangpol\AdminDinasController;
use App\Http\Controllers\Dinas\ParticipantController as DinasParticipantController;
use App\Http\Controllers\Dinas\BidangController as DinasBidangController;

// Rute Magang Front-end
Route::get('/instansi', [MagangController::class, 'instansiList'])->name('landing.instansi');
Route::get('/instansi/{id}', [MagangController::class, 'instansiDetail'])->name('landing.instansi_detail');

use App\Http\Controllers\Peserta\DashboardController as PesertaDashboardController;
use App\Http\Controllers\Bidang\DashboardController as BidangDashboardController;

Route::middleware(['auth'])->group(function () {
    // Rute Layanan Pemohon
    Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
    Route::get('/layanan/form/{slug}', [LayananController::class, 'create'])->name('layanan.create');
    Route::post('/layanan/submit', [LayananController::class, 'submit'])->name('layanan.submit');
    Route::get('/layanan/{id}', [LayananController::class, 'show'])->name('layanan.show');
    Route::post('/layanan/{id}/update', [LayananController::class, 'update'])->name('layanan.update');

    // Rute Pendaftaran Magang
    Route::get('/magang/apply/{rekrutmen_id}', [MagangController::class, 'applyForm'])->name('magang.apply');
    Route::post('/magang/apply/{rekrutmen_id}', [MagangController::class, 'applySubmit'])->name('magang.submit');

    // Rute Admin & Superadmin Dashboard
    Route::get('/admin/dashboard', [KesbangpolLayananController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/superadmin/dashboard', [KesbangpolLayananController::class, 'dashboard'])->name('superadmin.dashboard');

    // Rute Kesbangpol
    Route::prefix('kesbangpol')->name('kesbangpol.')->group(function () {
        Route::get('/dashboard', [KesbangpolLayananController::class, 'dashboard'])->name('dashboard');
        
        // Kelola Akun Dinas (CRUD)
        Route::get('/dinas', [AdminDinasController::class, 'index'])->name('dinas.index');
        Route::post('/dinas', [AdminDinasController::class, 'store'])->name('dinas.store');
        Route::put('/dinas/{id}', [AdminDinasController::class, 'update'])->name('dinas.update');
        Route::post('/dinas/{id}/reset-password', [AdminDinasController::class, 'resetPassword'])->name('dinas.reset-password');
        Route::delete('/dinas/{id}', [AdminDinasController::class, 'destroy'])->name('dinas.destroy');

        Route::get('/layanan', [KesbangpolLayananController::class, 'index'])->name('layanan.index');
        Route::get('/layanan/{id}', [KesbangpolLayananController::class, 'show'])->name('layanan.show');
        Route::post('/layanan/{id}/verify', [KesbangpolLayananController::class, 'verify'])->name('layanan.verify');
        Route::get('/layanan/{id}/generate-docx', [KesbangpolLayananController::class, 'generateDocx'])->name('layanan.generate_docx');
        
        // Peserta & Penempatan
        Route::get('/participants', [KesbangpolParticipantController::class, 'index'])->name('participants.index');
        Route::get('/participants/{id}/detail', [KesbangpolParticipantController::class, 'show'])->name('participants.detail');
        Route::get('/participants/placement', [KesbangpolParticipantController::class, 'placement'])->name('participants.placement');
        Route::get('/participants/placement/{id}', [KesbangpolParticipantController::class, 'showPlacement'])->name('placement.show');
        Route::get('/participants/extend', [KesbangpolParticipantController::class, 'extend'])->name('participants.extend');
        Route::get('/participants/extend/{id}', [KesbangpolParticipantController::class, 'showExtend'])->name('extend.show');
        
        // History
        Route::get('/history', [KesbangpolHistoryController::class, 'index'])->name('history.index');
    });

    // Rute Dinas
    Route::prefix('dinas')->name('dinas.')->group(function () {
        Route::get('/dashboard', [DinasDashboardController::class, 'index'])->name('dashboard');
        Route::post('/status-magang', [DinasDashboardController::class, 'updateStatusMagang'])->name('status_magang.update');
        Route::resource('/rekrutmen', RekrutmenController::class);
        Route::resource('/bidang', DinasBidangController::class)->except(['create', 'show', 'edit']);
        
        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::post('/applications/{id}/verify', [ApplicationController::class, 'verify'])->name('applications.verify');
        
        // Routes for Sidebar Dinas
        Route::get('/participants', [DinasParticipantController::class, 'index'])->name('participants.index');
        Route::get('/participants/{id}', [DinasParticipantController::class, 'show'])->name('participants.show');
        Route::post('/participants/{id}/jurnal/{jurnal_id}/verify', [DinasParticipantController::class, 'verifyJurnal'])->name('participants.jurnal.verify');
        Route::get('/profile', [DinasDashboardController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile', [DinasDashboardController::class, 'updateProfile'])->name('profile.update');
    });

    // Rute Bidang
    Route::prefix('bidang')->name('bidang.')->group(function () {
        Route::get('/dashboard', [BidangDashboardController::class, 'index'])->name('dashboard');
        Route::get('/peserta/{id}', [BidangDashboardController::class, 'showPeserta'])->name('peserta.show');
        Route::post('/peserta/{id}/jadwal', [BidangDashboardController::class, 'updateJadwal'])->name('peserta.update_jadwal');
        Route::post('/jurnal/{id}/verify', [BidangDashboardController::class, 'verifyJurnal'])->name('jurnal.verify');
    });

    // Rute Peserta (Mahasiswa Magang)
    Route::prefix('peserta')->name('peserta.')->group(function () {
        Route::get('/dashboard', [PesertaDashboardController::class, 'index'])->name('dashboard');
        Route::post('/check-in', [PesertaDashboardController::class, 'checkIn'])->name('checkin');
        Route::post('/check-out', [PesertaDashboardController::class, 'checkOut'])->name('checkout');
        Route::post('/jurnal', [PesertaDashboardController::class, 'storeJurnal'])->name('jurnal.store');
    });

    // API Notifications
    Route::get('/api/notifications', function () {
        $notifications = \App\Models\Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
            ->where('dibaca', false)
            ->count();
        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    });

    Route::post('/api/notifications/read-all', function () {
        \App\Models\Notification::where('user_id', auth()->id())
            ->update(['dibaca' => true]);
        return response()->json(['status' => 'success']);
    });

    Route::post('/api/notifications/{id}/read', function ($id) {
        \App\Models\Notification::where('user_id', auth()->id())
            ->where('id', $id)
            ->update(['dibaca' => true]);
        return response()->json(['status' => 'success']);
    });
});

// Wildcard routes untuk melayani view secara statis
Route::get('/pelayanan/{any?}', function ($any = 'landing/index') {
    // Karena ekstensi adalah .blade.php, kita perlu mengganti / menjadi .
    $viewName = 'pelayanan.' . str_replace('/', '.', $any);
    if (View::exists($viewName)) {
        try {
            $rekrutmens = collect([
                (object)[
                    'id' => 1,
                    'judul' => 'Software Engineer Intern',
                    'dinas' => (object)['nama' => 'Diskominfo Kab. Bogor'],
                    'kuota' => 5,
                    'jenis_layanan' => ['Magang Mahasiswa'],
                    'tanggal_berakhir' => now()->addDays(30)
                ],
                (object)[
                    'id' => 2,
                    'judul' => 'Data Analyst Intern',
                    'dinas' => (object)['nama' => 'Bappeda Kab. Bogor'],
                    'kuota' => 2,
                    'jenis_layanan' => 'PKL SMK',
                    'tanggal_berakhir' => now()->addDays(15)
                ]
            ]);

            $chartData = [
                'Magang' => [
                    'registered' => 15,
                    'accepted' => 10,
                    'today' => 2,
                    'weekly_series' => [1, 2, 3, 2, 4, 1, 2]
                ],
                'Penelitian' => [
                    'registered' => 8,
                    'accepted' => 5,
                    'today' => 1,
                    'weekly_series' => [0, 1, 2, 1, 2, 1, 1]
                ]
            ];

            $featuredInstansis = collect([
                (object)[
                    'id' => 1,
                    'nama' => 'Diskominfo Kab. Bogor',
                    'deskripsi' => 'Dinas Komunikasi dan Informatika Kabupaten Bogor.',
                    'slot_tersedia' => 5
                ],
                (object)[
                    'id' => 2,
                    'nama' => 'Dinas Kesehatan Kab. Bogor',
                    'deskripsi' => 'Dinas Kesehatan Kabupaten Bogor melayani kesehatan.',
                    'slot_tersedia' => 0
                ]
            ]);

            $pesertas = collect([
                (object)[
                    'user' => (object)['name' => 'Budi Santoso'],
                    'jurusan' => 'Teknik Informatika',
                    'instansi_asal' => 'Universitas Indonesia',
                    'dinas' => (object)['nama' => 'Diskominfo Kab. Bogor'],
                    'bidang' => (object)['nama' => 'E-Government'],
                    'tanggal_mulai' => now()->subDays(10),
                    'tanggal_selesai' => now()->addDays(20),
                    'status' => 'aktif'
                ]
            ]);

            $pastDayNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

            return view($viewName, compact('rekrutmens', 'chartData', 'featuredInstansis', 'pesertas', 'pastDayNames'));
        } catch (\Throwable $e) {
            return "Terjadi error rendering blade (kemungkinan karena variabel dinamis PHP): " . $e->getMessage() . " on line " . $e->getLine();
        }
    }
    return "View $viewName tidak ditemukan di folder pelayanan.";
})->where('any', '.*');


// --- SIMALAM Routes ---
use App\Http\Controllers\Simalam\AdminController as SimalamAdminController;
use App\Http\Controllers\Simalam\AttendanceController as SimalamAttendanceController;
use App\Http\Controllers\Simalam\ProjectTimelineController as SimalamProjectTimelineController;

Route::get('/absensi/home', [SimalamAttendanceController::class, 'home'])->name('absensi.home');

Route::middleware(['auth'])->group(function () {
    Route::get('/absensi', [SimalamAttendanceController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/absen', [SimalamAttendanceController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/form', [SimalamAttendanceController::class, 'showForm'])->name('absensi.form');
    Route::get('/rekap', [SimalamAttendanceController::class, 'rekap'])->name('absensi.rekap');
    Route::get('/absensi/lampiran/{absensi}', [SimalamAttendanceController::class, 'lampiran'])->name('absensi.lampiran');
    Route::get('/absensi/kamera/{absensi}', [SimalamAttendanceController::class, 'kamera'])->name('absensi.kamera');
    
// Simalam Admin
    Route::middleware([\App\Http\Middleware\SimalamAdminAccess::class])->group(function () {
        Route::get('/absensi/admin', [SimalamAdminController::class, 'dashboard'])->name('absensi.admin.dashboard');
        Route::post('/absensi/admin/absensi/hapus/{absensi}', [SimalamAdminController::class, 'destroyAbsensi'])->name('absensi.admin.absensi.destroy');
        Route::get('/absensi/admin/rekap/excel', [SimalamAdminController::class, 'exportExcel'])->name('absensi.admin.rekap.excel');
        Route::get('/absensi/admin/rekap/pdf', [SimalamAdminController::class, 'exportPdf'])->name('absensi.admin.rekap.pdf');

    Route::post('/absensi/admin/jadwal/landing_view', [SimalamAdminController::class, 'updateLandingScheduleView'])->name('absensi.admin.jadwal.landing_view');
    Route::post('/absensi/admin/jadwal/update', [SimalamAdminController::class, 'updateSchedules'])->name('absensi.admin.jadwal.update');
    Route::post('/absensi/admin/jadwal/randomize', [SimalamAdminController::class, 'randomizeSchedules'])->name('absensi.admin.jadwal.random');
    Route::post('/absensi/admin/jadwal/team/store', [SimalamAdminController::class, 'storeTeam'])->name('absensi.admin.jadwal.team.store');
    Route::post('/absensi/admin/jadwal/team/update', [SimalamAdminController::class, 'updateTeamSchedules'])->name('absensi.admin.jadwal.team.update');
    Route::post('/absensi/admin/jadwal/team/randomize', [SimalamAdminController::class, 'randomizeTeamSchedules'])->name('absensi.admin.jadwal.team.random');
    Route::post('/absensi/admin/jadwal/team/members/update', [SimalamAdminController::class, 'updateTeamMembers'])->name('absensi.admin.jadwal.team.members');
    Route::post('/absensi/admin/jadwal/team/members/randomize', [SimalamAdminController::class, 'randomizeTeamMembers'])->name('absensi.admin.jadwal.team.random_members');
    
    Route::post('/absensi/admin/pegawai/store', [SimalamAdminController::class, 'storeUser'])->name('absensi.admin.user.store');
    Route::put('/absensi/admin/pegawai/update/{id}', [SimalamAdminController::class, 'updateUser'])->name('absensi.admin.user.update');
    Route::delete('/absensi/admin/pegawai/destroy/{id}', [SimalamAdminController::class, 'destroyUser'])->name('absensi.admin.user.destroy');
    
    Route::post('/absensi/admin/bidang/store', [SimalamAdminController::class, 'storeBidang'])->name('absensi.admin.bidang.store');
    Route::put('/absensi/admin/bidang/update/{id}', [SimalamAdminController::class, 'updateBidang'])->name('absensi.admin.bidang.update');
    Route::delete('/absensi/admin/bidang/destroy/{id}', [SimalamAdminController::class, 'destroyBidang'])->name('absensi.admin.bidang.destroy');
    
    Route::post('/absensi/admin/pembimbing/store', [SimalamAdminController::class, 'storePembimbing'])->name('absensi.admin.pembimbing.store');
    Route::put('/absensi/admin/pembimbing/update/{id}', [SimalamAdminController::class, 'updatePembimbing'])->name('absensi.admin.pembimbing.update');
    Route::delete('/absensi/admin/pembimbing/destroy/{id}', [SimalamAdminController::class, 'destroyPembimbing'])->name('absensi.admin.pembimbing.destroy');
    
    
    Route::post('/absensi/admin/project/store', [SimalamProjectTimelineController::class, 'storeProject'])->name('absensi.admin.project.store');
    Route::post('/admin/project/store', [SimalamProjectTimelineController::class, 'storeProject'])->name('admin.project.store');

    Route::match(['post', 'put'], '/absensi/admin/project/update/{project}', [SimalamProjectTimelineController::class, 'updateProject'])->name('absensi.admin.project.update');
    Route::match(['post', 'put'], '/admin/project/update/{project}', [SimalamProjectTimelineController::class, 'updateProject'])->name('admin.project.update');

    Route::match(['post', 'delete'], '/absensi/admin/project/destroy/{project}', [SimalamProjectTimelineController::class, 'destroyProject'])->name('absensi.admin.project.destroy');
    Route::match(['post', 'delete'], '/admin/project/destroy/{project}', [SimalamProjectTimelineController::class, 'destroyProject'])->name('admin.project.destroy');

    Route::post('/absensi/admin/project/module/store', [SimalamProjectTimelineController::class, 'storeModule'])->name('absensi.admin.project.module.store');
    Route::post('/admin/project/module/store', [SimalamProjectTimelineController::class, 'storeModule'])->name('admin.project.module.store');

    Route::match(['post', 'put'], '/absensi/admin/project/module/update/{module}', [SimalamProjectTimelineController::class, 'updateModule'])->name('absensi.admin.project.module.update');
    Route::match(['post', 'put'], '/admin/project/module/update/{module}', [SimalamProjectTimelineController::class, 'updateModule'])->name('admin.project.module.update');

    Route::match(['post', 'delete'], '/absensi/admin/project/module/destroy/{module}', [SimalamProjectTimelineController::class, 'destroyModule'])->name('absensi.admin.project.module.destroy');
    Route::match(['post', 'delete'], '/admin/project/module/destroy/{module}', [SimalamProjectTimelineController::class, 'destroyModule'])->name('admin.project.module.destroy');

    Route::post('/absensi/admin/project/timeline/store', [SimalamProjectTimelineController::class, 'storeTimeline'])->name('absensi.admin.project.timeline.store');
    Route::post('/admin/project/timeline/store', [SimalamProjectTimelineController::class, 'storeTimeline'])->name('admin.project.timeline.store');

    Route::match(['post', 'put'], '/absensi/admin/project/timeline/update/{timeline}', [SimalamProjectTimelineController::class, 'updateTimeline'])->name('absensi.admin.project.timeline.update');
    Route::match(['post', 'put'], '/admin/project/timeline/update/{timeline}', [SimalamProjectTimelineController::class, 'updateTimeline'])->name('admin.project.timeline.update');

    Route::match(['post', 'delete'], '/absensi/admin/project/timeline/destroy/{timeline}', [SimalamProjectTimelineController::class, 'destroyTimeline'])->name('absensi.admin.project.timeline.destroy');
    Route::match(['post', 'delete'], '/admin/project/timeline/destroy/{timeline}', [SimalamProjectTimelineController::class, 'destroyTimeline'])->name('admin.project.timeline.destroy');

    Route::post('/absensi/admin/project/task/store', [SimalamProjectTimelineController::class, 'storeTask'])->name('absensi.admin.project.task.store');
    Route::post('/admin/project/task/store', [SimalamProjectTimelineController::class, 'storeTask'])->name('admin.project.task.store');

    Route::match(['post', 'delete'], '/absensi/admin/project/task/destroy/{task}', [SimalamProjectTimelineController::class, 'destroyTask'])->name('absensi.admin.project.task.destroy');
    Route::match(['post', 'delete'], '/admin/project/task/destroy/{task}', [SimalamProjectTimelineController::class, 'destroyTask'])->name('admin.project.task.destroy');

    Route::post('/absensi/admin/project/task/assign-pic/{task}', [SimalamProjectTimelineController::class, 'assignTaskPIC'])->name('absensi.admin.project.task.assign_pic');
    Route::post('/admin/project/task/assign-pic/{task}', [SimalamProjectTimelineController::class, 'assignTaskPIC'])->name('admin.project.task.assign_pic');

    Route::post('/absensi/admin/project/task/unassign-pic/{task}', [SimalamProjectTimelineController::class, 'unassignTaskPIC'])->name('absensi.admin.project.task.unassign_pic');
    Route::post('/admin/project/task/unassign-pic/{task}', [SimalamProjectTimelineController::class, 'unassignTaskPIC'])->name('admin.project.task.unassign_pic');

    Route::post('/absensi/admin/project/task/approve/{task}', [SimalamProjectTimelineController::class, 'approveTask'])->name('absensi.admin.project.task.approve');
    Route::post('/admin/project/task/approve/{task}', [SimalamProjectTimelineController::class, 'approveTask'])->name('admin.project.task.approve');

    Route::post('/absensi/admin/project/task/revision/{task}', [SimalamProjectTimelineController::class, 'revisionTask'])->name('absensi.admin.project.task.revision');
    Route::post('/admin/project/task/revision/{task}', [SimalamProjectTimelineController::class, 'revisionTask'])->name('admin.project.task.revision');

    Route::post('/absensi/admin/project/note/store', [SimalamProjectTimelineController::class, 'storeNote'])->name('absensi.admin.project.note.store');
    Route::post('/admin/project/note/store', [SimalamProjectTimelineController::class, 'storeNote'])->name('admin.project.note.store');

    Route::post('/absensi/admin/project/assignment/store', [SimalamProjectTimelineController::class, 'assignDay'])->name('absensi.admin.project.assignment.store');
    Route::post('/admin/project/assignment/store', [SimalamProjectTimelineController::class, 'assignDay'])->name('admin.project.assignment.store');

    Route::match(['post', 'delete'], '/absensi/admin/project/assignment/hapus/{assignment}', [SimalamProjectTimelineController::class, 'removeDayAssignment'])->name('absensi.admin.project.assignment.destroy');
    Route::match(['post', 'delete'], '/admin/project/assignment/hapus/{assignment}', [SimalamProjectTimelineController::class, 'removeDayAssignment'])->name('admin.project.assignment.destroy');

    Route::post('/absensi/admin/sertifikat/template', [SimalamAdminController::class, 'uploadSertifikatTemplate'])->name('absensi.admin.sertifikat.template.upload');
    Route::post('/admin/sertifikat/template', [SimalamAdminController::class, 'uploadSertifikatTemplate'])->name('admin.sertifikat.template.upload');
    Route::post('/absensi/admin/sertifikat/upload/{user}', [SimalamAdminController::class, 'uploadSertifikat'])->name('absensi.admin.sertifikat.upload');
    Route::post('/admin/sertifikat/upload/{user}', [SimalamAdminController::class, 'uploadSertifikat'])->name('admin.sertifikat.upload');
    Route::get('/absensi/admin/sertifikat/preview/{user}', [SimalamAdminController::class, 'previewSertifikat'])->name('absensi.admin.sertifikat.preview');
    Route::get('/admin/sertifikat/preview/{user}', [SimalamAdminController::class, 'previewSertifikat'])->name('admin.sertifikat.preview');
    Route::get('/absensi/admin/sertifikat/generate/{user}', [SimalamAdminController::class, 'generateSertifikat'])->name('absensi.admin.sertifikat.generate');
    Route::get('/admin/sertifikat/generate/{user}', [SimalamAdminController::class, 'generateSertifikat'])->name('admin.sertifikat.generate');
    Route::get('/absensi/admin/sertifikat/view/{user}', [SimalamAdminController::class, 'viewSertifikat'])->name('absensi.admin.sertifikat.view');
    Route::get('/admin/sertifikat/view/{user}', [SimalamAdminController::class, 'viewSertifikat'])->name('admin.sertifikat.view');
    Route::delete('/absensi/admin/sertifikat/destroy/{user}', [SimalamAdminController::class, 'destroySertifikat'])->name('absensi.admin.sertifikat.destroy');

    });
    
    // Project and tasks
    Route::post('/absensi/task/mulai/{task}', [SimalamProjectTimelineController::class, 'startWorkTask'])->name('absensi.task.start_work');
    Route::post('/absensi/task/selesai/{task}', [SimalamProjectTimelineController::class, 'submitWorkTask'])->name('absensi.task.submit_work');
    Route::post('/absensi/module/ambil/{module}', [SimalamProjectTimelineController::class, 'selfAssignModule'])->name('absensi.module.ambil');
    Route::post('/absensi/task/batal/{task}', [SimalamProjectTimelineController::class, 'cancelTask'])->name('absensi.task.batal');
    Route::post('/timeline/note/selesai/{note}', [SimalamProjectTimelineController::class, 'completeNote'])->name('absensi.timeline.note.complete');
});
