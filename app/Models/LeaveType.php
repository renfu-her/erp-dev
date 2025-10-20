<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'requires_approval',
        'default_quota',
        'affects_attendance',
        'rules',
    ];

    protected $casts = [
        'requires_approval' => 'boolean',
        'default_quota' => 'float',
        'affects_attendance' => 'boolean',
        'rules' => 'array',
    ];

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
