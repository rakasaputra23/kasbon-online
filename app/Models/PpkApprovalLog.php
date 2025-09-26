<?php
// app/Models/PpkApprovalLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Carbon\Carbon;

class PpkApprovalLog extends Model
{
    use HasFactory;

    protected $table = 'ppk_approval_log';

    protected $fillable = [
        'ppk_id',
        'user_id',
        'nama_user',
        'nip_user',
        'level',
        'aksi',
        'catatan',
        'disposisi_dana_tersedia',
        'disposisi_dana_terpakai',
        'disposisi_sisa_anggaran',
        'tanggal_aksi'
    ];

    protected $casts = [
        'disposisi_dana_tersedia' => 'decimal:2',
        'disposisi_dana_terpakai' => 'decimal:2',
        'disposisi_sisa_anggaran' => 'decimal:2',
        'tanggal_aksi' => 'datetime'
    ];

    // Action constants
    const AKSI_APPROVE = 'approve';
    const AKSI_REJECT = 'reject';

    // Level constants (matching Ppk model)
    const LEVEL_APPROVAL1 = 'approval1';
    const LEVEL_APPROVAL2 = 'approval2';
    const LEVEL_APPROVAL3 = 'approval3';
    const LEVEL_APPROVAL4 = 'approval4';
    const LEVEL_APPROVAL5 = 'approval5';
    const LEVEL_APPROVAL6 = 'approval6';

    /**
     * Relasi ke PPK
     */
    public function ppk(): BelongsTo
    {
        return $this->belongsTo(Ppk::class, 'ppk_id');
    }

    /**
     * Relasi ke User yang melakukan aksi
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Set default tanggal_aksi saat creating
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($log) {
            if (empty($log->tanggal_aksi)) {
                $log->tanggal_aksi = now();
            }
            
            // Auto-populate user data jika tidak diisi
            if ($log->user_id && (!$log->nama_user || !$log->nip_user)) {
                $user = User::find($log->user_id);
                if ($user) {
                    $log->nama_user = $log->nama_user ?: $user->nama;
                    $log->nip_user = $log->nip_user ?: $user->nip;
                }
            }
        });
    }

    /**
     * Scope untuk filter berdasarkan PPK
     */
    public function scopeForPpk($query, $ppkId)
    {
        return $query->where('ppk_id', $ppkId);
    }

    /**
     * Scope untuk filter berdasarkan level approval
     */
    public function scopeForLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope untuk filter berdasarkan aksi
     */
    public function scopeForAksi($query, $aksi)
    {
        return $query->where('aksi', $aksi);
    }

    /**
     * Scope untuk approve actions saja
     */
    public function scopeApproved($query)
    {
        return $query->where('aksi', self::AKSI_APPROVE);
    }

    /**
     * Scope untuk reject actions saja
     */
    public function scopeRejected($query)
    {
        return $query->where('aksi', self::AKSI_REJECT);
    }

