<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    protected $appends = ['users_count'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'group_permissions')
            ->withTimestamps();
    }

    public function getUsersCountAttribute(): int
    {
        return $this->users()->count();
    }

    public function hasPermission($routeName): bool
    {
        return $this->permissions()->where('route_name', $routeName)->exists();
    }

    /**
     * Assign permission to user group
     */
    public function assignPermission($permissionId): void
    {
        if (!$this->permissions()->where('permission_id', $permissionId)->exists()) {
            $this->permissions()->attach($permissionId);
        }
    }

    /**
     * Remove permission from user group
     */
    public function removePermission($permissionId): void
    {
        $this->permissions()->detach($permissionId);
    }

    /**
     * Sync permissions for user group
     */
    public function syncPermissions($permissionIds): void
    {
        $this->permissions()->sync($permissionIds);
    }

    /**
     * Check if this is super admin group
     */
    public function isSuperAdminGroup(): bool
    {
        return $this->id === 1;
    }

    /**
     * Get permission names as array
     */
    public function getPermissionNames(): array
    {
        return $this->permissions()->pluck('route_name')->toArray();
    }
}