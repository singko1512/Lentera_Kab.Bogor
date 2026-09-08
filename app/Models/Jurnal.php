<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $fillable = [
        'magang_application_id',
        'tanggal',
        'kegiatan',
        'file_lampiran',
        'status_verifikasi',
    ];

    public function magangApplication()
    {
        return $this->belongsTo(MagangApplication::class);
    }
}
