<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $fillable = [
        'magang_application_id',
        'tanggal',
        'kegiatan',
        'hasil_pekerjaan',
        'kendala',
        'file_lampiran',
        'file_pekerjaan',
        'status_verifikasi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status_verifikasi' => 'boolean',
    ];

    public function magangApplication()
    {
        return $this->belongsTo(MagangApplication::class);
    }
}
