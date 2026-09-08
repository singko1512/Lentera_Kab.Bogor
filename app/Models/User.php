<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Dinas;
use App\Models\Bidang;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'pembimbing_magang_id',
        'pembimbing_magang',
        'bidang_magang',
        'tanggal_mulai_magang',
        'tanggal_selesai_magang',
        'status_akun',
        'email_verified_at',
        'activation_token',
        'grup',
        'sertifikat_file_path',
        'sertifikat_file_name',
        'sertifikat_file_mime',
        'sertifikat_diunggah_pada',
        'name',
        'email',
        'password',
        'role',
        'dinas_id',
        'bidang_id',
        'nik',
        'no_hp',
        'tempat_lahir',
        'tanggal_lahir',
        'asal_instansi',
        'program_studi',
        'nim',
        'alamat',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tanggal_mulai_magang' => 'date',
            'tanggal_selesai_magang' => 'date',
        ];
    }

    public function dinas(): BelongsTo
    {
        return $this->belongsTo(Dinas::class);
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    public function getNamaAttribute(): string
    {
        return (string) ($this->attributes['name'] ?? '');
    }

    // SIMALAM Relations
    public function absensiSimalam()
    {
        return $this->hasMany(\App\Models\Simalam\Absensi::class, 'user_id');
    }

    public function jadwalMingguan()
    {
        return $this->hasOne(\App\Models\Simalam\JadwalMingguan::class, 'user_id');
    }

    public function pembimbingMagang()
    {
        return $this->belongsTo(\App\Models\Simalam\PembimbingMagang::class, 'pembimbing_magang_id');
    }

    public function projects()
    {
        return $this->hasMany(\App\Models\Simalam\Project::class, 'user_id');
    }

    public function timelineProjects()
    {
        return $this->belongsToMany(\App\Models\Simalam\Project::class, 'md_project_user', 'user_id', 'project_id')
            ->withTimestamps();
    }

    public function projectDayAssignments()
    {
        return $this->hasMany(\App\Models\Simalam\ProjectDayAssignment::class, 'user_id');
    }

    public function projectModules()
    {
        return $this->belongsToMany(\App\Models\Simalam\ProjectModule::class, 'module_members', 'user_id', 'module_id')
            ->withTimestamps();
    }

    public function taskParticipants()
    {
        return $this->hasMany(\App\Models\Simalam\ProjectTaskParticipant::class, 'user_id');
    }

    public function workSubmissions()
    {
        return $this->hasMany(\App\Models\Simalam\WorkSubmission::class, 'user_id');
    }

    public function magangApplications()
    {
        return $this->hasMany(MagangApplication::class);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin', 'kesbangpol'], true);
    }

    public function isDinas(): bool
    {
        return $this->role === 'dinas';
    }

    public function isBidang(): bool
    {
        return $this->role === 'bidang';
    }

    public function isUser(): bool
    {
        return in_array($this->role, ['user', 'peserta'], true);
    }

    public function isKesbangpol(): bool
    {
        return $this->isAdmin() || ($this->isDinas() && $this->dinas && $this->dinas->is_kesbangpol);
    }
}
