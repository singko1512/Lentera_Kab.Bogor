<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusMaster extends Model
{
    use HasFactory;

    protected $table = 'status_masters';

    protected $fillable = [
        'kode',
        'nama',
        'warna',
    ];

    public function permohonanLayanans(): HasMany
    {
        return $this->hasMany(PermohonanLayanan::class);
    }
}
