<?php

namespace App\Http\Controllers\Kesbangpol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermohonanLayanan;
use App\Models\StatusMaster;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class LayananController extends Controller
{
    public function dashboard()
    {
        $totalPermohonan = PermohonanLayanan::count();
        $sedangDiproses = PermohonanLayanan::whereHas('statusMaster', function ($q) {
            $q->whereIn('kode', ['menunggu_verifikasi', 'perlu_revisi']);
        })->count();
        $selesai = PermohonanLayanan::whereHas('statusMaster', function ($q) {
            $q->whereIn('kode', ['disetujui', 'selesai']);
        })->count();
        $ditolak = PermohonanLayanan::whereHas('statusMaster', function ($q) {
            $q->where('kode', 'ditolak');
        })->count();

        $pengajuanTerbarus = PermohonanLayanan::with(['jenisLayanan', 'statusMaster'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('pelayanan.kesbangpol.dashboard', compact(
            'totalPermohonan', 'sedangDiproses', 'selesai', 'ditolak', 'pengajuanTerbarus'
        ));
    }

    public function index()
    {
        $layanans = PermohonanLayanan::with(['jenisLayanan', 'statusMaster'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('pelayanan.kesbangpol.layanan.index', compact('layanans'));
    }

    public function show($id)
    {
        $layanan = PermohonanLayanan::with(['jenisLayanan', 'statusMaster'])->findOrFail($id);
        $allDinas = \App\Models\Dinas::where('is_kesbangpol', false)->orderBy('name')->get();
        return view('pelayanan.kesbangpol.layanan.show', compact('layanan', 'allDinas'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'dinas_id' => 'nullable|exists:dinas,id',
        ]);

        $layanan = PermohonanLayanan::findOrFail($id);
        $statusMaster = StatusMaster::where('kode', $request->status)->firstOrFail();

        $layanan->status_master_id = $statusMaster->id;

        if (in_array($request->status, ['perlu_revisi', 'ditolak'])) {
            $layanan->keterangan = $request->keterangan;
        }

        if ($request->status === 'disetujui') {
            if ($request->hasFile('file_surat_keluaran')) {
                $path = $request->file('file_surat_keluaran')->store('permohonan/keluaran', 'public');
                $layanan->file_surat_keluaran = $path;
            } else {
                // Generate DOCX otomatis dari template_kesbangpol.docx jika tidak ada upload manual
                try {
                    $templatePath = resource_path('templates/template_kesbangpol.docx');
                    if (!file_exists($templatePath)) {
                        $templatePath = base_path('template_kesbangpol.docx');
                    }

                    if (file_exists($templatePath)) {
                        $fileName = 'Surat_Rekomendasi_Kesbangpol_' . $layanan->id . '_' . \Illuminate\Support\Str::slug($layanan->atas_nama ?? 'pemohon') . '.docx';
                        $relativeStoragePath = 'permohonan/keluaran/' . $fileName;
                        $outputPath = storage_path('app/public/' . $relativeStoragePath);

                        if (!file_exists(dirname($outputPath))) {
                            mkdir(dirname($outputPath), 0755, true);
                        }
                        copy($templatePath, $outputPath);

                        $zip = new \ZipArchive();
                        if ($zip->open($outputPath) === TRUE) {
                            $xml = $zip->getFromName('word/document.xml');
                            $dinasName = $layanan->tempat_kegiatan ?? 'Dinas Tujuan';

                            $tglSurat = \Carbon\Carbon::parse(now())->translatedFormat('d F Y');
                            $tglAsal = \Carbon\Carbon::parse($layanan->created_at ?? now())->translatedFormat('d F Y');
                            $tglMulai = \Carbon\Carbon::parse($layanan->tanggal_mulai ?? now())->translatedFormat('d F Y');
                            $tglSelesai = \Carbon\Carbon::parse($layanan->tanggal_selesai ?? now())->translatedFormat('d F Y');

                            $replacements = [
                                '${tanggal_surat}' => $tglSurat,
                                '${nomor_surat}' => '070/' . $layanan->id . '/Bakesbangpol/' . date('Y'),
                                '${sifat_surat}' => 'Biasa',
                                '${lampiran_surat}' => '-',
                                '${tujuan_surat}' => 'Kepala ' . $dinasName,
                                '${tempat_tujuan}' => 'Kabupaten Bogor',
                                '${asal_surat}' => $layanan->asal_instansi ?? 'Perguruan Tinggi / Sekolah',
                                '${nomor_asal_surat}' => $layanan->nomor_surat_pengantar ?: ('SRT/' . $layanan->id . '/' . date('Y')),
                                '${tanggal_asal_surat}' => $tglAsal,
                                '${no_mhs}' => '1.',
                                '${nama_mahasiswa}' => $layanan->atas_nama ?? ($layanan->user->name ?? '-'),
                                '${alamat_pemohon}' => $layanan->user->alamat ?? $layanan->alamat ?? 'Kabupaten Bogor',
                                '${nama_penanggung_jawab}' => $layanan->atas_nama ?? ($layanan->user->name ?? '-'),
                                '${jumlah_peserta}' => ($layanan->jumlah_anggota ?? 1) . ' Orang',
                                '${tenggang_waktu}' => $tglMulai . ' s.d ' . $tglSelesai,
                                '${tempat_pkl}' => $dinasName,
                                '${nama_pejabat}' => 'Drs. BAMBANG WIDODO TAWEKAL, M.Si',
                                '${pangkat_pejabat}' => 'Pembina Utama Muda, IV/c',
                                '${nip_pejabat}' => '19680512 199003 1 005',
                            ];

                            $xml = str_replace(array_keys($replacements), array_values($replacements), $xml);
                            $zip->addFromString('word/document.xml', $xml);
                            $zip->close();

                            $layanan->file_surat_keluaran = $relativeStoragePath;
                        }
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal generate docx rekomendasi: ' . $e->getMessage());
                }
            }
        }

        $layanan->save();

        if ($request->status === 'disetujui' || $request->status === 'selesai') {
            \App\Models\Notification::create([
                'user_id' => $layanan->user_id,
                'judul' => 'Rekomendasi Kesbangpol Disetujui',
                'pesan' => 'Permohonan Rekomendasi Kesbangpol Anda (#' . $layanan->id . ') telah disetujui dan Surat Rekomendasi telah diterbitkan. Silakan mendaftar ke lowongan Dinas tujuan Anda melalui halaman Lowongan Magang.',
                'link' => route('landing.instansi'),
            ]);

            // Update untuk perpanjangan
            if ($layanan->jenisLayanan && $layanan->jenisLayanan->slug === 'perpanjangan') {
                $magangApplication = \App\Models\MagangApplication::where('user_id', $layanan->user_id)
                    ->whereIn('status', ['menunggu', 'diterima', 'aktif'])
                    ->latest()
                    ->first();
                    
                if ($magangApplication) {
                    $magangApplication->tanggal_mulai = $layanan->tanggal_mulai;
                    $magangApplication->tanggal_selesai = $layanan->tanggal_selesai;
                    $magangApplication->save();
                }
            }
        } elseif ($request->status === 'ditolak' || $request->status === 'perlu_revisi') {
            \App\Models\Notification::create([
                'user_id' => $layanan->user_id,
                'judul' => $request->status === 'ditolak' ? 'Permohonan Kesbangpol Ditolak' : 'Permohonan Kesbangpol Perlu Revisi',
                'pesan' => 'Permohonan Rekomendasi Kesbangpol Anda (#' . $layanan->id . ') ' . ($request->status === 'ditolak' ? 'ditolak.' : 'memerlukan revisi: ') . ($request->keterangan ?? ''),
                'link' => route('landing.profile'),
            ]);
        }

        return redirect()->route('kesbangpol.layanan.show', $id)
            ->with('success', 'Status permohonan berhasil diperbarui dan Surat Rekomendasi telah diterbitkan!');
    }

    /**
     * Generate & Download Surat Rekomendasi Kesbangpol (.docx).
     */
    public function generateDocx($id)
    {
        $layanan = PermohonanLayanan::with(['jenisLayanan', 'user', 'statusMaster'])->findOrFail($id);

        $templatePath = resource_path('templates/template_kesbangpol.docx');
        if (!file_exists($templatePath)) {
            if (file_exists(storage_path('app/templates/template_kesbangpol.docx'))) {
                $templatePath = storage_path('app/templates/template_kesbangpol.docx');
            } elseif (file_exists(base_path('template_kesbangpol.docx'))) {
                $templatePath = base_path('template_kesbangpol.docx');
            } else {
                abort(404, 'Template surat rekomendasi (.docx) tidak ditemukan.');
            }
        }

        $fileName = 'Surat_Rekomendasi_Kesbangpol_' . $layanan->id . '_' . \Illuminate\Support\Str::slug($layanan->atas_nama ?? 'pemohon') . '.docx';
        $tempOutput = storage_path('app/public/permohonan/keluaran/' . $fileName);

        if (!file_exists(dirname($tempOutput))) {
            mkdir(dirname($tempOutput), 0755, true);
        }

        copy($templatePath, $tempOutput);

        $zip = new \ZipArchive();
        if ($zip->open($tempOutput) === TRUE) {
            $xml = $zip->getFromName('word/document.xml');
            $dinasName = $layanan->tempat_kegiatan ?? 'Dinas Tujuan';

            $tglSurat = \Carbon\Carbon::parse($layanan->updated_at ?? now())->translatedFormat('d F Y');
            $tglAsal = \Carbon\Carbon::parse($layanan->created_at ?? now())->translatedFormat('d F Y');
            $tglMulai = \Carbon\Carbon::parse($layanan->tanggal_mulai ?? now())->translatedFormat('d F Y');
            $tglSelesai = \Carbon\Carbon::parse($layanan->tanggal_selesai ?? now())->translatedFormat('d F Y');

            $replacements = [
                '${tanggal_surat}' => $tglSurat,
                '${nomor_surat}' => '070/' . $layanan->id . '/Bakesbangpol/' . date('Y'),
                '${sifat_surat}' => 'Biasa',
                '${lampiran_surat}' => '-',
                '${tujuan_surat}' => 'Kepala ' . $dinasName,
                '${tempat_tujuan}' => 'Kabupaten Bogor',
                '${asal_surat}' => $layanan->asal_instansi ?? 'Perguruan Tinggi / Sekolah',
                '${nomor_asal_surat}' => $layanan->nomor_surat_pengantar ?: ('SRT/' . $layanan->id . '/' . date('Y')),
                '${tanggal_asal_surat}' => $tglAsal,
                '${no_mhs}' => '1.',
                '${nama_mahasiswa}' => $layanan->atas_nama ?? ($layanan->user->name ?? '-'),
                '${alamat_pemohon}' => $layanan->user->alamat ?? $layanan->alamat ?? 'Kabupaten Bogor',
                '${nama_penanggung_jawab}' => $layanan->atas_nama ?? ($layanan->user->name ?? '-'),
                '${jumlah_peserta}' => ($layanan->jumlah_anggota ?? 1) . ' Orang',
                '${tenggang_waktu}' => $tglMulai . ' s.d ' . $tglSelesai,
                '${tempat_pkl}' => $dinasName,
                '${nama_pejabat}' => 'Drs. BAMBANG WIDODO TAWEKAL, M.Si',
                '${pangkat_pejabat}' => 'Pembina Utama Muda, IV/c',
                '${nip_pejabat}' => '19680512 199003 1 005',
            ];

            $xml = str_replace(array_keys($replacements), array_values($replacements), $xml);
            $zip->addFromString('word/document.xml', $xml);
            $zip->close();
        }

        return response()->download($tempOutput, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    /**
     * Generate & Download / View Surat Rekomendasi Kesbangpol (.pdf) dengan QR Code.
     */
    public function downloadPdf($id)
    {
        $layanan = PermohonanLayanan::with(['jenisLayanan', 'user', 'statusMaster'])->findOrFail($id);

        $qrUrl = route('surat.pdf', $id);

        $pdf = Pdf::loadView('pdf.surat_kesbangpol', compact('layanan', 'qrUrl'))
            ->setPaper('a4', 'portrait');

        $fileName = 'Surat_Rekomendasi_Kesbangpol_' . $layanan->id . '_' . \Illuminate\Support\Str::slug($layanan->atas_nama ?? 'pemohon') . '.pdf';

        return $pdf->stream($fileName);
    }
}
