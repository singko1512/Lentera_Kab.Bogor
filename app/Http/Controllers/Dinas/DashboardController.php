<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rekrutmen;
use App\Models\MagangApplication;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dinasId = \App\Support\CurrentDinas::id();
        
        if (!$dinasId && in_array($user->role, ['superadmin', 'admin'])) {
            $dinasId = \App\Models\Dinas::first()?->id;
        }

        $dinas = $dinasId ? \App\Models\Dinas::find($dinasId) : null;

        if (!$dinas) {
            return redirect('/')->with('error', 'Akun Anda tidak tertaut dengan instansi manapun.');
        }

        $rekrutmens = Rekrutmen::where('dinas_id', $dinas->id)->get();
        $totalKuota = $rekrutmens->sum('kuota');
        $slotTersedia = $rekrutmens->sum('slot_tersedia');

        $totalPengajuanLayanan = MagangApplication::where(function($q) use ($dinas) {
            $q->where('dinas_id', $dinas->id)
              ->orWhereHas('rekrutmen', function($sq) use ($dinas) {
                  $sq->where('dinas_id', $dinas->id);
              });
        })->count();

        $pesertaAktif = MagangApplication::where(function($q) use ($dinas) {
            $q->where('dinas_id', $dinas->id)
              ->orWhereHas('rekrutmen', function($sq) use ($dinas) {
                  $sq->where('dinas_id', $dinas->id);
              });
        })->whereIn('status', ['diterima', 'aktif'])->count();

        $pesertaDiterima = MagangApplication::with(['user', 'rekrutmen.bidang', 'bidang'])
            ->where(function($q) use ($dinas) {
                $q->where('dinas_id', $dinas->id)
                  ->orWhereHas('rekrutmen', function($sq) use ($dinas) {
                      $sq->where('dinas_id', $dinas->id);
                  });
            })
            ->whereIn('status', ['diterima', 'aktif'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('pelayanan.dinas.dashboard', compact(
            'dinas', 'totalKuota', 'slotTersedia', 'totalPengajuanLayanan', 'pesertaAktif', 'pesertaDiterima'
        ));
    }

    public function updateStatusMagang(Request $request)
    {
        $request->validate([
            'status_magang' => 'required|in:otomatis,tersedia,penuh,tidak_tersedia',
        ]);

        $dinas = \App\Support\CurrentDinas::model();
        if ($dinas) {
            $dinas->update([
                'status_magang' => $request->status_magang,
            ]);
            return back()->with('success', 'Status kuota magang instansi berhasil diperbarui!');
        }

        return back()->with('error', 'Instansi tidak ditemukan.');
    }

    public function editProfile()
    {
        $user = Auth::user();
        $dinas = $user->dinas;
        return view('pelayanan.dinas.profile.edit', compact('user', 'dinas'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $dinas = $user->dinas;

        $request->validate([
            'nama' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,'.$user->id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'status_magang' => 'nullable|in:otomatis,tersedia,penuh,tidak_tersedia',
        ]);

        $userData = [
            'email' => $request->email,
        ];

        if ($request->filled('nama')) {
            $userData['nama'] = $request->nama;
        }

        if ($request->filled('username')) {
            $userData['username'] = $request->username;
        }

        if ($request->filled('password')) {
            $userData['password'] = \Hash::make($request->password);
        }

        $user->update($userData);

        if ($dinas && $request->has('status_magang')) {
            $dinas->update([
                'status_magang' => $request->status_magang,
            ]);
        }

        return back()->with('success', 'Profil dan pengaturan instansi berhasil disimpan!');
    }
}
