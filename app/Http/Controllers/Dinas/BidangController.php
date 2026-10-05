<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bidang;
use App\Models\User;
use App\Support\CurrentDinas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BidangController extends Controller
{
    public function index()
    {
        $dinasId = CurrentDinas::id();
        $dinas = CurrentDinas::model();
        $bidangs = Bidang::where('dinas_id', $dinasId)
            ->with(['users' => function($query) {
                $query->where('role', 'bidang');
            }])
            ->get();

        return view('pelayanan.dinas.bidang.index', compact('bidangs', 'dinas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'nullable|string|min:6',
        ]);

        $dinasId = CurrentDinas::id();

        $bidang = Bidang::create([
            'name' => $request->name,
            'dinas_id' => $dinasId,
        ]);

        $msg = 'Bidang berhasil ditambahkan.';

        if ($request->filled('email')) {
            $plainPass = $request->password ?: Str::password(12);

            User::create([
                'name' => 'Admin Bidang ' . $bidang->name,
                'email' => $request->email,
                'password' => Hash::make($plainPass),
                'role' => 'bidang',
                'dinas_id' => $dinasId,
                'bidang_id' => $bidang->id,
                'status_akun' => 'active',
                'email_verified_at' => now(),
            ]);

            if (!$request->filled('password')) {
                $msg .= ' Password akun bidang: ' . $plainPass . ' (Harap catat password ini)';
            }
        }

        return redirect()->route('dinas.bidang.index')->with('success_swal', $msg);
    }

    public function update(Request $request, $id)
    {
        $dinasId = CurrentDinas::id();
        $bidang = Bidang::where('dinas_id', $dinasId)->findOrFail($id);
        $user = User::where('bidang_id', $bidang->id)->where('role', 'bidang')->first();
        $userId = $user ? $user->id : null;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:6',
        ]);

        $bidang->update([
            'name' => $request->name,
        ]);

        $msg = 'Bidang berhasil diperbarui.';

        if ($request->filled('email')) {
            if ($user) {
                $userData = [
                    'name' => 'Admin Bidang ' . $bidang->name,
                    'email' => $request->email,
                ];
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $user->update($userData);
            } else {
                $plainPass = $request->password ?: Str::password(12);

                User::create([
                    'name' => 'Admin Bidang ' . $bidang->name,
                    'email' => $request->email,
                    'password' => Hash::make($plainPass),
                    'role' => 'bidang',
                    'dinas_id' => $dinasId,
                    'bidang_id' => $bidang->id,
                    'status_akun' => 'active',
                    'email_verified_at' => now(),
                ]);

                if (!$request->filled('password')) {
                    $msg .= ' Password akun bidang: ' . $plainPass . ' (Harap catat password ini)';
                }
            }
        } elseif ($user) {
            $user->update([
                'name' => 'Admin Bidang ' . $bidang->name,
            ]);
        }

        return redirect()->route('dinas.bidang.index')->with('success_swal', $msg);
    }

    public function resetPassword($id)
    {
        $dinasId = CurrentDinas::id();
        $bidang = Bidang::where('dinas_id', $dinasId)->findOrFail($id);
        $user = User::where('bidang_id', $bidang->id)->where('role', 'bidang')->first();

        if (!$user) {
            return redirect()->back()->with('error_swal', 'Akun user untuk bidang ini belum dibuat.');
        }

        $newPassword = Str::password(12);
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()->route('dinas.bidang.index')->with('success_swal', 'Password akun bidang berhasil direset ke: ' . $newPassword . ' (Harap simpan password ini).');
    }

    public function destroy($id)
    {
        $dinasId = CurrentDinas::id();
        $bidang = Bidang::where('dinas_id', $dinasId)->findOrFail($id);
        User::where('bidang_id', $bidang->id)->where('role', 'bidang')->delete();
        $bidang->delete();

        return redirect()->route('dinas.bidang.index')->with('success_swal', 'Bidang berhasil dihapus.');
    }
}
