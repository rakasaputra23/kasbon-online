<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use SoftDeletes;
    
    // Gunakan koneksi database eksternal
    protected $connection = 'adms_external';
    
    // Nama tabel di database ADMS-IMS
    protected $table = 'pegawai';
    
    // Primary key adalah varchar
    protected $primaryKey = 'id';
    public $incrementing = false; // Karena PK bukan auto increment
    protected $keyType = 'string'; // Karena PK varchar
    
    // Tidak menggunakan timestamp Laravel (created_at, updated_at)
    public $timestamps = false;
    
    // Soft delete column
    protected $dates = ['deleted_at'];
    
    // Kolom yang sesuai dengan struktur tabel ADMS-IMS
    protected $fillable = [
        'id',
        'nama_lengkap',
        'nip',
        'nama_jabatan',
        'master_unit_tree_id',
        'manjab_draft_id',
        'id_site',
        'site',
        'nama_unit'
    ];
    
    // Cast data
    protected $casts = [
        'id_site' => 'integer',
        'deleted_at' => 'datetime',
    ];
    
    // Scope untuk pegawai aktif (tidak terhapus)
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
    
    // Scope untuk pencarian pegawai
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('nip', 'like', "%{$search}%")
              ->orWhere('nama_unit', 'like', "%{$search}%")
              ->orWhere('nama_jabatan', 'like', "%{$search}%");
        });
    }
    
    // Scope untuk filter berdasarkan unit
    public function scopeByUnit($query, $unit)
    {
        return $query->where('nama_unit', $unit);
    }
    
    // Scope untuk filter berdasarkan site
    public function scopeBySite($query, $site)
    {
        return $query->where('site', $site);
    }
    
    // Accessor untuk format nama dengan NIP
    public function getNamaWithNipAttribute()
    {
        return "{$this->nip} - {$this->nama_lengkap}";
    }
    
    // Accessor untuk format nama dengan jabatan
    public function getNamaWithJabatanAttribute()
    {
        return "{$this->nama_lengkap} ({$this->nama_jabatan})";
    }
    
    // Accessor untuk format lengkap
    public function getInfoLengkapAttribute()
    {
        return "{$this->nip} - {$this->nama_lengkap} - {$this->nama_unit}";
    }
}