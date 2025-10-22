<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CompanyWorkSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'effective_from',
        'effective_until',
        'work_start_time',
        'work_end_time',
        'lunch_start_time',
        'lunch_end_time',
        'working_hours',
        'metadata',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_until' => 'date',
        'work_start_time' => 'datetime:H:i',
        'work_end_time' => 'datetime:H:i',
        'lunch_start_time' => 'datetime:H:i',
        'lunch_end_time' => 'datetime:H:i',
        'working_hours' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeActive($query)
    {
        $today = Carbon::today();
        return $query->where('effective_from', '<=', $today)
                    ->where('effective_until', '>=', $today);
    }

    public function getWorkingHoursAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Calculate working hours if not set
        $start = Carbon::parse($this->work_start_time);
        $end = Carbon::parse($this->work_end_time);
        $lunchStart = Carbon::parse($this->lunch_start_time);
        $lunchEnd = Carbon::parse($this->lunch_end_time);

        $totalHours = $end->diffInHours($start);
        $lunchHours = $lunchEnd->diffInHours($lunchStart);
        
        return $totalHours - $lunchHours;
    }
}
