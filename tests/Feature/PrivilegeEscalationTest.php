<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dinas;
use App\Models\Bidang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\MasterDataSeeder;

/**
 * PrivilegeEscalationTest
 *
 * Verifikasi keamanan: Update profil tidak bisa mengubah role atau dinas_id.
 * Melindungi dari serangan mass-assignment dan privilege escalation.
 */
class PrivilegeEscalationTest extends TestCase
{
    use RefreshDatabase;

    protected Dinas $dinasA;
    protected Dinas $dinasB;
    protected Bidang $bidangA;
    protected User $peserta;
    protected User $dinasUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MasterDataSeeder::class);

        $this->dinasA = Dinas::create([
            'nama'         => 'Dinas Komunikasi dan Informatika',
            'name'         => 'Dinas Komunikasi dan Informatika',
            'kuota'        => 10,
            'status_aktif' => true,
        ]);

        $this->dinasB = Dinas::create([
            'nama'         => 'Badan Kesatuan Bangsa dan Politik',
            'name'         => 'Badan Kesatuan Bangsa dan Politik',
            'kuota'        => 10,
            'status_aktif' => true,
            'is_kesbangpol' => true,
        ]);

        $this->bidangA = Bidang::create([
            'dinas_id'     => $this->dinasA->id,
            'name'         => 'Bidang Aplikasi Informatika',
            'nama_bidang'  => 'Bidang Aplikasi Informatika',
            'status_aktif' => true,
        ]);

        $this->peserta = User::factory()->create([
            'name'              => 'Mahasiswa Asli',
            'email'             => 'mahasiswa@example.com',
            'role'              => 'peserta',
            'dinas_id'          => null,
            'bidang_id'         => null,
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->dinasUser = User::factory()->create([
            'name'              => 'Staff Dinas A',
            'email'             => 'staff.dinas@example.com',
            'role'              => 'dinas',
            'dinas_id'          => $this->dinasA->id,
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);
    }

    public function test_peserta_profile_update_cannot_escalate_role_to_admin(): void
    {
        $this->actingAs($this->peserta)
            ->post('/profile', [
                'nama'     => 'Hacker Name',
                'name'     => 'Hacker Name',
                'email'    => $this->peserta->email,
                'role'     => 'admin', // Serangan escalation!
            ]);

        $this->peserta->refresh();
        $this->assertEquals('peserta', $this->peserta->role, 'Role peserta tidak boleh berubah menjadi admin.');
    }

    public function test_peserta_profile_update_cannot_escalate_role_to_superadmin(): void
    {
        $this->actingAs($this->peserta)
            ->post('/profile', [
                'nama'     => 'Hacker Name',
                'email'    => $this->peserta->email,
                'role'     => 'superadmin',
            ]);

        $this->peserta->refresh();
        $this->assertEquals('peserta', $this->peserta->role, 'Role peserta tidak boleh berubah menjadi superadmin.');
    }

    public function test_peserta_profile_update_cannot_change_dinas_id(): void
    {
        $this->actingAs($this->peserta)
            ->post('/profile', [
                'nama'     => 'Hacker Name',
                'email'    => $this->peserta->email,
                'dinas_id' => $this->dinasA->id, // Serangan inject dinas_id!
            ]);

        $this->peserta->refresh();
        $this->assertNull($this->peserta->dinas_id, 'dinas_id peserta tidak boleh di-assign via update profil.');
    }

    public function test_peserta_profile_update_cannot_change_bidang_id(): void
    {
        $this->actingAs($this->peserta)
            ->post('/profile', [
                'nama'      => 'Hacker Name',
                'email'     => $this->peserta->email,
                'bidang_id' => $this->bidangA->id,
            ]);

        $this->peserta->refresh();
        $this->assertNull($this->peserta->bidang_id, 'bidang_id peserta tidak boleh di-assign via update profil.');
    }

    public function test_peserta_profile_update_cannot_alter_account_status(): void
    {
        $this->actingAs($this->peserta)
            ->post('/profile', [
                'nama'        => 'Hacker Name',
                'email'       => $this->peserta->email,
                'status_akun' => 'dibatasi',
            ]);

        $this->peserta->refresh();
        $this->assertEquals('aktif', $this->peserta->status_akun);
    }

    public function test_dinas_user_profile_update_cannot_escalate_to_superadmin(): void
    {
        $originalDinasId = $this->dinasUser->dinas_id;

        $this->actingAs($this->dinasUser)
            ->post('/profile', [
                'nama'     => 'Staff Dinas A Modified',
                'email'    => $this->dinasUser->email,
                'role'     => 'superadmin',
                'dinas_id' => $this->dinasB->id,
            ]);

        $this->dinasUser->refresh();
        $this->assertEquals('dinas', $this->dinasUser->role, 'Role dinas tidak boleh berubah menjadi superadmin.');
        $this->assertEquals($originalDinasId, $this->dinasUser->dinas_id, 'dinas_id tidak boleh berpindah ke instansi lain.');
    }

    public function test_dinas_dashboard_profile_update_cannot_escalate_role(): void
    {
        $originalDinasId = $this->dinasUser->dinas_id;

        $this->actingAs($this->dinasUser)
            ->post(route('dinas.profile.update'), [
                'nama'     => 'Staff Dinas Rename',
                'email'    => $this->dinasUser->email,
                'role'     => 'superadmin',
                'dinas_id' => $this->dinasB->id,
            ]);

        $this->dinasUser->refresh();
        $this->assertEquals('dinas', $this->dinasUser->role);
        $this->assertEquals($originalDinasId, $this->dinasUser->dinas_id);
    }
}
