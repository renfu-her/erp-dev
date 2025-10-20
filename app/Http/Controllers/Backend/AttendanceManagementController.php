<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceLogRequest;
use App\Models\AttendanceLog;
use App\Models\AttendanceSummary;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceManagementController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['employee_id', 'date_from', 'date_to']);

        $logs = AttendanceLog::query()
            ->with(['employee', 'device'])
            ->when($filters['employee_id'] ?? null, fn ($query, $employeeId) => $query->where('employee_id', $employeeId))
            ->when($filters['date_from'] ?? null, fn ($query, $from) => $query->whereDate('recorded_at', '>=', $from))
            ->when($filters['date_to'] ?? null, fn ($query, $to) => $query->whereDate('recorded_at', '<=', $to))
            ->orderByDesc('recorded_at')
            ->paginate(15)
            ->withQueryString();

        $summaries = collect();
        if ($filters['employee_id'] ?? null) {
            $summaryQuery = AttendanceSummary::query()
                ->where('employee_id', $filters['employee_id'])
                ->when($filters['date_from'] ?? null, fn ($query, $from) => $query->whereDate('work_date', '>=', $from))
                ->when($filters['date_to'] ?? null, fn ($query, $to) => $query->whereDate('work_date', '<=', $to))
                ->orderByDesc('work_date')
                ->limit(14);

            $summaries = $summaryQuery->get();
        }

        return view('backend.attendance.index', [
            'logs' => $logs,
            'summaries' => $summaries,
            'filters' => $filters,
            'employees' => Employee::orderBy('last_name')->orderBy('first_name')->get(),
        ]);
    }

    public function store(StoreAttendanceLogRequest $request): RedirectResponse
    {
        $data = $request->validated();

        AttendanceLog::create([
            'employee_id' => $data['employee_id'],
            'device_id' => $data['device_id'] ?? null,
            'recorded_at' => $data['recorded_at'],
            'type' => $data['type'],
            'source' => $data['source'] ?? 'manual',
            'remarks' => $data['remarks'] ?? null,
        ]);

        return redirect()->route('backend.attendance.index')->with('status', '已新增出勤紀錄。');
    }
}
