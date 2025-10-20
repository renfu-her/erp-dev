<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessLeaveRequest;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestManagementController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'leave_type_id', 'employee_id']);

        $leaveRequests = LeaveRequest::query()
            ->with(['employee', 'leaveType'])
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['leave_type_id'] ?? null, fn ($query, $typeId) => $query->where('leave_type_id', $typeId))
            ->when($filters['employee_id'] ?? null, fn ($query, $employeeId) => $query->where('employee_id', $employeeId))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('backend.leave-requests.index', [
            'leaveRequests' => $leaveRequests,
            'filters' => $filters,
            'statuses' => ['pending', 'approved', 'rejected', 'cancelled'],
            'leaveTypes' => LeaveType::orderBy('name')->get(),
            'employees' => Employee::orderBy('last_name')->orderBy('first_name')->get(),
        ]);
    }

    public function update(ProcessLeaveRequest $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $data = $request->validated();

        $leaveRequest->fill([
            'status' => $data['status'],
            'metadata' => array_merge($leaveRequest->metadata ?? [], [
                'note' => $data['note'] ?? null,
            ]),
        ]);

        if ($data['status'] === 'approved') {
            $leaveRequest->approved_at = now();
        }

        $leaveRequest->save();

        return redirect()->route('backend.leave-requests.index')->with('status', '假單狀態已更新。');
    }
}
