<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bidang;
use Illuminate\Support\Facades\Auth;

class BidangController extends Controller
{
    public function index()
    {
        $dinas = Auth::user()->dinas;
        $bidangs = Bidang::where('dinas_id', $dinas->id)->get();
        return view('pelayanan.dinas.bidang.index', compact('bidangs', 'dinas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Bidang::create([
            'name' => $request->name,
            'dinas_id' => Auth::user()->dinas_id,
        ]);

        return redirect()->route('dinas.bidang.index')->with('success_swal', 'Bidang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $bidang = Bidang::where('dinas_id', Auth::user()->dinas_id)->findOrFail($id);
        $bidang->update([
            'name' => $request->name,
        ]);

        return redirect()->route('dinas.bidang.index')->with('success_swal', 'Bidang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $bidang = Bidang::where('dinas_id', Auth::user()->dinas_id)->findOrFail($id);
        $bidang->delete();

        return redirect()->route('dinas.bidang.index')->with('success_swal', 'Bidang berhasil dihapus.');
    }
}
