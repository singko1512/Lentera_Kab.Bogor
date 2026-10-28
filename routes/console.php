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

Artisan::command('admin:set-superadmin {password=admin123}', function (string $password = 'admin123') {
    $hashed = \Illuminate\Support\Facades\Hash::make($password);
    
    // Update or create in admins table
    $admin = \App\Models\Admin::updateOrCreate(
        ['username' => 'superadmin'],
        [
            'email' => 'superadmin@dinas.com',
            'name' => 'Super Administrator',
            'password' => $hashed,
            'role' => 'superadmin',
            'status_akun' => 'aktif',
        ]
    );

    // Also update any matching user record if exists
    \App\Models\User::where('username', 'superadmin')
        ->orWhere('email', 'superadmin@dinas.com')
        ->update(['password' => $hashed, 'status_akun' => 'aktif']);

    $this->info("Superadmin credentials updated successfully!");
    $this->line("Username : " . $admin->username);
    $this->line("Email    : " . $admin->email);
    $this->line("Password : {$password}");
})->purpose('Set or reset superadmin password');

Artisan::command('users:reset-dummy', function () {
    $this->info("Menghapus akun user biasa lama dan membuat 10 akun dummy baru yang aktif...");
    $this->call(\Database\Seeders\DummyUserFreshSeeder::class);
    $this->info("Berhasil! 10 Akun dummy baru telah aktif dan siap digunakan untuk pengujian.\n");

    $dummyList = \Database\Seeders\DummyUserFreshSeeder::getDummyUsers();
    $pwdMap = collect($dummyList)->keyBy('email');

    $users = \App\Models\User::where('role', 'peserta')->get();
    $headers = ['ID', 'Nama', 'Email', 'Username', 'Password (Random)', 'Asal Instansi', 'Status'];
    $rows = $users->map(fn($u) => [
        $u->id,
        $u->name,
        $u->email,
        $u->username,
        $pwdMap[$u->email]['plain_password'] ?? '-',
        $u->asal_instansi,
        $u->status_akun . ($u->email_verified_at ? ' (Verified)' : '')
    ])->toArray();

    $this->table($headers, $rows);
    $this->call('users:export-excel');
})->purpose('Reset akun peserta biasa dan buat 10 data dummy aktif siap uji');

