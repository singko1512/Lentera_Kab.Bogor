<?php

namespace App\Http\Controllers\Kesbangpol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dinas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SuratController extends Controller
{
    public function index()
    {
        if (!in_array(Auth::user()->role, ['superadmin', 'admin'])) {
            abort(403);
        }

        $dinas = Dinas::orderBy('is_kesbangpol', 'desc')->orderBy('name', 'asc')->get();
        return view('pelayanan.kesbangpol.surat.index', compact('dinas'));
    }

    public function edit(Dinas $surat)
    {
        if (!in_array(Auth::user()->role, ['superadmin', 'admin'])) {
            abort(403);
        }

        return view('pelayanan.kesbangpol.surat.edit', compact('surat'));
    }

    public function update(Request $request, Dinas $surat)
    {
        if (!in_array(Auth::user()->role, ['superadmin', 'admin'])) {
            abort(403);
        }

        $request->validate([
            'nama_kepala' => 'nullable|string|max:255',
            'nip_kepala' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'kop_surat' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('kop_surat')) {
            if ($surat->kop_surat) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $surat->kop_surat));
            }
            $path = $request->file('kop_surat')->store('kop_surat', 'public');
            $surat->kop_surat = '/storage/' . $path;
        }

        $surat->nama_kepala = $request->nama_kepala;
        $surat->nip_kepala = $request->nip_kepala;
        $surat->alamat = $request->alamat;
        $surat->telepon = $request->telepon;
        $surat->email = $request->email;
        $surat->save();

        return redirect()->route('admin.surat.index')->with('success', 'Data Surat (Kop & Kepala Instansi) berhasil diperbarui.');
    }
}
