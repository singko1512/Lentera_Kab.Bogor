<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dinas;
use App\Models\Rekrutmen;
use App\Models\MagangApplication;
use App\Models\PermohonanLayanan;
use Illuminate\Support\Facades\Auth;

class MagangController extends Controller
{
    public function instansiList(Request $request)
    {
        $query = Dinas::where('is_kesbangpol', false)->with('rekrutmens');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('filter') && $request->filter != 'semua') {
            if ($request->filter == 'tersedia') {
                $query->where(function ($q) {
                    $q->where('status_magang', 'tersedia')
                      ->orWhere(function ($subQ) {
                          $subQ->where(function ($s) {
                              $s->where('status_magang', 'otomatis')->orWhereNull('status_magang');
                          })->whereHas('rekrutmens', function ($q2) {
                              $q2->where('is_active', true)
                                 ->whereRaw('kuota > (SELECT COUNT(*) FROM magang_applications WHERE magang_applications.rekrutmen_id = rekrutmens.id AND magang_applications.status IN (?, ?))', ['menunggu', 'diterima']);
                          });
                      });
                });
            } elseif ($request->filter == 'penuh') {
                $query->where(function ($q) {
                    $q->where('status_magang', 'penuh')
                      ->orWhere(function ($subQ) {
                          $subQ->where(function ($s) {
                              $s->where('status_magang', 'otomatis')->orWhereNull('status_magang');
                          })->whereHas('rekrutmens', function ($q2) {
                              $q2->where('is_active', true);
                          })->whereDoesntHave('rekrutmens', function ($q3) {
                              $q3->where('is_active', true)
                                 ->whereRaw('kuota > (SELECT COUNT(*) FROM magang_applications WHERE magang_applications.rekrutmen_id = rekrutmens.id AND magang_applications.status IN (?, ?))', ['menunggu', 'diterima']);
                          });
                      });
                });
            } elseif ($request->filter == 'tidak_tersedia') {
                $query->where(function ($q) {
                    $q->where('status_magang', 'tidak_tersedia')
                      ->orWhere(function ($subQ) {
                          $subQ->where(function ($s) {
                              $s->where('status_magang', 'otomatis')->orWhereNull('status_magang');
                          })->whereDoesntHave('rekrutmens', function ($q2) {
                              $q2->where('is_active', true);
                          });
                      });
                });
            }
        }

        if ($request->filled('sort')) {
            if ($request->sort == 'nama_asc') {
                $query->orderBy('name', 'asc');
            } elseif ($request->sort == 'nama_desc') {
                $query->orderBy('name', 'desc');
            } elseif ($request->sort == 'kuota_terbanyak') {
                $query->withSum(['rekrutmens' => function($q) {
                    $q->where('is_active', true);
                }], 'kuota')->orderBy('rekrutmens_sum_kuota', 'desc');
            }
        } else {
            $query->orderBy('name', 'asc');
        }

        $instansis = $query->paginate(12)->withQueryString();

        return view('pelayanan.landing.instansi', compact('instansis'));
    }

    public function instansiDetail($id)
    {
        $instansi = Dinas::with(['rekrutmens' => function ($q) {
            $q->where('is_active', true)->with(['magangApplications' => function ($mq) {
                $mq->where('status', 'diterima')->with('user', 'permohonanLayanan');
            }]);
        }])->findOrFail($id);

        $totalKuota = $instansi->rekrutmens->sum('kuota');
        $slotTersedia = $instansi->rekrutmens->sum('slot_tersedia');
        $totalDiterima = $totalKuota - $slotTersedia;

        return view('pelayanan.landing.instansi_detail', compact(
            'instansi', 'totalKuota', 'slotTersedia', 'totalDiterima'
        ));
    }

    public function applyForm($rekrutmenId)
    {
        $rekrutmen = Rekrutmen::with('dinas')->findOrFail($rekrutmenId);
        
        // Cek apakah user punya permohonan magang yang sudah disetujui
        $permohonanLayanan = PermohonanLayanan::where('user_id', Auth::id())
            ->whereHas('statusMaster', function($q) {
                $q->where('kode', 'disetujui');
            })
            // ->where('is_magang', true) // Kalau ada is_magang filter, tambahkan di sini
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$permohonanLayanan) {
            return redirect()->back()->with('error', 'Anda harus memiliki Surat Rekomendasi dari Kesbangpol yang sudah disetujui sebelum mendaftar magang.');
        }

        return view('pelayanan.landing.forms.magang_apply', compact('rekrutmen', 'permohonanLayanan'));
    }

    public function applySubmit(Request $request, $rekrutmenId)
    {
        $request->validate([
            'pesan_lamaran' => 'nullable|string',
            'permohonan_layanan_id' => 'required|exists:permohonan_layanans,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $rekrutmen = Rekrutmen::findOrFail($rekrutmenId);

        // Hapus pengajuan pendahulu/manual yang masih status 'menunggu' untuk permohonan ini agar tidak ganda
        MagangApplication::where('user_id', Auth::id())
            ->where('permohonan_layanan_id', $request->permohonan_layanan_id)
            ->where('status', 'menunggu')
            ->delete();

        MagangApplication::create([
            'user_id' => Auth::id(),
            'rekrutmen_id' => $rekrutmenId,
            'dinas_id' => $rekrutmen->dinas_id,
            'permohonan_layanan_id' => $request->permohonan_layanan_id,
            'status' => 'menunggu',
            'pesan_lamaran' => $request->pesan_lamaran,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->route('landing.instansi')->with('success', 'Pendaftaran magang berhasil dikirim. Menunggu verifikasi Dinas.');
    }
}
