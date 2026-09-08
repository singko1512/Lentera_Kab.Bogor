<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'bidang';

    protected $fillable = [
        'dinas_id',
        'name',
    ];

    public function dinas(): BelongsTo
    {
        return $this->belongsTo(Dinas::class);
    }

    public function pembimbingMagangs()
    {
        return $this->hasMany(\App\Models\Simalam\PembimbingMagang::class, 'bidang_id');
    }

    public function getNamaAttribute(): string
    {
        return (string) ($this->attributes['name'] ?? '');
    }
}
