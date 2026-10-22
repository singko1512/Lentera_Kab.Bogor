<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('lentera:expire-pengajuan')->daily();

Artisan::command('mail:test {email}', function (string $email) {
    $this->info("=== Pengujian Pengiriman Email SMTP Bangga Online ===");
    $this->line("Mailer: " . config('mail.default'));
    $this->line("Host  : " . config('mail.mailers.smtp.host') . ":" . config('mail.mailers.smtp.port'));
    $this->line("User  : " . (config('mail.mailers.smtp.username') ?: '(KOSONG - Isi MAIL_USERNAME di .env)'));
    $this->line("From  : " . config('mail.from.address'));
    $this->newLine();

    if (empty(config('mail.mailers.smtp.username')) || empty(config('mail.mailers.smtp.password'))) {
        $this->warn("PERINGATAN: MAIL_USERNAME atau MAIL_PASSWORD di file .env Anda masih kosong!");
        $this->line("Jika menggunakan Gmail (smtp.gmail.com), Anda wajib mengisinya dengan email Gmail dan 16 digit Sandi Aplikasi (App Password).");
        $this->newLine();
    }

    $this->info("Mengirim email percobaan ke: {$email}...");
    try {
        \Illuminate\Support\Facades\Mail::raw("Halo!\n\nIni adalah email percobaan untuk memverifikasi bahwa konfigurasi SMTP Bangga Online Kabupaten Bogor di lingkungan lokal Anda sudah aktif dan berhasil mengirim email.\n\nWaktu kirim: " . now()->toDateTimeString(), function ($msg) use ($email) {
            $msg->to($email)->subject('Test Koneksi SMTP Bangga Online Local');
        });
        $this->info("BERHASIL! Email percobaan telah berhasil dikirim ke: {$email}");
    } catch (\Throwable $e) {
        $this->error("GAGAL MENGIRIM EMAIL: " . $e->getMessage());
        if (str_contains($e->getMessage(), '530') || str_contains($e->getMessage(), 'Authentication Required')) {
            $this->warn("\nSolusi untuk Error 530 (Authentication Required):");
            $this->line("1. Pastikan MAIL_USERNAME di .env diisi alamat Gmail Anda (contoh: user@gmail.com).");
            $this->line("2. Buka https://myaccount.google.com/apppasswords");
            $this->line("3. Buat Sandi Aplikasi (16 karakter).");
            $this->line("4. Masukkan ke MAIL_PASSWORD di .env.");
            $this->line("5. Pastikan MAIL_FROM_ADDRESS di .env sama dengan MAIL_USERNAME.");
            $this->line("6. Jalankan: php artisan optimize:clear lalu coba kirim lagi.");
        }
    }
})->purpose('Kirim email percobaan untuk memverifikasi pengaturan SMTP');

