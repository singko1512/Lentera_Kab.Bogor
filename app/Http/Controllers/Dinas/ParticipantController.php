<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    public function index()
    {
        $dinas = \App\Models\Dinas::find((session('superadmin_instansi_id') ?? Auth::user()->dinas_id));
        $participants = \App\Models\MagangApplication::with(['user', 'rekrutmen.bidang'])
            ->whereHas('rekrutmen', function($q) use ($dinas) {
                $q->where('dinas_id', (session('superadmin_instansi_id') ?? Auth::user()->dinas_id));
            })
            ->whereIn('status', ['diterima', 'aktif', 'selesai'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('pelayanan.dinas.participants.index', compact('dinas', 'participants'));
    }

    public function show($id)
    {
        $dinas = \App\Models\Dinas::find((session('superadmin_instansi_id') ?? Auth::user()->dinas_id));
        $participant = \App\Models\MagangApplication::with(['user', 'rekrutmen.bidang', 'absensis', 'jurnals' => function($q) {
            $q->orderBy('tanggal', 'desc');
        }])
            ->whereHas('rekrutmen', function($q) use ($dinas) {
                $q->where('dinas_id', (session('superadmin_instansi_id') ?? Auth::user()->dinas_id));
            })
            ->whereIn('status', ['diterima', 'aktif', 'selesai'])
            ->findOrFail($id);

        $suratPenerimaan = null;
        if (!empty($participant->file_surat_penerimaan)) {
            $suratPenerimaan = (object)['file_path' => $participant->file_surat_penerimaan];
        }

        $bidangs = \App\Models\Bidang::where('dinas_id', $dinas->id)->orderBy('name')->get();

        return view('pelayanan.dinas.participants.show', compact('dinas', 'participant', 'suratPenerimaan', 'bidangs'));
    }

    public function updatePenempatan(Request $request, $id)
    {
        $dinas = \App\Models\Dinas::find((session('superadmin_instansi_id') ?? Auth::user()->dinas_id));
        $participant = \App\Models\MagangApplication::whereHas('rekrutmen', function($q) use ($dinas) {
            $q->where('dinas_id', (session('superadmin_instansi_id') ?? Auth::user()->dinas_id));
        })->findOrFail($id);

        $request->validate([
            'bidang_id' => 'required|exists:bidang,id'
        ]);

        $participant->update([
            'bidang_id' => $request->bidang_id
        ]);

        return redirect()->back()->with('success', 'Penempatan bidang berhasil diperbarui.');
    }

    public function updateSurat(Request $request, $id)
    {
        $dinas = \App\Models\Dinas::find((session('superadmin_instansi_id') ?? Auth::user()->dinas_id));
        $participant = \App\Models\MagangApplication::whereHas('rekrutmen', function($q) use ($dinas) {
            $q->where('dinas_id', (session('superadmin_instansi_id') ?? Auth::user()->dinas_id));
        })->findOrFail($id);

        $request->validate([
            'surat' => 'required|mimes:pdf|max:5120'
        ]);

        $path = $request->file('surat')->store('permohonan/penerimaan_dinas', 'public');
        
        $participant->update([
            'file_surat_penerimaan' => $path
        ]);

        return redirect()->back()->with('success', 'Surat balasan dinas berhasil diunggah.');
    }

    public function generateSurat($id)
    {
        $dinas = \App\Models\Dinas::find((session('superadmin_instansi_id') ?? Auth::user()->dinas_id));
        $application = \App\Models\MagangApplication::with(['user', 'rekrutmen.bidang', 'permohonanLayanan'])
            ->whereHas('rekrutmen', function($q) use ($dinas) {
                $q->where('dinas_id', (session('superadmin_instansi_id') ?? Auth::user()->dinas_id));
            })->findOrFail($id);

        $bidangObj = \App\Models\Bidang::find($application->bidang_id);
        $bidangNama = $bidangObj ? $bidangObj->name : 'Bidang Tujuan';

        try {
            $pdfFileName = 'Surat_Penerimaan_' . \Illuminate\Support\Str::slug($dinas->name) . '_' . $application->id . '.pdf';
            $relativePdfPath = 'permohonan/penerimaan_dinas/' . $pdfFileName;
            $outputPdfPath = storage_path('app/public/' . $relativePdfPath);

            if (!file_exists(dirname($outputPdfPath))) {
                mkdir(dirname($outputPdfPath), 0755, true);
            }

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.surat_penerimaan_dinas', compact('application', 'dinas', 'bidangNama'))
                ->setPaper('a4', 'portrait');
            $pdf->save($outputPdfPath);

            $application->update(['file_surat_penerimaan' => $relativePdfPath]);

            return redirect()->back()->with('success', 'Surat penerimaan berhasil di-generate otomatis.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Gagal generate PDF penerimaan dinas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal generate surat penerimaan.');
        }
    }

    public function verifyJurnal(Request $request, $id, $jurnal_id)
    {
        $dinas = \App\Models\Dinas::find((session('superadmin_instansi_id') ?? Auth::user()->dinas_id));
        
        $participant = \App\Models\MagangApplication::whereHas('rekrutmen', function($q) use ($dinas) {
                $q->where('dinas_id', (session('superadmin_instansi_id') ?? Auth::user()->dinas_id));
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
