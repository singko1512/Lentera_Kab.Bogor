<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekrutmen extends Model
{
    protected $fillable = [
        'dinas_id',
        'bidang_id',
        'judul',
        'deskripsi_persyaratan',
        'kuota',
        'tanggal_berakhir',
        'is_active',
    ];

    protected $casts = [
        'tanggal_berakhir' => 'date',
        'is_active' => 'boolean',
    ];

    public function dinas()
    {
        return $this->belongsTo(Dinas::class);
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function magangApplications()
    {
        return $this->hasMany(MagangApplication::class);
    }

    public function getSlotTersediaAttribute()
    {
        $statusMemakai = config('lentera.status_memakai_kuota', ['menunggu', 'diterima', 'aktif']);

        if ($this->relationLoaded('magangApplications')) {
            $terisi = (int) $this->magangApplications
                ->whereIn('status', $statusMemakai)
                ->sum(function ($app) {
                    return (int) ($app->jumlah_orang ?? 1);
                });
        } else {
            $terisi = (int) $this->magangApplications()
                ->whereIn('status', $statusMemakai)
                ->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(jumlah_orang, 1)'));
        }

        return max(0, $this->kuota - $terisi);
    }
}
