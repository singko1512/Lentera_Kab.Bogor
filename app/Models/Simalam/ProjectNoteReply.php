<?php

namespace App\Models\Simalam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Bidang;

class ProjectNoteReply extends Model
{
    use HasFactory;

    protected $table = 'md_project_note_replies';

    protected $fillable = [
        'submission_id',
        'task_id',
        'user_id',
        'tipe',
        'isi',
        'lampiran',
    ];

    public function submission()
    {
        return $this->belongsTo(WorkSubmission::class, 'submission_id');
    }

    public function task()
    {
        return $this->belongsTo(ProjectTask::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
