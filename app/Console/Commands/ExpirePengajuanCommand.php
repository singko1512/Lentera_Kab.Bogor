<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MagangApplication;
use App\Models\SuratRekomendasi;
use App\Models\StatusMaster;
use App\Models\Notification;
use Carbon\Carbon;

class ExpirePengajuanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lentera:expire-pengajuan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memeriksa dan menandai pengajuan magang serta surat rekomendasi yang telah melewati masa berlaku sebagai expired.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $statusExpired = StatusMaster::firstOrCreate(
            ['kode' => 'expired'],
            ['kode' => 'expired', 'nama' => 'Kedaluwarsa', 'warna' => '#6c757d']
        );

        $now = Carbon::now();
        $expiredCount = 0;

        $this->info("Menjalankan pemeriksaan kedaluwarsa pengajuan pada {$now->toDateTimeString()}...");

        // =========================================================================
        // ATURAN 1: MagangApplication berstatus 'menunggu' yang melewati
        // batas masa berlaku pengajuan (sejak dibuat dan belum disetujui / diproses).
        // =========================================================================
        // TODO: konfirmasi ke mentor - apakah pengajuan yang belum diverifikasi Kesbangpol
        // dihitung murni dari tanggal pengajuan dibuat (created_at) atau tanggal_mulai kegiatan.
        // Saat ini dikonfigurasi melalui config('lentera.masa_berlaku_pengajuan_hari', 30).
        $masaPengajuanHari = config('lentera.masa_berlaku_pengajuan_hari', 30);
        $cutoffPengajuan = $now->copy()->subDays($masaPengajuanHari);

        $pendingApps = MagangApplication::where('status', 'menunggu')
            ->whereDoesntHave('permohonanLayanan.suratRekomendasi', function ($q) {
                $q->whereNotNull('berlaku_sampai');
            })
            ->where(function ($q) use ($now, $cutoffPengajuan) {
                $q->whereNotNull('expired_at')->where('expired_at', '<', $now)
                  ->orWhere(function ($sq) use ($cutoffPengajuan) {
                      $sq->whereNull('expired_at')->where('created_at', '<', $cutoffPengajuan);
                  });
            })
            ->with(['permohonanLayanan.statusMaster', 'dinas', 'user'])
            ->get();

        foreach ($pendingApps as $app) {
            $app->status = 'expired';
            $app->catatan_admin = trim(($app->catatan_admin ? $app->catatan_admin . "\n" : '') . "Kedaluwarsa otomatis oleh sistem: pengajuan melewati batas waktu {$masaPengajuanHari} hari.");
            $app->save();

            // Jika permohonan layanan belum selesai, ubah status permohonan menjadi expired
            if ($app->permohonanLayanan && in_array($app->permohonanLayanan->statusMaster?->kode, ['menunggu_verifikasi', 'perlu_revisi'])) {
                $app->permohonanLayanan->status_master_id = $statusExpired->id;
                $app->permohonanLayanan->save();
            }

            // Notifikasi ke mahasiswa
            if ($app->user_id) {
                Notification::create([
                    'user_id' => $app->user_id,
                    'judul' => 'Pengajuan Magang Kedaluwarsa',
                    'pesan' => 'Pengajuan magang Anda ke ' . ($app->dinas->name ?? 'instansi tujuan') . ' telah kedaluwarsa karena melewati batas waktu ' . $masaPengajuanHari . ' hari tanpa penyelesaian berkas.',
                    'link' => route('landing.profile'),
                    'dibaca' => false,
                ]);
            }

            $expiredCount++;
        }

        // =========================================================================
        // ATURAN 2: Surat Rekomendasi yang berlaku_sampai telah lewat
        // dan belum diterima oleh Dinas tujuan.
        // =========================================================================
        // TODO: konfirmasi ke mentor - apakah masa berlaku surat dihitung dari
        // tanggal_surat + masa_berlaku_surat_hari (default 30 hari) atau dapat
        // diperpanjang otomatis jika dinas tujuan terlambat memproses verifikasi.
        $today = $now->toDateString();
        $expiredSurats = SuratRekomendasi::whereNotNull('berlaku_sampai')
            ->where('berlaku_sampai', '<', $today)
            ->with(['permohonanLayanan.magangApplication', 'permohonanLayanan.statusMaster'])
            ->get();

        foreach ($expiredSurats as $surat) {
            $layanan = $surat->permohonanLayanan;
            if ($layanan) {
                $magang = $layanan->magangApplication;
                // Hanya jika belum diterima dinas (status masih 'menunggu')
                if ($magang && $magang->status === 'menunggu') {
                    $magang->status = 'expired';
                    $magang->catatan_admin = trim(($magang->catatan_admin ? $magang->catatan_admin . "\n" : '') . "Kedaluwarsa otomatis: masa berlaku Surat Rekomendasi telah habis pada {$surat->berlaku_sampai}.");
                    $magang->save();

                    if ($layanan->statusMaster?->kode !== 'expired') {
                        $layanan->status_master_id = $statusExpired->id;
                        $layanan->save();
                    }

                    if ($layanan->user_id) {
                        Notification::create([
                            'user_id' => $layanan->user_id,
                            'judul' => 'Masa Berlaku Surat Rekomendasi Habis',
                            'pesan' => 'Masa berlaku Surat Rekomendasi Anda (#' . $layanan->id . ') telah habis pada ' . $surat->berlaku_sampai . '. Silakan ajukan permohonan baru jika masih memerlukan rekomendasi.',
                            'link' => route('landing.profile'),
                            'dibaca' => false,
                        ]);
                    }

                    $expiredCount++;
                }
            }
        }

        $this->info("Pemeriksaan selesai. Total pengajuan/surat kedaluwarsa diproses: {$expiredCount} data. Kuota telah dikembalikan.");

        return Command::SUCCESS;
    }
}
