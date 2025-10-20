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
        return view('frontend.hr.leave-request', [
            'leaveTypes' => LeaveType::orderBy('name')->get(),
            'employees' => Employee::orderBy('last_name')->orderBy('first_name')->get(),
        ]);
    }

    public function store(SubmitLeaveRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        $days = $start->diffInDays($end) + 1;

        LeaveRequest::create([
            'employee_id' => $data['employee_id'],
            'leave_type_id' => $data['leave_type_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'days' => $days,
            'status' => 'pending',
            'reason' => $data['reason'],
            'approval_flow' => null,
            'metadata' => [
                'submitted_via' => 'frontend',
            ],
        ]);

        return redirect()->route('frontend.hr.leave-request')->with('status', '假單已提交，等待審核。');
    }
}
