<?php

namespace App\Models\Simalam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Bidang;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'md_pengaturan';

    protected $fillable = [
        'kunci',
        'nilai',
    ];
}
