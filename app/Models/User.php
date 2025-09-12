<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function userGroup(): BelongsTo
    {
        return $this->belongsTo(UserGroup::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->userGroup && $this->userGroup->id === 1;
    }

    public function hasPermission($routeName): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->userGroup && $this->userGroup->hasPermission($routeName);
    }

    public function refreshRelations(): void
    {
        $this->load('userGroup.permissions');
    }

    /**
     * Get first accessible route for user
     */
    public function getFirstAccessibleRoute(): ?string
    {
        if ($this->isSuperAdmin()) {
            return 'dashboard';
        }

        // Daftar route yang akan dicek secara berurutan
        $routesToCheck = [
            'dashboard',
            'user',
            'user.group',
            'permissions.index',
            'profile'
        ];

        // Cek setiap route untuk mencari yang pertama bisa diakses
        foreach ($routesToCheck as $route) {
            if ($this->canAccessRoute($route)) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Check if user can access specific route
     */
    public function canAccessRoute($routeName): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Route yang tidak memerlukan permission khusus (accessible for all authenticated users)
        $publicRoutes = [
            'profile',
            'profile.edit',
            'profile.update'
        ];

        if (in_array($routeName, $publicRoutes)) {
            return true;
        }

        // Untuk route lainnya, cek permission
        return $this->hasPermission($routeName);
    }

    /**
     * Get accessible menu items for user
     */
    public function getAccessibleMenus(): array
    {
        $menus = [];

        if ($this->canAccessRoute('dashboard')) {
            $menus[] = [
                'name' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ];
        }

        if ($this->canAccessRoute('user')) {
            $menus[] = [
                'name' => 'User Management',
                'route' => 'user',
                'icon' => 'fas fa-users',
            ];
        }

        if ($this->canAccessRoute('user.group')) {
            $menus[] = [
                'name' => 'User Groups',
                'route' => 'user.group',
                'icon' => 'fas fa-user-tag',
            ];
        }

        if ($this->canAccessRoute('permissions.index')) {
            $menus[] = [
                'name' => 'Permissions',
                'route' => 'permissions.index',
                'icon' => 'fas fa-key',
            ];
        }

        return $menus;
    }

    /**
     * Get all permissions for this user
     */
    public function getPermissions()
    {
        if ($this->isSuperAdmin()) {
            return Permission::all();
        }

        if (!$this->userGroup) {
            return collect();
        }

        return $this->userGroup->permissions;
    }
}