<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'permohonan_layanan_id',
        'rating_ui_ux',
        'rating_kemudahan',
        'rating_kecepatan',
        'rating_kejelasan',
        'kritik_saran',
    ];

    protected $casts = [
        'rating_ui_ux' => 'integer',
        'rating_kemudahan' => 'integer',
        'rating_kecepatan' => 'integer',
        'rating_kejelasan' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permohonanLayanan()
    {
        return $this->belongsTo(PermohonanLayanan::class, 'permohonan_layanan_id');
    }

    public function getAverageScoreAttribute(): float
    {
        return round(($this->rating_ui_ux + $this->rating_kemudahan + $this->rating_kecepatan + $this->rating_kejelasan) / 4, 1);
    }
}
