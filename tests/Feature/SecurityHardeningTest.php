<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dinas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_dev_and_debug_routes_return_404(): void
    {
        $this->get('/dev/login/1')->assertStatus(404);
        $this->get('/diagnosa')->assertStatus(404);
        $this->get('/buat-symlink')->assertStatus(404);
        $this->get('/pelayanan/any-random-route')->assertStatus(404);
    }

    public function test_login_with_backdoor_password_fails(): void
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'username' => 'testuser',
            'password' => Hash::make('MyRealPassword123!'),
            'role' => 'peserta',
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        // Attempt login with backdoor password 'password123'
        $response1 = $this->from('/login')->post('/login', [
            'login' => 'testuser@example.com',
            'password' => 'password123',
        ]);
        $this->assertGuest();
        $response1->assertRedirect('/login');
        $response1->assertSessionHas('error');

        // Attempt login with backdoor password 'admin123'
        $response2 = $this->from('/login')->post('/login', [
            'login' => 'testuser@example.com',
            'password' => 'admin123',
        ]);
        $this->assertGuest();
        $response2->assertRedirect('/login');

        // Correct password works
        $response3 = $this->post('/login', [
            'login' => 'testuser@example.com',
            'password' => 'MyRealPassword123!',
        ]);
        $this->assertAuthenticatedAs($user);
    }

    public function test_peserta_cannot_access_kesbangpol_or_dinas(): void
    {
        $peserta = User::factory()->create([
            'role' => 'peserta',
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($peserta)
            ->get('/kesbangpol/dashboard')
            ->assertStatus(403);

        $this->actingAs($peserta)
            ->get('/dinas/dashboard')
            ->assertStatus(403);

        $this->actingAs($peserta)
            ->get('/admin/dashboard')
            ->assertStatus(403);
    }

    public function test_dinas_cannot_access_switch_instansi(): void
    {
        $dinas = Dinas::create([
            'nama' => 'Dinas Pendidikan',
            'status_aktif' => true,
            'is_kesbangpol' => false,
        ]);

        $dinasUser = User::factory()->create([
            'role' => 'dinas',
            'dinas_id' => $dinas->id,
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($dinasUser)
            ->post('/admin/switch-instansi', ['instansi_id' => 999])
            ->assertStatus(403);

        $this->actingAs($dinasUser)
            ->post('/admin/dinas/store', ['nama' => 'Dinas Baru'])
            ->assertStatus(403);
    }

    public function test_profile_update_whitelist_prevents_privilege_escalation(): void
    {
        $user = User::factory()->create([
            'role' => 'peserta',
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
            'name' => 'Original Name',
            'nik' => '3201010101010001',
        ]);

        $this->actingAs($user)
            ->post('/profile', [
                'nama' => 'Updated Name',
                'email' => $user->email,
                'nik' => '3201010101010002',
                'role' => 'admin',
                'dinas_id' => 1,
                'bidang_id' => 1,
                'status_akun' => 'dibatasi',
                'email_verified_at' => null,
            ]);

        $user->refresh();

        // Whitelisted fields update
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('3201010101010002', $user->nik);

        // Protected fields must NOT change
        $this->assertEquals('peserta', $user->role);
        $this->assertNull($user->dinas_id);
        $this->assertNull($user->bidang_id);
        $this->assertEquals('aktif', $user->status_akun);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_dinas_cannot_modify_participant_of_another_dinas(): void
    {
        $dinasA = Dinas::create(['name' => 'Dinas Kominfo', 'is_kesbangpol' => false]);
        $dinasB = Dinas::create(['name' => 'Dinas Pendidikan', 'is_kesbangpol' => false]);

        $userDinasA = User::factory()->create([
            'role' => 'dinas',
            'dinas_id' => $dinasA->id,
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        $pesertaB = User::factory()->create([
            'role' => 'peserta',
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);

        $statusMaster = \App\Models\StatusMaster::firstOrCreate(
            ['kode' => 'disetujui'],
            ['nama' => 'Disetujui', 'warna' => 'green']
        );

        $jenisLayanan = \App\Models\JenisLayanan::firstOrCreate(
            ['slug' => 'magang-mahasiswa'],
            ['nama' => 'Magang Mahasiswa']
        );

        $permohonanB = \App\Models\PermohonanLayanan::create([
            'user_id' => $pesertaB->id,
            'jenis_layanan_id' => $jenisLayanan->id,
            'status_master_id' => $statusMaster->id,
            'dinas_id' => $dinasB->id,
            'atas_nama' => $pesertaB->name,
            'no_hp' => '08123456789',
            'tempat_kegiatan' => $dinasB->name,
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addMonths(3),
        ]);

        // PesertaB belongs to Dinas B
        \App\Models\MagangApplication::create([
            'user_id' => $pesertaB->id,
            'dinas_id' => $dinasB->id,
            'permohonan_layanan_id' => $permohonanB->id,
            'status' => 'diterima',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addMonths(3),
        ]);

        // Dinas A attempts to modify Peserta B status
        $this->actingAs($userDinasA)
            ->post('/dinas/participants/' . $pesertaB->id . '/status', [
                'status_akun' => 'diblokir',
            ])
            ->assertStatus(404);

        $pesertaB->refresh();
        $this->assertEquals('aktif', $pesertaB->status_akun);
    }

    public function test_surat_pdf_requires_authentication(): void
    {
        $this->get('/surat-rekomendasi/pdf/1')
            ->assertRedirect('/login');
    }

    public function test_public_surat_verification_route_is_accessible_without_auth(): void
    {
        $response = $this->get('/verifikasi-surat/non-existent-token-12345');
        $response->assertStatus(200);
        $response->assertSee('TIDAK DITEMUKAN / TIDAK VALID');
    }

    public function test_forgot_password_and_resend_activation_give_generic_responses(): void
    {
        // Non-existent user forgot password
        $res1 = $this->post('/forgot-password/verify', [
            'nik' => '9999999999999999',
            'email' => 'unknown@example.com',
        ]);
        $res1->assertSessionHas('success', 'Jika data NIK dan email cocok serta terdaftar dalam sistem, tautan reset password telah dikirim ke email Anda.');

        // Non-existent user resend activation
        $res2 = $this->post('/resend-activation', [
            'email' => 'unknown@example.com',
        ]);
        $res2->assertSessionHas('success', 'Jika email Anda terdaftar dan belum aktif, tautan aktivasi baru telah dikirimkan ke kotak masuk email Anda.');
    }

    public function test_expired_reset_password_token_is_rejected(): void
    {
        $user = User::factory()->create([
            'email' => 'expired@example.com',
            'status_akun' => 'aktif',
        ]);

        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => \Illuminate\Support\Facades\Hash::make('test-token-123'),
            'created_at' => now()->subMinutes(61), // Expired (> 60 minutes)
        ]);

        $response = $this->post('/reset-password', [
            'token' => 'test-token-123',
            'email' => $user->email,
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'NewSecurePassword123!',
        ]);

        $response->assertSessionHas('error', 'Tautan reset password telah kadaluarsa (lebih dari 60 menit). Silakan ajukan ulang.');
    }
    public function test_instansi_detail_route_blocks_sql_injection_payloads(): void
    {
        $dinas = Dinas::create([
            'nama' => 'Dinas Komunikasi dan Informatika',
            'status_magang' => 'tersedia',
            'is_kesbangpol' => false,
        ]);

        // Clean semantic slug renders successfully (Pendekatan 2)
        $this->assertEquals('diskominfo', $dinas->slug);
        $this->get('/instansi/' . $dinas->slug)->assertStatus(200);

        // Multiple dinas resolution test (testing cache and non-cache paths)
        $dinasBkpsdm = Dinas::create([
            'nama' => 'BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA',
            'status_magang' => 'tersedia',
        ]);
        $this->assertEquals('bkpsdm', $dinasBkpsdm->slug);
        $this->get('/instansi/bkpsdm')->assertStatus(200);

        $dinasKecamatan = Dinas::create([
            'nama' => 'KECAMATAN CIBINONG',
            'status_magang' => 'tersedia',
        ]);
        $this->assertEquals('kecamatan-cibinong', $dinasKecamatan->slug);
        $this->get('/instansi/kecamatan-cibinong')->assertStatus(200);

        // Encrypted non-sequential hashid renders successfully (backward compatibility)
        $this->get('/instansi/' . $dinas->hashid)->assertStatus(200);

        // Raw sequential IDs (1, 2, 3, ...) are rejected with 404 to prevent resource enumeration
        $this->get('/instansi/' . $dinas->id)->assertStatus(404);
        $this->get('/instansi/1')->assertStatus(404);
        $this->get('/instansi/2')->assertStatus(404);

        // SQL injection and invalid payloads must be rejected with 404
        $payloads = [
            '1 OR 1=1',
            "1' OR '1'='1",
            "1' UNION SELECT 1,2,3,4--",
            '1; DROP TABLE users;--',
            'abc',
            '-1',
            '0',
            '1.5',
            '1e10',
            '<script>alert(1)</script>',
        ];

        foreach ($payloads as $payload) {
            $response = $this->get('/instansi/' . urlencode($payload));
            $response->assertStatus(404);
        }
    }
    public function test_daftar_peserta_blocks_time_based_blind_sql_injection_on_page_parameter(): void
    {
        // Valid page returns 200
        $this->get('/daftar-peserta?page=1')->assertStatus(200);

        // SQL injection, time-based blind payloads, and non-integer values must be immediately rejected with 404
        $maliciousPages = [
            '1 AND SLEEP(5)',
            "1' OR SLEEP(5)--",
            '(SELECT 1 FROM (SELECT(SLEEP(5)))a)',
            '1;WAITFOR DELAY \x270:0:5\x27--',
            '1 UNION SELECT 1,2,3--',
            '-1',
            '0',
            'abc',
            '1.5',
            '<script>alert(1)</script>',
        ];

        foreach ($maliciousPages as $payload) {
            $startTime = microtime(true);
            $response = $this->get('/daftar-peserta?page=' . urlencode($payload));
            $duration = microtime(true) - $startTime;

            $response->assertStatus(404);
            // Must return instantly without executing SLEEP
            $this->assertLessThan(2.0, $duration, "Time-based blind payload caused significant delay: " . $payload);
        }
    }
    public function test_security_headers_including_content_security_policy_are_present(): void
    {
        $response = $this->get('/refresh-csrf');
        $response->assertHeader('Content-Security-Policy');
        $response->assertHeader('Permissions-Policy');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '0');
        $response->assertHeader('Strict-Transport-Security');

        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
        $this->assertStringContainsString("font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com", $csp);

        $pp = $response->headers->get('Permissions-Policy');
        $this->assertStringContainsString("camera=(self)", $pp);
        $this->assertStringContainsString("geolocation=(self)", $pp);
        $this->assertStringContainsString("microphone=()", $pp);
        $this->assertStringContainsString("payment=()", $pp);
    }
}