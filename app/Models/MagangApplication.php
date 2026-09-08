<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagangApplication extends Model
{
    protected $fillable = [
        'user_id',
        'rekrutmen_id',
        'permohonan_layanan_id',
        'dinas_id',
        'bidang_id',
        'status',
        'pesan_lamaran',
        'file_surat_penerimaan',
        'catatan_admin',
        'tanggal_mulai',
        'tanggal_selesai',
        'jadwal_wfh_wfo',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'jadwal_wfh_wfo' => 'array',
    ];

    public function getJadwalWfhWfoAttribute($value)
    {
        $default = [
            'senin' => 'wfo',
            'selasa' => 'wfo',
            'rabu' => 'wfo',
            'kamis' => 'wfo',
            'jumat' => 'wfo',
        ];

        if (!$value) {
            return $default;
        }

        $decoded = is_string($value) ? json_decode($value, true) : $value;
        return array_merge($default, is_array($decoded) ? $decoded : []);
    }

    public function getTodayWorkModeAttribute()
    {
        $dayMap = [
            'Monday' => 'senin',
            'Tuesday' => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday' => 'kamis',
            'Friday' => 'jumat',
            'Saturday' => 'senin',
            'Sunday' => 'senin',
        ];

        $todayKey = $dayMap[now()->format('l')] ?? 'senin';
        $jadwal = $this->jadwal_wfh_wfo;

        return strtoupper($jadwal[$todayKey] ?? 'WFO');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dinas()
    {
        return $this->belongsTo(Dinas::class);
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function rekrutmen()
    {
        return $this->belongsTo(Rekrutmen::class);
    }

    public function permohonanLayanan()
    {
        return $this->belongsTo(PermohonanLayanan::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function jurnals()
    {
        return $this->hasMany(Jurnal::class);
    }
}
