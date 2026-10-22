<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ResetDinasPasswordsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lentera:reset-dinas-passwords
                            {--output= : Path file output Excel (default: Daftar_Password_Dinas_Lentera.xlsx)}
                            {--length=12 : Panjang karakter password acak (default: 12)}
                            {--dry-run : Uji coba generate password dan Excel tanpa mengubah password di database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ubah password semua akun dinas di tabel admins menjadi password acak dan ekspor ke file Excel.';

    /**
     * Generate password acak yang kuat dan mudah dibaca (menghindari karakter ambigu seperti 0, O, 1, l, I).
     */
    protected function generateSecurePassword(int $length = 12): string
    {
        $uppers  = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // 22 karakter (tanpa I, O)
        $lowers  = 'abcdefghijkmnopqrstuvwxyz'; // 24 karakter (tanpa l)
        $numbers = '23456789';                  // 8 karakter (tanpa 0, 1)
        $symbols = '@#%!*?';                    // 6 simbol aman dan umum

        // Memastikan minimal ada 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 simbol
        $pwd = [
            $uppers[random_int(0, strlen($uppers) - 1)],
            $lowers[random_int(0, strlen($lowers) - 1)],
            $numbers[random_int(0, strlen($numbers) - 1)],
            $symbols[random_int(0, strlen($symbols) - 1)],
        ];

        $all = $uppers . $lowers . $numbers . $symbols;
        for ($i = 4; $i < $length; $i++) {
            $pwd[] = $all[random_int(0, strlen($all) - 1)];
        }

        shuffle($pwd);
        return implode('', $pwd);
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $length   = max(8, (int) $this->option('length'));
        $output   = $this->option('output') ?: base_path('Daftar_Password_Dinas_Lentera.xlsx');

        $this->info("=== Reset Password Akun Dinas & Ekspor Excel ===");
        if ($isDryRun) {
            $this->warn("[DRY-RUN MODE] Password di database TIDAK akan diubah.");
        }

        $admins = Admin::with('dinas')
            ->where('role', 'dinas')
            ->orderBy('id', 'asc')
            ->get();

        $total = $admins->count();
        if ($total === 0) {
            $this->error("Tidak ditemukan akun admin dengan role 'dinas' di tabel admins.");
            return Command::FAILURE;
        }

        $this->info("Ditemukan {$total} akun dinas di tabel admins.");

        $records = [];
        $usedPasswords = [];

        $this->output->progressStart($total);

        DB::beginTransaction();
        try {
            foreach ($admins as $index => $adm) {
                // Generate password unik
                do {
                    $plainPassword = $this->generateSecurePassword($length);
                } while (isset($usedPasswords[$plainPassword]));
                $usedPasswords[$plainPassword] = true;

                if (! $isDryRun) {
                    DB::table('admins')
                        ->where('id', $adm->id)
                        ->update([
                            'password'   => Hash::make($plainPassword),
                            'updated_at' => now(),
                        ]);
                }

                $records[] = [
                    'no'           => $index + 1,
                    'id'           => $adm->id,
                    'name'         => $adm->name,
                    'dinas_name'   => $adm->dinas ? $adm->dinas->name : '-',
                    'username'     => $adm->username ?: '-',
                    'email'        => $adm->email,
                    'password'     => $plainPassword,
                    'status_akun'  => ucfirst($adm->status_akun ?? 'aktif'),
                    'role'         => strtoupper($adm->role),
                    'updated_at'   => now()->format('Y-m-d H:i:s'),
                ];

                $this->output->progressAdvance();
            }

            if (! $isDryRun) {
                DB::commit();
            } else {
                DB::rollBack();
            }

            $this->output->progressFinish();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error("Gagal melakukan reset password: " . $e->getMessage());
            return Command::FAILURE;
        }

        $this->info("Menyusun file Excel: {$output}");
        $this->generateExcelFile($records, $output, $isDryRun);

        $this->newLine();
        $this->info("BERHASIL! {$total} akun dinas telah berhasil diproses.");
        $this->info("File Excel tersimpan di: {$output}");

        return Command::SUCCESS;
    }

    /**
     * Format dan simpan file Excel dengan PhpSpreadsheet.
     */
    protected function generateExcelFile(array $records, string $filePath, bool $isDryRun): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Daftar Akun Dinas');
        $sheet->setShowGridLines(true);

        // Header Dokumen
        $sheet->setCellValue('A2', 'DAFTAR AKUN & PASSWORD DINAS / KECAMATAN / RSUD');
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->getFont()->setSize(16)->setBold(true)->getColor()->setARGB('FF1E3A8A');

        $sheet->setCellValue('A3', 'Sistem Informasi Pelayanan & Magang Bangga Online - Pemerintah Kabupaten Bogor');
        $sheet->mergeCells('A3:J3');
        $sheet->getStyle('A3')->getFont()->setSize(11)->setItalic(true)->getColor()->setARGB('FF4B5563');

        $infoDate = 'Digenerate pada: ' . date('d F Y, H:i:s') . ' WIB | Total Akun: ' . count($records);
        if ($isDryRun) {
            $infoDate .= ' (SIMULASI / DRY-RUN)';
        }
        $sheet->setCellValue('A4', $infoDate);
        $sheet->mergeCells('A4:J4');
        $sheet->getStyle('A4')->getFont()->setSize(10)->getColor()->setARGB('FF6B7280');

        // Header Kolom Tabel
        $headers = [
            'A6' => 'NO',
            'B6' => 'ID ADMIN',
            'C6' => 'NAMA AKUN',
            'D6' => 'NAMA INSTANSI / DINAS',
            'E6' => 'USERNAME',
            'F6' => 'EMAIL LOGIN',
            'G6' => 'PASSWORD BARU',
            'H6' => 'STATUS AKUN',
            'I6' => 'ROLE',
            'J6' => 'TERAKHIR UPDATE',
        ];

        foreach ($headers as $cell => $title) {
            $sheet->setCellValue($cell, $title);
        }

        $headerStyle = [
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1E3A8A'], // Navy Blue
            ],
        ];
        $sheet->getStyle('A6:J6')->applyFromArray($headerStyle);
        $sheet->getRowDimension(6)->setRowHeight(30);

        // Isi Data
        $row = 7;
        foreach ($records as $item) {
            $sheet->setCellValueExplicit('A' . $row, $item['no'], DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('B' . $row, $item['id'], DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('C' . $row, $item['name'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D' . $row, $item['dinas_name'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $row, $item['username'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F' . $row, $item['email'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('G' . $row, $item['password'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('H' . $row, $item['status_akun'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('I' . $row, $item['role'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('J' . $row, $item['updated_at'], DataType::TYPE_STRING);

            // Warna baris selang-seling (Zebra striping)
            $isEven = ($item['no'] % 2 === 0);
            $rowBg  = $isEven ? 'FFF8FAFC' : 'FFFFFFFF';
            $sheet->getStyle('A' . $row . ':J' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB($rowBg);

            // Alignment & Styling spesifik kolom
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $row)->getFont()->setBold(true);

            // Kolom Password diberi sorotan khusus dan font Consolas monospaced
            $sheet->getStyle('G' . $row)->getFont()->setName('Consolas')->setSize(11)->setBold(true);
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF3C7'); // Light Amber

            $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getRowDimension($row)->setRowHeight(22);
            $row++;
        }

        $lastRow = $row - 1;

        // Border tipis untuk seluruh tabel
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FFE2E8F0'],
                ],
            ],
        ];
        $sheet->getStyle('A6:J' . $lastRow)->applyFromArray($borderStyle);

        // Auto size untuk semua kolom
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Freeze panes agar header tetap terlihat saat scroll ke bawah
        $sheet->freezePane('A7');
        $sheet->setAutoFilter('A6:J' . $lastRow);

        // Simpan file
        $dir = dirname($filePath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
    }
}
