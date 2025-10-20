<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitLeaveRequest;
use App\Models\ActivityLog;
use App\Models\ApprovalRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Position;
use App\Support\InsuranceContributionSummary;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class EmployeeManagementController extends Controller
{
    protected array $statuses = ['active', 'inactive', 'onboarding', 'blocked', 'terminated'];

    protected function calculateAnnualLeaveEntitlement(Employee $employee, int $year): float
    {
        if (! $employee->hired_at) {
            return 0;
        }

        $referenceDate = Carbon::create($year, 1, 1);

        if ($employee->hired_at->greaterThan($referenceDate)) {
            return 0;
        }

        $monthsOfService = $employee->hired_at->diffInMonths($referenceDate);

        if ($monthsOfService < 6) {
            return 0;
        }

        $yearsOfService = intdiv($monthsOfService, 12);

        if ($yearsOfService < 1) {
            return 3;
        }

        return match (true) {
            $yearsOfService < 2 => 7,
            $yearsOfService < 3 => 10,
            $yearsOfService < 5 => 14,
            $yearsOfService < 10 => 15,
            default => min(15 + ($yearsOfService - 10), 30),
        };
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['company_id', 'department_id', 'status', 'search']);

        $employees = Employee::query()
            ->with(['company', 'department', 'position'])
            ->when($filters['company_id'] ?? null, fn ($query, $companyId) => $query->where('company_id', $companyId))
            ->when($filters['department_id'] ?? null, fn ($query, $departmentId) => $query->where('department_id', $departmentId))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['search'] ?? null, function ($query, $term) {
                $query->where(function ($sub) use ($term) {
                    $sub->where('employee_no', 'like', "%{$term}%")
                        ->orWhere('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%");
                });
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(12)
            ->withQueryString();

        $companies = Company::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('backend.employees.index', [
            'employees' => $employees,
            'companies' => $companies,
            'departments' => $departments,
            'statuses' => $this->statuses,
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        $employee = new Employee();

        $leaveTypes = LeaveType::whereIn('name', SubmitLeaveRequest::ALLOWED_LEAVE_NAMES)
            ->get()
            ->sortBy(function (LeaveType $type) {
                return array_search($type->name, SubmitLeaveRequest::ALLOWED_LEAVE_NAMES, true);
            })
            ->values();

        return view('backend.employees.create', [
            'employee' => $employee,
            'companies' => Company::orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'positions' => Position::orderBy('title')->get(),
            'statuses' => $this->statuses,
            'leaveTypes' => $leaveTypes,
            'leaveSummaries' => collect(),
            'currentYear' => now()->year,
            'insuranceSummary' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateEmployee($request);

        $data['is_indigenous'] = $request->boolean('is_indigenous');
        $data['is_disabled'] = $request->boolean('is_disabled');

        $employee = Employee::create($data);

        ActivityLog::create([
            'event' => 'employee.created',
            'user_id' => optional($request->user())->id,
            'subject_type' => Employee::class,
            'subject_id' => $employee->id,
            'properties' => ['payload' => $data],
        ]);

        return redirect()->route('backend.employees.index')->with('status', '員工資料已建立。');
    }

    public function edit(Employee $employee): View
    {
        $employee->load('activeContract');

        $leaveTypes = LeaveType::whereIn('name', SubmitLeaveRequest::ALLOWED_LEAVE_NAMES)
            ->get()
            ->sortBy(function (LeaveType $type) {
                return array_search($type->name, SubmitLeaveRequest::ALLOWED_LEAVE_NAMES, true);
            })
            ->values();

        $currentYear = now()->year;

        $balances = $employee->leaveBalances()
            ->where('year', $currentYear)
            ->whereIn('leave_type_id', $leaveTypes->pluck('id'))
            ->get()
            ->keyBy('leave_type_id');

        $leaveSummaries = $leaveTypes->map(function (LeaveType $type) use ($balances, $employee, $currentYear) {
            $balance = $balances->get($type->id);

            $entitled = $balance->entitled ?? ($type->default_quota ?? 0);
            if ($type->code === 'ANNUAL') {
                $entitled = $balance->entitled ?? $this->calculateAnnualLeaveEntitlement($employee, $currentYear);
            }
            $taken = $balance->taken ?? 0;
            $remaining = $balance->remaining ?? ($entitled - $taken);

            return [
                'type' => $type,
                'entitled' => max($entitled, 0),
                'taken' => max($taken, 0),
                'remaining' => max($remaining, 0),
                'pay' => $type->rules['pay'] ?? null,
                'notes' => $type->rules['notes'] ?? null,
            ];
        });

        $insuranceSummary = $this->prepareInsuranceSummary($employee);

        return view('backend.employees.edit', [
            'employee' => $employee,
            'companies' => Company::orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'positions' => Position::orderBy('title')->get(),
            'statuses' => $this->statuses,
            'leaveTypes' => $leaveTypes,
            'leaveSummaries' => $leaveSummaries,
            'currentYear' => $currentYear,
            'insuranceSummary' => $insuranceSummary,
        ]);
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $data = $this->validateEmployee($request, $employee->id);

        $data['is_indigenous'] = $request->boolean('is_indigenous');
        $data['is_disabled'] = $request->boolean('is_disabled');

        $employee->update($data);

        ActivityLog::create([
            'event' => 'employee.updated',
            'user_id' => optional($request->user())->id,
            'subject_type' => Employee::class,
            'subject_id' => $employee->id,
            'properties' => ['changes' => $employee->getChanges()],
        ]);

        return redirect()->route('backend.employees.index')->with('status', '員工資料已更新。');
    }

    public function destroy(Request $request, Employee $employee): RedirectResponse
    {
        $employee->delete();

        ActivityLog::create([
            'event' => 'employee.deleted',
            'user_id' => optional($request->user())->id,
            'subject_type' => Employee::class,
            'subject_id' => $employee->id,
        ]);

        return redirect()->route('backend.employees.index')->with('status', '員工資料已刪除。');
    }

    public function block(Request $request, Employee $employee): RedirectResponse
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $employee->fill([
            'status' => 'blocked',
            'blocked_at' => now(),
            'blocked_reason' => $data['reason'],
        ])->save();

        ApprovalRequest::create([
            'type' => 'employee.block',
            'subject_type' => Employee::class,
            'subject_id' => $employee->id,
            'requested_by' => optional($request->user())->id,
            'status' => 'pending',
            'reason' => $data['reason'],
        ]);

        ActivityLog::create([
            'event' => 'employee.blocked',
            'user_id' => optional($request->user())->id,
            'subject_type' => Employee::class,
            'subject_id' => $employee->id,
            'properties' => ['reason' => $data['reason']],
        ]);

        return redirect()->route('backend.employees.index')->with('status', "員工 {$employee->employee_no} 已被阻擋。");
    }

    public function unblock(Request $request, Employee $employee): RedirectResponse
    {
        $employee->fill([
            'status' => 'active',
            'blocked_at' => null,
            'blocked_reason' => null,
        ])->save();

        ActivityLog::create([
            'event' => 'employee.unblocked',
            'user_id' => optional($request->user())->id,
            'subject_type' => Employee::class,
            'subject_id' => $employee->id,
        ]);

        return redirect()->route('backend.employees.index')->with('status', "員工 {$employee->employee_no} 已解除阻擋。");
    }

    protected function prepareInsuranceSummary(Employee $employee): ?array
    {
        $baseSalary = optional($employee->activeContract)->base_salary;

        return InsuranceContributionSummary::make($baseSalary);
    }

    protected function validateEmployee(Request $request, ?int $employeeId = null): array
    {
        return $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'position_id' => ['nullable', 'exists:positions,id'],
            'employee_no' => [
                'required',
                'string',
                'max:64',
                Rule::unique('employees', 'employee_no')->ignore($employeeId),
            ],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'salary_grade' => ['nullable', 'string', 'max:50'],
            'labor_grade' => ['nullable', 'string', 'max:50'],
            'is_indigenous' => ['sometimes', 'boolean'],
            'is_disabled' => ['sometimes', 'boolean'],
            'status' => ['required', 'in:' . implode(',', $this->statuses)],
            'hired_at' => ['nullable', 'date'],
            'terminated_at' => ['nullable', 'date', 'after_or_equal:hired_at'],
        ]);
    }
}
