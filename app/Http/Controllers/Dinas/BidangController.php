<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bidang;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BidangController extends Controller
{
    public function index()
    {
        $dinas = Auth::user()->dinas;
        $bidangs = Bidang::where('dinas_id', $dinas->id)
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

        $bidang = Bidang::create([
            'name' => $request->name,
            'dinas_id' => Auth::user()->dinas_id,
        ]);

        if ($request->filled('email')) {
            User::create([
                'name' => 'Admin Bidang ' . $bidang->name,
                'email' => $request->email,
                'password' => Hash::make($request->password ?? 'password123'),
                'role' => 'bidang',
                'dinas_id' => Auth::user()->dinas_id,
                'bidang_id' => $bidang->id,
                'status_akun' => 'active',
                'email_verified_at' => now(),
            ]);
        }

        return redirect()->route('dinas.bidang.index')->with('success_swal', 'Bidang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $bidang = Bidang::where('dinas_id', Auth::user()->dinas_id)->findOrFail($id);
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
                User::create([
                    'name' => 'Admin Bidang ' . $bidang->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password ?? 'password123'),
                    'role' => 'bidang',
                    'dinas_id' => Auth::user()->dinas_id,
                    'bidang_id' => $bidang->id,
                    'status_akun' => 'active',
                    'email_verified_at' => now(),
                ]);
            }
        } elseif ($user) {
            $user->update([
                'name' => 'Admin Bidang ' . $bidang->name,
            ]);
        }

        return redirect()->route('dinas.bidang.index')->with('success_swal', 'Bidang berhasil diperbarui.');
    }

    public function resetPassword($id)
    {
        $bidang = Bidang::where('dinas_id', Auth::user()->dinas_id)->findOrFail($id);
        $user = User::where('bidang_id', $bidang->id)->where('role', 'bidang')->first();

        if (!$user) {
            return redirect()->back()->with('error_swal', 'Akun user untuk bidang ini belum dibuat.');
        }

        $user->update([
            'password' => Hash::make('password123'),
        ]);

        return redirect()->route('dinas.bidang.index')->with('success_swal', 'Password akun bidang berhasil direset ke "password123".');
    }

    public function destroy($id)
    {
        $bidang = Bidang::where('dinas_id', Auth::user()->dinas_id)->findOrFail($id);
        User::where('bidang_id', $bidang->id)->where('role', 'bidang')->delete();
        $bidang->delete();

        return redirect()->route('dinas.bidang.index')->with('success_swal', 'Bidang berhasil dihapus.');
    }
}
