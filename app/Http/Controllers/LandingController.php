<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekrutmen;
use App\Models\Dinas;
use App\Models\MagangApplication;
use App\Models\PermohonanLayanan;

class LandingController extends Controller
{
    public function index()
    {
        // 1. Ambil Lowongan Aktif
        $rekrutmens = Rekrutmen::with(['dinas', 'bidang'])
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereDate('tanggal_berakhir', '>=', now())
                  ->orWhereNull('tanggal_berakhir');
            })
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        
        // Tambahkan dummy jenis_layanan agar tampilan blade tidak error (jika sebelumnya ada properti ini)
        foreach ($rekrutmens as $r) {
            $r->jenis_layanan = $r->bidang ? $r->bidang->nama : 'Umum';
        }

        // 2. Ambil Instansi (Prioritaskan yang memiliki kuota, dibatasi tepat 1 row / 3 kartu)
        $allInstansis = Dinas::with([
                'rekrutmens' => function($q) {
                    $q->where('is_active', true);
                },
                'rekrutmens.magangApplications'
            ])
            ->get();

        foreach ($allInstansis as $dinas) {
            $dinas->slot_tersedia = $dinas->sisa_kuota;
        }

        // Pisahkan instansi yang memiliki kuota dan yang belum ada kuota
        $withKuota = $allInstansis->filter(function ($dinas) {
            return $dinas->sisa_kuota > 0;
        })->sortByDesc('sisa_kuota')->values();

        $withoutKuota = $allInstansis->filter(function ($dinas) {
            return $dinas->sisa_kuota <= 0;
        })->sortBy('name')->values();

        // Gabungkan: utamakan yang memiliki kuota, jika kurang/kosong diisi instansi lainnya (1 row = 3 card)
        $featuredInstansis = $withKuota->concat($withoutKuota)->take(3)->values();

        // 3. Ambil Peserta Magang Diterima
        $pesertas = MagangApplication::with(['user', 'rekrutmen.dinas', 'rekrutmen.bidang'])
            ->where('status', 'diterima')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
        
        // Mapping properti agar sesuai dengan view (dulu pakai struktur dummy)
        foreach ($pesertas as $p) {
            $p->jurusan = $p->permohonanLayanan->jenisLayanan->nama ?? 'Umum';
            $p->instansi_asal = $p->permohonanLayanan->asal_instansi ?? 'Instansi/Kampus';
            $p->dinas = $p->rekrutmen?->dinas;
            $p->bidang = $p->rekrutmen?->bidang;
        }

        // 4. Data Statistik Chart & Cards (Real dari DB)
        $totalRegistered = PermohonanLayanan::count();
        $totalAccepted = PermohonanLayanan::whereHas('statusMaster', function($q){
            $q->where('kode', 'disetujui');
        })->count();

        $stats = [
            'total_pendaftar' => $totalRegistered,
            'diterima' => $totalAccepted,
            'aktif' => MagangApplication::where('status', 'diterima')->count(),
            'dinas_tersedia' => Dinas::count()
        ];

        $pastDayNames = [];
        for ($i = 6; $i >= 0; $i--) {
            $pastDayNames[] = now()->subDays($i)->locale('id')->minDayName;
        }

        $servicesList = [
            'Penelitian (Perguruan Tinggi)' => 'penelitian_pt',
            'Penelitian (Instansi / Lembaga Lainnya)' => 'penelitian_instansi',
            'KKL / PKL / Magang (Mahasiswa)' => 'kkl_mahasiswa',
            'KKN Mahasiswa' => 'kkn_mahasiswa',
            'KKL / PKL / Magang (Siswa Sekolah)' => 'kkl_siswa',
            'Pelaksanaan Kegiatan' => 'pelaksanaan_kegiatan',
            'Perpanjangan Izin Rekomendasi' => 'perpanjangan',
        ];

        $chartData = [];

        foreach ($servicesList as $groupKey => $slug) {
            $registeredCount = PermohonanLayanan::whereHas('jenisLayanan', function($q) use ($slug) {
                $q->where('slug', $slug);
            })->count();

            $acceptedCount = PermohonanLayanan::whereHas('jenisLayanan', function($q) use ($slug) {
                $q->where('slug', $slug);
            })->whereHas('statusMaster', function($q) {
                $q->whereIn('kode', ['disetujui', 'selesai']);
            })->count();

            $todayCount = PermohonanLayanan::whereDate('created_at', now()->format('Y-m-d'))
                ->whereHas('jenisLayanan', function($q) use ($slug) {
                    $q->where('slug', $slug);
                })->count();

            $weeklySeries = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $weeklySeries[] = PermohonanLayanan::whereDate('created_at', $date->format('Y-m-d'))
                    ->whereHas('jenisLayanan', function($q) use ($slug) {
                        $q->where('slug', $slug);
                    })->count();
            }

            $chartData[$groupKey] = [
                'registered' => $registeredCount,
                'accepted' => $acceptedCount,
                'today' => $todayCount,
                'weekly_series' => $weeklySeries
            ];
        }

        $userApplications = auth()->check() 
            ? PermohonanLayanan::with(['jenisLayanan', 'statusMaster', 'dinas', 'magangApplication.rekrutmen.bidang', 'magangApplication.bidang'])
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get()
            : collect();

        $userMagang = auth()->check()
            ? MagangApplication::with(['rekrutmen.dinas', 'rekrutmen.bidang', 'dinas', 'bidang', 'permohonanLayanan'])
                ->where('user_id', auth()->id())
                ->whereIn('status', ['diterima', 'aktif'])
                ->first()
            : null;

        $bidangParticipants = collect();
        if ($userMagang) {
            $bidangId = $userMagang->bidang_id ?? ($userMagang->rekrutmen?->bidang_id ?? null);
            $dinasId = $userMagang->dinas_id ?? ($userMagang->rekrutmen?->dinas_id ?? null);

            $query = MagangApplication::with(['user', 'absensis' => function($q) {
                $q->where('tanggal', now()->format('Y-m-d'));
            }, 'permohonanLayanan'])
            ->whereIn('status', ['diterima', 'aktif']);

            if ($bidangId) {
                $query->where(function($q) use ($bidangId) {
                    $q->where('bidang_id', $bidangId)
                      ->orWhereHas('rekrutmen', function($rq) use ($bidangId) {
                          $rq->where('bidang_id', $bidangId);
                      });
                });
            } elseif ($dinasId) {
                $query->where(function($q) use ($dinasId) {
                    $q->where('dinas_id', $dinasId)
                      ->orWhereHas('rekrutmen', function($rq) use ($dinasId) {
                          $rq->where('dinas_id', $dinasId);
                      });
                });
            }

            $bidangParticipants = $query->get();
        }

        return view('pelayanan.landing.index', compact(
            'rekrutmens', 
            'featuredInstansis', 
            'pesertas', 
            'chartData', 
            'pastDayNames',
            'totalRegistered',
            'totalAccepted',
            'stats',
            'userApplications',
            'userMagang',
            'bidangParticipants'
        ));
    }

    public function peserta(Request $request)
    {
        $search = $request->input('search');
        $instansiAsal = $request->input('instansi_asal');
        $dinasId = $request->input('dinas_id');

        $query = MagangApplication::with(['user', 'rekrutmen.dinas', 'rekrutmen.bidang', 'permohonanLayanan.jenisLayanan'])
            ->where('status', 'diterima');

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($instansiAsal) {
            $query->whereHas('permohonanLayanan', function($q) use ($instansiAsal) {
                $q->where('asal_instansi', $instansiAsal);
            });
        }

        if ($dinasId) {
            $query->whereHas('rekrutmen', function($q) use ($dinasId) {
                $q->where('dinas_id', $dinasId);
            });
        }

        $pesertas = $query->orderBy('updated_at', 'desc')->paginate(12);

        // Ambil semua instansi unik dari peserta magang yang diterima
        $semuaInstansi = \App\Models\PermohonanLayanan::whereIn('id', function($q) {
            $q->select('permohonan_layanan_id')
              ->from('magang_applications')
              ->where('status', 'diterima');
        })->pluck('asal_instansi')->filter()->unique()->values();

        // Ambil semua dinas untuk filter
        $semuaDinas = \App\Models\Dinas::orderBy('name')->get();

        foreach ($pesertas as $p) {
            $p->jurusan = $p->permohonanLayanan->jenisLayanan->nama ?? 'Umum';
            $p->instansi_asal = $p->permohonanLayanan->asal_instansi ?? 'Instansi/Kampus';
            $p->dinas = $p->rekrutmen?->dinas;
            $p->bidang = $p->rekrutmen?->bidang;
        }

        return view('pelayanan.landing.peserta', compact('pesertas', 'search', 'instansiAsal', 'dinasId', 'semuaInstansi', 'semuaDinas'));
    }

    public function instansiList(Request $request)
    {
        $query = Dinas::with(['rekrutmens' => function ($q) {
            $q->where('is_active', true);
        }, 'rekrutmens.magangApplications']);

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
                                 ->whereRaw('kuota > (SELECT COUNT(*) FROM magang_applications WHERE magang_applications.rekrutmen_id = rekrutmens.id AND magang_applications.status IN (?, ?, ?))', ['menunggu', 'diterima', 'aktif']);
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
                                 ->whereRaw('kuota > (SELECT COUNT(*) FROM magang_applications WHERE magang_applications.rekrutmen_id = rekrutmens.id AND magang_applications.status IN (?, ?, ?))', ['menunggu', 'diterima', 'aktif']);
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

        $instansis = $query->paginate(40)->withQueryString();

        return view('pelayanan.landing.instansi', compact('instansis'));
    }

    public function instansiDetail($id)
    {
        $instansi = Dinas::with(['bidang', 'rekrutmens' => function ($q) {
            $q->where('is_active', true)->with(['magangApplications' => function ($mq) {
                $mq->whereIn('status', ['diterima', 'aktif'])->with('user', 'permohonanLayanan');
            }]);
        }])->findOrFail($id);

        $totalKuota = $instansi->rekrutmens->sum('kuota');
        $slotTersedia = $instansi->rekrutmens->sum('slot_tersedia');
        $totalDiterima = MagangApplication::whereHas('rekrutmen', function ($q) use ($id) {
            $q->where('dinas_id', $id);
        })->whereIn('status', ['diterima', 'aktif'])->count();

        return view('pelayanan.landing.instansi_detail', compact('instansi', 'totalKuota', 'totalDiterima', 'slotTersedia'));
    }
    public function profile()
    {
        return view('pelayanan.landing.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'nik' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $tglLahir = $request->input('tanggal_lahir');
                    if (!$tglLahir) return;
                    try {
                        $age = \Carbon\Carbon::parse($tglLahir)->age;
                        if ($age >= 17 && empty($value)) {
                            $fail('NIK wajib diisi untuk pemohon berusia 17 tahun atau lebih.');
                        }
                    } catch (\Exception $e) {}
                }
            ],
            'no_hp' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'asal_instansi' => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            'nim' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only([
            'nik',
            'no_hp',
            'tempat_lahir',
            'tanggal_lahir',
            'asal_instansi',
            'program_studi',
            'nim',
            'alamat',
        ]);

        $data['name'] = $request->input('nama');
        $data['email'] = $request->input('email');

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success_swal', 'Profil berhasil diperbarui!');
    }

    /**
     * Verifikasi Publik Surat Rekomendasi via Token QR Code
     * Menampilkan data terbatas dengan masking privasi pemohon.
     */
    public function verifikasiSurat($token)
    {
        $surat = \App\Models\SuratRekomendasi::with(['permohonanLayanan.dinas', 'permohonanLayanan.user', 'permohonanLayanan.statusMaster'])
            ->where('verification_token', $token)
            ->first();

        if (!$surat || !$surat->permohonanLayanan) {
            return view('pelayanan.landing.verifikasi_surat', [
                'isValid' => false,
                'statusLabel' => 'TIDAK DITEMUKAN / TIDAK VALID',
                'nomorSurat' => '-',
                'namaPemohon' => '-',
                'instansiTujuan' => '-',
                'tanggalTerbit' => '-',
                'catatan' => 'Dokumen Surat Rekomendasi dengan tanda verifikasi digital ini tidak terdaftar dalam sistem resmi LENTERA Kabupaten Bogor.',
            ]);
        }

        $permohonan = $surat->permohonanLayanan;
        $namaAsli = $permohonan->atas_nama ?: ($permohonan->user->name ?? '-');

        // Masking nama pemohon demi privasi (misal: B*** S******)
        $maskedName = collect(explode(' ', trim($namaAsli)))->map(function ($part) {
            $len = mb_strlen($part);
            if ($len <= 2) {
                return $part;
            }
            return mb_substr($part, 0, 1) . str_repeat('*', min(6, $len - 1));
        })->implode(' ');

        $instansiTujuan = $permohonan->dinas?->name ?: ($permohonan->tempat_kegiatan ?: 'Pemerintah Kabupaten Bogor');
        $tanggalTerbit = $surat->tanggal_surat 
            ? \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y')
            : \Carbon\Carbon::parse($surat->created_at)->translatedFormat('d F Y');

        $statusKode = strtolower($permohonan->statusMaster->kode ?? '');
        $isDisetujui = in_array($statusKode, ['disetujui', 'selesai'], true);

        // Cek kedaluwarsa
        $isExpired = false;
        if ($permohonan->tanggal_selesai) {
            $isExpired = \Carbon\Carbon::parse($permohonan->tanggal_selesai)->endOfDay()->isPast();
        }

        if (!$isDisetujui) {
            $isValid = false;
            $statusLabel = 'TIDAK AKTIF / DIBATALKAN';
            $catatan = 'Status surat rekomendasi ini belum disetujui atau telah dibatalkan.';
        } elseif ($isExpired) {
            $isValid = false;
            $statusLabel = 'KEDALUWARSA';
            $catatan = 'Masa berlaku kegiatan/rekomendasi pada surat ini telah berakhir (' . \Carbon\Carbon::parse($permohonan->tanggal_selesai)->translatedFormat('d F Y') . ').';
        } else {
            $isValid = true;
            $statusLabel = 'VALID';
            $catatan = 'Surat Rekomendasi ini sah dan terverifikasi secara elektronik oleh Bakesbangpol Kabupaten Bogor.';
        }

        return view('pelayanan.landing.verifikasi_surat', [
            'isValid' => $isValid,
            'statusLabel' => $statusLabel,
            'nomorSurat' => $surat->nomor_surat ?: ('000.1.5/' . $permohonan->id . '/Bakesbangpol/' . date('Y')),
            'namaPemohon' => $maskedName,
            'instansiTujuan' => $instansiTujuan,
            'tanggalTerbit' => $tanggalTerbit,
            'catatan' => $catatan,
        ]);
    }
}
