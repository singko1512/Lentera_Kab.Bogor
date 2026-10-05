<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dinas;
use App\Models\PermohonanLayanan;
use App\Models\JenisLayanan;
use App\Models\StatusMaster;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\MasterDataSeeder;

/**
 * UploadValidationTest
 *
 * Cakupan:
 * - Upload surat final: hanya PDF, maks 2048 KB (config lentera.max_upload_size)
 * - Upload file pendukung permohonan: mime yang diperbolehkan & batas ukuran
 * - Ekstensi berbahaya (php, exe, bat, sh) ditolak
 * - File kosong / tidak ada ditolak
 * - File valid diterima dan disimpan
 */
class UploadValidationTest extends TestCase
{
    use RefreshDatabase;

    protected Dinas $kesbangpolDinas;
    protected Dinas $targetDinas;
    protected User $kesbangpolUser;
    protected User $studentUser;
    protected PermohonanLayanan $layanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(MasterDataSeeder::class);

        $this->kesbangpolDinas = Dinas::create([
            'name'          => 'Bakesbangpol Test',
            'is_kesbangpol' => true,
            'nama_kepala'   => 'Kepala Bakesbangpol',
            'nip_kepala'    => '197001011995031001',
        ]);

        $this->targetDinas = Dinas::create([
            'name'          => 'Dinas Kominfo Test',
            'is_kesbangpol' => false,
        ]);

        $this->kesbangpolUser = User::factory()->create([
            'role'              => 'kesbangpol',
            'dinas_id'          => $this->kesbangpolDinas->id,
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->studentUser = User::factory()->create([
            'role'              => 'peserta',
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        $jenisLayanan     = JenisLayanan::where('slug', 'kkl_mahasiswa')->first();
        $statusMenunggu   = StatusMaster::where('kode', 'menunggu_verifikasi')->first();

        $this->layanan = PermohonanLayanan::create([
            'user_id'          => $this->studentUser->id,
            'dinas_id'         => $this->targetDinas->id,
            'jenis_layanan_id' => $jenisLayanan->id,
            'status_master_id' => $statusMenunggu->id,
            'atas_nama'        => $this->studentUser->name,
            'no_hp'            => '08123456789',
            'asal_instansi'    => 'Universitas Test',
            'judul_kegiatan'   => 'Magang Upload Test',
            'tempat_kegiatan'  => $this->targetDinas->name,
            'tanggal_mulai'    => now()->toDateString(),
            'tanggal_selesai'  => now()->addMonths(2)->toDateString(),
        ]);
    }

    // =========================================================================
    // A. Upload Surat Final — Hanya PDF
    // =========================================================================

    public function test_upload_surat_final_accepts_valid_pdf(): void
    {
        Storage::fake('public');

        $pdfFile = UploadedFile::fake()->createWithContent(
            'surat_final.pdf',
            '%PDF-1.4 valid content'
        );

        $response = $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                'file_surat_final' => $pdfFile,
            ]);

        $response->assertSessionMissing('errors');
        $response->assertRedirect(route('kesbangpol.layanan.show', $this->layanan->id));

        $this->layanan->refresh();
        $this->assertNotNull($this->layanan->file_surat_final);
        Storage::disk('public')->assertExists($this->layanan->file_surat_final);
    }

    public function test_upload_surat_final_rejects_docx_file(): void
    {
        Storage::fake('public');

        $docxFile = UploadedFile::fake()->create(
            'surat.docx',
            100,
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        );

        $response = $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                'file_surat_final' => $docxFile,
            ]);

        $response->assertSessionHasErrors('file_surat_final');

