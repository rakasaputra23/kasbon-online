<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nip',
        'nama',
        'posisi',
        'user_group_id',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationship dengan user_groups
    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class, 'user_group_id');
    }

    // Method untuk mendapatkan nama lengkap
    public function getFullNameAttribute()
    {
        return $this->nama;
    }

    // Method untuk cek role
    public function hasRole($role)
    {
        return $this->userGroup && $this->userGroup->name === $role;
    }

    // Method untuk cek multiple roles
    public function hasAnyRole(array $roles)
    {
        return $this->userGroup && in_array($this->userGroup->name, $roles);
    }
}