<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dinas extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_kesbangpol',
        'deskripsi',
        'alamat',
        'email',
        'telepon',
        'logo',
        'status_magang',
    ];

    protected $casts = [
        'is_kesbangpol' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function bidang(): HasMany
    {
        return $this->hasMany(Bidang::class);
    }

    public function rekrutmens(): HasMany
    {
        return $this->hasMany(Rekrutmen::class);
    }

    public function getComputedStatusAttribute()
    {
        $setting = $this->status_magang ?? 'otomatis';
        if ($setting !== 'otomatis' && !empty($setting)) {
            return $setting;
        }

        $activeRekrutmens = $this->rekrutmens->where('is_active', true);
        if ($activeRekrutmens->count() === 0) {
            return 'tidak_tersedia';
        }

        $slotTersedia = $activeRekrutmens->sum(function ($r) {
            return $r->slot_tersedia;
        });

        if ($slotTersedia > 0) {
            return 'tersedia';
        }

        return 'penuh';
    }

    public function getStatusBadgeAttribute()
    {
        $status = $this->computed_status;

        if ($status === 'tersedia') {
            return [
                'key' => 'tersedia',
                'label' => 'KUOTA TERSEDIA',
                'bg_class' => 'bg-[#E8F5E9] text-[#2E7D32] border border-[#C8E6C9]',
                'icon' => 'check_circle',
            ];
        } elseif ($status === 'penuh') {
            return [
                'key' => 'penuh',
                'label' => 'KUOTA PENUH',
                'bg_class' => 'bg-[#FFEBEE] text-[#C62828] border border-[#FFCDD2]',
                'icon' => 'cancel',
            ];
        } else {
            return [
                'key' => 'tidak_tersedia',
                'label' => 'TIDAK TERSEDIA',
                'bg_class' => 'bg-surface-variant text-on-surface-variant border border-outline-variant/50',
                'icon' => 'block',
            ];
        }
    }
}
