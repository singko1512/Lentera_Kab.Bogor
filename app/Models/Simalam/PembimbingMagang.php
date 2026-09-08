<?php

namespace App\Models\Simalam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Bidang;

class PembimbingMagang extends Model
{
    use HasFactory;

    protected $table = 'md_pembimbing_magang';

    protected $fillable = [
        'nama',
        'bidang_id',
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }
}
