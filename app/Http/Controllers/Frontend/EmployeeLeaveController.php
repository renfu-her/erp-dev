<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitLeaveRequest;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeLeaveController extends Controller
{
    public function create(): View
    {
        $user = auth()->user();
        $employee = $user?->employee;

        if (! $employee) {
            return redirect()
                ->route('frontend.hr.self-service')
                ->withErrors(['leave_request' => '尚未綁定員工資料，無法提交請假申請。']);
        }

        $leaveTypesCollection = LeaveType::whereIn('name', SubmitLeaveRequest::ALLOWED_LEAVE_NAMES)
            ->get()
            ->keyBy('name');

        $leaveTypes = collect(SubmitLeaveRequest::ALLOWED_LEAVE_NAMES)
            ->map(fn ($name) => $leaveTypesCollection->get($name))
            ->filter();

        $delegates = Employee::query()
            ->where('company_id', $employee->company_id)
            ->where('id', '<>', $employee->id)
            ->where(function ($query) {
                $query->whereNull('user_id')
                    ->orWhereHas('user', function ($userQuery) {
                        $userQuery->whereDoesntHave('roles', function ($roleQuery) {
                            $roleQuery->where('slug', 'system-owner');
                        });
                    });
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('frontend.hr.leave-request', [
            'leaveTypes' => $leaveTypes,
            'employee' => $employee,
            'delegates' => $delegates,
        ]);
    }

    public function store(SubmitLeaveRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $employee = $request->user()?->employee;

        if (! $employee) {
            return redirect()
                ->route('frontend.hr.self-service')
                ->withErrors(['leave_request' => '尚未綁定員工資料，無法提交請假申請。']);
        }

        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        $days = $start->diffInDays($end) + 1;

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $data['leave_type_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'days' => $days,
            'status' => 'pending',
            'reason' => $data['reason'],
            'approval_flow' => null,
            'metadata' => [
                'submitted_via' => 'frontend',
                'delegate_employee_id' => $data['delegate_employee_id'] ?? null,
            ],
        ]);

        return redirect()->route('frontend.hr.leave-request')->with('status', '假單已提交，等待審核。');
    }
}
