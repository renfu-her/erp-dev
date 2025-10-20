<?php

namespace App\View\Components;

use App\Models\AttendanceLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class EmployeeAttendance extends Component
{
    public string $title;
    public Collection $logs;

    public function __construct(string $title = '我的打卡紀錄')
    {
        $this->title = $title;
        $this->logs = $this->resolveLogs();
    }

    protected function resolveLogs(): Collection
    {
        /** @var Authenticatable|null $user */
        $user = Auth::user();

        if (! $user || ! $user->employee) {
            return collect();
        }

        return AttendanceLog::query()
            ->where('employee_id', $user->employee->id)
            ->orderByDesc('recorded_at')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('components.employee-attendance');
    }
}
