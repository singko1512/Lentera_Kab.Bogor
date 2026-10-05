<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
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
use App\Http\Controllers\Peserta\DashboardController as PesertaDashboardController;
use App\Http\Controllers\Bidang\DashboardController as BidangDashboardController;
use App\Http\Controllers\Simalam\AdminController as SimalamAdminController;
use App\Http\Controllers\Simalam\AttendanceController as SimalamAttendanceController;
use App\Http\Controllers\Simalam\ProjectTimelineController as SimalamProjectTimelineController;

/*
|--------------------------------------------------------------------------
| Public & Landing Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/pelayanan', [LandingController::class, 'index']);
Route::get('/daftar-peserta', [LandingController::class, 'peserta'])->name('landing.peserta');
Route::get('/refresh-csrf', function() { return response()->json(['csrf_token' => csrf_token()]); })->name('refresh.csrf');
Route::get('/instansi', [LandingController::class, 'instansiList'])->name('landing.instansi');
Route::get('/instansi/{id}', [LandingController::class, 'instansiDetail'])->name('landing.instansi_detail');
Route::get('/verifikasi-surat/{token}', [LandingController::class, 'verifikasiSurat'])->name('surat.verifikasi');
Route::get('/sertifikat/{slug}', function($slug) { return redirect()->route('absensi.admin.dashboard'); })->name('sertifikat.show');
Route::get('/absensi/home', [SimalamAttendanceController::class, 'home'])->name('absensi.home');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Throttled)
|--------------------------------------------------------------------------
*/
Route::get('/login', function (\Illuminate\Http\Request $request) {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'dinas') {
            return redirect()->route('dinas.dashboard');
        } elseif ($user->role === 'kesbangpol') {
            return redirect()->route('kesbangpol.dashboard');
        } elseif (in_array($user->role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'bidang') {
            return redirect()->route('bidang.dashboard');
        }
        return redirect()->route('peserta.dashboard');
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

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login');

Route::match(['get', 'post'], '/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
})->name('logout');

Route::get('/register', function () { return redirect()->route('login.form', ['mode' => 'register']); })->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.store');
Route::get('/activate-account/{token}', [AuthController::class, 'activateAccount'])->name('account.activate');
Route::post('/resend-activation', [AuthController::class, 'resendActivation'])->middleware('throttle:5,1')->name('account.activate.resend');

Route::get('/forgot-password', function () { return redirect()->route('login.form', ['mode' => 'forgot']); })->name('password.request');
Route::post('/forgot-password/verify', [AuthController::class, 'forgotPasswordVerify'])->middleware('throttle:5,1')->name('password.verify');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Authenticated Core Routes (All Authenticated Roles)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // User Profile
    Route::get('/profile', [LandingController::class, 'profile'])->name('landing.profile');
    Route::post('/profile', [LandingController::class, 'updateProfile'])->name('landing.profile.update');

    // Secure Document & Recommendation Letter PDF Delivery
    Route::get('/berkas/{permohonan}/{field}', [LayananController::class, 'previewBerkas'])->name('berkas.preview');
    Route::get('/surat-rekomendasi/pdf/{id}', [KesbangpolLayananController::class, 'downloadPdf'])->name('surat.pdf');

    // Simalam User Absensi & Attendance Attachments
    Route::get('/absensi', [SimalamAttendanceController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/absen', [SimalamAttendanceController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/form', [SimalamAttendanceController::class, 'showForm'])->name('absensi.form');
    Route::get('/rekap', [SimalamAttendanceController::class, 'rekap'])->name('absensi.rekap');
    Route::get('/absensi/lampiran/{absensi}', [SimalamAttendanceController::class, 'lampiran'])->name('absensi.lampiran');
    Route::get('/absensi/kamera/{absensi}', [SimalamAttendanceController::class, 'kamera'])->name('absensi.kamera');
    Route::post('/absensi/jurnal', [SimalamAttendanceController::class, 'storeJurnal'])->name('absensi.jurnal.store');

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

    // Legacy & Prototype Route Aliases
    Route::get('/participant/dashboard', function() { return redirect()->route('peserta.dashboard'); })->name('participant.dashboard');
    Route::post('/participant/profile/update', [LandingController::class, 'updateProfile'])->name('participant.profile.update');
    Route::get('/booking', function() { return redirect()->route('landing.instansi'); })->name('booking.index');
    Route::post('/booking', function() { return redirect()->route('landing.instansi'); })->name('booking.store');
    Route::get('/booking/search-users', function(\Illuminate\Http\Request $request) { 
        return response()->json(\App\Models\User::where('nama', 'like', '%'.$request->q.'%')->orWhere('name', 'like', '%'.$request->q.'%')->orWhere('email', 'like', '%'.$request->q.'%')->take(5)->get()); 
    })->name('booking.search_users');
    Route::get('/application/form', function() { return redirect()->route('layanan.index'); })->name('application.form');
    Route::post('/internship/store', function() { return redirect()->route('peserta.dashboard')->with('success', 'Pendaftaran magang berhasil diajukan.'); })->name('internship.store');
});

