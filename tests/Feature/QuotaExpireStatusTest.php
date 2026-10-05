<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dinas;
use App\Models\Bidang;
use App\Models\Rekrutmen;
use App\Models\PermohonanLayanan;
use App\Models\JenisLayanan;
use App\Models\StatusMaster;
use App\Models\MagangApplication;
use App\Models\SuratRekomendasi;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Database\Seeders\MasterDataSeeder;

class QuotaExpireStatusTest extends TestCase
{
    use RefreshDatabase;

    protected $kesbangpolDinas;
    protected $dinasA;
    protected $dinasB;
    protected $bidangA;
    protected $bidangB;
    protected $rekrutmenA;
    protected $rekrutmenB;
    protected $kesbangpolUser;
    protected $dinasAUser;
    protected $dinasBUser;
    protected $student1;
    protected $student2;
    protected $student3;
    protected $student4;
    protected $jenisLayanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(MasterDataSeeder::class);

        // 1. Kesbangpol Dinas & Admin User
        $this->kesbangpolDinas = Dinas::create([
            'name' => 'Badan Kesatuan Bangsa dan Politik',
            'is_kesbangpol' => true,
            'nama_kepala' => 'DR. H. KEPALA KESBANGPOL, M.SI',
            'nip_kepala' => '197001011995031001',
        ]);

