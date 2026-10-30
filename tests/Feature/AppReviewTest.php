<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AppReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_and_kesbangpol_can_delete_review(): void
    {
        $kesbangpol = Admin::create([
            'name' => 'Admin Kesbangpol',
            'username' => 'kesbangpol_test',
            'email' => 'kesbangpol_test@example.com',
            'password' => bcrypt('Password123!'),
            'role' => 'kesbangpol',
            'status_akun' => 'aktif',
        ]);

        $review = AppReview::create([
            'rating_ui_ux' => 9,
            'rating_kemudahan' => 9,
            'rating_kecepatan' => 9,
            'rating_kejelasan' => 9,
            'kritik_saran' => 'Kritik saran yang akan dihapus',
        ]);

        $this->assertDatabaseHas('app_reviews', ['id' => $review->id]);

        $response = $this->actingAs($kesbangpol, 'admin')
            ->delete(route('superadmin.reviews.destroy', $review->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Ulasan / kritik & saran berhasil dihapus.');
        $this->assertDatabaseMissing('app_reviews', ['id' => $review->id]);
    }
}
