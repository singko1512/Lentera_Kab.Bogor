<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\CurrentDinas;

class ParticipantController extends Controller
{
    public function index()
    {
        $dinasId = CurrentDinas::id();
        $dinas = CurrentDinas::model();

        $participants = \App\Models\MagangApplication::with(['user', 'bidang', 'rekrutmen.bidang', 'permohonanLayanan.dinas'])
            ->where(function($q) use ($dinasId) {
                $q->where('dinas_id', $dinasId)
                  ->orWhereHas('rekrutmen', function($sq) use ($dinasId) {
                      $sq->where('dinas_id', $dinasId);
                  })
                  ->orWhereHas('permohonanLayanan', function($sq) use ($dinasId) {
                      $sq->where('dinas_id', $dinasId);
                  });
            })
            ->whereIn('status', ['diterima', 'aktif', 'selesai'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('pelayanan.dinas.participants.index', compact('dinas', 'participants'));
    }

    public function show($id)
    {
        $dinasId = CurrentDinas::id();
        $dinas = CurrentDinas::model();

        $participant = \App\Models\MagangApplication::with(['user', 'bidang', 'rekrutmen.bidang', 'permohonanLayanan.dinas', 'absensis', 'jurnals' => function($q) {
            $q->orderBy('tanggal', 'desc');
        }])
            ->where(function($q) use ($dinasId) {
                $q->where('dinas_id', $dinasId)
                  ->orWhereHas('rekrutmen', function($sq) use ($dinasId) {
                      $sq->where('dinas_id', $dinasId);
                  })
                  ->orWhereHas('permohonanLayanan', function($sq) use ($dinasId) {
                      $sq->where('dinas_id', $dinasId);
                  });
            })
            ->whereIn('status', ['diterima', 'aktif', 'selesai'])
            ->findOrFail($id);

        $suratPenerimaan = null;
        if (!empty($participant->file_surat_penerimaan)) {
            $suratPenerimaan = (object)['file_path' => $participant->file_surat_penerimaan];
        }

        $bidangs = \App\Models\Bidang::where('dinas_id', $dinasId)->orderBy('name')->get();

        return view('pelayanan.dinas.participants.show', compact('dinas', 'participant', 'suratPenerimaan', 'bidangs'));
    }

    public function updateStatusAccount(Request $request, $id)
    {
        $request->validate([
            'status_akun' => 'required|in:aktif,dibatasi,diblokir'
        ]);

        $dinasId = CurrentDinas::id();

        // Scope query to ensure the target user is a peserta belonging to this dinas
        $user = \App\Models\User::where('id', $id)
            ->where('role', 'peserta')
            ->where(function($q) use ($dinasId) {
                $q->where('dinas_id', $dinasId)
                  ->orWhereHas('magangApplications', function($maq) use ($dinasId) {
                      $maq->where('dinas_id', $dinasId);
                  })
                  ->orWhereHas('permohonanLayanans', function($plq) use ($dinasId) {
                      $plq->where('dinas_id', $dinasId);
                  });
            })
            ->firstOrFail();

        $user->status_akun = $request->status_akun;
        $user->save();

        return redirect()->back()->with('success', 'Status akun peserta berhasil diperbarui menjadi ' . ucfirst($request->status_akun) . '.');
    }

    public function updatePenempatan(Request $request, $id)
    {
        $dinasId = CurrentDinas::id();
        $participant = \App\Models\MagangApplication::where(function($q) use ($dinasId) {
            $q->where('dinas_id', $dinasId)
              ->orWhereHas('rekrutmen', function($sq) use ($dinasId) {
                  $sq->where('dinas_id', $dinasId);
              })
              ->orWhereHas('permohonanLayanan', function($sq) use ($dinasId) {
                  $sq->where('dinas_id', $dinasId);
              });
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
        $dinasId = CurrentDinas::id();
        $participant = \App\Models\MagangApplication::where(function($q) use ($dinasId) {
            $q->where('dinas_id', $dinasId)
              ->orWhereHas('rekrutmen', function($sq) use ($dinasId) {
                  $sq->where('dinas_id', $dinasId);
              })
              ->orWhereHas('permohonanLayanan', function($sq) use ($dinasId) {
                  $sq->where('dinas_id', $dinasId);
              });
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
        $dinasId = CurrentDinas::id();
        $dinas = CurrentDinas::model();
        $application = \App\Models\MagangApplication::with(['user', 'bidang', 'rekrutmen.bidang', 'permohonanLayanan'])
            ->where(function($q) use ($dinasId) {
                $q->where('dinas_id', $dinasId)
                  ->orWhereHas('rekrutmen', function($sq) use ($dinasId) {
                      $sq->where('dinas_id', $dinasId);
                  })
                  ->orWhereHas('permohonanLayanan', function($sq) use ($dinasId) {
                      $sq->where('dinas_id', $dinasId);
                  });
            })->findOrFail($id);

        $bidangObj = \App\Models\Bidang::find($application->bidang_id);
        $bidangNama = $bidangObj ? $bidangObj->name : 'Bidang Tujuan';

        try {
            $pdfFileName = 'Surat_Penerimaan_' . \Illuminate\Support\Str::slug($dinas->name ?? 'dinas') . '_' . $application->id . '.pdf';
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
        $dinasId = CurrentDinas::id();
        
        $participant = \App\Models\MagangApplication::where(function($q) use ($dinasId) {
                $q->where('dinas_id', $dinasId)
                  ->orWhereHas('rekrutmen', function($sq) use ($dinasId) {
                      $sq->where('dinas_id', $dinasId);
                  })
                  ->orWhereHas('permohonanLayanan', function($sq) use ($dinasId) {
                      $sq->where('dinas_id', $dinasId);
                  });
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
