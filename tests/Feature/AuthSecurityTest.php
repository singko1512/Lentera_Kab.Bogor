<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dinas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

/**
 * AuthSecurityTest
 *
 * Cakupan:
 * - Tidak ada backdoor password ('password123', 'admin123', 'lentera')
 * - Throttle login (RateLimiter key dibersihkan, lalu dicek setelah N percobaan gagal)
 * - Reset token kedaluwarsa ditolak
 * - Respons generik forgot-password & resend-activation
 * - Dev/debug routes mengembalikan 404
 * - Surat PDF butuh autentikasi
 * - Rute verifikasi surat publik menampilkan data minimal
 */
class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // A. Tidak ada backdoor password
    // -------------------------------------------------------------------------

    public function test_login_with_common_backdoor_passwords_fails(): void
    {
        $user = User::factory()->create([
            'email'              => 'secure@lentera.go.id',
            'username'           => 'secure_user',
            'password'           => Hash::make('R3alS3cur3P@ss!'),
            'role'               => 'peserta',
            'status_akun'        => 'aktif',
            'email_verified_at'  => now(),
        ]);

        $backdoors = ['password123', 'admin123', 'lentera', 'password', '123456', 'password@123'];

        foreach ($backdoors as $attempt) {
            $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)
                ->from('/login')->post('/login', [
                    'login'    => $user->email,
                    'password' => $attempt,
                ]);
            $this->assertGuest();
        }

        // Password asli tetap berfungsi
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)
            ->post('/login', [
                'login'    => $user->email,
                'password' => 'R3alS3cur3P@ss!',
            ]);
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_username_backdoor_also_fails(): void
    {
        $user = User::factory()->create([
            'username'          => 'myusername',
            'password'          => Hash::make('UniquePassw0rd!'),
            'role'              => 'peserta',
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->from('/login')->post('/login', [
            'login'    => 'myusername',
            'password' => 'admin123',
        ]);
        $this->assertGuest();
    }

    // -------------------------------------------------------------------------
    // B. Throttle Login
    // -------------------------------------------------------------------------

    public function test_login_throttle_blocks_after_excessive_failures(): void
    {
        $user = User::factory()->create([
            'email'              => 'throttle@lentera.go.id',
            'username'           => 'throttleuser',
            'password'           => Hash::make('CorrectPass99!'),
            'role'               => 'peserta',
            'status_akun'        => 'aktif',
            'email_verified_at'  => now(),
        ]);

        // Bersihkan rate limit sebelum test
        RateLimiter::clear('login|' . request()->ip());

        // Lakukan 6 percobaan login gagal berturut-turut
        for ($i = 0; $i < 6; $i++) {
            $this->from('/login')->post('/login', [
                'login'    => $user->email,
                'password' => 'WrongPassword!',
            ]);
        }

        // Percobaan ke-7: harus di-throttle (422 atau redirect dengan error too many attempts)
        $response = $this->from('/login')->post('/login', [
            'login'    => $user->email,
            'password' => 'CorrectPass99!',
        ]);

        // Either throttled (redirect with session error containing 'banyak' or 'too many')
        // OR still rejected because throttle applies per key
        // Verify user is NOT authenticated (throttle should prevent it)
        $this->assertGuest();
    }

    // -------------------------------------------------------------------------
    // C. Reset Token Kedaluwarsa
    // -------------------------------------------------------------------------

    public function test_expired_reset_password_token_is_rejected(): void
    {
        $user = User::factory()->create([
            'email'      => 'expired@lentera.go.id',
            'status_akun' => 'aktif',
        ]);

        $ttl = config('lentera.reset_token_ttl_minutes', 60);

        DB::table('password_reset_tokens')->insert([
            'email'      => $user->email,
            'token'      => Hash::make('stale-reset-token-xyz'),
            'created_at' => now()->subMinutes($ttl + 5), // Melewati TTL
        ]);

        $response = $this->post('/reset-password', [
            'token'                 => 'stale-reset-token-xyz',
            'email'                 => $user->email,
            'password'              => 'NewSecurePass123!',
            'password_confirmation' => 'NewSecurePass123!',
        ]);

        $response->assertSessionHas('error');
        // Password tidak berubah
        $user->refresh();
        $this->assertFalse(Hash::check('NewSecurePass123!', $user->password));
    }

    public function test_valid_reset_token_within_ttl_is_accepted(): void
    {
        $user = User::factory()->create([
            'email'      => 'validreset@lentera.go.id',
            'status_akun' => 'aktif',
        ]);

        $plainToken = 'fresh-reset-token-abc';

        DB::table('password_reset_tokens')->insert([
            'email'      => $user->email,
            'token'      => Hash::make($plainToken),
            'created_at' => now()->subMinutes(5), // Masih dalam TTL
        ]);

        $response = $this->post('/reset-password', [
            'token'                 => $plainToken,
            'email'                 => $user->email,
            'password'              => 'FreshNewPass456!',
            'password_confirmation' => 'FreshNewPass456!',
        ]);

        // Harus redirect ke login (sukses) atau session success
        $user->refresh();
        $this->assertTrue(Hash::check('FreshNewPass456!', $user->password));
    }

    // -------------------------------------------------------------------------
    // D. Respons Generik (Anti Enumeration)
    // -------------------------------------------------------------------------

    public function test_forgot_password_returns_generic_response_for_unknown_email(): void
    {
        $response = $this->post('/forgot-password/verify', [
            'nik'   => '9999999999999999',
            'email' => 'doesnotexist@nowhere.com',
        ]);

        $response->assertSessionHas(
            'success',
            'Jika data NIK dan email cocok serta terdaftar dalam sistem, tautan reset password telah dikirim ke email Anda.'
        );
    }

    public function test_forgot_password_returns_same_generic_response_for_known_email(): void
    {
        $user = User::factory()->create([
            'email'  => 'known@lentera.go.id',
            'nik'    => '3201010101010099',
            'status_akun' => 'aktif',
        ]);

        $response = $this->post('/forgot-password/verify', [
            'nik'   => $user->nik,
            'email' => $user->email,
        ]);

        // Respons HARUS identik untuk menghindari user enumeration
        $response->assertSessionHas(
            'success',
            'Jika data NIK dan email cocok serta terdaftar dalam sistem, tautan reset password telah dikirim ke email Anda.'
        );
    }

    public function test_resend_activation_returns_generic_response(): void
    {
        $response = $this->post('/resend-activation', [
            'email' => 'notregistered@nowhere.com',
        ]);

        $response->assertSessionHas(
            'success',
            'Jika email Anda terdaftar dan belum aktif, tautan aktivasi baru telah dikirimkan ke kotak masuk email Anda.'
        );
    }

    // -------------------------------------------------------------------------
    // E. Dev/Debug routes harus 404
    // -------------------------------------------------------------------------

    public function test_development_debug_routes_return_404(): void
    {
        $dangerousPaths = [
            '/dev/login/1',
            '/dev/login/999',
            '/diagnosa',
            '/buat-symlink',
            '/debug',
            '/phpinfo',
            '/telescope',   // disable di testing via env
        ];

        foreach ($dangerousPaths as $path) {
            $response = $this->get($path);
            // Either 404 Not Found or 302 redirect to login — never 200 directly
            $this->assertNotEquals(
                200,
                $response->status(),
                "Rute debug '{$path}' seharusnya tidak mengembalikan 200."
            );
        }
    }

    // -------------------------------------------------------------------------
    // F. Surat PDF butuh autentikasi
    // -------------------------------------------------------------------------

    public function test_surat_pdf_route_redirects_unauthenticated_to_login(): void
    {
        $this->get('/surat-rekomendasi/pdf/1')
            ->assertRedirect('/login');
    }

    // -------------------------------------------------------------------------
    // G. Rute verifikasi surat publik hanya data minimal
    // -------------------------------------------------------------------------

    public function test_public_surat_verification_route_accessible_without_auth(): void
    {
        // Token yang tidak ada tetap menampilkan halaman (bukan 500 atau 403)
        $response = $this->get('/verifikasi-surat/token-tidak-ada-xyz-999');
        $response->assertStatus(200);
        $response->assertSee('TIDAK DITEMUKAN');
    }

    public function test_public_surat_verification_does_not_expose_private_data(): void
    {
        // Halaman verifikasi publik tidak boleh menampilkan NIK, email, atau password
        $response = $this->get('/verifikasi-surat/nonexistent-token');
        $response->assertStatus(200);

        // Tidak mengandung data sensitif pemohon
        $content = $response->content();
        $this->assertStringNotContainsString('NIK', $content,
            'Verifikasi publik seharusnya tidak menampilkan NIK.');
        $this->assertStringNotContainsString('Email Pemohon', $content,
            'Verifikasi publik seharusnya tidak menampilkan email.');
        $this->assertStringNotContainsString('Nomor HP', $content,
            'Verifikasi publik seharusnya tidak menampilkan nomor HP.');
    }
}

