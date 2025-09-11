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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nip',
        'nama',
        'posisi',
        'user_group_id',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user group that owns the user.
     */
    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class);
    }

    /**
     * Check if user is superadmin
     */
    public function isSuperAdmin()
    {
        return $this->userGroup && $this->userGroup->name === 'Admin';
    }

    /**
     * Get first accessible route for user
     */
    public function getFirstAccessibleRoute()
    {
        // For demo purposes, return dashboard if user has any group
        if ($this->userGroup) {
            return 'dashboard';
        }
        
        return null;
    }

    /**
     * Check if user has permission
     */
    public function hasPermission($permission)
    {
        // For demo purposes, admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Basic permission check based on user group
        return $this->userGroup !== null;
    }
}