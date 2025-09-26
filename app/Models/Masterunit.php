<?php
// app/Models/MasterUnit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterUnit extends Model
{
    protected $connection = 'mysql_external'; // Menggunakan koneksi external
    protected $table = 'master_unit';
    
    // Tidak perlu fillable karena kita hanya read data
    
    /**
     * Get list divisi untuk dropdown
     */
    public static function getDivisiList()
    {
        return static::whereNotNull('unit')
                    ->where('unit', '!=', '')
                    ->whereNull('deleted_at') // jika menggunakan soft delete
                    ->distinct()
                    ->orderBy('unit')
                    ->pluck('unit')
                    ->toArray();
    }
}