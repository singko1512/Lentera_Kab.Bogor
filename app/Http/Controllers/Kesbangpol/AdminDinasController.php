<?php

namespace App\Http\Controllers\Kesbangpol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dinas;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminDinasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Dinas::with(['users' => function($q) {
            $q->where('role', 'dinas');
        }, 'bidang'])->orderBy('name', 'asc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhereHas('users', function($u) use ($search) {
                      $u->where('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $dinasList = $query->paginate(12)->withQueryString();

        $totalDinas = Dinas::count();
        $totalAkun = User::where('role', 'dinas')->count();
        $totalBidang = \App\Models\Bidang::count();

        return view('pelayanan.kesbangpol.dinas.index', compact(
            'dinasList',
            'totalDinas',
            'totalAkun',
            'totalBidang',
            'search'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ], [
            'name.required' => 'Nama Dinas / Perangkat Daerah wajib diisi.',
            'email.required' => 'Email login wajib diisi.',
            'email.unique' => 'Email tersebut sudah digunakan oleh akun lain.',
            'password.required' => 'Password wajib diisi (minimal 6 karakter).',
        ]);

        // 1. Buat record Dinas
        $dinas = Dinas::create([
            'name' => mb_strtoupper($request->name),
            'email' => $request->email,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'is_kesbangpol' => stripos($request->name, 'KESATUAN BANGSA DAN POLITIK') !== false,
        ]);

        // 2. Buat record User untuk Dinas
        User::create([
            'name' => 'Admin ' . $dinas->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dinas',
            'dinas_id' => $dinas->id,
            'status_akun' => 'active',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('kesbangpol.dinas.index')
            ->with('success', 'Akun Dinas "' . $dinas->name . '" berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $dinas = Dinas::findOrFail($id);
        $user = User::where('dinas_id', $dinas->id)->where('role', 'dinas')->first();

        $userId = $user ? $user->id : null;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:6',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        $dinas->update([
            'name' => mb_strtoupper($request->name),
            'email' => $request->email,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
        ]);

        if ($user) {
            $userData = [
                'name' => 'Admin ' . $dinas->name,
                'email' => $request->email,
            ];

            if (!empty($request->password)) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);
        } else {
            // Buat jika akun user belum ada sebelumnya
            User::create([
                'name' => 'Admin ' . $dinas->name,
                'email' => $request->email,
                'password' => Hash::make($request->password ?? 'password123'),
                'role' => 'dinas',
                'dinas_id' => $dinas->id,
                'status_akun' => 'active',
                'email_verified_at' => now(),
            ]);
        }

        return redirect()->route('kesbangpol.dinas.index')
            ->with('success', 'Data Akun Dinas "' . $dinas->name . '" berhasil diperbarui.');
    }

    public function resetPassword(Request $request, $id)
    {
        $dinas = Dinas::findOrFail($id);
        $user = User::where('dinas_id', $dinas->id)->where('role', 'dinas')->first();

        if ($user) {
            $user->update([
                'password' => Hash::make('password123')
            ]);
            return redirect()->route('kesbangpol.dinas.index')
                ->with('success', 'Password akun "' . $dinas->name . '" berhasil di-reset menjadi "password123".');
        }

        return redirect()->route('kesbangpol.dinas.index')
            ->with('error', 'Akun user untuk dinas tersebut tidak ditemukan.');
    }

    public function destroy($id)
    {
        $dinas = Dinas::findOrFail($id);
        $dinasName = $dinas->name;

        // Delete associated users and bidang
        User::where('dinas_id', $dinas->id)->delete();
        \App\Models\Bidang::where('dinas_id', $dinas->id)->delete();
        $dinas->delete();

        return redirect()->route('kesbangpol.dinas.index')
            ->with('success', 'Akun Dinas "' . $dinasName . '" beserta data terkait berhasil dihapus.');
    }
}
