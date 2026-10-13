<?php

namespace App\Http\Controllers\Kesbangpol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dinas;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminDinasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Dinas::with(['admins' => function($q) {
            $q->where('role', 'dinas');
        }, 'bidang'])->orderBy('name', 'asc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhereHas('admins', function($u) use ($search) {
                      $u->where('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $dinasList = $query->paginate(12)->withQueryString();

        $totalDinas = Dinas::count();
        $totalAkun = Admin::where('role', 'dinas')->count();
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
            'email' => 'required|email|unique:admins,email|unique:users,email',
            'password' => 'required|string|min:6',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'nama_kepala' => 'nullable|string|max:255',
            'nip_kepala' => 'nullable|string|max:255',
            'kop_surat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama Dinas / Perangkat Daerah wajib diisi.',
            'email.required' => 'Email login wajib diisi.',
            'email.unique' => 'Email tersebut sudah digunakan oleh akun lain.',
            'password.required' => 'Password wajib diisi (minimal 6 karakter).',
            'kop_surat.image' => 'File Kop Surat harus berupa gambar.',
        ]);

        $kopSuratPath = null;
        if ($request->hasFile('kop_surat')) {
            $kopSuratPath = $request->file('kop_surat')->store('dinas/kop', 'public');
        }

        // 1. Buat record Dinas
        $dinas = Dinas::create([
            'name' => mb_strtoupper($request->name),
            'email' => $request->email,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'nama_kepala' => $request->nama_kepala,
            'nip_kepala' => $request->nip_kepala,
            'kop_surat' => $kopSuratPath,
            'is_kesbangpol' => stripos($request->name, 'KESATUAN BANGSA DAN POLITIK') !== false,
        ]);

        // 2. Buat record Admin untuk Dinas di tabel admins
        Admin::create([
            'name' => 'Admin ' . $dinas->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dinas',
            'dinas_id' => $dinas->id,
            'status_akun' => 'aktif',
        ]);

        return redirect()->route('kesbangpol.dinas.index')
            ->with('success', 'Akun Dinas "' . $dinas->name . '" berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $dinas = Dinas::findOrFail($id);
        $admin = Admin::where('dinas_id', $dinas->id)->where('role', 'dinas')->first();

        $adminId = $admin ? $admin->id : null;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $adminId . '|unique:users,email',
            'password' => 'nullable|string|min:6',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'nama_kepala' => 'nullable|string|max:255',
            'nip_kepala' => 'nullable|string|max:255',
            'kop_surat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $kopSuratPath = $dinas->kop_surat;
        if ($request->hasFile('kop_surat')) {
            if ($kopSuratPath) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($kopSuratPath);
            }
            $kopSuratPath = $request->file('kop_surat')->store('dinas/kop', 'public');
        }

        $dinas->update([
            'name' => mb_strtoupper($request->name),
            'email' => $request->email,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'nama_kepala' => $request->nama_kepala,
            'nip_kepala' => $request->nip_kepala,
            'kop_surat' => $kopSuratPath,
        ]);

        if ($admin) {
            $adminData = [
                'name' => 'Admin ' . $dinas->name,
                'email' => $request->email,
            ];

            if (!empty($request->password)) {
                $adminData['password'] = Hash::make($request->password);
            }

            $admin->update($adminData);
        } else {
            // Buat jika akun admin dinas belum ada sebelumnya
            $plainPass = $request->password ?: Str::password(12);
            Admin::create([
                'name' => 'Admin ' . $dinas->name,
                'email' => $request->email,
                'password' => Hash::make($plainPass),
                'role' => 'dinas',
                'dinas_id' => $dinas->id,
                'status_akun' => 'aktif',
            ]);
        }

        return redirect()->route('kesbangpol.dinas.index')
            ->with('success', 'Data Akun Dinas "' . $dinas->name . '" berhasil diperbarui.');
    }

    public function resetPassword(Request $request, $id)
    {
        $dinas = Dinas::findOrFail($id);
        $admin = Admin::where('dinas_id', $dinas->id)->where('role', 'dinas')->first();

        if ($admin) {
            $newPassword = Str::password(12);
            $admin->update([
                'password' => Hash::make($newPassword)
            ]);
            return redirect()->route('kesbangpol.dinas.index')
                ->with('success', 'Password akun "' . $dinas->name . '" berhasil di-reset menjadi: ' . $newPassword . ' (Harap simpan password ini).');
        }

        return redirect()->route('kesbangpol.dinas.index')
            ->with('error', 'Akun admin untuk dinas tersebut tidak ditemukan.');
    }

    public function destroy($id)
    {
        $dinas = Dinas::findOrFail($id);
        $dinasName = $dinas->name;

        // Delete associated admin accounts and bidang
        Admin::where('dinas_id', $dinas->id)->delete();
        User::where('dinas_id', $dinas->id)->update(['dinas_id' => null]);
        \App\Models\Bidang::where('dinas_id', $dinas->id)->delete();
        $dinas->delete();

        return redirect()->route('kesbangpol.dinas.index')
            ->with('success', 'Akun Dinas "' . $dinasName . '" beserta data terkait berhasil dihapus.');
    }
}
