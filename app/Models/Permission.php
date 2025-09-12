<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['route_name', 'deskripsi'];

    public function userGroups(): BelongsToMany
    {
        return $this->belongsToMany(UserGroup::class, 'group_permissions')
            ->withTimestamps();
    }
}