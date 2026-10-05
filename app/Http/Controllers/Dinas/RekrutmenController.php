<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rekrutmen;
use App\Models\Bidang;
use App\Support\CurrentDinas;

class RekrutmenController extends Controller
{
    public function index()
    {
        $dinasId = CurrentDinas::id();
        $rekrutmen = Rekrutmen::with('bidang')->where('dinas_id', $dinasId)->get();
        return view('pelayanan.dinas.rekrutmen.index', compact('rekrutmen'));
    }

    public function create()
    {
        $dinasId = CurrentDinas::id();
        $bidangs = Bidang::where('dinas_id', $dinasId)->get();
        return view('pelayanan.dinas.rekrutmen.create', compact('bidangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'bidang_id' => 'required|exists:bidang,id',
            'deskripsi_persyaratan' => 'nullable|string',
            'kuota' => 'required|integer|min:1',
            'tanggal_berakhir' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $dinasId = CurrentDinas::id();

        $data = $request->all();
        $data['dinas_id'] = $dinasId;

        Rekrutmen::create($data);
        return redirect()->route('dinas.rekrutmen.index')->with('success', 'Lowongan berhasil dibuat.');
    }

    public function edit($id)
    {
        $dinasId = CurrentDinas::id();
        $rekrutmen = Rekrutmen::where('dinas_id', $dinasId)->findOrFail($id);
        $bidangs = Bidang::where('dinas_id', $dinasId)->get();
        return view('pelayanan.dinas.rekrutmen.edit', compact('rekrutmen', 'bidangs'));
    }

    public function update(Request $request, $id)
    {
        $dinasId = CurrentDinas::id();
        $rekrutmen = Rekrutmen::where('dinas_id', $dinasId)->findOrFail($id);

        $request->validate([
            'judul' => 'required|string',
            'bidang_id' => 'required|exists:bidang,id',
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
        $dinasId = CurrentDinas::id();
        $rekrutmen = Rekrutmen::where('dinas_id', $dinasId)->findOrFail($id);
        $rekrutmen->delete();
        return redirect()->route('dinas.rekrutmen.index')->with('success', 'Lowongan berhasil dihapus.');
    }
}
