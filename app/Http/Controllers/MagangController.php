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
        $query = Dinas::with('rekrutmens');

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
        $instansi = Dinas::with(['bidang', 'rekrutmens' => function ($q) {
            $q->where('is_active', true)->with('magangApplications');
        }])->findOrFail($id);

        $totalKuota = $instansi->rekrutmens->sum('kuota');
        $slotTersedia = $instansi->rekrutmens->sum('slot_tersedia');
        $totalDiterima = MagangApplication::whereHas('rekrutmen', function ($q) use ($id) {
            $q->where('dinas_id', $id);
        })->whereIn('status', ['diterima', 'aktif'])->count();

        return view('pelayanan.landing.instansi_detail', compact(
            'instansi', 'totalKuota', 'slotTersedia', 'totalDiterima'
        ));
    }

    public function applyForm($rekrutmenId)
    {
        return redirect()->route('peserta.dashboard')->with('info', 'Pendaftaran magang otomatis diproses melalui pengajuan layanan rekomendasi Kesbangpol. Dinas tujuan Anda sudah terbooking dan penempatan bidang ditentukan langsung oleh akun Dinas tujuan.');
    }

    public function applySubmit(Request $request, $rekrutmenId)
    {
        return redirect()->route('peserta.dashboard')->with('info', 'Pendaftaran magang otomatis diproses melalui pengajuan layanan rekomendasi Kesbangpol. Dinas tujuan Anda sudah terbooking dan penempatan bidang ditentukan langsung oleh akun Dinas tujuan.');
    }
}
