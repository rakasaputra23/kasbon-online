<?php
// app/Models/PpkApprovalConfig.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class PpkApprovalConfig extends Model
{
    use HasFactory;

    protected $table = 'ppk_approval_config';

    protected $fillable = [
        'level',
        'nama_level',
        'deskripsi',
        'user_group_id',
        'batas_nominal',
        'is_active'
    ];

    protected $casts = [
        'batas_nominal' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Relasi ke user group
     */
    public function userGroup(): BelongsTo
    {
        return $this->belongsTo(UserGroup::class);
    }

    /**
     * Scope untuk config yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope berdasarkan level
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Get config untuk user group tertentu
     */
    public static function getByUserGroup($userGroupId)
    {
        return self::where('user_group_id', $userGroupId)
                  ->where('is_active', true)
                  ->first();
    }

    /**
     * FIXED: Get semua level approval yang aktif berurutan
     * Menambahkan fallback jika database kosong
     */
    public static function getActiveApprovalLevels()
    {
        $levels = [
            'approval1',
            'approval2', 
            'approval3',
            'approval4',
            'approval5',
            'approval6'
        ];

        $activeConfigs = self::whereIn('level', $levels)
                  ->where('is_active', true)
                  ->orderByRaw("FIELD(level, 'approval1', 'approval2', 'approval3', 'approval4', 'approval5', 'approval6')")
                  ->get();

        // FALLBACK: Jika tidak ada config aktif di database, return default config
        if ($activeConfigs->isEmpty()) {
            return collect([
                (object)[
                    'level' => 'approval1',
                    'nama_level' => 'Staf/Kabag',
                    'user_group_id' => null,
                    'is_active' => true
                ],
                (object)[
                    'level' => 'approval2', 
                    'nama_level' => 'Kepala Departemen',
                    'user_group_id' => null,
                    'is_active' => true
                ],
                (object)[
                    'level' => 'approval3',
                    'nama_level' => 'Kepala Divisi User',
                    'user_group_id' => null,
                    'is_active' => true
                ],
                (object)[
                    'level' => 'approval4',
                    'nama_level' => 'Kepala Divisi Keuangan', 
                    'user_group_id' => null,
                    'is_active' => true
                ],
                (object)[
                    'level' => 'approval5',
                    'nama_level' => 'Direktur',
                    'user_group_id' => null,
                    'is_active' => true
                ],
                (object)[
                    'level' => 'approval6',
                    'nama_level' => 'Kasir',
                    'user_group_id' => null,
                    'is_active' => true
                ]
            ]);
        }

        return $activeConfigs;
    }

    /**
     * FIXED: Check apakah user group bisa approve di level tertentu
     * Menambahkan fallback untuk backward compatibility
     */
    public static function canUserGroupApproveAt($userGroupId, $level)
    {
        // Cek di database terlebih dahulu
        $exists = self::where('user_group_id', $userGroupId)
                     ->where('level', $level)
                     ->where('is_active', true)
                     ->exists();

        if ($exists) {
            return true;
        }

        // FALLBACK: Jika tidak ada config di database, gunakan mapping berdasarkan user group name
        try {
            $userGroup = \App\Models\UserGroup::find($userGroupId);
            if (!$userGroup) {
                return false;
            }

            $groupToLevelMap = [
                'Staf/Kabag' => 'approval1',
                'Kepala Departemen' => 'approval2',
                'Kepala Divisi User' => 'approval3',
                'Kepala Divisi Keuangan' => 'approval4',
                'Direktur' => 'approval5',
                'Kasir' => 'approval6',
            ];

            return isset($groupToLevelMap[$userGroup->name]) && 
                   $groupToLevelMap[$userGroup->name] === $level;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get next approval level dari level saat ini
     */
    public function getNextLevel()
    {
        $levelOrder = [
            'approval1' => 'approval2',
            'approval2' => 'approval3',
            'approval3' => 'approval4',
            'approval4' => 'approval5',
            'approval5' => 'approval6',
            'approval6' => null // Final level
        ];

        return $levelOrder[$this->level] ?? null;
    }

    /**
     * Get previous approval level dari level saat ini
     */
    public function getPreviousLevel()
    {
        $levelOrder = [
            'approval2' => 'approval1',
            'approval3' => 'approval2',
            'approval4' => 'approval3',
            'approval5' => 'approval4',
            'approval6' => 'approval5',
        ];

        return $levelOrder[$this->level] ?? null;
    }

    /**
     * Get level number untuk sorting dan progress calculation
     */
    public function getLevelNumber()
    {
        $numbers = [
            'approval1' => 1,
            'approval2' => 2,
            'approval3' => 3,
            'approval4' => 4,
            'approval5' => 5,
            'approval6' => 6
        ];

        return $numbers[$this->level] ?? 0;
    }

    /**
     * ADDED: Get level by user group for User model compatibility
     */
    public static function getLevelByUserGroup($userGroupId)
    {
        $config = self::where('user_group_id', $userGroupId)
                     ->where('is_active', true)
                     ->first();

        if ($config) {
            return $config->level;
        }

        // FALLBACK: Gunakan mapping berdasarkan nama group
        try {
            $userGroup = \App\Models\UserGroup::find($userGroupId);
            if (!$userGroup) {
                return null;
            }

            $groupToLevelMap = [
                'Staf/Kabag' => 'approval1',
                'Kepala Departemen' => 'approval2',
                'Kepala Divisi User' => 'approval3',
                'Kepala Divisi Keuangan' => 'approval4',
                'Direktur' => 'approval5',
                'Kasir' => 'approval6',
            ];

            return $groupToLevelMap[$userGroup->name] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * ADDED: Get nama level by user group
     */
    public static function getLevelNameByUserGroup($userGroupId)
    {
        $config = self::where('user_group_id', $userGroupId)
                     ->where('is_active', true)
                     ->first();

        if ($config) {
            return $config->nama_level;
        }

        // FALLBACK: Default names
        try {
            $userGroup = \App\Models\UserGroup::find($userGroupId);
            if (!$userGroup) {
                return 'Unknown';
            }

            $groupToNameMap = [
                'Staf/Kabag' => 'Staf/Kabag Divisi',
                'Kepala Departemen' => 'Kepala Departemen',
                'Kepala Divisi User' => 'Kepala Divisi User/Verifikator',
                'Kepala Divisi Keuangan' => 'Kepala Divisi Keuangan',
                'Direktur' => 'Direktur SDM & Keuangan',
                'Kasir' => 'Kasir/Otorisasi Pembayaran',
            ];

            return $groupToNameMap[$userGroup->name] ?? $userGroup->name;
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * ADDED: Seed default approval config jika tabel kosong
     */
    public static function seedDefaultConfig()
    {
        // Hanya jalankan jika tabel kosong
        if (self::count() > 0) {
            return;
        }

        $defaultConfigs = [
            [
                'level' => 'approval1',
                'nama_level' => 'Staf/Kabag',
                'deskripsi' => 'Approval pertama oleh Staf atau Kepala Bagian',
                'user_group_id' => null, // Will be set based on actual user groups
                'batas_nominal' => null,
                'is_active' => true
            ],
            [
                'level' => 'approval2',
                'nama_level' => 'Kepala Departemen',
                'deskripsi' => 'Approval kedua oleh Kepala Departemen dengan disposisi anggaran',
                'user_group_id' => null,
                'batas_nominal' => null,
                'is_active' => true
            ],
            [
                'level' => 'approval3',
                'nama_level' => 'Kepala Divisi User',
                'deskripsi' => 'Approval ketiga oleh Kepala Divisi User/Verifikator',
                'user_group_id' => null,
                'batas_nominal' => null,
                'is_active' => true
            ],
            [
                'level' => 'approval4',
                'nama_level' => 'Kepala Divisi Keuangan',
                'deskripsi' => 'Approval keempat oleh Kepala Divisi Keuangan',
                'user_group_id' => null,
                'batas_nominal' => null,
                'is_active' => true
            ],
            [
                'level' => 'approval5',
                'nama_level' => 'Direktur',
                'deskripsi' => 'Approval kelima oleh Direktur SDM & Keuangan',
                'user_group_id' => null,
                'batas_nominal' => null,
                'is_active' => true
            ],
            [
                'level' => 'approval6',
                'nama_level' => 'Kasir',
                'deskripsi' => 'Otorisasi akhir oleh Kasir untuk pembayaran',
                'user_group_id' => null,
                'batas_nominal' => null,
                'is_active' => true
            ]
        ];

        foreach ($defaultConfigs as $config) {
            self::create($config);
        }
    }
}