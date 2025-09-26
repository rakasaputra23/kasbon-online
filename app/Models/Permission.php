<?php
// app/Models/Permission.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'route_name',
        'deskripsi'
    ];

    /**
     * Relasi many-to-many ke UserGroups melalui group_permissions
     */
    public function userGroups(): BelongsToMany
    {
        return $this->belongsToMany(UserGroup::class, 'group_permissions', 'permission_id', 'user_group_id')
                    ->withTimestamps();
    }

    /**
     * Scope untuk PPK permissions
     */
    public function scopePpkPermissions($query)
    {
        return $query->where('route_name', 'like', 'ppk.%');
    }

    /**
     * Scope untuk User management permissions
     */
    public function scopeUserManagementPermissions($query)
    {
        return $query->where('route_name', 'like', 'user.%');
    }

    /**
     * Get permission category
     */
    public function getCategoryAttribute()
    {
        if (str_starts_with($this->route_name, 'ppk.')) {
            return 'PPK Management';
        } elseif (str_starts_with($this->route_name, 'user.')) {
            return 'User Management';
        } elseif (str_starts_with($this->route_name, 'permission')) {
            return 'Permission Management';
        } elseif ($this->route_name === 'dashboard') {
            return 'Dashboard';
        } elseif ($this->route_name === 'profile') {
            return 'Profile';
        }
        
        return 'General';
    }

    /**
     * Get grouped permissions by category
     */
    public static function getGroupedPermissions()
    {
        return self::all()->groupBy('category');
    }

    /**
     * Check if permission is PPK related
     */
    public function isPpkPermission(): bool
    {
        return str_starts_with($this->route_name, 'ppk.');
    }

    /**
     * Check if permission is approval related
     */
    public function isApprovalPermission(): bool
    {
        return in_array($this->route_name, [
            'ppk.approve',
            'ppk.reject',
            'ppk.approval'
        ]);
    }
}