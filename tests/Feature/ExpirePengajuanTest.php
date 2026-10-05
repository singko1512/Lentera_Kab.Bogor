<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dinas;
use App\Models\Rekrutmen;
use App\Models\PermohonanLayanan;
use App\Models\JenisLayanan;
use App\Models\MagangApplication;
use App\Models\SuratRekomendasi;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Database\Seeders\MasterDataSeeder;

/**
 * ExpirePengajuanTest
 *
 * Menguji artisan command `lentera:expire-pengajuan` dengan simulasi waktu via Carbon::setTestNow():
 * 1. Permohonan magang yang tidak diproses > 30 hari otomatis berstatus expired dan mengembalikan kuota.
 * 2. Surat Rekomendasi yang diterbitkan Kesbangpol tetapi tidak diproses dinas hingga masa berlaku habis otomatis expired.
 * 3. Notifikasi kedaluwarsa terkirim ke mahasiswa.
 */
class ExpirePengajuanTest extends TestCase
{
    use RefreshDatabase;

    protected Dinas $kesbangpolDinas;
    protected Dinas $dinasA;
    protected Rekrutmen $rekrutmenA;
    protected User $kesbangpolUser;
    protected User $student;
    protected $jenisLayanan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MasterDataSeeder::class);

        $this->kesbangpolDinas = Dinas::create([
            'name'          => 'Badan Kesatuan Bangsa dan Politik',
            'nama'          => 'Badan Kesatuan Bangsa dan Politik',
            'is_kesbangpol' => true,
        ]);

        $this->kesbangpolUser = User::factory()->create([
            'name'              => 'Admin Kesbangpol',
            'email'             => 'kesbangpol@bogorkab.go.id',
            'role'              => 'kesbangpol',
            'dinas_id'          => $this->kesbangpolDinas->id,
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->dinasA = Dinas::create([
            'name'          => 'Dinas Komunikasi dan Informatika',
            'nama'          => 'Dinas Komunikasi dan Informatika',
            'is_kesbangpol' => false,
            'status_magang' => 'tersedia',
        ]);

        $this->rekrutmenA = Rekrutmen::create([
            'dinas_id'  => $this->dinasA->id,
            'judul'     => 'Rekrutmen Magang Diskominfo',
            'kuota'     => 2,
            'is_active' => true,
        ]);

        $this->student = User::factory()->create([
            'name'              => 'Mahasiswa Expire Test',
            'email'             => 'expiretest@kampus.ac.id',
            'role'              => 'peserta',
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->jenisLayanan = JenisLayanan::where('slug', 'kkl_mahasiswa')->first()
            ?: JenisLayanan::first();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_expire_command_sets_status_to_expired_and_restores_quota(): void
    {
        // 1. Submit pengajuan
        $this->actingAs($this->student)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id'           => $this->dinasA->id,
            'atas_nama'          => 'Mahasiswa Expire Test',
            'asal_instansi'      => 'Universitas Indonesia',
            'judul_kegiatan'     => 'Uji Kedaluwarsa Otomatis',
            'tanggal_mulai'      => now()->toDateString(),
            'tanggal_selesai'    => now()->addMonth()->toDateString(),
        ]);

        $permohonan = PermohonanLayanan::where('user_id', $this->student->id)->firstOrFail();
        $magangApp = MagangApplication::where('permohonan_layanan_id', $permohonan->id)->firstOrFail();

        $this->assertEquals(1, $this->rekrutmenA->fresh()->slot_tersedia);
        $this->assertEquals('menunggu', $magangApp->status);

        // 2. Simulasikan waktu melompati 31 hari (melewati batas kedaluwarsa 30 hari)
        Carbon::setTestNow(now()->addDays(31));

        // 3. Jalankan command
        $this->artisan('lentera:expire-pengajuan')
            ->expectsOutputToContain('Pemeriksaan selesai')
            ->assertSuccessful();

        // 4. Verifikasi status magang application menjadi expired
        $magangApp->refresh();
        $this->assertEquals('expired', $magangApp->status);
        $this->assertStringContainsString('Kedaluwarsa otomatis oleh sistem', $magangApp->catatan_admin);

        // 5. Kuota kembali pulih
        $this->assertEquals(2, $this->rekrutmenA->fresh()->slot_tersedia);

        // 6. Notifikasi terkirim ke mahasiswa
        $this->assertTrue(
            Notification::where('user_id', $this->student->id)
                ->where('judul', 'Pengajuan Magang Kedaluwarsa')
                ->exists(),
            'Notifikasi kedaluwarsa harus terkirim ke mahasiswa.'
        );
    }

    public function test_expire_command_expires_surat_rekomendasi_when_past_validity(): void
    {
        // 1. Pengajuan dan disetujui Kesbangpol
        $this->actingAs($this->student)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id'           => $this->dinasA->id,
            'atas_nama'          => 'Mahasiswa Expire Test',
            'asal_instansi'      => 'Universitas Indonesia',
            'judul_kegiatan'     => 'Uji Kedaluwarsa Surat',
            'tanggal_mulai'      => now()->toDateString(),
            'tanggal_selesai'    => now()->addMonth()->toDateString(),
        ]);

        $permohonan = PermohonanLayanan::where('user_id', $this->student->id)->firstOrFail();

        $this->actingAs($this->kesbangpolUser)->post(route('kesbangpol.layanan.verify', $permohonan->id), [
            'status'   => 'disetujui',
            'dinas_id' => $this->dinasA->id,
        ]);

        $surat = SuratRekomendasi::where('permohonan_layanan_id', $permohonan->id)->firstOrFail();
        $this->assertNotNull($surat->berlaku_sampai);

        // Kuota masih terpotong (1 slot tersisa)
        $this->assertEquals(1, $this->rekrutmenA->fresh()->slot_tersedia);

        // 2. Waktu maju 31 hari ke depan
        Carbon::setTestNow(now()->addDays(31));

        $this->artisan('lentera:expire-pengajuan')->assertSuccessful();

        $magangApp = MagangApplication::where('permohonan_layanan_id', $permohonan->id)->firstOrFail();
        $this->assertEquals('expired', $magangApp->status);
        $this->assertStringContainsString('masa berlaku Surat Rekomendasi telah habis', $magangApp->catatan_admin);

        // Kuota kembali ke 2
        $this->assertEquals(2, $this->rekrutmenA->fresh()->slot_tersedia);
    }
}
