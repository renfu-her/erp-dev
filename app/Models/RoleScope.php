<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleScope extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'scope_type',
        'scope_id',
        'constraints',
    ];

    protected $casts = [
        'constraints' => 'array',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
