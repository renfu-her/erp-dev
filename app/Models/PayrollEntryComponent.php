<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollEntryComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_entry_id',
        'salary_component_id',
        'amount',
        'is_manual',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'float',
        'is_manual' => 'boolean',
        'metadata' => 'array',
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(PayrollEntry::class, 'payroll_entry_id');
    }

    public function salaryComponent(): BelongsTo
    {
        return $this->belongsTo(SalaryComponent::class);
    }
}
