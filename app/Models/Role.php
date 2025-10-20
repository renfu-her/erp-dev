<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'display_name',
        'level',
        'description',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Role $role) {
            if ($role->slug === 'system-owner') {
                return false;
            }
        });
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role')
            ->using(UserRole::class)
            ->withTimestamps()
            ->withPivot('rules');
    }

    public function scopes(): HasMany
    {
        return $this->hasMany(RoleScope::class);
    }
}