    /**
     * Scope untuk log dalam periode tertentu
     */
    public function scopeInPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_aksi', [$startDate, $endDate]);
    }

    /**
     * Get formatted tanggal aksi
     */
    public function getFormattedTanggalAksiAttribute()
    {
        return $this->tanggal_aksi ? $this->tanggal_aksi->format('d/m/Y H:i:s') : null;
    }

    /**
     * Get level name in Indonesian
     */
    public function getLevelNameAttribute()
    {
        $levelNames = [
            self::LEVEL_APPROVAL1 => 'Staf/Kabag Divisi',
            self::LEVEL_APPROVAL2 => 'Kepala Departemen',
            self::LEVEL_APPROVAL3 => 'Kepala Divisi User/Verifikator',
            self::LEVEL_APPROVAL4 => 'Kepala Divisi Keuangan',
            self::LEVEL_APPROVAL5 => 'Direktur SDM & Keuangan',
            self::LEVEL_APPROVAL6 => 'Kasir/Otorisasi Pembayaran',
        ];

        return $levelNames[$this->level] ?? $this->level;
    }

    /**
     * Get aksi name in Indonesian
     */
    public function getAksiNameAttribute()
    {
        return $this->aksi === self::AKSI_APPROVE ? 'Disetujui' : 'Ditolak';
    }

    /**
     * Check if this log has disposisi anggaran data
     */
    public function hasDisposisiAnggaran()
    {
        return $this->level === self::LEVEL_APPROVAL2 && 
               ($this->disposisi_dana_tersedia !== null || 
                $this->disposisi_dana_terpakai !== null || 
                $this->disposisi_sisa_anggaran !== null);
    }

    /**
     * Get disposisi anggaran summary
     */
    public function getDisposisiAnggaranSummary()
    {
        if (!$this->hasDisposisiAnggaran()) {
            return null;
        }

        return [
            'dana_tersedia' => $this->disposisi_dana_tersedia,
            'dana_terpakai' => $this->disposisi_dana_terpakai,
            'sisa_anggaran' => $this->disposisi_sisa_anggaran,
            'formatted' => [
                'dana_tersedia' => number_format((float) $this->disposisi_dana_tersedia, 2, ',', '.'),
                'dana_terpakai' => number_format((float) $this->disposisi_dana_terpakai, 2, ',', '.'),
                'sisa_anggaran' => number_format((float) $this->disposisi_sisa_anggaran, 2, ',', '.'),
            ]
        ];
    }

    public static function createApprovalLog($ppkId, $userId, $level, $aksi, $catatan = null, $disposisiData = null)
{
    $user = User::find($userId);
    
    $data = [
        'ppk_id' => $ppkId,
        'user_id' => $userId,
        'nama_user' => $user->nama,
        'nip_user' => $user->nip,
        'level' => $level,
        'aksi' => $aksi,
        'catatan' => $catatan,
        'tanggal_aksi' => now()
    ];
    
    // Add disposisi data untuk approval2
    if ($disposisiData) {
        $data['disposisi_dana_tersedia'] = $disposisiData['dana_tersedia'] ?? null;
        $data['disposisi_dana_terpakai'] = $disposisiData['dana_terpakai'] ?? null;
        $data['disposisi_sisa_anggaran'] = $disposisiData['sisa_anggaran'] ?? null;
    }
    
    return self::create($data);
}


    /**
     * Get approval history untuk specific PPK
     */
    public static function getApprovalHistory($ppkId)
    {
        return self::where('ppk_id', $ppkId)
                   ->orderBy('tanggal_aksi', 'asc')
                   ->get();
    }

    /**
     * Get latest approval for PPK
     */
    public static function getLatestApproval($ppkId)
    {
        return self::where('ppk_id', $ppkId)
                   ->orderBy('tanggal_aksi', 'desc')
                   ->first();
    }

    /**
     * Check if specific level has been approved
     */
    public static function isLevelApproved($ppkId, $level)
    {
        return self::where('ppk_id', $ppkId)
                   ->where('level', $level)
                   ->where('aksi', self::AKSI_APPROVE)
                   ->exists();
    }

    /**
     * Get approval count for PPK
     */
    public static function getApprovalCount($ppkId)
    {
        return self::where('ppk_id', $ppkId)
                   ->where('aksi', self::AKSI_APPROVE)
                   ->count();
    }

    /**
     * Check if PPK has been rejected at any level
     */
    public static function isPpkRejected($ppkId)
    {
        return self::where('ppk_id', $ppkId)
                   ->where('aksi', self::AKSI_REJECT)
                   ->exists();
    }

    /**
     * Get rejection reason if any
     */
    public static function getRejectionReason($ppkId)
    {
        $rejection = self::where('ppk_id', $ppkId)
                         ->where('aksi', self::AKSI_REJECT)
                         ->orderBy('tanggal_aksi', 'desc')
                         ->first();
        
        return $rejection ? $rejection->catatan : null;
    }
}