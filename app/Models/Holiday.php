<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'type',
        'is_working_day',
        'year',
        'metadata',
    ];

    protected $casts = [
        'date' => 'date',
        'is_working_day' => 'boolean',
        'metadata' => 'array',
    ];

    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeNational($query)
    {
        return $query->where('type', 'national');
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public static function isHoliday($date)
    {
        $date = Carbon::parse($date)->format('Y-m-d');
        return static::where('date', $date)
                    ->where('is_working_day', false)
                    ->exists();
    }

    public static function isWeekend($date)
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        return $dayOfWeek === Carbon::SATURDAY || $dayOfWeek === Carbon::SUNDAY;
    }

    public static function isWorkingDay($date)
    {
        $date = Carbon::parse($date);
        
        // Check if it's a weekend
        if (static::isWeekend($date)) {
            return false;
        }

        // Check if it's a holiday
        if (static::isHoliday($date)) {
            return false;
        }

        return true;
    }
}
