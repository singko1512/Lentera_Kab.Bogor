<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dinas;
use App\Models\PermohonanLayanan;
use App\Models\JenisLayanan;
use App\Models\StatusMaster;
use App\Models\SuratRekomendasi;
use App\Models\Notification;
use App\Models\MagangApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\MasterDataSeeder;

class SuratFinalTest extends TestCase
{
    use RefreshDatabase;

    protected $kesbangpolDinas;
    protected $targetDinas;
    protected $kesbangpolUser;
    protected $dinasUser;
    protected $studentUser;
    protected $otherStudentUser;
    protected $layanan;
    protected $jenisLayanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(MasterDataSeeder::class);

        // 1. Kesbangpol Dinas
        $this->kesbangpolDinas = Dinas::create([
            'name' => 'Badan Kesatuan Bangsa dan Politik',
            'is_kesbangpol' => true,
            'nama_kepala' => 'DR. H. KEPALA KESBANGPOL, M.SI',
            'nip_kepala' => '197001011995031001',
        ]);

        // 2. Target Dinas
        $this->targetDinas = Dinas::create([
            'name' => 'Dinas Komunikasi dan Informatika',
            'is_kesbangpol' => false,
            'nama_kepala' => 'Ir. Kepala Diskominfo',
            'nip_kepala' => '197505052000031002',
        ]);

        // 3. Users
        $this->kesbangpolUser = User::factory()->create([
            'name' => 'Admin Kesbangpol',
            'email' => 'kesbangpol@bogorkab.go.id',
            'role' => 'kesbangpol',
            'dinas_id' => $this->kesbangpolDinas->id,
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->dinasUser = User::factory()->create([
            'name' => 'Admin Diskominfo',
            'email' => 'diskominfo@bogorkab.go.id',
            'role' => 'dinas',
            'dinas_id' => $this->targetDinas->id,
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->studentUser = User::factory()->create([
            'name' => 'Mahasiswa Test',
            'email' => 'mahasiswa@kampus.ac.id',
            'role' => 'peserta',
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->otherStudentUser = User::factory()->create([
            'name' => 'Mahasiswa Lain',
            'email' => 'lain@kampus.ac.id',
            'role' => 'peserta',
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->jenisLayanan = JenisLayanan::where('slug', 'kkl_mahasiswa')->first()
            ?: JenisLayanan::first();

        $statusMenunggu = StatusMaster::where('kode', 'menunggu_verifikasi')->first();

        $this->layanan = PermohonanLayanan::create([
            'user_id' => $this->studentUser->id,
            'dinas_id' => $this->targetDinas->id,
            'jenis_layanan_id' => $this->jenisLayanan->id,
            'status_master_id' => $statusMenunggu->id,
            'atas_nama' => 'Mahasiswa Test',
            'no_hp' => '08123456789',
            'asal_instansi' => 'Universitas Indonesia',
            'judul_kegiatan' => 'Riset Komunikasi Publik',
            'tempat_kegiatan' => 'Dinas Komunikasi dan Informatika',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(2)->toDateString(),
        ]);
    }

    public function test_kesbangpol_verify_preserves_manual_nomor_surat_and_uses_kesbangpol_signatory(): void
    {
        $customNomor = '070/CUSTOM-999/Bakesbangpol/2026';

        $response = $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.verify', $this->layanan->id), [
                'status' => 'disetujui',
                'dinas_id' => $this->targetDinas->id,
                'nomor_surat' => $customNomor,
            ]);

        $response->assertRedirect(route('kesbangpol.layanan.show', $this->layanan->id));

        $this->layanan->refresh();
        $this->assertEquals('disetujui', $this->layanan->statusMaster->kode);
        $this->assertEquals($this->targetDinas->id, $this->layanan->dinas_id);

        $surat = SuratRekomendasi::where('permohonan_layanan_id', $this->layanan->id)->first();
        $this->assertNotNull($surat);
        $this->assertEquals($customNomor, $surat->nomor_surat);
        $this->assertEquals($this->kesbangpolDinas->nama_kepala, $surat->pejabat_nama);
        $this->assertEquals($this->kesbangpolDinas->nip_kepala, $surat->pejabat_nip);

        // Verification again without typing nomor_surat preserves existing custom number
        $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.verify', $this->layanan->id), [
                'status' => 'disetujui',
                'dinas_id' => $this->targetDinas->id,
                'nomor_surat' => '',
            ]);

        $surat->refresh();
        $this->assertEquals($customNomor, $surat->nomor_surat);
    }

    public function test_kesbangpol_can_download_draft_pdf_and_docx(): void
    {
        $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.verify', $this->layanan->id), [
                'status' => 'disetujui',
                'dinas_id' => $this->targetDinas->id,
            ]);

        // 1. Download Draft PDF
        $responsePdf = $this->actingAs($this->kesbangpolUser)
            ->get(route('kesbangpol.layanan.generate_pdf', $this->layanan->id));

        $responsePdf->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $responsePdf->headers->get('Content-Type'));

        // 2. Download Draft DOCX
        $responseDocx = $this->actingAs($this->kesbangpolUser)
            ->get(route('kesbangpol.layanan.generate_docx', $this->layanan->id));

        $responseDocx->assertStatus(200);
        $this->assertStringContainsString('wordprocessingml', $responseDocx->headers->get('Content-Type'));
    }

