<?php
// app/Models/Ppk.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ppk extends Model
{
    use HasFactory;

    protected $table = 'ppk'; // Menggunakan tabel ppk

    protected $fillable = [
        'no_dokumen',
        'divisi',
        'kode_unit',
        'kode_anggaran',
        'diajukan_tanggal',
        'kembali_tanggal',
        'jangka_waktu',
        'total_nilai',
        'status',
        'lampiran',
        'dibuat_oleh'
    ];

    protected $casts = [
        'diajukan_tanggal' => 'date',
        'kembali_tanggal' => 'date',
        'total_nilai' => 'decimal:2',
        'lampiran' => 'array',
        'tanggal_create' => 'datetime'
    ];

    // STATUS CONSTANTS
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING_APPROVAL1 = 'pending_approval1';
    const STATUS_PENDING_APPROVAL2 = 'pending_approval2';
    const STATUS_PENDING_APPROVAL3 = 'pending_approval3';
    const STATUS_PENDING_APPROVAL4 = 'pending_approval4';
    const STATUS_PENDING_APPROVAL5 = 'pending_approval5';
    const STATUS_PENDING_APPROVAL6 = 'pending_approval6';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // APPROVAL LEVELS
    const APPROVAL_LEVELS = [
        'approval1' => 'pending_approval1',
        'approval2' => 'pending_approval2',
        'approval3' => 'pending_approval3',
        'approval4' => 'pending_approval4',
        'approval5' => 'pending_approval5',
        'approval6' => 'pending_approval6'
    ];

    /**
     * Relasi ke ppk_detail (menggunakan nama tabel yang baru)
     */
    public function details(): HasMany
    {
        return $this->hasMany(PpkDetail::class, 'ppk_id')->orderBy('no_aktivitas');
    }

    /**
     * Relasi ke users (pembuat)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Relasi ke ppk_approval_log
     */
    public function approvalLogs(): HasMany
    {
        return $this->hasMany(PpkApprovalLog::class, 'ppk_id')->orderBy('tanggal_aksi');
    }

    /**
     * Get next approval status based on active approval config
     */
    public function getNextApprovalStatus(): string
    {
        $activeConfig = PpkApprovalConfig::getActiveApprovalLevels();
        $currentLevel = $this->getCurrentApprovalLevel();
        
        if (!$currentLevel) {
            // If draft or no current level, get first active approval level
            $firstLevel = $activeConfig->first();
            return $firstLevel ? 'pending_' . $firstLevel->level : self::STATUS_PENDING_APPROVAL1;
        }

        // Find current config
        $currentConfig = $activeConfig->where('level', $currentLevel)->first();
        if (!$currentConfig) {
            return $this->status;
        }

        // Get next level
        $nextLevel = $currentConfig->getNextLevel();
        
        if (!$nextLevel) {
            return self::STATUS_APPROVED;
        }

        // Check if next level is active
        $nextConfig = $activeConfig->where('level', $nextLevel)->first();
        if ($nextConfig) {
            return 'pending_' . $nextLevel;
        }

        // If next level is not active, keep looking for next active level
        return $this->findNextActiveLevel($activeConfig, $nextLevel);
    }

    /**
     * Find next active approval level
     */
    private function findNextActiveLevel($activeConfig, $startLevel)
    {
        $levelOrder = [
            'approval1', 'approval2', 'approval3', 
            'approval4', 'approval5', 'approval6'
        ];

        $startIndex = array_search($startLevel, $levelOrder);
        if ($startIndex === false) {
            return self::STATUS_APPROVED;
        }

        for ($i = $startIndex; $i < count($levelOrder); $i++) {
            $level = $levelOrder[$i];
            if ($activeConfig->where('level', $level)->first()) {
                return 'pending_' . $level;
            }
        }

        return self::STATUS_APPROVED;
    }
    
    /**
     * Generate nomor dokumen otomatis (sudah di-handle trigger DB)
     */
    protected static function boot()
    {
        parent::boot();
        
        // Set default values
        static::creating(function ($ppk) {
            $ppk->tanggal_create = now();
            
            if (empty($ppk->status)) {
                $ppk->status = self::STATUS_DRAFT;
            }
        });

        // Update total_nilai when details change
        static::saved(function ($ppk) {
            $ppk->updateTotalNilai();
        });
    }

    /**
     * Update total nilai from details
     */
    public function updateTotalNilai()
    {
        $total = $this->details()->sum('rencana');
        if ($this->total_nilai != $total) {
            $this->update(['total_nilai' => $total]);
        }
    }

    /**
     * Get current approval level from status using config
     */
    public function getCurrentApprovalLevel()
    {
        if (!in_array($this->status, [
            'pending_approval1', 'pending_approval2', 'pending_approval3',
            'pending_approval4', 'pending_approval5', 'pending_approval6'
        ])) {
            return null;
        }

        // Extract level from status
        $level = str_replace('pending_', '', $this->status);
        
        // Verify level is active in config
        $config = PpkApprovalConfig::byLevel($level)->active()->first();
        
        return $config ? $level : null;
    }

    /**
     * Check if PPK can be approved
     */
    public function canBeApproved()
    {
        return in_array($this->status, [
            self::STATUS_PENDING_APPROVAL1,
            self::STATUS_PENDING_APPROVAL2, 
            self::STATUS_PENDING_APPROVAL3,
            self::STATUS_PENDING_APPROVAL4,
            self::STATUS_PENDING_APPROVAL5,
            self::STATUS_PENDING_APPROVAL6,
        ]);
    }

    /**
     * Check if PPK can be edited
     */
    public function canBeEdited()
    {
        return in_array($this->status, [
            self::STATUS_DRAFT,
            self::STATUS_REJECTED
        ]);
    }

    /**
     * FIXED: Check if PPK can be deleted (HANYA draft, bukan rejected)
     * Requirement: User tidak bisa hapus kecuali status draft
     */
    public function canBeDeleted()
    {
        return $this->status === self::STATUS_DRAFT;
    }


    /**
     * Submit PPK for approval (from draft)
     */
    public function submit()
    {
        if ($this->status !== self::STATUS_DRAFT) {
            return false;
        }
        
        // Get first active approval level
        $firstLevel = PpkApprovalConfig::getActiveApprovalLevels()->first();
        if (!$firstLevel) {
            return false;
        }
        
        $this->update([
            'status' => 'pending_' . $firstLevel->level
        ]);
        
        return true;
    }

    /**
     * Approve PPK to next level using config
     */
    public function approve()
    {
        $nextStatus = $this->getNextApprovalStatus();
        
        if ($nextStatus === $this->status) {
            return false; // No change possible
        }
        
        $this->update([
            'status' => $nextStatus
        ]);
        
        return true;
    }

    /**
     * Reject PPK
     */
    public function reject()
    {
        if (!$this->canBeApproved()) {
            return false;
        }
        
        $this->update([
            'status' => self::STATUS_REJECTED
        ]);
        
        return true;
    }

    /**
     * Get status color for badge
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_PENDING_APPROVAL1 => 'warning',
            self::STATUS_PENDING_APPROVAL2 => 'warning', 
            self::STATUS_PENDING_APPROVAL3 => 'warning',
            self::STATUS_PENDING_APPROVAL4 => 'warning',
            self::STATUS_PENDING_APPROVAL5 => 'warning',
            self::STATUS_PENDING_APPROVAL6 => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get status label for display using config
     */
    public function getStatusLabelAttribute()
    {
        $defaultLabels = [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
        ];

        // For pending statuses, get label from config
        if (strpos($this->status, 'pending_') === 0) {
            $level = str_replace('pending_', '', $this->status);
            $config = PpkApprovalConfig::byLevel($level)->active()->first();
            
            if ($config) {
                return 'Menunggu ' . $config->nama_level;
            }
            
            // Fallback to default labels
            $fallbackLabels = [
                self::STATUS_PENDING_APPROVAL1 => 'Menunggu Approval Staf/Kabag',
                self::STATUS_PENDING_APPROVAL2 => 'Menunggu Approval Kadept',
                self::STATUS_PENDING_APPROVAL3 => 'Menunggu Approval Kadiv User', 
                self::STATUS_PENDING_APPROVAL4 => 'Menunggu Approval Kadiv Keuangan',
                self::STATUS_PENDING_APPROVAL5 => 'Menunggu Approval Direktur',
                self::STATUS_PENDING_APPROVAL6 => 'Menunggu Otorisasi Kasir',
            ];
            
            return $fallbackLabels[$this->status] ?? $this->status;
        }
        
        return $defaultLabels[$this->status] ?? $this->status;
    }

    /**
     * FIXED: Check if user can edit lampiran for this PPK
     * Requirement: User bisa edit lampiran sampai status final (approved/rejected)
     */
    public function canEditLampiran($user)
    {
        // Owner can edit if not yet final status
        if ($this->dibuat_oleh === $user->id) {
            return !in_array($this->status, ['approved', 'rejected']);
        }
        
        // Admin can always edit
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Approvers can edit lampiran during approval process
        if ($this->canBeApproved() && $user->canApprovePpk($this)) {
            return true;
        }
        
        return false;
    }

    /**
     * Get formatted total nilai
     */
    public function getFormattedTotalNilaiAttribute()
    {
        return 'Rp ' . number_format((float)$this->total_nilai, 0, ',', '.');
    }

    /**
     * Get approval progress percentage
     */
    public function getApprovalProgressAttribute()
    {
        if ($this->status === self::STATUS_DRAFT) {
            return 0;
        }

        if ($this->status === self::STATUS_APPROVED) {
            return 100;
        }

        if ($this->status === self::STATUS_REJECTED) {
            return 0;
        }

        $activeConfigs = PpkApprovalConfig::getActiveApprovalLevels();
        $totalLevels = $activeConfigs->count();

        if ($totalLevels === 0) {
            return 0;
        }

        $currentLevel = $this->getCurrentApprovalLevel();
        if (!$currentLevel) {
            return 0;
        }

        $currentConfig = $activeConfigs->where('level', $currentLevel)->first();
        if (!$currentConfig) {
            return 0;
        }

        $currentPosition = $currentConfig->getLevelNumber();
        return (($currentPosition - 1) / $totalLevels) * 100;
    }

   

    public function isFullyApproved()
{
    $activeLevels = PpkApprovalConfig::getActiveApprovalLevels();
    
    foreach ($activeLevels as $config) {
        if (!PpkApprovalLog::isLevelApproved($this->id, $config->level)) {
            return false;
        }
    }
    
    return true;
}


    /**
     * Scope for filtering by status group
     */
    public function scopeByStatusGroup($query, $group)
    {
        switch ($group) {
            case 'pending':
                return $query->where('status', 'like', 'pending_%');
            case 'completed':
                return $query->whereIn('status', [self::STATUS_APPROVED, self::STATUS_REJECTED]);
            case 'active':
                return $query->whereNotIn('status', [self::STATUS_DRAFT]);
            default:
                return $query;
        }
    }

/**
     * FIXED: Scope Filter PPK berdasarkan user role dan level approval
     * Sesuai requirement workflow yang tepat
     */
    public function scopeForUser($query, $user)
    {
        if ($user->isSuperAdmin()) {
            // Admin melihat semua PPK
            return $query;
        }

        if ($user->isPegawai()) {
            // Pegawai hanya melihat PPK miliknya
            return $query->where('dibuat_oleh', $user->id);
        }

        // Untuk Approver (approval1-6)
        $userLevel = $user->getApprovalLevel();
        
        if ($userLevel === 'approval1') {
            // Approval1 melihat: 
            // 1. PPK pending_approval1 di divisinya SAJA
            // 2. PPK miliknya yang draft/rejected (untuk edit)
            return $query->where(function($q) use ($user) {
                $q->where(function($subQ) use ($user) {
                    // PPK pending approval1 di divisi yang sama dengan user
                    $subQ->where('status', 'pending_approval1')
                         ->where('divisi', $user->divisi);
                })
                ->orWhere(function($subQ) use ($user) {
                    // PPK miliknya yang masih bisa diedit (draft/rejected)
                    $subQ->where('dibuat_oleh', $user->id)
                         ->whereIn('status', ['draft', 'rejected']);
                });
            });
        }

        if ($userLevel === 'approval2') {
            // Approval2 melihat PPK pending_approval2 lintas divisi + PPK miliknya yang draft/rejected
            return $query->where(function($q) use ($user) {
                $q->where('status', 'pending_approval2')
                  ->orWhere(function($subQ) use ($user) {
                      $subQ->where('dibuat_oleh', $user->id)
                           ->whereIn('status', ['draft', 'rejected']);
                  });
            });
        }

        if (in_array($userLevel, ['approval3', 'approval4', 'approval5', 'approval6'])) {
            // Approval3-6 melihat PPK sesuai levelnya + PPK miliknya yang draft/rejected
            $statusToCheck = 'pending_' . $userLevel;
            return $query->where(function($q) use ($user, $statusToCheck) {
                $q->where('status', $statusToCheck)
                  ->orWhere(function($subQ) use ($user) {
                      $subQ->where('dibuat_oleh', $user->id)
                           ->whereIn('status', ['draft', 'rejected']);
                  });
            });
        }

        // Default: hanya PPK milik user yang draft/rejected
        return $query->where(function($q) use ($user) {
            $q->where('dibuat_oleh', $user->id)
              ->whereIn('status', ['draft', 'rejected']);
        });
    }

    /**
     * Scope: Filter untuk dashboard stats berdasarkan user access
     */
    public function scopeForUserStats($query, $user)
    {
        if ($user->isSuperAdmin()) {
            return $query; // Admin lihat semua
        }

        if ($user->isPegawai()) {
            return $query->where('dibuat_oleh', $user->id);
        }

        // Untuk approver, lihat PPK yang bisa mereka akses
        $userLevel = $user->getApprovalLevel();
        
        if ($userLevel === 'approval1') {
            return $query->where(function($q) use ($user) {
                $q->where(function($subQ) use ($user) {
                    // PPK di divisi mereka (semua status)
                    $subQ->where('divisi', $user->divisi);
                })
                ->orWhere('dibuat_oleh', $user->id);
            });
        }

        if (in_array($userLevel, ['approval2', 'approval3', 'approval4', 'approval5', 'approval6'])) {
            // Approver level 2-6 bisa lihat semua PPK yang sudah sampai level mereka atau di atasnya
            return $query->where(function($q) use ($user) {
                $q->whereIn('status', [
                    'pending_approval2', 'pending_approval3', 'pending_approval4', 
                    'pending_approval5', 'pending_approval6', 'approved', 'rejected'
                ])
                ->orWhere('dibuat_oleh', $user->id);
            });
        }

        return $query->where('dibuat_oleh', $user->id);
    }
}