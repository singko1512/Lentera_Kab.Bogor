<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MagangApplication;
use App\Models\Absensi;
use Carbon\Carbon;

class AbsensiAdminController extends Controller
{
    public function index(Request $request)
    {
        $dinas = Auth::user()->dinas;
        if (!$dinas) {
            abort(403, 'Akses ditolak.');
        }

        // Variables needed by the overly complex SIMALAM admin template
        $isSuperAdmin = false;
        $activeAdminTab = (string) $request->get('tab', 'pegawai');
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        $status = $request->get('status', 'all');
        
        // Handle Bidang user role isolation
        $isBidangUser = Auth::user()->role === 'bidang';
        $activeBidangId = $isBidangUser ? Auth::user()->bidang_id : $request->get('bidang_id');

        $absensiStatuses = collect([
            (object)['kode' => 'hadir', 'nama' => 'Hadir'],
            (object)['kode' => 'wfh', 'nama' => 'WFH'],
            (object)['kode' => 'sakit', 'nama' => 'Sakit'],
            (object)['kode' => 'izin', 'nama' => 'Izin'],
        ]);

        $projectStatuses = collect([
            (object)['kode' => 'on_track', 'nama' => 'On Track'],
            (object)['kode' => 'at_risk', 'nama' => 'At Risk'],
            (object)['kode' => 'off_track', 'nama' => 'Off Track'],
        ]);

        $noteCategories = collect([
            (object)['kode' => 'tinggi', 'nama' => 'Tinggi'],
            (object)['kode' => 'sedang', 'nama' => 'Sedang'],
            (object)['kode' => 'rendah', 'nama' => 'Rendah'],
        ]);

        $availableTeams = ['A', 'B', 'C', 'D'];
        $bidangs = collect();
        $manageableBidangs = collect();
        $pembimbingMagangs = collect();
        
        $magangUsersQuery = MagangApplication::with(['user', 'bidang', 'rekrutmen'])
            ->where(function($q) use ($dinas) {
                $q->where('dinas_id', $dinas->id)
                  ->orWhereHas('rekrutmen', function($sq) use ($dinas) {
                      $sq->where('dinas_id', $dinas->id);
                  });
            })
            ->whereIn('status', ['diterima', 'aktif', 'selesai']);

        if ($activeBidangId) {
            $magangUsersQuery->where(function($q) use ($activeBidangId) {
                $q->where('bidang_id', $activeBidangId)
                  ->orWhereHas('user', function($uq) use ($activeBidangId) {
                      $uq->where('bidang_id', $activeBidangId);
                  })
                  ->orWhereHas('rekrutmen', function($rq) use ($activeBidangId) {
                      $rq->where('bidang_id', $activeBidangId);
                  });
            });
        }

        $magangSearch = $request->get('magang_search', $request->get('magangSearch', $request->get('search', '')));
        if (!empty($magangSearch)) {
            $magangUsersQuery->whereHas('user', function($uq) use ($magangSearch) {
                $uq->where('name', 'LIKE', '%' . $magangSearch . '%')
                  ->orWhere('email', 'LIKE', '%' . $magangSearch . '%')
                  ->orWhere('asal_instansi', 'LIKE', '%' . $magangSearch . '%');
            });
        }

        $magangUsers = $magangUsersQuery->get();
            
        $users = $magangUsers->pluck('user')->filter()->unique('id');
        $magangAppIds = $magangUsers->pluck('id');

        $query = Absensi::with('magangApplication.user')
            ->whereIn('magang_application_id', $magangAppIds)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $absensiRecords = $query->orderBy('tanggal', 'desc')->get();

        $adminBidangScope = null;
        if ($isBidangUser) {
            $adminBidangScope = \App\Models\Bidang::find($activeBidangId);
        }

        $bidangRowNumber = 1;

        // Variables for SIMALAM layout
        $projectCount = 0;
        $projectAktifCount = 0;
        $projectSelesaiCount = 0;
        $taskCount = 0;
        $taskReviewCount = 0;
        $taskTerlambatCount = 0;
        $moduleCount = 0;
        $pendingTasks = collect();
        $activityLogs = collect();
        $certificateTemplate = \App\Support\CertificateTemplate::current();
        $dinasName = $dinas->nama ?? 'Instansi';
        $jadwalStatus = [];
        $jadwalLandingView = null;
        $dashboardRouteName = $isBidangUser ? 'bidang.dashboard' : 'absensi.admin.dashboard';
        
        $search = $request->get('search', '');
        $magangSearch = $request->get('magangSearch', '');
        $pembimbingMagang = $request->get('pembimbingMagang', '');
        $pembimbingOptions = collect();
        
        $adminRole = Auth::user()->role;
        $adminBidangOptions = collect();
        $magangGroups = [];
        $projects = collect();

        $userGroupIds = $users->pluck('id');
        $sertifikatUsersQuery = \App\Models\User::query()
            ->whereIn('role', ['peserta', 'user'])
            ->orderByRaw('tanggal_selesai_magang is null asc')
            ->orderBy('tanggal_selesai_magang', 'desc')
            ->orderBy('name', 'asc');

        if ($activeBidangId) {
            $sertifikatUsersQuery->where('bidang_id', $activeBidangId);
        } else {
            $sertifikatUsersQuery->where(function($q) use ($dinas, $userGroupIds) {
                $q->where('dinas_id', $dinas->id)
                  ->orWhereIn('id', $userGroupIds);
            });
        }
        $sertifikatUsers = $sertifikatUsersQuery->get();

        $projectMembers = collect();
        $projectModules = collect();
        $projectTasks = collect();

        return view('absensi.admin.dashboard', compact(
            'adminRole',
            'isSuperAdmin',
            'activeAdminTab',
            'month',
            'year',
            'status',
            'activeBidangId',
            'adminBidangScope',
            'adminBidangOptions',
            'absensiStatuses',
            'absensiRecords',
            'users',
            'magangUsers',
            'magangGroups',
            'bidangs',
            'manageableBidangs',
            'pembimbingMagangs',
            'pembimbingOptions',
            'availableTeams',
            'projectStatuses',
            'noteCategories',
            'bidangRowNumber',
            'projectCount',
            'projectAktifCount',
            'projectSelesaiCount',
            'projects',
            'taskCount',
            'taskReviewCount',
            'taskTerlambatCount',
            'moduleCount',
            'pendingTasks',
            'activityLogs',
            'certificateTemplate',
            'sertifikatUsers',
            'dinasName',
            'jadwalStatus',
            'jadwalLandingView',
            'dashboardRouteName',
            'search',
            'magangSearch',
            'pembimbingMagang',
            'projectMembers',
            'projectModules',
            'projectTasks'
        ));
    }
}
