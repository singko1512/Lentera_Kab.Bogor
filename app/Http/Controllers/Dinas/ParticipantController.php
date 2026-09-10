<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    public function index()
    {
        $dinas = Auth::user()->dinas;
        $participants = \App\Models\MagangApplication::with(['user', 'rekrutmen.bidang'])
            ->whereHas('rekrutmen', function($q) use ($dinas) {
                $q->where('dinas_id', $dinas->id);
            })
            ->whereIn('status', ['diterima', 'aktif', 'selesai'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('pelayanan.dinas.participants.index', compact('dinas', 'participants'));
    }

    public function show($id)
    {
        $dinas = Auth::user()->dinas;
        $participant = \App\Models\MagangApplication::with(['user', 'rekrutmen.bidang', 'absensis', 'jurnals' => function($q) {
            $q->orderBy('tanggal', 'desc');
        }])
            ->whereHas('rekrutmen', function($q) use ($dinas) {
                $q->where('dinas_id', $dinas->id);
            })
            ->whereIn('status', ['diterima', 'aktif', 'selesai'])
            ->findOrFail($id);

        $suratPenerimaan = null;
        if (!empty($participant->surat_balasan)) {
            $suratPenerimaan = (object)['file_path' => $participant->surat_balasan];
        }

        return view('pelayanan.dinas.participants.show', compact('dinas', 'participant', 'suratPenerimaan'));
    }

    public function updatePenempatan(Request $request, $id)
    {
        $dinas = Auth::user()->dinas;
        $participant = \App\Models\MagangApplication::whereHas('rekrutmen', function($q) use ($dinas) {
            $q->where('dinas_id', $dinas->id);
        })->findOrFail($id);

        $request->validate([
            'bidang_id' => 'required|exists:bidangs,id'
        ]);

        $participant->update([
            'bidang_id' => $request->bidang_id
        ]);

        return redirect()->back()->with('success', 'Penempatan bidang berhasil diperbarui.');
    }

    public function updateSurat(Request $request, $id)
    {
        $dinas = Auth::user()->dinas;
        $participant = \App\Models\MagangApplication::whereHas('rekrutmen', function($q) use ($dinas) {
            $q->where('dinas_id', $dinas->id);
        })->findOrFail($id);

        $request->validate([
            'surat' => 'required|mimes:pdf|max:5120'
        ]);

        $path = $request->file('surat')->store('surat_balasan', 'public');
        
        $participant->update([
            'surat_balasan' => $path
        ]);

        return redirect()->back()->with('success', 'Surat balasan dinas berhasil diunggah.');
    }

    public function verifyJurnal(Request $request, $id, $jurnal_id)
    {
        $dinas = Auth::user()->dinas;
        
        $participant = \App\Models\MagangApplication::whereHas('rekrutmen', function($q) use ($dinas) {
                $q->where('dinas_id', $dinas->id);
            })
            ->findOrFail($id);

        $jurnal = \App\Models\Jurnal::where('magang_application_id', $participant->id)
            ->findOrFail($jurnal_id);

        $jurnal->update([
            'status_verifikasi' => true
        ]);

        return redirect()->back()->with('success', 'Jurnal harian berhasil diverifikasi.');
    }
}
