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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\MasterDataSeeder;

/**
 * DinasIsolationTest
 *
 * Verifikasi bahwa Dinas A tidak bisa mengakses resource milik Dinas B:
 * - Applications (permohonan / MagangApplication)
 * - Participants (peserta yang sudah diterima dinas B)
 * - Bidang (field/unit dinas lain)
 * - Jurnal / absensi peserta dinas lain
 * - Switch instansi (hanya superadmin)
 *
 * Juga mencakup PrivilegeEscalationTest:
 * - Update profil tidak dapat mengubah role / dinas_id
 */
class DinasIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected Dinas $kesbangpolDinas;
    protected Dinas $dinasA;
    protected Dinas $dinasB;
    protected Bidang $bidangA;
    protected Bidang $bidangB;
    protected Rekrutmen $rekrutmenA;
    protected Rekrutmen $rekrutmenB;

    protected User $kesbangpolUser;
    protected User $userDinasA;
    protected User $userDinasB;
    protected User $pesertaA;  // Terdaftar di Dinas A
    protected User $pesertaB;  // Terdaftar di Dinas B
    protected User $pesertaC;  // Bebas (belum diterima manapun)

    protected $jenisLayanan;
    protected $statusDisetujui;
    protected MagangApplication $magangAppA;
    protected MagangApplication $magangAppB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(MasterDataSeeder::class);

        // Kesbangpol
        $this->kesbangpolDinas = Dinas::create([
            'name'          => 'Bakesbangpol',
            'is_kesbangpol' => true,
            'nama_kepala'   => 'Kepala Bakesbangpol',
            'nip_kepala'    => '197001011995031001',
        ]);

        $this->kesbangpolUser = User::factory()->create([
            'role'              => 'kesbangpol',
            'dinas_id'          => $this->kesbangpolDinas->id,
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        // Dinas A
        $this->dinasA = Dinas::create([
            'name'          => 'Dinas Komunikasi dan Informatika',
            'is_kesbangpol' => false,
            'status_magang' => 'tersedia',
        ]);

        $this->bidangA = Bidang::create([
            'dinas_id' => $this->dinasA->id,
            'name'     => 'Bidang Aptika',
        ]);

        $this->rekrutmenA = Rekrutmen::create([
            'dinas_id'  => $this->dinasA->id,
            'judul'     => 'Rekrutmen Diskominfo',
            'kuota'     => 5,
            'is_active' => true,
        ]);

        $this->userDinasA = User::factory()->create([
            'role'              => 'dinas',
            'dinas_id'          => $this->dinasA->id,
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        // Dinas B
        $this->dinasB = Dinas::create([
            'name'          => 'Dinas Pendidikan',
            'is_kesbangpol' => false,
            'status_magang' => 'tersedia',
        ]);

        $this->bidangB = Bidang::create([
            'dinas_id' => $this->dinasB->id,
            'name'     => 'Bidang Kurikulum',
        ]);

        $this->rekrutmenB = Rekrutmen::create([
            'dinas_id'  => $this->dinasB->id,
            'judul'     => 'Rekrutmen Disdik',
            'kuota'     => 5,
            'is_active' => true,
        ]);

        $this->userDinasB = User::factory()->create([
            'role'              => 'dinas',
            'dinas_id'          => $this->dinasB->id,
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        // Peserta
        $this->pesertaA = User::factory()->create([
            'role'              => 'peserta',
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->pesertaB = User::factory()->create([
            'role'              => 'peserta',
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->pesertaC = User::factory()->create([
            'role'              => 'peserta',
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        // Status & JenisLayanan
        $this->jenisLayanan = JenisLayanan::where('slug', 'kkl_mahasiswa')->first();
        $this->statusDisetujui = StatusMaster::where('kode', 'disetujui')->first();
        $statusMenunggu = StatusMaster::where('kode', 'menunggu_verifikasi')->first();

        // Buat PermohonanLayanan & MagangApplication untuk Peserta A → Dinas A
        $permohonanA = PermohonanLayanan::create([
            'user_id'          => $this->pesertaA->id,
            'dinas_id'         => $this->dinasA->id,
            'jenis_layanan_id' => $this->jenisLayanan->id,
            'status_master_id' => $this->statusDisetujui->id,
            'atas_nama'        => $this->pesertaA->name,
            'no_hp'            => '08111111111',
            'tempat_kegiatan'  => $this->dinasA->name,
            'tanggal_mulai'    => now(),
            'tanggal_selesai'  => now()->addMonths(3),
        ]);

        $this->magangAppA = MagangApplication::create([
            'user_id'              => $this->pesertaA->id,
            'dinas_id'             => $this->dinasA->id,
            'bidang_id'            => $this->bidangA->id,
            'permohonan_layanan_id' => $permohonanA->id,
            'rekrutmen_id'         => $this->rekrutmenA->id,
            'status'               => 'diterima',
            'tanggal_mulai'        => now(),
            'tanggal_selesai'      => now()->addMonths(3),
        ]);

        // Buat PermohonanLayanan & MagangApplication untuk Peserta B → Dinas B
        $permohonanB = PermohonanLayanan::create([
            'user_id'          => $this->pesertaB->id,
            'dinas_id'         => $this->dinasB->id,
            'jenis_layanan_id' => $this->jenisLayanan->id,
            'status_master_id' => $this->statusDisetujui->id,
            'atas_nama'        => $this->pesertaB->name,
            'no_hp'            => '08222222222',
            'tempat_kegiatan'  => $this->dinasB->name,
            'tanggal_mulai'    => now(),
            'tanggal_selesai'  => now()->addMonths(3),
        ]);

        $this->magangAppB = MagangApplication::create([
            'user_id'               => $this->pesertaB->id,
            'dinas_id'              => $this->dinasB->id,
            'bidang_id'             => $this->bidangB->id,
            'permohonan_layanan_id' => $permohonanB->id,
            'rekrutmen_id'          => $this->rekrutmenB->id,
            'status'                => 'diterima',
            'tanggal_mulai'         => now(),
            'tanggal_selesai'       => now()->addMonths(3),
        ]);
    }

    // =========================================================================
    // A. Isolasi Application (MagangApplication)
    // =========================================================================

    public function test_dinas_a_can_view_its_own_applications(): void
    {
        $this->actingAs($this->userDinasA)
            ->get(route('dinas.applications.show', $this->magangAppA->id))
            ->assertStatus(200);
    }

    public function test_dinas_a_cannot_view_dinas_b_application(): void
    {
        $this->actingAs($this->userDinasA)
            ->get(route('dinas.applications.show', $this->magangAppB->id))
            ->assertStatus(404);
    }

    public function test_dinas_b_cannot_verify_dinas_a_application(): void
    {
        $this->actingAs($this->userDinasB)
            ->post(route('dinas.applications.verify', $this->magangAppA->id), [
                'status'    => 'diterima',
                'bidang_id' => $this->bidangB->id,
            ])
            ->assertStatus(404);
    }

    // =========================================================================
    // B. Isolasi Bidang (tidak bisa pakai bidang dinas lain)
    // =========================================================================

    public function test_dinas_a_cannot_assign_dinas_b_bidang_to_application(): void
    {
        // Buat permohonan baru untuk dinasA yang statusnya menunggu + sudah disetujui kesbangpol
        $statusMenunggu = StatusMaster::where('kode', 'menunggu_verifikasi')->first();
        $permohonan = PermohonanLayanan::create([
            'user_id'          => $this->pesertaC->id,
            'dinas_id'         => $this->dinasA->id,
            'jenis_layanan_id' => $this->jenisLayanan->id,
            'status_master_id' => $this->statusDisetujui->id,
            'atas_nama'        => $this->pesertaC->name,
            'no_hp'            => '08333333333',
            'tempat_kegiatan'  => $this->dinasA->name,
            'tanggal_mulai'    => now(),
            'tanggal_selesai'  => now()->addMonths(3),
        ]);

        $magangApp = MagangApplication::create([
            'user_id'               => $this->pesertaC->id,
            'dinas_id'              => $this->dinasA->id,
            'permohonan_layanan_id' => $permohonan->id,
            'rekrutmen_id'          => $this->rekrutmenA->id,
            'status'                => 'menunggu',
        ]);

        // Kesbangpol approve terlebih dahulu
        $this->actingAs($this->kesbangpolUser)
            ->post(route('kesbangpol.layanan.verify', $permohonan->id), [
                'status'   => 'disetujui',
                'dinas_id' => $this->dinasA->id,
            ]);

        $magangApp->refresh();

        // Dinas A mencoba assign bidang_id milik Dinas B → harus 422
        $res = $this->actingAs($this->userDinasA)
            ->postJson(route('dinas.applications.verify', $magangApp->id), [
                'status'    => 'diterima',
                'bidang_id' => $this->bidangB->id, // Bidang milik Dinas B!
            ]);

        $res->assertStatus(422);
        $res->assertJsonValidationErrors('bidang_id');
    }

    // =========================================================================
    // C. Isolasi Participant List
    // =========================================================================

    public function test_dinas_a_cannot_modify_participant_of_dinas_b(): void
    {
        $this->actingAs($this->userDinasA)
            ->post('/dinas/participants/' . $this->pesertaB->id . '/status', [
                'status_akun' => 'diblokir',
            ])
            ->assertStatus(404);

        $this->pesertaB->refresh();
        $this->assertEquals('aktif', $this->pesertaB->status_akun);
    }

    // =========================================================================
    // D. Privilege Escalation via Profile Update
    // =========================================================================

    public function test_peserta_profile_update_cannot_change_role_to_admin(): void
    {
        $this->actingAs($this->pesertaA)
            ->post('/profile', [
                'nama'                   => 'Updated Name',
                'email'                  => $this->pesertaA->email,
                'nik'                    => '3201010101010099',
                'role'                   => 'admin',            // Serangan!
                'dinas_id'               => $this->dinasA->id, // Serangan!
                'bidang_id'              => $this->bidangA->id, // Serangan!
                'status_akun'            => 'dibatasi',         // Serangan!
                'email_verified_at'      => null,               // Serangan!
            ]);

        $this->pesertaA->refresh();

        // Nama boleh berubah (whitelist)
        $this->assertEquals('Updated Name', $this->pesertaA->name);

        // Protected fields HARUS TIDAK berubah
        $this->assertEquals('peserta', $this->pesertaA->role,
            'Role peserta tidak boleh diubah via update profil.');
        $this->assertNull($this->pesertaA->dinas_id,
            'dinas_id tidak boleh diubah via update profil.');
        $this->assertNull($this->pesertaA->bidang_id,
            'bidang_id tidak boleh diubah via update profil.');
        $this->assertEquals('aktif', $this->pesertaA->status_akun,
            'status_akun tidak boleh diubah via update profil.');
        $this->assertNotNull($this->pesertaA->email_verified_at,
            'email_verified_at tidak boleh dihapus via update profil.');
    }

    public function test_dinas_user_profile_update_cannot_change_to_superadmin(): void
    {
        $originalDinasId = $this->userDinasA->dinas_id;

        $this->actingAs($this->userDinasA)
            ->post('/profile', [
                'nama'     => 'Hacked Dinas User',
                'email'    => $this->userDinasA->email,
                'role'     => 'superadmin',
                'dinas_id' => null,
            ]);

        $this->userDinasA->refresh();
        $this->assertEquals('dinas', $this->userDinasA->role);
        $this->assertEquals($originalDinasId, $this->userDinasA->dinas_id);
    }

    // =========================================================================
    // E. Switch Instansi Isolation
    // =========================================================================

    public function test_dinas_a_user_cannot_switch_to_dinas_b_context(): void
    {
        $this->actingAs($this->userDinasA)
            ->post('/admin/switch-instansi', ['instansi_id' => $this->dinasB->id])
            ->assertStatus(403);
    }

    // =========================================================================
    // F. Absensi / Jurnal Isolation
    // =========================================================================

    public function test_dinas_a_admin_cannot_view_dinas_b_absensi_list(): void
    {
        // Coba akses halaman absensi peserta B sebagai admin Dinas A
        // Admin Simalam Dinas A tidak boleh lihat peserta Dinas B
        $res = $this->actingAs($this->userDinasA)
            ->get('/absensi/admin');

        // Harus bisa lihat halaman (200) tapi data di-scope ke dinasA
        // Tidak boleh ada data pesertaB (terdaftar di Dinas B)
        if ($res->status() === 200) {
            $res->assertDontSee($this->pesertaB->name);
        } else {
            // Redirect atau 403 juga diterima (beda role handling)
            $this->assertNotEquals(500, $res->status());
        }
    }
}

