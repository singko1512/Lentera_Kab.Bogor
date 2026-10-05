<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dinas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\MasterDataSeeder;

/**
 * RoleAccessTest
 *
 * Matriks akses: role × rute → status HTTP yang diharapkan
 *
 * Role:         peserta | dinas | kesbangpol | admin | superadmin | guest
 * Rute utama:   /peserta/dashboard, /dinas/dashboard, /kesbangpol/dashboard,
 *               /admin/dashboard, /superadmin/dashboard, /admin/switch-instansi
 */
class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected Dinas $dinas;
    protected Dinas $kesbangpolDinas;

    protected User $pesertaUser;
    protected User $dinasUser;
    protected User $kesbangpolUser;
    protected User $adminUser;
    protected User $superadminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(MasterDataSeeder::class);

        $this->kesbangpolDinas = Dinas::create([
            'name'         => 'Bakesbangpol Test',
            'is_kesbangpol' => true,
        ]);

        $this->dinas = Dinas::create([
            'name'         => 'Dinas Test',
            'is_kesbangpol' => false,
        ]);

        $this->pesertaUser = User::factory()->create([
            'role'               => 'peserta',
            'status_akun'        => 'aktif',
            'email_verified_at'  => now(),
        ]);

        $this->dinasUser = User::factory()->create([
            'role'               => 'dinas',
            'dinas_id'           => $this->dinas->id,
            'status_akun'        => 'aktif',
            'email_verified_at'  => now(),
        ]);

        $this->kesbangpolUser = User::factory()->create([
            'role'               => 'kesbangpol',
            'dinas_id'           => $this->kesbangpolDinas->id,
            'status_akun'        => 'aktif',
            'email_verified_at'  => now(),
        ]);

        $this->adminUser = User::factory()->create([
            'role'               => 'admin',
            'dinas_id'           => $this->dinas->id,
            'status_akun'        => 'aktif',
            'email_verified_at'  => now(),
        ]);

        $this->superadminUser = User::factory()->create([
            'role'               => 'superadmin',
            'status_akun'        => 'aktif',
            'email_verified_at'  => now(),
        ]);
    }

    // =========================================================================
    // Dashboard Peserta
    // =========================================================================

    public function test_peserta_can_access_peserta_dashboard(): void
    {
        $this->actingAs($this->pesertaUser)
            ->get('/peserta/dashboard')
            ->assertStatus(200);
    }

    public function test_dinas_cannot_access_peserta_dashboard(): void
    {
        $this->actingAs($this->dinasUser)
            ->get('/peserta/dashboard')
            ->assertStatus(403);
    }

    public function test_kesbangpol_cannot_access_peserta_dashboard(): void
    {
        $this->actingAs($this->kesbangpolUser)
            ->get('/peserta/dashboard')
            ->assertStatus(403);
    }

    public function test_guest_is_redirected_from_peserta_dashboard(): void
    {
        $this->get('/peserta/dashboard')
            ->assertRedirect('/login');
    }

    // =========================================================================
    // Dashboard Dinas
    // =========================================================================

    public function test_dinas_can_access_dinas_dashboard(): void
    {
        $this->actingAs($this->dinasUser)
            ->get('/dinas/dashboard')
            ->assertStatus(200);
    }

    public function test_peserta_cannot_access_dinas_dashboard(): void
    {
        $this->actingAs($this->pesertaUser)
            ->get('/dinas/dashboard')
            ->assertStatus(403);
    }

    public function test_kesbangpol_cannot_access_dinas_dashboard(): void
    {
        $this->actingAs($this->kesbangpolUser)
            ->get('/dinas/dashboard')
            ->assertStatus(403);
    }

    public function test_guest_redirected_from_dinas_dashboard(): void
    {
        $this->get('/dinas/dashboard')
            ->assertRedirect('/login');
    }

    // =========================================================================
    // Dashboard Kesbangpol
    // =========================================================================

    public function test_kesbangpol_can_access_kesbangpol_dashboard(): void
    {
        $this->actingAs($this->kesbangpolUser)
            ->get('/kesbangpol/dashboard')
            ->assertStatus(200);
    }

    public function test_admin_can_access_kesbangpol_dashboard(): void
    {
        // Admin bertindak sebagai kesbangpol
        $this->actingAs($this->adminUser)
            ->get('/kesbangpol/dashboard')
            ->assertStatus(200);
    }

    public function test_peserta_cannot_access_kesbangpol_dashboard(): void
    {
        $this->actingAs($this->pesertaUser)
            ->get('/kesbangpol/dashboard')
            ->assertStatus(403);
    }

    public function test_dinas_cannot_access_kesbangpol_dashboard(): void
    {
        $this->actingAs($this->dinasUser)
            ->get('/kesbangpol/dashboard')
            ->assertStatus(403);
    }

    // =========================================================================
    // Dashboard Admin (Simalam)
    // =========================================================================

    public function test_admin_can_access_admin_dashboard(): void
    {
        $this->actingAs($this->adminUser)
            ->get('/admin/dashboard')
            ->assertStatus(200);
    }

    public function test_superadmin_can_access_admin_dashboard(): void
    {
        $this->actingAs($this->superadminUser)
            ->get('/admin/dashboard')
            ->assertStatus(200);
    }

    public function test_peserta_cannot_access_admin_dashboard(): void
    {
        $this->actingAs($this->pesertaUser)
            ->get('/admin/dashboard')
            ->assertStatus(403);
    }

    public function test_dinas_cannot_access_admin_dashboard(): void
    {
        $this->actingAs($this->dinasUser)
            ->get('/admin/dashboard')
            ->assertStatus(403);
    }

    public function test_kesbangpol_cannot_access_admin_dashboard(): void
    {
        $this->actingAs($this->kesbangpolUser)
            ->get('/admin/dashboard')
            ->assertStatus(403);
    }

    // =========================================================================
    // Switch Instansi (Superadmin only)
    // =========================================================================

    public function test_only_superadmin_can_post_switch_instansi(): void
    {
        // Superadmin → allowed (redirect back 302 or 200)
        $response = $this->actingAs($this->superadminUser)
            ->post('/admin/switch-instansi', ['instansi_id' => $this->dinas->id]);
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));

        // All others → 403
        foreach ([$this->adminUser, $this->dinasUser, $this->kesbangpolUser, $this->pesertaUser] as $user) {
            $this->actingAs($user)
                ->post('/admin/switch-instansi', ['instansi_id' => $this->dinas->id])
                ->assertStatus(403);
        }
    }

    // =========================================================================
    // Superadmin Dashboard
    // =========================================================================

    public function test_superadmin_can_access_superadmin_dashboard(): void
    {
        $this->actingAs($this->superadminUser)
            ->get('/superadmin/dashboard')
            ->assertStatus(200);
    }

    public function test_admin_cannot_access_superadmin_dashboard(): void
    {
        $this->actingAs($this->adminUser)
            ->get('/superadmin/dashboard')
            ->assertStatus(403);
    }

    // =========================================================================
    // Landing page publik (tanpa auth)
    // =========================================================================

    public function test_landing_page_accessible_to_guest(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_login_page_accessible_to_guest(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_authenticated_peserta_cannot_access_login_page_again(): void
    {
        // Tergantung middleware RedirectIfAuthenticated
        $this->actingAs($this->pesertaUser)
            ->get('/login')
            ->assertRedirect(); // Any redirect is fine (to dashboard)
    }
}

