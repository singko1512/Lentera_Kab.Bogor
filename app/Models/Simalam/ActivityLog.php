<?php

namespace App\Models\Simalam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Bidang;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'md_activity_logs';

    protected $fillable = [
        'user_id',
        'project_id',
        'aktivitas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
