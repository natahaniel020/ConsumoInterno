<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

        // ─── Relaciones ───────────────────────
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function supplyRequests()
    {
        return $this->hasMany(SupplyRequest::class, 'employee_id');
    }

    public function approvedRequests()
    {
        return $this->hasMany(SupplyRequest::class, 'approved_by');
    }

    // helpers en User.php
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }
}
