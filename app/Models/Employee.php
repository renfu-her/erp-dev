<?php

namespace App\Models;

use App\Models\AttendanceLog;
use App\Models\AttendanceSummary;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\OvertimeRequest;
use App\Models\PayrollEntry;
use App\Models\PerformanceReview;
use App\Models\RewardRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'department_id',
        'position_id',
        'user_id',
        'employee_no',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'gender',
        'national_id',
        'salary_grade',
        'is_indigenous',
        'is_disabled',
        'labor_grade',
        'personal_data',
        'status',
        'hired_at',
        'terminated_at',
        'blocked_at',
        'blocked_reason',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hired_at' => 'date',
        'terminated_at' => 'date',
        'blocked_at' => 'datetime',
        'personal_data' => 'array',
        'is_indigenous' => 'boolean',
        'is_disabled' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(EmployeeContact::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(EmployeeAddress::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(EmploymentContract::class);
    }

    public function activeContract(): HasOne
    {
        return $this->hasOne(EmploymentContract::class)
            ->where('is_active', true)
            ->ofMany('starts_on', 'max');
    }

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function attendanceSummaries(): HasMany
    {
        return $this->hasMany(AttendanceSummary::class);
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function overtimeRequests(): HasMany
    {
        return $this->hasMany(OvertimeRequest::class);
    }

    public function payrollEntries(): HasMany
    {
        return $this->hasMany(PayrollEntry::class);
    }

    public function performanceReviews(): HasMany
    {
        return $this->hasMany(PerformanceReview::class);
    }

    public function rewardRecords(): HasMany
    {
        return $this->hasMany(RewardRecord::class);
    }
}
