<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PositionLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'rank',
        'description',
    ];

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }
}
