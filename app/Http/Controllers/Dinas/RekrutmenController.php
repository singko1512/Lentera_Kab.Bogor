<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rekrutmen;
use App\Models\Bidang;
use Illuminate\Support\Facades\Auth;

class RekrutmenController extends Controller
{
    public function index()
    {
        $dinas = Auth::user()->dinas;
        $rekrutmen = Rekrutmen::with('bidang')->where('dinas_id', $dinas->id)->get();
        return view('pelayanan.dinas.rekrutmen.index', compact('rekrutmen'));
    }

    public function create()
    {
        $bidangs = Bidang::where('dinas_id', Auth::user()->dinas_id)->get();
        return view('pelayanan.dinas.rekrutmen.create', compact('bidangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'bidang_id' => 'nullable|exists:bidang,id',
            'deskripsi_persyaratan' => 'nullable|string',
            'kuota' => 'required|integer|min:1',
            'tanggal_berakhir' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['dinas_id'] = Auth::user()->dinas_id;

        Rekrutmen::create($data);
        return redirect()->route('dinas.rekrutmen.index')->with('success', 'Lowongan berhasil dibuat.');
    }

    public function edit($id)
    {
        $rekrutmen = Rekrutmen::where('dinas_id', Auth::user()->dinas_id)->findOrFail($id);
        $bidangs = Bidang::where('dinas_id', Auth::user()->dinas_id)->get();
        return view('pelayanan.dinas.rekrutmen.edit', compact('rekrutmen', 'bidangs'));
    }

    public function update(Request $request, $id)
    {
        $rekrutmen = Rekrutmen::where('dinas_id', Auth::user()->dinas_id)->findOrFail($id);

        $request->validate([
            'judul' => 'required|string',
            'bidang_id' => 'nullable|exists:bidang,id',
            'deskripsi_persyaratan' => 'nullable|string',
            'kuota' => 'required|integer|min:1',
            'tanggal_berakhir' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $rekrutmen->update($request->all());
        return redirect()->route('dinas.rekrutmen.index')->with('success', 'Lowongan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $rekrutmen = Rekrutmen::where('dinas_id', Auth::user()->dinas_id)->findOrFail($id);
        $rekrutmen->delete();
        return redirect()->route('dinas.rekrutmen.index')->with('success', 'Lowongan berhasil dihapus.');
    }
}
