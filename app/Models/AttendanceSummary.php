<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'work_date',
        'start_time',
        'end_time',
        'worked_hours',
        'late_minutes',
        'early_leave_minutes',
        'overtime_hours',
        'metadata',
    ];

    protected $casts = [
        'work_date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'metadata' => 'array',
        'worked_hours' => 'float',
        'late_minutes' => 'float',
        'early_leave_minutes' => 'float',
        'overtime_hours' => 'float',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
