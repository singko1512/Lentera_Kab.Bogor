<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dinas;
use App\Models\Bidang;
use Illuminate\Support\Str;

class AuditBidangCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lentera:audit-bidang
                            {--json : Output sebagai JSON}
                            {--fix-pikap : Tambahkan Bidang PIKAP ke Diskominfo (sudah dikonfirmasi mentor)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit master bidang: laporan dinas tanpa bidang, jumlah bidang per dinas, duplikasi nama, dan perbandingan dengan dinas_bidang.json.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $jsonPath = database_path('seeders/dinas_bidang.json');

        if (! file_exists($jsonPath)) {
            $this->error("File dinas_bidang.json tidak ditemukan di: {$jsonPath}");
            return Command::FAILURE;
        }

        $jsonData = json_decode(file_get_contents($jsonPath), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Gagal mem-parse dinas_bidang.json: ' . json_last_error_msg());
            return Command::FAILURE;
        }

        // --- 1. Data dari database ---
        $allDinas  = Dinas::with('bidang')->orderBy('name')->get();
        $allBidang = Bidang::with('dinas')->get();

        // --- 2. Data dari JSON, kelompokkan per instansi ---
        $jsonPerInstansi = [];
        foreach ($jsonData as $item) {
            $instansiNorm  = $this->normStr($item['instansi'] ?? '');
            $unitKerjaNorm = $this->normStr($item['unit_kerja'] ?? '');
            if ($instansiNorm && $unitKerjaNorm) {
                $jsonPerInstansi[$instansiNorm][] = $unitKerjaNorm;
            }
        }
        foreach ($jsonPerInstansi as $instansi => $bidangs) {
            $jsonPerInstansi[$instansi] = array_values(array_unique($bidangs));
        }

        // --- 3. Analisis ---

        // A. Dinas tanpa bidang
        $dinasTanpaBidang = $allDinas->filter(fn($d) => $d->bidang->isEmpty());

        // B. Duplikasi nama bidang dalam satu dinas (case-insensitive)
        $duplikasiPerDinas = [];
        foreach ($allDinas as $dinas) {
            $namaLower = $dinas->bidang->map(fn($b) => $this->normStr($b->name));
            $duplikat  = $namaLower->duplicates()->values()->unique();
            if ($duplikat->isNotEmpty()) {
                $duplikasiPerDinas[] = [
                    'dinas'    => $dinas->name,
                    'duplikat' => $duplikat->toArray(),
                ];
            }
        }

        // C. Perbandingan JSON vs Database per dinas yang ada di DB
        $dbInstansiNorms = $allDinas->map(fn($d) => $this->normStr($d->name))->toArray();
        $compareResult   = [];

        foreach ($allDinas as $dinas) {
            $namaInstansiNorm = $this->normStr($dinas->name);
            $bidangDiDB       = $dinas->bidang->map(fn($b) => $this->normStr($b->name))->toArray();

            // Cari padanan di JSON (exact first, lalu fuzzy)
            $jsonBidangs = $jsonPerInstansi[$namaInstansiNorm] ?? [];
            if (empty($jsonBidangs)) {
                foreach ($jsonPerInstansi as $jsonInstansi => $bids) {
                    $maxLen = max(mb_strlen($jsonInstansi), mb_strlen($namaInstansiNorm));
                    if ($maxLen === 0) continue;
                    $sim = similar_text($jsonInstansi, $namaInstansiNorm) / $maxLen;
                    if ($sim > 0.80
                        || str_contains($namaInstansiNorm, $jsonInstansi)
                        || str_contains($jsonInstansi, $namaInstansiNorm)
                    ) {
                        $jsonBidangs = $bids;
                        break;
                    }
                }
            }

            $hanyaDiDB   = array_values(array_diff($bidangDiDB, $jsonBidangs));
            $hanyaDiJSON = array_values(array_diff($jsonBidangs, $bidangDiDB));

            $compareResult[] = [
                'dinas_id'      => $dinas->id,
                'dinas'         => $dinas->name,
                'jml_db'        => count($bidangDiDB),
                'jml_json'      => count($jsonBidangs),
                'hanya_di_db'   => $hanyaDiDB,
                'hanya_di_json' => $hanyaDiJSON,
                'match'         => empty($hanyaDiDB) && empty($hanyaDiJSON),
            ];
        }

        // D. Instansi di JSON yang tidak ada di database sama sekali
        $jsonInstansiTidakDiDB = [];
        foreach ($jsonPerInstansi as $instansiNorm => $bids) {
            $found = false;
            foreach ($dbInstansiNorms as $dbNorm) {
                $maxLen = max(mb_strlen($instansiNorm), mb_strlen($dbNorm));
                if ($maxLen === 0) continue;
                $sim = similar_text($instansiNorm, $dbNorm) / $maxLen;
                if ($instansiNorm === $dbNorm || $sim > 0.80
                    || str_contains($dbNorm, $instansiNorm)
                    || str_contains($instansiNorm, $dbNorm)
                ) {
                    $found = true;
                    break;
                }
            }
            if (! $found) {
                $jsonInstansiTidakDiDB[$instansiNorm] = $bids;
            }
        }

        // --- JSON output mode ---
        if ($this->option('json')) {
            $this->line(json_encode([
                'dinas_tanpa_bidang'            => $dinasTanpaBidang->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values(),
                'duplikasi_per_dinas'           => $duplikasiPerDinas,
                'perbandingan_db_vs_json'       => $compareResult,
                'instansi_json_tidak_di_db'     => $jsonInstansiTidakDiDB,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return Command::SUCCESS;
        }

        // --- Output teks interaktif ---
        $this->newLine();
        $this->info('╔══════════════════════════════════════════════════════════╗');
        $this->info('║       LAPORAN AUDIT MASTER BIDANG — LENTERA              ║');
        $this->info('╚══════════════════════════════════════════════════════════╝');
        $this->newLine();

        $totalDinas          = $allDinas->count();
        $totalBidang         = $allBidang->count();
        $dinasDenganBidang   = $allDinas->filter(fn($d) => $d->bidang->isNotEmpty())->count();
        $totalBidangDiJSON   = collect($jsonPerInstansi)->sum(fn($b) => count($b));
        $totalInstansiDiJSON = count($jsonPerInstansi);

        $this->line("  Total dinas di database   : <fg=cyan>{$totalDinas}</>");
        $this->line("  Total bidang di database  : <fg=cyan>{$totalBidang}</>");
        $this->line("  Dinas yang punya bidang   : <fg=cyan>{$dinasDenganBidang}</>");
        $this->line("  Total instansi di JSON    : <fg=cyan>{$totalInstansiDiJSON}</>");
        $this->line("  Total bidang di JSON      : <fg=cyan>{$totalBidangDiJSON}</>");
        $this->newLine();

        // --- A ---
        $this->warn('━━━ A. DINAS TANPA BIDANG (' . $dinasTanpaBidang->count() . ') ━━━');
        if ($dinasTanpaBidang->isEmpty()) {
            $this->line('  <fg=green>✓ Semua dinas sudah memiliki minimal 1 bidang.</>');
        } else {
            foreach ($dinasTanpaBidang as $d) {
                $this->line("  <fg=red>✗ [{$d->id}] {$d->name}</>");
            }
        }
        $this->newLine();

        // --- B ---
        $this->warn('━━━ B. DUPLIKASI NAMA BIDANG DALAM SATU DINAS (' . count($duplikasiPerDinas) . ') ━━━');
        if (empty($duplikasiPerDinas)) {
            $this->line('  <fg=green>✓ Tidak ada duplikasi nama bidang.</>');
        } else {
            foreach ($duplikasiPerDinas as $item) {
                $this->line("  <fg=red>⚠ {$item['dinas']}</>");
                foreach ($item['duplikat'] as $dup) {
                    $this->line("      - \"{$dup}\" (terduplikat)");
                }
            }
        }
        $this->newLine();

        // --- C ---
        $mismatchCount = 0;
        $this->warn('━━━ C. PERBANDINGAN DATABASE vs JSON PER DINAS ━━━');
        foreach ($compareResult as $row) {
            if ($row['match']) {
                continue;
            }
            $mismatchCount++;
            $this->line("  <fg=yellow>▶ [{$row['dinas_id']}] {$row['dinas']}</>");
            $this->line("    DB: {$row['jml_db']} bidang | JSON: {$row['jml_json']} bidang");
            if (! empty($row['hanya_di_db'])) {
                $this->line('    <fg=magenta>Hanya di DB (tidak ada di JSON):</>');
                foreach ($row['hanya_di_db'] as $b) {
                    $this->line("      + {$b}");
                }
            }
            if (! empty($row['hanya_di_json'])) {
                $this->line('    <fg=cyan>Hanya di JSON (belum ada di DB):</>');
                foreach ($row['hanya_di_json'] as $b) {
                    $this->line("      - {$b}");
                }
            }
            $this->newLine();
        }
        if ($mismatchCount === 0) {
            $this->line('  <fg=green>✓ Semua dinas sudah sinkron antara database dan JSON.</>');
        } else {
            $this->line("  Total dinas dengan perbedaan: <fg=yellow>{$mismatchCount}</>");
        }
        $this->newLine();

        // --- D ---
        $this->warn('━━━ D. INSTANSI DI JSON YANG TIDAK ADA DI DATABASE (' . count($jsonInstansiTidakDiDB) . ') ━━━');
        if (empty($jsonInstansiTidakDiDB)) {
            $this->line('  <fg=green>✓ Semua instansi JSON sudah terdaftar di database.</>');
        } else {
            foreach ($jsonInstansiTidakDiDB as $instansi => $bids) {
                $this->line("  <fg=red>✗ \"{$instansi}\"</> (" . count($bids) . ' bidang)');
                foreach ($bids as $b) {
                    $this->line("    - {$b}");
                }
            }
        }
        $this->newLine();

        // --- E. Tabel per dinas ---
        $this->warn('━━━ E. JUMLAH BIDANG PER DINAS ━━━');
        $tableRows = $allDinas->map(fn($d) => [
            $d->id,
            Str::limit($d->name, 55),
            $d->bidang->count(),
            $d->bidang->count() === 0 ? '⚠ KOSONG' : '✓',
        ])->toArray();
        $this->table(['ID', 'Nama Dinas/Instansi', 'Jml Bidang', 'Status'], $tableRows);
        $this->newLine();

        // --- Tindakan opsional: --fix-pikap ---
        if ($this->option('fix-pikap')) {
            $this->info('Menjalankan --fix-pikap: Menambahkan Bidang PIKAP ke Diskominfo...');
            $diskominfo = Dinas::whereRaw('UPPER(name) LIKE ?', ['%KOMUNIKASI DAN INFORMATIKA%'])->first();
            if (! $diskominfo) {
                $this->error('Dinas Diskominfo tidak ditemukan. Tidak ada yang ditambahkan.');
            } else {
                $sudahAda = Bidang::where('dinas_id', $diskominfo->id)
                    ->whereRaw('UPPER(name) LIKE ?', ['%PIKAP%'])
                    ->exists();
                if ($sudahAda) {
                    $this->warn('Bidang PIKAP sudah ada di Diskominfo. Tidak ditambahkan ulang.');
                } else {
                    Bidang::create([
                        'dinas_id' => $diskominfo->id,
                        'name'     => 'BIDANG PENGELOLAAN INFORMASI DAN KOMUNIKASI PUBLIK (PIKAP)',
                    ]);
                    $this->info('✓ Bidang PIKAP berhasil ditambahkan ke Diskominfo (ID: ' . $diskominfo->id . ').');
                }
            }
            $this->newLine();
        }

        $this->info('Audit selesai. Silakan periksa CHANGELOG_REVISI.md untuk daftar TODO bidang.');
        $this->newLine();

        return Command::SUCCESS;
    }

    /**
     * Normalisasi string: uppercase, trim, single-space.
     */
    private function normStr(string $str): string
    {
        return trim(preg_replace('/\s+/', ' ', strtoupper($str)));
    }
}
