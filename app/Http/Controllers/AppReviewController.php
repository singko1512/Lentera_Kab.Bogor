<?php

namespace App\Http\Controllers;

use App\Models\AppReview;
use App\Models\PermohonanLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppReviewController extends Controller
{
    /**
     * Simpan review / feedback aplikasi dari mahasiswa/pemohon (Opsional / Uji Coba)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating_ui_ux' => 'required|integer|min:1|max:10',
            'rating_kemudahan' => 'required|integer|min:1|max:10',
            'rating_kecepatan' => 'required|integer|min:1|max:10',
            'rating_kejelasan' => 'required|integer|min:1|max:10',
            'kritik_saran' => 'nullable|string|max:3000',
            'permohonan_layanan_id' => 'nullable|integer|exists:permohonan_layanans,id',
        ], [
            'rating_ui_ux.required' => 'Mohon berikan penilaian untuk Desain & Antarmuka (UI/UX).',
            'rating_kemudahan.required' => 'Mohon berikan penilaian untuk Kemudahan Penggunaan.',
            'rating_kecepatan.required' => 'Mohon berikan penilaian untuk Kecepatan & Kinerja Sistem.',
            'rating_kejelasan.required' => 'Mohon berikan penilaian untuk Kejelasan Informasi & Alur Layanan.',
        ]);

        $review = AppReview::create([
            'user_id' => Auth::id(),
            'permohonan_layanan_id' => $validated['permohonan_layanan_id'] ?? null,
            'rating_ui_ux' => (int) $validated['rating_ui_ux'],
            'rating_kemudahan' => (int) $validated['rating_kemudahan'],
            'rating_kecepatan' => (int) $validated['rating_kecepatan'],
            'rating_kejelasan' => (int) $validated['rating_kejelasan'],
            'kritik_saran' => $validated['kritik_saran'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Terima kasih! Ulasan dan masukan Anda.',
            'review_id' => $review->id,
        ]);
    }

    /**
     * Halaman Dashboard Rekapitulasi Ulasan Aplikasi untuk Super Admin
     */
    public function superadminIndex(Request $request)
    {
        $query = AppReview::with(['user', 'permohonanLayanan'])->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('kritik_saran', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('asal_instansi', 'like', "%{$search}%");
                  });
            });
        }

        // Filter skor
        if ($request->filled('min_score')) {
            $minScore = (float) $request->min_score;
            $query->whereRaw('((rating_ui_ux + rating_kemudahan + rating_kecepatan + rating_kejelasan) / 4) >= ?', [$minScore]);
        }

        $reviews = $query->paginate(15)->withQueryString();

        // Statistik agregat
        $totalReviews = AppReview::count();
        $avgUiUx = $totalReviews > 0 ? round(AppReview::avg('rating_ui_ux'), 1) : 0;
        $avgKemudahan = $totalReviews > 0 ? round(AppReview::avg('rating_kemudahan'), 1) : 0;
        $avgKecepatan = $totalReviews > 0 ? round(AppReview::avg('rating_kecepatan'), 1) : 0;
        $avgKejelasan = $totalReviews > 0 ? round(AppReview::avg('rating_kejelasan'), 1) : 0;
        
        $overallAvg = $totalReviews > 0 
            ? round(($avgUiUx + $avgKemudahan + $avgKecepatan + $avgKejelasan) / 4, 1) 
            : 0;

        return view('pelayanan.admin.reviews.index', compact(
            'reviews',
            'totalReviews',
            'avgUiUx',
            'avgKemudahan',
            'avgKecepatan',
            'avgKejelasan',
            'overallAvg'
        ));
    }
}