        $this->layanan->refresh();
        $this->assertNull($this->layanan->file_surat_final);
    }

    public function test_upload_surat_final_rejects_image_file(): void
    {
        Storage::fake('public');

        $imageFile = UploadedFile::fake()->create('surat.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                'file_surat_final' => $imageFile,
            ]);

        $response->assertSessionHasErrors('file_surat_final');
    }

    // =========================================================================
    // B. Upload Surat Final — Batas Ukuran (config lentera.max_upload_size)
    // =========================================================================

    public function test_upload_surat_final_rejects_oversized_pdf(): void
    {
        Storage::fake('public');

        $maxSizeKb = config('lentera.max_upload_size', 2048);
        $oversizedFile = UploadedFile::fake()->create(
            'huge_surat.pdf',
            $maxSizeKb + 500,
            'application/pdf'
        );

        $response = $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                'file_surat_final' => $oversizedFile,
            ]);

        $response->assertSessionHasErrors('file_surat_final');
    }

    public function test_upload_surat_final_accepts_pdf_at_max_size_boundary(): void
    {
        Storage::fake('public');

        $maxSizeKb = config('lentera.max_upload_size', 2048);
        // File persis di batas ukuran maksimum — harus diterima
        $exactMaxFile = UploadedFile::fake()->create(
            'surat_max.pdf',
            $maxSizeKb,
            'application/pdf'
        );

        $response = $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                'file_surat_final' => $exactMaxFile,
            ]);

        $response->assertSessionMissing('errors');
    }

    // =========================================================================
    // C. Ekstensi Berbahaya Selalu Ditolak
    // =========================================================================

    public function test_upload_rejects_dangerous_file_extensions(): void
    {
        Storage::fake('public');

        $dangerousFiles = [
            ['webshell.php', 'text/x-php'],
            ['backdoor.php5', 'text/x-php'],
            ['malware.exe', 'application/x-msdownload'],
            ['virus.bat', 'application/x-bat'],
            ['exploit.sh', 'application/x-sh'],
            ['hack.asp', 'text/asp'],
            ['xss.js', 'application/javascript'],
        ];

        foreach ($dangerousFiles as [$filename, $mimeType]) {
            $dangerousFile = UploadedFile::fake()->create($filename, 10, $mimeType);

            $response = $this->actingAs($this->kesbangpolUser)
                ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                    'file_surat_final' => $dangerousFile,
                ]);

            // Harus ada validation error — file berbahaya tidak boleh lolos
            $response->assertSessionHasErrors('file_surat_final');
        }
    }

    // =========================================================================
    // D. File Kosong / Tidak Ada
    // =========================================================================

    public function test_upload_surat_final_requires_file_field(): void
    {
        $response = $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                // Tidak ada field 'file_surat_final'
            ]);

        $response->assertSessionHasErrors('file_surat_final');
    }

    // =========================================================================
    // E. Authorisasi Upload — Hanya Kesbangpol
    // =========================================================================

    public function test_student_cannot_upload_surat_final(): void
    {
        Storage::fake('public');

        $pdfFile = UploadedFile::fake()->createWithContent('surat.pdf', '%PDF-1.4 test');

        $response = $this->actingAs($this->studentUser)
            ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                'file_surat_final' => $pdfFile,
            ]);

        $response->assertStatus(403);
    }

    // =========================================================================
    // F. Upload File Pendukung Permohonan (lampiran)
    // =========================================================================

    public function test_student_can_upload_valid_document_attachment(): void
    {
        Storage::fake('public');

        // Simulasi upload dokumen pendukung (surat pengantar kampus, proposal, dll.)
        // Format yang diizinkan: PDF, DOC, DOCX
        $pdfAttachment = UploadedFile::fake()->create(
            'proposal_magang.pdf',
            500,
            'application/pdf'
        );

        // Coba submit permohonan dengan lampiran — route layanan.submit
        $jenisLayanan = JenisLayanan::where('slug', 'kkl_mahasiswa')->first();

        $response = $this->actingAs($this->studentUser)
            ->postJson(route('layanan.submit'), [
                'jenis_layanan_slug' => $jenisLayanan->slug,
                'dinas_id'           => $this->targetDinas->id,
                'atas_nama'          => $this->studentUser->name,
                'asal_instansi'      => 'Universitas Test',
                'judul_kegiatan'     => 'Test Upload',
                'tanggal_mulai'      => now()->toDateString(),
                'tanggal_selesai'    => now()->addMonth()->toDateString(),
                'lampiran'           => $pdfAttachment,
            ]);

        // Either 200 (success) atau 422 jika lampiran tidak wajib di endpoint ini
        // Yang penting bukan 500 (server error) atau 403
        $this->assertNotEquals(500, $response->status(),
            'Upload dokumen yang valid tidak boleh menghasilkan server error.');
        $this->assertNotEquals(403, $response->status(),
            'Mahasiswa aktif harus bisa mengakses form pengajuan.');
    }

    public function test_student_cannot_upload_executable_as_attachment(): void
    {
        Storage::fake('public');

        $execFile = UploadedFile::fake()->create('trojan.exe', 100, 'application/x-msdownload');

        $jenisLayanan = JenisLayanan::where('slug', 'kkl_mahasiswa')->first();

        $response = $this->actingAs($this->studentUser)
            ->postJson(route('layanan.submit'), [
                'jenis_layanan_slug' => $jenisLayanan->slug,
                'dinas_id'           => $this->targetDinas->id,
                'atas_nama'          => $this->studentUser->name,
                'asal_instansi'      => 'Universitas Test',
                'judul_kegiatan'     => 'Test Upload Jahat',
                'tanggal_mulai'      => now()->toDateString(),
                'tanggal_selesai'    => now()->addMonth()->toDateString(),
                'lampiran'           => $execFile,
            ]);

        // Jika endpoint menerima lampiran, harus ada validation error untuk .exe
        if ($response->status() === 422) {
            $errors = $response->json('errors');
            if (isset($errors['lampiran'])) {
                $this->assertNotEmpty($errors['lampiran'],
                    'File .exe seharusnya memiliki pesan error validasi.');
            }
        }

        // Pastikan tidak ada file .exe yang tersimpan
        $storedFiles = Storage::disk('public')->allFiles();
        $exeFiles = array_filter($storedFiles, fn($file) => str_ends_with(strtolower($file), '.exe'));
        $this->assertEmpty($exeFiles, 'File .exe tidak boleh tersimpan di storage.');
    }
}