/*
|--------------------------------------------------------------------------
| Role: User / Peserta
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role.user'])->group(function () {
    // Rute Layanan Pemohon
    Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
    Route::get('/layanan/form/{slug}', [LayananController::class, 'create'])->name('layanan.create');
    Route::post('/layanan/submit', [LayananController::class, 'submit'])->name('layanan.submit');
    Route::get('/layanan/{id}', [LayananController::class, 'show'])->name('layanan.show');
    Route::post('/layanan/{id}/update', [LayananController::class, 'update'])->name('layanan.update');
    Route::delete('/layanan/{id}', [LayananController::class, 'destroy'])->name('layanan.destroy');
    Route::post('/layanan/{id}/delete', [LayananController::class, 'destroy']);

    // Rute Pendaftaran Magang
    Route::get('/magang/apply/{rekrutmen_id}', [MagangController::class, 'applyForm'])->name('magang.apply');
    Route::post('/magang/apply/{rekrutmen_id}', [MagangController::class, 'applySubmit'])->name('magang.submit');

    // Rute Peserta (Mahasiswa Magang)
    Route::prefix('peserta')->name('peserta.')->group(function () {
        Route::get('/dashboard', [PesertaDashboardController::class, 'index'])->name('dashboard');
        Route::post('/check-in', [PesertaDashboardController::class, 'checkIn'])->name('checkin');
        Route::post('/check-out', [PesertaDashboardController::class, 'checkOut'])->name('checkout');
        Route::post('/jurnal', [PesertaDashboardController::class, 'storeJurnal'])->name('jurnal.store');
    });

    // Project and tasks (Peserta)
    Route::post('/absensi/task/mulai/{task}', [SimalamProjectTimelineController::class, 'startWorkTask'])->name('absensi.task.start_work');
    Route::post('/absensi/task/selesai/{task}', [SimalamProjectTimelineController::class, 'submitWorkTask'])->name('absensi.task.submit_work');
    Route::post('/absensi/module/ambil/{module}', [SimalamProjectTimelineController::class, 'selfAssignModule'])->name('absensi.module.ambil');
    Route::post('/absensi/task/ambil/{task}', [SimalamProjectTimelineController::class, 'selfAssignTask'])->name('absensi.task.ambil');
    Route::post('/absensi/task/batal/{task}', [SimalamProjectTimelineController::class, 'cancelTask'])->name('absensi.task.batal');
    Route::post('/timeline/note/selesai/{note}', [SimalamProjectTimelineController::class, 'completeNote'])->name('absensi.timeline.note.complete');
});

/*
|--------------------------------------------------------------------------
| Role: Kesbangpol (Bakesbangpol & Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role.kesbangpol'])->group(function () {
    Route::prefix('kesbangpol')->name('kesbangpol.')->group(function () {
        Route::get('/dashboard', [KesbangpolLayananController::class, 'dashboard'])->name('dashboard');

        Route::get('/layanan', [KesbangpolLayananController::class, 'index'])->name('layanan.index');
        Route::get('/layanan/{id}', [KesbangpolLayananController::class, 'show'])->name('layanan.show');
        Route::post('/layanan/{id}/verify', [KesbangpolLayananController::class, 'verify'])->name('layanan.verify');
        Route::put('/layanan/{id}/update-pemohon', [KesbangpolLayananController::class, 'updatePemohon'])->name('layanan.update_pemohon');
        Route::post('/layanan/{id}/upload-surat-final', [KesbangpolLayananController::class, 'uploadSuratFinal'])->name('layanan.upload_surat_final');
        Route::get('/layanan/{id}/generate-docx', [KesbangpolLayananController::class, 'generateDocx'])->name('layanan.generate_docx');
        Route::get('/layanan/{id}/generate-pdf', [KesbangpolLayananController::class, 'generateDraftPdf'])->name('layanan.generate_pdf');
        
        // Peserta & Penempatan
        Route::get('/participants', [KesbangpolParticipantController::class, 'index'])->name('participants.index');
        Route::get('/participants/{id}/detail', [KesbangpolParticipantController::class, 'show'])->name('participants.detail');
        Route::post('/participants/{id}/status', [KesbangpolParticipantController::class, 'updateStatusAccount'])->name('participants.status.update');
        Route::get('/participants/placement', [KesbangpolParticipantController::class, 'placement'])->name('participants.placement');
        Route::get('/participants/placement/{id}', [KesbangpolParticipantController::class, 'showPlacement'])->name('placement.show');
        Route::get('/participants/extend', [KesbangpolParticipantController::class, 'extend'])->name('participants.extend');
        Route::get('/participants/extend/{id}', [KesbangpolParticipantController::class, 'showExtend'])->name('extend.show');
        
        // History
        Route::get('/history', [KesbangpolHistoryController::class, 'index'])->name('history.index');
    });
});

/*
|--------------------------------------------------------------------------
| Role: Admin / Superadmin Only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role.admin'])->group(function () {
    Route::get('/admin/dashboard', [KesbangpolLayananController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/superadmin/dashboard', function(\Illuminate\Http\Request $request) {
        if (Auth::user()?->role !== 'superadmin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Super Admin.');
        }
        return app(KesbangpolLayananController::class)->dashboard($request);
    })->name('superadmin.dashboard');

    // Switch Instansi Context (Superadmin only)
    Route::post('/admin/switch-instansi', function(\Illuminate\Http\Request $request) {
        if (Auth::user()?->role !== 'superadmin') {
            abort(403, 'Hanya Super Admin yang dapat mengganti instansi.');
        }
        if ($request->filled('instansi_id')) {
            session(['superadmin_instansi_id' => $request->input('instansi_id')]);
        } else {
            session()->forget('superadmin_instansi_id');
        }
        return redirect()->back();
    })->name('admin.switch_instansi');

    // Kelola Akun Dinas (Kesbangpol)
    Route::prefix('kesbangpol')->name('kesbangpol.')->group(function () {
        Route::get('/dinas', [AdminDinasController::class, 'index'])->name('dinas.index');
        Route::post('/dinas', [AdminDinasController::class, 'store'])->name('dinas.store');
        Route::put('/dinas/{id}', [AdminDinasController::class, 'update'])->name('dinas.update');
        Route::post('/dinas/{id}/reset-password', [AdminDinasController::class, 'resetPassword'])->name('dinas.reset-password');
        Route::delete('/dinas/{id}', [AdminDinasController::class, 'destroy'])->name('dinas.destroy');
    });

    // Kelola Instansi / Dinas (Admin Simalam)
    Route::post('/admin/dinas/store', function(\Illuminate\Http\Request $request) {
        $request->validate(['nama' => 'required|string|max:255']);
        \App\Models\Dinas::create(['nama' => $request->nama, 'status_aktif' => true]);
        return redirect()->back()->with('success', 'Dinas berhasil ditambahkan.');
    })->name('admin.dinas.store');

    Route::delete('/admin/dinas/{id}', function($id) {
        \App\Models\Dinas::destroy($id);
        return redirect()->back()->with('success', 'Dinas berhasil dihapus.');
    })->name('admin.dinas.destroy');

    Route::post('/admin/dinas-user/store', [AdminDinasController::class, 'store'])->name('admin.dinas_user.store');
    Route::delete('/admin/dinas-user/{id}', [AdminDinasController::class, 'destroy'])->name('admin.dinas_user.destroy');
});

/*
|--------------------------------------------------------------------------
| Role: Dinas
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role.dinas'])->prefix('dinas')->name('dinas.')->group(function () {
    Route::get('/dashboard', [DinasDashboardController::class, 'index'])->name('dashboard');
    Route::post('/status-magang', [DinasDashboardController::class, 'updateStatusMagang'])->name('status_magang.update');
    Route::resource('/rekrutmen', RekrutmenController::class);
    Route::resource('/bidang', DinasBidangController::class)->except(['create', 'show', 'edit']);
    Route::post('/bidang/{id}/reset-password', [DinasBidangController::class, 'resetPassword'])->name('bidang.reset_password');
    
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{id}/verify', [ApplicationController::class, 'verify'])->name('applications.verify');
    
    // Routes for Sidebar Dinas
    Route::get('/participants', [DinasParticipantController::class, 'index'])->name('participants.index');
    Route::get('/participants/{id}', [DinasParticipantController::class, 'show'])->name('participants.show');
    Route::post('/participants/{id}/status', [DinasParticipantController::class, 'updateStatusAccount'])->name('participants.status.update');
    Route::post('/participants/{id}/penempatan', [DinasParticipantController::class, 'updatePenempatan'])->name('participants.penempatan.update');
    Route::post('/participants/{id}/surat', [DinasParticipantController::class, 'updateSurat'])->name('participants.surat.update');
    Route::post('/participants/{id}/surat/generate', [DinasParticipantController::class, 'generateSurat'])->name('participants.surat.generate');
    Route::post('/participants/{id}/jurnal/{jurnal_id}/verify', [DinasParticipantController::class, 'verifyJurnal'])->name('participants.jurnal.verify');
    Route::get('/profile', [DinasDashboardController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile', [DinasDashboardController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Role: Bidang
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role.bidang'])->prefix('bidang')->name('bidang.')->group(function () {
    Route::get('/dashboard', [BidangDashboardController::class, 'index'])->name('dashboard');
    Route::get('/peserta/{id}', [BidangDashboardController::class, 'showPeserta'])->name('peserta.show');
    Route::post('/peserta/{id}/jadwal', [BidangDashboardController::class, 'updateJadwal'])->name('peserta.update_jadwal');
    Route::post('/jurnal/{id}/verify', [BidangDashboardController::class, 'verifyJurnal'])->name('jurnal.verify');
});

/*
|--------------------------------------------------------------------------
| Simalam Admin Access (Dinas & Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\SimalamAdminAccess::class])->group(function () {
    Route::get('/absensi/admin', [SimalamAdminController::class, 'dashboard'])->name('absensi.admin.dashboard');
    Route::post('/absensi/admin/absensi/hapus/{absensi}', [SimalamAdminController::class, 'destroyAbsensi'])->name('absensi.admin.absensi.destroy');
    Route::post('/absensi/admin/absensi/koreksi', [SimalamAdminController::class, 'koreksiAbsensi'])->name('absensi.admin.absensi.koreksi');
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
    
    // Alias admin.* routes for Simalam views
    Route::get('/admin/rekap/excel', [SimalamAdminController::class, 'exportExcel'])->name('admin.rekap.excel');
    Route::get('/admin/rekap/pdf', [SimalamAdminController::class, 'exportPdf'])->name('admin.rekap.pdf');
    Route::post('/admin/absensi/hapus/{absensi}', [SimalamAdminController::class, 'destroyAbsensi'])->name('admin.absensi.destroy');
    Route::post('/admin/absensi/koreksi', [SimalamAdminController::class, 'koreksiAbsensi'])->name('admin.absensi.koreksi');
    Route::post('/admin/jadwal/landing_view', [SimalamAdminController::class, 'updateLandingScheduleView'])->name('admin.jadwal.landing_view');
    Route::post('/admin/jadwal/update', [SimalamAdminController::class, 'updateSchedules'])->name('admin.jadwal.update');
    Route::post('/admin/jadwal/randomize', [SimalamAdminController::class, 'randomizeSchedules'])->name('admin.jadwal.random');
    Route::post('/admin/jadwal/team/store', [SimalamAdminController::class, 'storeTeam'])->name('admin.jadwal.team.store');
    Route::post('/admin/jadwal/team/update', [SimalamAdminController::class, 'updateTeamSchedules'])->name('admin.jadwal.team.update');
    Route::post('/admin/jadwal/team/randomize', [SimalamAdminController::class, 'randomizeTeamSchedules'])->name('admin.jadwal.team.random');
    Route::post('/admin/jadwal/team/members/update', [SimalamAdminController::class, 'updateTeamMembers'])->name('admin.jadwal.team.members');
    Route::post('/admin/jadwal/team/members/randomize', [SimalamAdminController::class, 'randomizeTeamMembers'])->name('admin.jadwal.team.random_members');
    
    Route::post('/admin/pegawai/store', [SimalamAdminController::class, 'storeUser'])->name('admin.user.store');
    Route::put('/admin/pegawai/update/{id}', [SimalamAdminController::class, 'updateUser'])->name('admin.user.update');
    Route::delete('/admin/pegawai/destroy/{id}', [SimalamAdminController::class, 'destroyUser'])->name('admin.user.destroy');
    
    Route::post('/admin/bidang/store', [SimalamAdminController::class, 'storeBidang'])->name('admin.bidang.store');
    Route::put('/admin/bidang/update/{id}', [SimalamAdminController::class, 'updateBidang'])->name('admin.bidang.update');
    Route::delete('/admin/bidang/destroy/{id}', [SimalamAdminController::class, 'destroyBidang'])->name('admin.bidang.destroy');
    
    Route::post('/admin/pembimbing/store', [SimalamAdminController::class, 'storePembimbing'])->name('admin.pembimbing.store');
    Route::put('/admin/pembimbing/update/{id}', [SimalamAdminController::class, 'updatePembimbing'])->name('admin.pembimbing.update');
    Route::delete('/admin/pembimbing/destroy/{id}', [SimalamAdminController::class, 'destroyPembimbing'])->name('admin.pembimbing.destroy');

    Route::get('/admin/surat', [\App\Http\Controllers\Kesbangpol\SuratController::class, 'index'])->name('admin.surat.index');
    Route::get('/admin/surat/{surat}/edit', [\App\Http\Controllers\Kesbangpol\SuratController::class, 'edit'])->name('admin.surat.edit');
    Route::put('/admin/surat/{surat}', [\App\Http\Controllers\Kesbangpol\SuratController::class, 'update'])->name('admin.surat.update');

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
