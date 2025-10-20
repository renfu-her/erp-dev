<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalaryComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'is_taxable',
        'is_recurring',
        'calculation_rules',
    ];

    protected $casts = [
        'is_taxable' => 'boolean',
        'is_recurring' => 'boolean',
        'calculation_rules' => 'array',
    ];

    public function entryComponents(): HasMany
    {
        return $this->hasMany(PayrollEntryComponent::class);
    }
}
