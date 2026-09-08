<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'magang_application_id',
        'tanggal',
        'waktu_masuk',
        'waktu_pulang',
        'status',
        'catatan',
    ];

    public function magangApplication()
    {
        return $this->belongsTo(MagangApplication::class);
    }
}
