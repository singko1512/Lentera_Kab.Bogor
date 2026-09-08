<?php

namespace App\Models\Simalam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Bidang;

class ProjectDayAssignment extends Model
{
    use HasFactory;

    protected $table = 'md_project_day_assignments';

    protected $fillable = [
        'project_id',
        'user_id',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