        $this->kesbangpolUser = User::factory()->create([
            'name' => 'Admin Kesbangpol',
            'email' => 'kesbangpol@bogorkab.go.id',
            'role' => 'kesbangpol',
            'dinas_id' => $this->kesbangpolDinas->id,
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        // 2. Dinas A (Diskominfo) with Quota = 2
        $this->dinasA = Dinas::create([
            'name' => 'Dinas Komunikasi dan Informatika',
            'is_kesbangpol' => false,
            'status_magang' => 'tersedia',
            'nama_kepala' => 'Ir. Kepala Diskominfo',
            'nip_kepala' => '197505052000031002',
        ]);

        $this->bidangA = Bidang::create([
            'dinas_id' => $this->dinasA->id,
            'name' => 'Bidang Aplikasi Informatika',
        ]);

        $this->rekrutmenA = Rekrutmen::create([
            'dinas_id' => $this->dinasA->id,
            'judul' => 'Rekrutmen Magang Diskominfo',
            'kuota' => 2,
            'is_active' => true,
        ]);

        $this->dinasAUser = User::factory()->create([
            'name' => 'Admin Diskominfo',
            'email' => 'diskominfo@bogorkab.go.id',
            'role' => 'dinas',
            'dinas_id' => $this->dinasA->id,
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        // 3. Dinas B (Disdik) with Quota = 5
        $this->dinasB = Dinas::create([
            'name' => 'Dinas Pendidikan',
            'is_kesbangpol' => false,
            'status_magang' => 'tersedia',
            'nama_kepala' => 'Drs. Kepala Disdik',
            'nip_kepala' => '197202021998031003',
        ]);

        $this->bidangB = Bidang::create([
            'dinas_id' => $this->dinasB->id,
            'name' => 'Bidang Kurikulum',
        ]);

        $this->rekrutmenB = Rekrutmen::create([
            'dinas_id' => $this->dinasB->id,
            'judul' => 'Rekrutmen Magang Disdik',
            'kuota' => 5,
            'is_active' => true,
        ]);

        $this->dinasBUser = User::factory()->create([
            'name' => 'Admin Disdik',
            'email' => 'disdik@bogorkab.go.id',
            'role' => 'dinas',
            'dinas_id' => $this->dinasB->id,
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        // 4. Students
        $this->student1 = User::factory()->create([
            'name' => 'Mahasiswa 1',
            'email' => 'mhs1@kampus.ac.id',
            'role' => 'peserta',
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->student2 = User::factory()->create([
            'name' => 'Mahasiswa 2',
            'email' => 'mhs2@kampus.ac.id',
            'role' => 'peserta',
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->student3 = User::factory()->create([
            'name' => 'Mahasiswa 3',
            'email' => 'mhs3@kampus.ac.id',
            'role' => 'peserta',
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->student4 = User::factory()->create([
            'name' => 'Mahasiswa 4',
            'email' => 'mhs4@kampus.ac.id',
            'role' => 'peserta',
            'status_akun' => 'aktif',
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

    /**
     * Verifikasi 1:
     * Kuota 2, tiga permohonan diajukan berurutan -> yang ketiga ditolak (422).
     */
    public function test_quota_limits_consecutive_applications_and_rejects_third(): void
    {
        $this->assertEquals(2, $this->rekrutmenA->slot_tersedia);

        // 1. Pengajuan pertama oleh Mahasiswa 1
        $res1 = $this->actingAs($this->student1)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id' => $this->dinasA->id,
            'atas_nama' => 'Mahasiswa 1',
            'asal_instansi' => 'Universitas Indonesia',
            'judul_kegiatan' => 'Magang Smart City 1',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);
        $res1->assertStatus(200);

        $this->assertEquals(1, $this->rekrutmenA->fresh()->slot_tersedia);
        $this->assertDatabaseHas('magang_applications', [
            'user_id' => $this->student1->id,
            'dinas_id' => $this->dinasA->id,
            'status' => 'menunggu',
        ]);

        // 2. Pengajuan kedua oleh Mahasiswa 2
        $res2 = $this->actingAs($this->student2)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id' => $this->dinasA->id,
            'atas_nama' => 'Mahasiswa 2',
            'asal_instansi' => 'IPB University',
            'judul_kegiatan' => 'Magang Smart City 2',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);
        $res2->assertStatus(200);

        $this->assertEquals(0, $this->rekrutmenA->fresh()->slot_tersedia);
        $this->assertDatabaseHas('magang_applications', [
            'user_id' => $this->student2->id,
            'dinas_id' => $this->dinasA->id,
            'status' => 'menunggu',
        ]);

        // 3. Pengajuan ketiga oleh Mahasiswa 3 -> Harus ditolak 422 karena kuota habis
        $res3 = $this->actingAs($this->student3)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id' => $this->dinasA->id,
            'atas_nama' => 'Mahasiswa 3',
            'asal_instansi' => 'ITB',
            'judul_kegiatan' => 'Magang Smart City 3',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);

        $res3->assertStatus(422);
        $res3->assertJsonFragment(['status' => 'error']);
        $this->assertStringContainsString('kuotanya sudah penuh', $res3->json('message'));

        // Pastikan tidak ada aplikasi magang ketiga yang tercipta
        $this->assertDatabaseMissing('magang_applications', [
            'user_id' => $this->student3->id,
            'dinas_id' => $this->dinasA->id,
        ]);
        $this->assertEquals(0, $this->rekrutmenA->fresh()->slot_tersedia);
    }

    /**
     * Verifikasi 2:
     * Satu ditolak Kesbangpol -> kuota kembali, permohonan baru bisa masuk.
     */
    public function test_kesbangpol_rejection_returns_quota_and_allows_new_submission(): void
    {
        // 1. Mahasiswa 1 & 2 mengajukan hingga kuota habis
        $this->actingAs($this->student1)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id' => $this->dinasA->id,
            'atas_nama' => 'Mahasiswa 1',
            'asal_instansi' => 'Universitas Indonesia',
            'judul_kegiatan' => 'Magang 1',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);

        $this->actingAs($this->student2)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id' => $this->dinasA->id,
            'atas_nama' => 'Mahasiswa 2',
            'asal_instansi' => 'IPB University',
            'judul_kegiatan' => 'Magang 2',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);

        $this->assertEquals(0, $this->rekrutmenA->fresh()->slot_tersedia);

        $permohonan1 = PermohonanLayanan::where('user_id', $this->student1->id)->firstOrFail();
        $magangApp1 = MagangApplication::where('permohonan_layanan_id', $permohonan1->id)->firstOrFail();
        $this->assertEquals('menunggu', $magangApp1->status);

        // 2. Kesbangpol menolak permohonan Mahasiswa 1
        $verifyRes = $this->actingAs($this->kesbangpolUser)->post(route('kesbangpol.layanan.verify', $permohonan1->id), [
            'status' => 'ditolak',
            'keterangan' => 'Berkas proposal tidak relevan dengan dinas tujuan',
        ]);
        $verifyRes->assertRedirect(route('kesbangpol.layanan.show', $permohonan1->id));

        // Verifikasi MagangApplication ikut berubah menjadi ditolak
        $magangApp1->refresh();
        $this->assertEquals('ditolak', $magangApp1->status);
        $this->assertStringContainsString('Ditolak oleh Kesbangpol', $magangApp1->catatan_admin);

        // Kuota otomatis bertambah kembali (slot_tersedia menjadi 1)
        $this->assertEquals(1, $this->rekrutmenA->fresh()->slot_tersedia);

        // Notifikasi ke mahasiswa & dinas tujuan
        $this->assertTrue(
            Notification::where('user_id', $this->student1->id)
                ->where('judul', 'Permohonan Kesbangpol Ditolak')
                ->exists()
        );
        $this->assertTrue(
            Notification::where('user_id', $this->dinasAUser->id)
                ->where('judul', 'Permohonan Rekomendasi Ditolak Kesbangpol')
                ->exists()
        );

        // 3. Mahasiswa 3 sekarang bisa mengajukan karena kuota sudah kembali
        $res3 = $this->actingAs($this->student3)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id' => $this->dinasA->id,
            'atas_nama' => 'Mahasiswa 3',
            'asal_instansi' => 'ITB',
            'judul_kegiatan' => 'Magang 3',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);
        $res3->assertStatus(200);

        $this->assertEquals(0, $this->rekrutmenA->fresh()->slot_tersedia);
        $this->assertDatabaseHas('magang_applications', [
            'user_id' => $this->student3->id,
            'dinas_id' => $this->dinasA->id,
            'status' => 'menunggu',
        ]);
    }

    /**
     * Verifikasi 3:
     * Jalankan lentera:expire-pengajuan dengan Carbon::setTestNow(+31 hari)
     * -> status expired, kuota kembali, notifikasi terkirim.
     */
    public function test_expiry_command_expires_applications_and_returns_quota(): void
    {
        // 1. Mahasiswa 1 mengajukan permohonan ke Dinas A
        $this->actingAs($this->student1)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id' => $this->dinasA->id,
            'atas_nama' => 'Mahasiswa 1',
            'asal_instansi' => 'Universitas Indonesia',
            'judul_kegiatan' => 'Magang Uji Expire',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);

        $permohonan1 = PermohonanLayanan::where('user_id', $this->student1->id)->firstOrFail();
        $magangApp1 = MagangApplication::where('permohonan_layanan_id', $permohonan1->id)->firstOrFail();

        $this->assertEquals(1, $this->rekrutmenA->fresh()->slot_tersedia);
        $this->assertEquals('menunggu', $magangApp1->status);

        // 2. Simulasikan waktu maju 31 hari ke depan (> masa_berlaku_pengajuan_hari = 30 hari)
        Carbon::setTestNow(now()->addDays(31));

        // 3. Jalankan artisan command expire
        $this->artisan('lentera:expire-pengajuan')
            ->expectsOutputToContain('Pemeriksaan selesai')
            ->assertSuccessful();

        // 4. Verifikasi status MagangApplication menjadi expired
        $magangApp1->refresh();
        $this->assertEquals('expired', $magangApp1->status);
        $this->assertStringContainsString('Kedaluwarsa otomatis oleh sistem', $magangApp1->catatan_admin);

        // Status permohonan layanan juga expired
        $permohonan1->refresh();
        $this->assertEquals('expired', $permohonan1->statusMaster->kode);

        // Kuota otomatis kembali penuh (2 slot)
        $this->assertEquals(2, $this->rekrutmenA->fresh()->slot_tersedia);

        // Notifikasi kedaluwarsa terkirim ke mahasiswa
        $this->assertTrue(
            Notification::where('user_id', $this->student1->id)
                ->where('judul', 'Pengajuan Magang Kedaluwarsa')
                ->exists()
        );
    }

    /**
     * Verifikasi 3b:
     * Expirasi Surat Rekomendasi yang berlaku_sampai-nya lewat dan belum diproses dinas.
     */
    public function test_expiry_command_expires_unprocessed_surat_rekomendasi(): void
    {
        // 1. Mahasiswa 1 mengajukan permohonan
        $this->actingAs($this->student1)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id' => $this->dinasA->id,
            'atas_nama' => 'Mahasiswa 1',
            'asal_instansi' => 'Universitas Indonesia',
            'judul_kegiatan' => 'Magang Uji Surat Expire',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);

        $permohonan1 = PermohonanLayanan::where('user_id', $this->student1->id)->firstOrFail();

        // 2. Kesbangpol menyetujui permohonan (surat diterbitkan dengan masa berlaku 30 hari)
        $this->actingAs($this->kesbangpolUser)->post(route('kesbangpol.layanan.verify', $permohonan1->id), [
            'status' => 'disetujui',
            'dinas_id' => $this->dinasA->id,
        ]);

        $surat = SuratRekomendasi::where('permohonan_layanan_id', $permohonan1->id)->firstOrFail();
        $this->assertNotNull($surat->berlaku_sampai);

        // Kuota masih terpotong (slot = 1) karena MagangApplication status = 'menunggu'
        $this->assertEquals(1, $this->rekrutmenA->fresh()->slot_tersedia);

        // 3. Waktu maju 31 hari (surat kedaluwarsa sebelum dinas menerima)
        Carbon::setTestNow(now()->addDays(31));

        $this->artisan('lentera:expire-pengajuan')->assertSuccessful();

        $magangApp = MagangApplication::where('permohonan_layanan_id', $permohonan1->id)->firstOrFail();
        $this->assertEquals('expired', $magangApp->status);
        $this->assertStringContainsString('masa berlaku Surat Rekomendasi telah habis', $magangApp->catatan_admin);

        // Kuota kembali ke 2
        $this->assertEquals(2, $this->rekrutmenA->fresh()->slot_tersedia);
    }

    /**
     * Verifikasi 4:
     * Dinas A tidak bisa verify pengajuan milik dinas B; bidang_id dinas lain ditolak.
     */
    public function test_dinas_cannot_verify_other_dinas_application_or_use_foreign_bidang(): void
    {
        // 1. Mahasiswa 1 mengajukan ke Dinas A
        $this->actingAs($this->student1)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id' => $this->dinasA->id,
            'atas_nama' => 'Mahasiswa 1',
            'asal_instansi' => 'Universitas Indonesia',
            'judul_kegiatan' => 'Magang Uji Otorisasi',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);

        $permohonan = PermohonanLayanan::where('user_id', $this->student1->id)->firstOrFail();
        $magangAppA = MagangApplication::where('permohonan_layanan_id', $permohonan->id)->firstOrFail();

        // Kesbangpol menyetujui rekomendasi terlebih dahulu
        $this->actingAs($this->kesbangpolUser)->post(route('kesbangpol.layanan.verify', $permohonan->id), [
            'status' => 'disetujui',
            'dinas_id' => $this->dinasA->id,
        ]);

        // A. Dinas B mencoba memverifikasi pengajuan milik Dinas A -> Harus 404 (Scoping dinas_id)
        $resB = $this->actingAs($this->dinasBUser)->post(route('dinas.applications.verify', $magangAppA->id), [
            'status' => 'diterima',
            'bidang_id' => $this->bidangB->id,
        ]);
        $resB->assertStatus(404);

        // B. Dinas A memverifikasi tetapi menyisipkan bidang_id milik Dinas B -> Harus 422 (Validasi Rule::exists dinas_id)
        $resForeignBidang = $this->actingAs($this->dinasAUser)->postJson(route('dinas.applications.verify', $magangAppA->id), [
            'status' => 'diterima',
            'bidang_id' => $this->bidangB->id, // Bidang milik Dinas B!
        ]);
        $resForeignBidang->assertStatus(422);
        $resForeignBidang->assertJsonValidationErrors('bidang_id');

        // C. Dinas A memverifikasi dengan bidang miliknya sendiri -> Berhasil!
        $resValid = $this->actingAs($this->dinasAUser)->post(route('dinas.applications.verify', $magangAppA->id), [
            'status' => 'diterima',
            'bidang_id' => $this->bidangA->id, // Bidang valid milik Dinas A
            'catatan_admin' => 'Diterima di bidang Aptika',
        ]);
        $resValid->assertRedirect(route('dinas.applications.show', $magangAppA->id));

        $magangAppA->refresh();
        $this->assertEquals('diterima', $magangAppA->status);
        $this->assertEquals($this->bidangA->id, $magangAppA->bidang_id);
    }

    /**
     * Verifikasi 4b:
     * Dinas tidak bisa verifikasi bila Kesbangpol belum menyetujui,
     * dan tidak bisa menurunkan role akun staf (guard).
     */
    public function test_dinas_cannot_verify_before_kesbangpol_approval_and_guards_staff_role(): void
    {
        // 1. Mahasiswa mengajukan ke Dinas A (belum diverifikasi Kesbangpol, status 'menunggu_verifikasi')
        $this->actingAs($this->student1)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id' => $this->dinasA->id,
            'atas_nama' => 'Mahasiswa 1',
            'asal_instansi' => 'Universitas Indonesia',
            'judul_kegiatan' => 'Magang Belum Disetujui Kesbangpol',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);

        $magangApp = MagangApplication::where('user_id', $this->student1->id)->firstOrFail();

        // Dinas A mencoba menerima sebelum Kesbangpol menyetujui
        $res = $this->actingAs($this->dinasAUser)->postJson(route('dinas.applications.verify', $magangApp->id), [
            'status' => 'diterima',
            'bidang_id' => $this->bidangA->id,
        ]);

        $res->assertStatus(422);
        $this->assertStringContainsString('Surat Rekomendasi Kesbangpol belum disetujui', $res->json('message'));

        // 2. Guard: Pastikan role admin tidak diturunkan ke 'peserta'
        $adminStaff = User::factory()->create([
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        $statusDisetujui = StatusMaster::where('kode', 'disetujui')->first();
        $permohonanStaff = PermohonanLayanan::create([
            'user_id' => $adminStaff->id,
            'dinas_id' => $this->dinasA->id,
            'jenis_layanan_id' => $this->jenisLayanan->id,
            'status_master_id' => $statusDisetujui->id,
            'atas_nama' => 'Staff Admin',
            'no_hp' => '08123456789',
            'tempat_kegiatan' => 'Dinas Komunikasi dan Informatika',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addMonth(),
        ]);

        $magangAppStaff = MagangApplication::create([
            'user_id' => $adminStaff->id,
            'permohonan_layanan_id' => $permohonanStaff->id,
            'dinas_id' => $this->dinasA->id,
            'rekrutmen_id' => $this->rekrutmenA->id,
            'status' => 'menunggu',
        ]);

        $this->actingAs($this->dinasAUser)->post(route('dinas.applications.verify', $magangAppStaff->id), [
            'status' => 'diterima',
            'bidang_id' => $this->bidangA->id,
        ]);

        $adminStaff->refresh();
        $this->assertEquals('admin', $adminStaff->role, 'Role admin staf tidak boleh diturunkan menjadi peserta');
    }

    /**
     * Verifikasi 5:
     * Hapus permohonan oleh user (destroy) -> booking ikut dibatalkan (kuota kembali).
     */
    public function test_user_deleting_application_cancels_booking_and_returns_quota(): void
    {
        $this->actingAs($this->student1)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id' => $this->dinasA->id,
            'atas_nama' => 'Mahasiswa 1',
            'asal_instansi' => 'Universitas Indonesia',
            'judul_kegiatan' => 'Magang Batal',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);

        $permohonan = PermohonanLayanan::where('user_id', $this->student1->id)->firstOrFail();
        $this->assertEquals(1, $this->rekrutmenA->fresh()->slot_tersedia);

        // Mahasiswa menghapus permohonan
        $delRes = $this->actingAs($this->student1)->deleteJson(route('layanan.destroy', $permohonan->id));
        $delRes->assertStatus(200);

        // Permohonan & MagangApplication terhapus
        $this->assertDatabaseMissing('permohonan_layanans', ['id' => $permohonan->id]);
        $this->assertDatabaseMissing('magang_applications', ['permohonan_layanan_id' => $permohonan->id]);

        // Kuota kembali ke 2
        $this->assertEquals(2, $this->rekrutmenA->fresh()->slot_tersedia);
    }

    /**
     * Verifikasi 6:
     * Mengganti dinas pada update permohonan -> lepaskan booking lama dan buat booking baru.
     */
    public function test_user_updating_dinas_transfers_booking_in_single_transaction(): void
    {
        $this->actingAs($this->student1)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id' => $this->dinasA->id,
            'atas_nama' => 'Mahasiswa 1',
            'asal_instansi' => 'Universitas Indonesia',
            'judul_kegiatan' => 'Magang Pindah Dinas',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);

        $permohonan = PermohonanLayanan::where('user_id', $this->student1->id)->firstOrFail();

        // Slot Dinas A berkurang 1 (sisa 1), Dinas B tetap 5
        $this->assertEquals(1, $this->rekrutmenA->fresh()->slot_tersedia);
        $this->assertEquals(5, $this->rekrutmenB->fresh()->slot_tersedia);

        // Mahasiswa mengalihkan dinas ke Dinas B
        $updRes = $this->actingAs($this->student1)->postJson(route('layanan.update', $permohonan->id), [
            'dinas_id' => $this->dinasB->id,
            'atas_nama' => 'Mahasiswa 1 Update',
        ]);
        $updRes->assertStatus(200);

        // Kuota Dinas A kembali bertambah menjadi 2
        $this->assertEquals(2, $this->rekrutmenA->fresh()->slot_tersedia);

        // Kuota Dinas B terpotong menjadi 4
        $this->assertEquals(4, $this->rekrutmenB->fresh()->slot_tersedia);

        // MagangApplication sekarang terhubung ke Dinas B
        $magangApp = MagangApplication::where('permohonan_layanan_id', $permohonan->id)->firstOrFail();
        $this->assertEquals($this->dinasB->id, $magangApp->dinas_id);
        $this->assertEquals($this->rekrutmenB->id, $magangApp->rekrutmen_id);
    }
}
