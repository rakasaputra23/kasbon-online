<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PpkApprovalConfig;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nip',
        'nama',
        'posisi',
        'divisi',
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

        $routesToCheck = [
            'dashboard',
            'ppk.index',
            'user.index',
            'user.group.index', 
            'permissions.index',
            'profile'
        ];

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

        $publicRoutes = [
            'profile',
            'profile.edit',
            'profile.update'
        ];

        if (in_array($routeName, $publicRoutes)) {
            return true;
        }

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

        if ($this->canAccessRoute('ppk.index')) {
            $menus[] = [
                'name' => 'PPK/Kasbon',
                'route' => 'ppk.index', 
                'icon' => 'fas fa-file-invoice-dollar',
            ];
        }

        if ($this->canAccessRoute('user.index')) {
            $menus[] = [
                'name' => 'User Management',
                'route' => 'user.index',
                'icon' => 'fas fa-users',
            ];
        }

        if ($this->canAccessRoute('user.group.index')) {
            $menus[] = [
                'name' => 'User Groups',
                'route' => 'user.group.index',
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

    /**
     * FIXED: Check if user can approve at specific level
     */
    public function canApproveAtLevel($level)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $userLevel = $this->getApprovalLevel();
        return $userLevel === $level;
    }

     /**
     * FINAL: Enhanced canApproveAt method
     */
    public function canApproveAt($status)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Map PPK status to approval levels
        $statusLevelMap = [
            'pending_approval1' => 'approval1',
            'pending_approval2' => 'approval2', 
            'pending_approval3' => 'approval3',
            'pending_approval4' => 'approval4',
            'pending_approval5' => 'approval5',
            'pending_approval6' => 'approval6',
        ];
        
        if (!isset($statusLevelMap[$status])) {
            return false;
        }
        
        $requiredLevel = $statusLevelMap[$status];
        $userLevel = $this->getApprovalLevel();
        
        return $userLevel === $requiredLevel;
    }

    public function canApprovePpk($ppk)
{
    if ($this->isSuperAdmin()) {
        return true;
    }

    if (!$ppk->canBeApproved()) {
        return false;
    }

    // Cek apakah user bisa approve di level saat ini
    $currentLevel = $ppk->getCurrentApprovalLevel();
    $userLevel = $this->getApprovalLevel();
    
    // User harus sesuai dengan level approval yang dibutuhkan
    if ($currentLevel !== $userLevel) {
        return false;
    }
    
    // Special case untuk approval1 - harus same divisi
    if ($currentLevel === 'approval1') {
        return $this->divisi === $ppk->divisi;
    }
    
    return true;
}

    /**
     * FINAL: Get approval level berdasarkan database yang ada
     */
    public function getApprovalLevel()
    {
        if ($this->isSuperAdmin()) {
            return 'admin';
        }

        // Check if user is regular employee (Pegawai)
        if ($this->userGroup && $this->userGroup->name === 'Pegawai') {
            return 'pegawai';
        }

        // BERDASARKAN DATABASE: Mapping user_group_id ke approval level
        $groupLevelMap = [
            29 => 'approval1', // Staf/Kabag
            30 => 'approval2', // Kepala Departemen  
            24 => 'approval3', // Kepala Divisi User
            25 => 'approval4', // Kepala Divisi Keuangan
            26 => 'approval5', // Direktur SDM Keuangan
            27 => 'approval6', // Kasir
        ];

        if (isset($groupLevelMap[$this->user_group_id])) {
            return $groupLevelMap[$this->user_group_id];
        }

        // Fallback: gunakan PpkApprovalConfig
        $fallbackLevel = PpkApprovalConfig::getLevelByUserGroup($this->user_group_id);
        if ($fallbackLevel !== null) {
            return $fallbackLevel;
        }

        // Always return a string as a fallback
        return 'unknown';
    }

    /**
     * Check if user is in same division (for approval1 level filtering)
     */
    public function isInSameDivision($divisi)
    {
        // For approval1, user should be in same division
        if ($this->getApprovalLevel() === 'approval1') {
            return $this->divisi === $divisi;
        }
        
        // Other levels can see across divisions
        return true;
    }

    /**
     * Check if user is a Pegawai (regular employee)
     */
    public function isPegawai()
    {
        return $this->userGroup && $this->userGroup->name === 'Pegawai';
    }

    /**
     * FINAL FIX: Get approval level name menggunakan PpkApprovalConfig
     */
    public function getApprovalLevelName()
    {
        $level = $this->getApprovalLevel();
        
        if ($level === 'admin') {
            return 'Administrator';
        }
        
        if ($level === 'pegawai') {
            return 'Pegawai';
        }

        // GUNAKAN PpkApprovalConfig untuk nama level
        return PpkApprovalConfig::getLevelNameByUserGroup($this->user_group_id);
    }

    /**
     * FIXED: Check if user can edit lampiran at specific PPK status
     * Requirement: User bisa edit lampiran sampai status final (approve/reject)
     */
    public function canEditLampiran($ppkStatus, $isOwner = false)
    {
        // Admin always can edit
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Owner can edit lampiran SAMPAI status final (bukan hanya draft/rejected)
        if ($isOwner) {
            // Owner bisa edit sampai PPK belum final (approved/rejected)
            return !in_array($ppkStatus, ['approved', 'rejected']);
        }

        // Approvers can edit lampiran during their approval process
        return $this->canApproveAt($ppkStatus);
    }

    /**
     * FIXED: Check if user can delete PPK (HANYA draft, bukan rejected)
     */
    public function canDeletePpk($ppk)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Only owner can delete, and only if status is draft
        return ($ppk->dibuat_oleh === $this->id && $ppk->status === 'draft');
    }

    /**
     * Check if user can submit PPK (only owners can submit their draft PPK)
     */
    public function canSubmitPpk($ppk)
    {
        return $ppk->dibuat_oleh === $this->id && 
               $ppk->status === 'draft' &&
               $this->hasPermission('ppk.submit');
    }

    /**
     * FIXED: Check if user can view specific PPK
     * Sesuai requirement workflow
     */
    public function canViewPpk($ppk)
    {
        if ($this->isSuperAdmin()) {
            return true; // Admin bisa lihat semua
        }

        if ($this->isPegawai()) {
            return $ppk->dibuat_oleh === $this->id; // Pegawai hanya PPK miliknya
        }

        // Untuk approver
        $userLevel = $this->getApprovalLevel();

        if ($userLevel === 'approval1') {
            // Approval1: PPK pending_approval1 di divisinya + PPK miliknya yang draft/rejected
            return ($ppk->status === 'pending_approval1' && $ppk->divisi === $this->divisi) ||
                   ($ppk->dibuat_oleh === $this->id && in_array($ppk->status, ['draft', 'rejected']));
        }

        if ($userLevel === 'approval2') {
            // Approval2: PPK pending_approval2 lintas divisi + PPK miliknya yang draft/rejected
            return ($ppk->status === 'pending_approval2') ||
                   ($ppk->dibuat_oleh === $this->id && in_array($ppk->status, ['draft', 'rejected']));
        }

        if (in_array($userLevel, ['approval3', 'approval4', 'approval5', 'approval6'])) {
            $statusToCheck = 'pending_' . $userLevel;
            // Approval3-6: PPK di levelnya + PPK miliknya yang draft/rejected
            return ($ppk->status === $statusToCheck) ||
                   ($ppk->dibuat_oleh === $this->id && in_array($ppk->status, ['draft', 'rejected']));
        }

        return false;
    }



    /**
     * Get divisi name for display
     */
    public function getDivisiName()
    {
        return $this->divisi ?? 'Tidak ada divisi';
    }

    /**
     * Check if user is approver (any level)
     */
    public function isApprover()
    {
        $level = $this->getApprovalLevel();
        return in_array($level, ['approval1', 'approval2', 'approval3', 'approval4', 'approval5', 'approval6']);
    }
}