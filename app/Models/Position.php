<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_id',
        'position_level_id',
        'title',
        'grade',
        'is_managerial',
        'metadata',
    ];

    protected $casts = [
        'is_managerial' => 'boolean',
        'metadata' => 'array',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(PositionLevel::class, 'position_level_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
