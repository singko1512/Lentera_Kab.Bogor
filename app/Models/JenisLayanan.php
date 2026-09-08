<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisLayanan extends Model
{
    use HasFactory;

    protected $table = 'jenis_layanans';

    protected $fillable = [
        'nama',
        'slug',
        'is_magang',
    ];

    public function permohonanLayanans(): HasMany
    {
        return $this->hasMany(PermohonanLayanan::class);
    }
}
