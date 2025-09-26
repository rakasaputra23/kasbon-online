<?php
// app/Models/PpkDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpkDetail extends Model
{
    use HasFactory;

    protected $table = 'ppk_detail';

    protected $fillable = [
        'ppk_id',
        'no_aktivitas',
        'tanggal_aktivitas',
        'aktivitas',
        'rencana'
    ];

    protected $casts = [
        'tanggal_aktivitas' => 'date',
        'rencana' => 'decimal:2'
    ];

    /**
     * Relasi ke PPK parent
     */
    public function ppk(): BelongsTo
    {
        return $this->belongsTo(Ppk::class, 'ppk_id');
    }

    /**
     * Get formatted rencana amount
     */
    public function getFormattedRencanaAttribute()
    {
        return number_format((float) $this->rencana, 2, ',', '.');
    }

    /**
     * Get formatted tanggal aktivitas
     */
    public function getFormattedTanggalAktivitasAttribute()
    {
        return $this->tanggal_aktivitas ? \Carbon\Carbon::parse($this->tanggal_aktivitas)->format('d/m/Y') : null;
    }

    /**
     * Scope untuk ordering by no_aktivitas
     */
    public function scopeOrderedByActivity($query)
    {
        return $query->orderBy('no_aktivitas');
    }

    /**
     * Scope untuk filter by PPK
     */
    public function scopeForPpk($query, $ppkId)
    {
        return $query->where('ppk_id', $ppkId);
    }

    /**
     * Scope untuk filter by date range
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_aktivitas', [$startDate, $endDate]);
    }

    /**
     * Get next activity number for PPK
     */
    public static function getNextActivityNumber($ppkId)
    {
        $lastActivity = self::where('ppk_id', $ppkId)
                           ->orderBy('no_aktivitas', 'desc')
                           ->first();
        
        return $lastActivity ? $lastActivity->no_aktivitas + 1 : 1;
    }

    /**
     * Validate activity data
     */
    public function validateActivity()
    {
        $errors = [];

        if (empty($this->aktivitas)) {
            $errors[] = 'Uraian aktivitas harus diisi';
        }

        if ($this->rencana <= 0) {
            $errors[] = 'Rencana biaya harus lebih dari 0';
        }

        if (!$this->tanggal_aktivitas) {
            $errors[] = 'Tanggal aktivitas harus diisi';
        }

        return $errors;
    }
}