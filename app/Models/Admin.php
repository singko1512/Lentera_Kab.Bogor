<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status_akun',
        'dinas_id',
        'bidang_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
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

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin'], true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isDinas(): bool
    {
        return $this->role === 'dinas';
    }

    public function isBidang(): bool
    {
        return $this->role === 'bidang';
    }

    public function isKesbangpol(): bool
    {
        return $this->isAdmin() || $this->role === 'kesbangpol';
    }

    public function isUser(): bool
    {
        return false;
    }
}