Artisan::command('users:export-excel', function () {
    $this->info("Menyusun file Excel daftar akun dan password uji coba...");

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    // ==========================================
    // SHEET 1: Akun Uji Mahasiswa (Dummy Users)
    // ==========================================
    $sheet1 = $spreadsheet->getActiveSheet();
    $sheet1->setTitle('Akun Uji Peserta');

    // Judul & Header Info
    $sheet1->setCellValue('A1', 'DAFTAR AKUN UJI COBA PESERTA / MAHASISWA (TRIAL)');
    $sheet1->mergeCells('A1:J1');
    $sheet1->getStyle('A1')->getFont()->setName('Arial')->setSize(14)->setBold(true)->getColor()->setARGB('FF1E3A8A');

    $sheet1->setCellValue('A2', 'Aplikasi Bangga Online - Pemerintah Kabupaten Bogor');
    $sheet1->mergeCells('A2:J2');
    $sheet1->getStyle('A2')->getFont()->setName('Arial')->setSize(11)->setBold(true)->getColor()->setARGB('FF475569');

    $sheet1->setCellValue('A3', 'Catatan: Akun di bawah ini berstatus AKTIF (VERIFIED) dan FRESH (siap digunakan untuk uji pengajuan rekomendasi & ulasan aplikasi).');
    $sheet1->mergeCells('A3:J3');
    $sheet1->getStyle('A3')->getFont()->setName('Arial')->setSize(9)->setItalic(true)->getColor()->setARGB('FF64748B');

    // Kolom Header Table
    $headers1 = [
        'A5' => 'NO',
        'B5' => 'NAMA AKUN',
        'C5' => 'ASAL INSTANSI / KAMPUS',
        'D5' => 'PROGRAM STUDI',
        'E5' => 'NIM / NIS',
        'F5' => 'USERNAME',
        'G5' => 'EMAIL (LOGIN ID)',
        'H5' => 'PASSWORD',
        'I5' => 'STATUS AKUN',
        'J5' => 'KETERANGAN',
    ];

    foreach ($headers1 as $cell => $val) {
        $sheet1->setCellValue($cell, $val);
    }

    $headerStyle = [
        'font' => ['name' => 'Arial', 'bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 10],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FF1E3A8A'], // Navy
        ],
    ];
    $sheet1->getStyle('A5:J5')->applyFromArray($headerStyle);
    $sheet1->getRowDimension(5)->setRowHeight(28);

    // Ambil Data Peserta Dummy
    $dummyUsers = \App\Models\User::where('role', 'peserta')->orderBy('id', 'asc')->get();
    $dummyList = \Database\Seeders\DummyUserFreshSeeder::getDummyUsers();
    $pwdMap = collect($dummyList)->keyBy('email');
    $row = 6;
    $no = 1;

    foreach ($dummyUsers as $u) {
        $sheet1->setCellValueExplicit('A' . $row, $no, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        $sheet1->setCellValueExplicit('B' . $row, $u->name, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet1->setCellValueExplicit('C' . $row, $u->asal_instansi, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet1->setCellValueExplicit('D' . $row, $u->program_studi, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet1->setCellValueExplicit('E' . $row, $u->nim, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet1->setCellValueExplicit('F' . $row, $u->username, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet1->setCellValueExplicit('G' . $row, $u->email, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet1->setCellValueExplicit('H' . $row, $pwdMap[$u->email]['plain_password'] ?? 'password123', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet1->setCellValueExplicit('I' . $row, 'Aktif (Verified)', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet1->setCellValueExplicit('J' . $row, 'Siap Uji Pengajuan Rekomendasi', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        // Zebra striping
        $bg = ($no % 2 === 0) ? 'FFF8FAFC' : 'FFFFFFFF';
        $sheet1->getStyle('A' . $row . ':J' . $row)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB($bg);

        // Alignment
        $sheet1->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet1->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet1->getStyle('I' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet1->getStyle('F' . $row)->getFont()->setBold(true);

        // Password Column styling (Amber highlight & Consolas)
        $sheet1->getStyle('H' . $row)->getFont()->setName('Consolas')->setSize(11)->setBold(true);
        $sheet1->getStyle('H' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet1->getStyle('H' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF3C7');

        $sheet1->getRowDimension($row)->setRowHeight(22);
        $row++;
        $no++;
    }

    $lastRow1 = $row - 1;
    $sheet1->getStyle('A5:J' . $lastRow1)->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FFE2E8F0'],
            ],
        ],
    ]);

    foreach (range('A', 'J') as $col) {
        $sheet1->getColumnDimension($col)->setAutoSize(true);
    }
    $sheet1->freezePane('A6');
    $sheet1->setAutoFilter('A5:J' . $lastRow1);

    // ==========================================
    // SHEET 2: Akun Super Admin & Panduan Akses
    // ==========================================
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('Akun Super Admin');

    $sheet2->setCellValue('A1', 'KREDENSIAL AKUN SUPER ADMINISTRATOR');
    $sheet2->mergeCells('A1:G1');
    $sheet2->getStyle('A1')->getFont()->setName('Arial')->setSize(14)->setBold(true)->getColor()->setARGB('FF1E3A8A');

    $sheet2->setCellValue('A2', 'Akses khusus untuk memantau Ulasan & Hasil Rating Aplikasi');
    $sheet2->mergeCells('A2:G2');
    $sheet2->getStyle('A2')->getFont()->setName('Arial')->setSize(11)->setBold(true)->getColor()->setARGB('FF475569');

    $headers2 = [
        'A4' => 'NO',
        'B4' => 'ROLE',
        'C4' => 'NAMA ADMIN',
        'D4' => 'USERNAME',
        'E4' => 'EMAIL (LOGIN ID)',
        'F4' => 'PASSWORD',
        'G4' => 'URL / MENU AKSES',
    ];

    foreach ($headers2 as $cell => $val) {
        $sheet2->setCellValue($cell, $val);
    }
    $sheet2->getStyle('A4:G4')->applyFromArray($headerStyle);
    $sheet2->getRowDimension(4)->setRowHeight(28);

    // Data Superadmin
    $adminRow = 5;
    $sheet2->setCellValueExplicit('A' . $adminRow, 1, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
    $sheet2->setCellValueExplicit('B' . $adminRow, 'Superadmin', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet2->setCellValueExplicit('C' . $adminRow, 'Super Administrator', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet2->setCellValueExplicit('D' . $adminRow, 'superadmin', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet2->setCellValueExplicit('E' . $adminRow, 'superadmin@dinas.com', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet2->setCellValueExplicit('F' . $adminRow, 'admin123', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet2->setCellValueExplicit('G' . $adminRow, 'Login di /login -> Master Data -> Ulasan Aplikasi (/superadmin/reviews)', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet2->getStyle('A' . $adminRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet2->getStyle('B' . $adminRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet2->getStyle('D' . $adminRow)->getFont()->setBold(true);

    $sheet2->getStyle('F' . $adminRow)->getFont()->setName('Consolas')->setSize(11)->setBold(true);
    $sheet2->getStyle('F' . $adminRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet2->getStyle('F' . $adminRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF3C7');

    $sheet2->getStyle('A4:G5')->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FFE2E8F0'],
            ],
        ],
    ]);

    foreach (range('A', 'G') as $col) {
        $sheet2->getColumnDimension($col)->setAutoSize(true);
    }

    // Set active sheet back to Sheet 1
    $spreadsheet->setActiveSheetIndex(0);

    // Simpan di root folder dan di public folder
    $fileRoot = base_path('Daftar_Akun_Uji_Coba_Bangga_Online.xlsx');
    $filePublic = public_path('Daftar_Akun_Uji_Coba_Bangga_Online.xlsx');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

    $savedRoot = $fileRoot;
    try {
        $writer->save($fileRoot);
    } catch (\Throwable $e) {
        $savedRoot = base_path('Daftar_Akun_Uji_Coba_Bangga_Online_Terbaru.xlsx');
        $writer->save($savedRoot);
    }

    $savedPublic = $filePublic;
    try {
        $writer->save($filePublic);
    } catch (\Throwable $e) {
        $savedPublic = public_path('Daftar_Akun_Uji_Coba_Bangga_Online_Terbaru.xlsx');
        $writer->save($savedPublic);
    }

    $this->info("BERHASIL! File Excel telah dibuat:");
    $this->line("1. Root Project  : {$savedRoot}");
    $this->line("2. Public Folder : {$savedPublic}");
})->purpose('Export daftar akun dan password uji coba ke file Excel .xlsx');


