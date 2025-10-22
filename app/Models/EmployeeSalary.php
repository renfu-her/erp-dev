<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class EmployeeSalary extends Model
{
    protected $fillable = [
        'employee_id',
        'starts_on',
        'base_salary',
        'allowance',
        'locked',
        'note',
    ];

    protected $casts = [
        'starts_on' => 'date',
        'base_salary' => 'decimal:2',
        'allowance' => 'decimal:2',
        'locked' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            // Auto-lock if starts_on is in the past or today
            $startsOn = Carbon::parse($model->starts_on);
            if ($startsOn->isPast() || $startsOn->isToday()) {
                $model->locked = true;
            }
        });

        static::updating(function (self $model) {
            // Prevent modifying records that are locked or already effective
            $originalStarts = Carbon::parse($model->getOriginal('starts_on'));
            $originalLocked = (bool) $model->getOriginal('locked');
            if ($originalLocked || $originalStarts->isPast() || $originalStarts->isToday()) {
                throw new \RuntimeException('此筆薪資已生效或已鎖定，無法更改');
            }
        });
    }
}