    public function test_student_cannot_download_draft_when_file_surat_final_is_null(): void
    {
        $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.verify', $this->layanan->id), [
                'status' => 'disetujui',
                'dinas_id' => $this->targetDinas->id,
            ]);

        $this->layanan->refresh();
        $this->assertNull($this->layanan->file_surat_final);

        // Student tries to access route surat.pdf -> MUST be 404, never receives draft
        $response = $this->actingAs($this->studentUser)
            ->get(route('surat.pdf', $this->layanan->id));

        $response->assertStatus(404);

        // Student landing index & profile show pending signature label
        $profileResponse = $this->actingAs($this->studentUser)
            ->get(route('landing.profile'));

        $profileResponse->assertStatus(200);
        $profileResponse->assertSee('Surat sedang diproses/ menunggu penandatanganan');
    }

    public function test_kesbangpol_uploads_final_signed_pdf_and_notifies_student_and_dinas(): void
    {
        Storage::fake('public');

        $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.verify', $this->layanan->id), [
                'status' => 'disetujui',
                'dinas_id' => $this->targetDinas->id,
            ]);

        $fileContent = '%PDF-1.4 Fake signed PDF content by Kesbangpol TTE';
        $uploadedFile = UploadedFile::fake()->createWithContent('surat_rekomendasi_final_tte.pdf', $fileContent);

        $response = $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                'file_surat_final' => $uploadedFile,
                'nomor_surat' => '000.1.5/FINAL-TTE/2026',
            ]);

        $response->assertRedirect(route('kesbangpol.layanan.show', $this->layanan->id));
        $response->assertSessionHas('success');

        $this->layanan->refresh();
        $this->assertNotNull($this->layanan->file_surat_final);
        $this->assertNotNull($this->layanan->surat_final_diunggah_pada);
        $this->assertEquals($this->kesbangpolUser->id, $this->layanan->surat_final_diunggah_oleh);

        // Check file exists on storage
        Storage::disk('public')->assertExists($this->layanan->file_surat_final);

        // Check student notification
        $this->assertTrue(Notification::where('user_id', $this->studentUser->id)
            ->where('judul', 'Surat Rekomendasi Terbit')
            ->exists());

        // Check destination dinas notification
        $this->assertTrue(Notification::where('user_id', $this->dinasUser->id)
            ->where('judul', 'Surat Rekomendasi Final Tersedia')
            ->exists());
    }

    public function test_student_and_dinas_download_exact_same_final_file_hash(): void
    {
        Storage::fake('public');

        $fileContent = '%PDF-1.4 EXACT_FINAL_TTE_BYTES_VERIFIED_FASE_2';
        $uploadedFile = UploadedFile::fake()->createWithContent('rekomendasi_final.pdf', $fileContent);

        $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                'file_surat_final' => $uploadedFile,
            ]);

        $this->layanan->refresh();

        // 1. Student downloads surat.pdf
        $studentResponse = $this->actingAs($this->studentUser)
            ->get(route('surat.pdf', $this->layanan->id));

        $studentResponse->assertStatus(200);
        $studentFileContent = $studentResponse->streamedContent();
        $this->assertEquals(md5($fileContent), md5($studentFileContent));

        // 2. Dinas tujuan downloads surat.pdf
        $dinasResponse = $this->actingAs($this->dinasUser)
            ->get(route('surat.pdf', $this->layanan->id));

        $dinasResponse->assertStatus(200);
        $dinasFileContent = $dinasResponse->streamedContent();
        $this->assertEquals(md5($fileContent), md5($dinasFileContent));

        // 3. Kesbangpol downloads surat.pdf
        $kesbangpolResponse = $this->actingAs($this->kesbangpolUser)
            ->get(route('surat.pdf', $this->layanan->id));

        $kesbangpolResponse->assertStatus(200);
        $kesbangpolFileContent = $kesbangpolResponse->streamedContent();
        $this->assertEquals(md5($fileContent), md5($kesbangpolFileContent));
    }

    public function test_kesbangpol_can_replace_final_pdf_and_student_receives_updated_version(): void
    {
        Storage::fake('public');

        // Initial upload (Version 1)
        $fileContentV1 = '%PDF-1.4 VERSION_1_CONTENT';
        $fileV1 = UploadedFile::fake()->createWithContent('surat_v1.pdf', $fileContentV1);

        $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                'file_surat_final' => $fileV1,
            ]);

        $this->layanan->refresh();
        $oldPath = $this->layanan->file_surat_final;
        Storage::disk('public')->assertExists($oldPath);

        // Replacement upload (Version 2)
        $fileContentV2 = '%PDF-1.4 VERSION_2_CORRECTED_NOMOR_AND_SIGNATURE';
        $fileV2 = UploadedFile::fake()->createWithContent('surat_v2.pdf', $fileContentV2);

        $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                'file_surat_final' => $fileV2,
            ]);

        $this->layanan->refresh();
        $newPath = $this->layanan->file_surat_final;

        // Old file deleted, new file exists
        $this->assertNotEquals($oldPath, $newPath);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($newPath);

        // Student downloads surat.pdf and receives Version 2
        $response = $this->actingAs($this->studentUser)
            ->get(route('surat.pdf', $this->layanan->id));

        $response->assertStatus(200);
        $downloadedContent = $response->streamedContent();
        $this->assertEquals(md5($fileContentV2), md5($downloadedContent));
        $this->assertNotEquals(md5($fileContentV1), md5($downloadedContent));
    }

    public function test_unauthorized_user_cannot_access_surat_pdf(): void
    {
        Storage::fake('public');

        $uploadedFile = UploadedFile::fake()->createWithContent('surat.pdf', '%PDF-1.4 Secret');
        $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                'file_surat_final' => $uploadedFile,
            ]);

        // Another student who is NOT the owner gets 403
        $this->actingAs($this->otherStudentUser)
            ->get(route('surat.pdf', $this->layanan->id))
            ->assertStatus(403);
    }

    public function test_validation_rejects_non_pdf_and_oversized_final_surat(): void
    {
        Storage::fake('public');

        // Non-PDF (e.g. DOCX)
        $docFile = UploadedFile::fake()->create('surat.docx', 100, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $response1 = $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                'file_surat_final' => $docFile,
            ]);
        $response1->assertSessionHasErrors('file_surat_final');

        // Oversized file (> max_upload_size = 2048 KB)
        $maxSize = config('lentera.max_upload_size', 2048);
        $largeFile = UploadedFile::fake()->create('huge.pdf', $maxSize + 500, 'application/pdf');
        $response2 = $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.upload_surat_final', $this->layanan->id), [
                'file_surat_final' => $largeFile,
            ]);
        $response2->assertSessionHasErrors('file_surat_final');
    }
}
