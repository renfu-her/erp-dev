<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Models\PayrollPeriod;
use App\Models\PayrollRun;
use App\Models\SalaryComponent;
use App\Support\InsuranceContributionSummary;
use App\Support\InsuranceSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function index(): View
    {
        $recentPeriods = PayrollPeriod::orderByDesc('period_start')->limit(6)->get();
        $periodOptions = PayrollPeriod::orderByDesc('period_start')->get();
        $components = SalaryComponent::orderBy('type')->orderBy('name')->get();
        $recentRuns = PayrollRun::with(['period', 'company'])->orderByDesc('created_at')->limit(5)->get();
        $companies = Company::orderBy('name')->get();
        $employees = Employee::with(['company', 'department', 'position', 'activeContract'])
            ->whereIn('status', ['active', 'onboarding'])
            ->orderBy('company_id')
            ->orderBy('department_id')
            ->orderBy('employee_no')
            ->get();

        $insuranceSchedule = null;
        $insuranceScheduleAvailable = false;

        try {
            $insuranceSchedule = InsuranceSchedule::resolve();
            $insuranceScheduleAvailable = true;
        } catch (\Throwable $e) {
            $insuranceSchedule = null;
            $insuranceScheduleAvailable = false;
        }

        $payrollEmployees = $employees->map(function (Employee $employee) use ($insuranceSchedule) {
            $contract = $employee->activeContract;
            $baseSalary = $contract?->base_salary;
            $positionGrade = $employee->position?->insurance_grade;

            $summary = null;

            if ($insuranceSchedule && ! is_null($baseSalary)) {
                $summary = InsuranceContributionSummary::make((float) $baseSalary, null, $insuranceSchedule);
            }

            $positionSnapshot = $employee->position?->insurance_snapshot ?? null;

            return [
                'employee' => $employee,
                'contract' => $contract,
                'base_salary' => $baseSalary,
                'insurance_summary' => $summary,
                'grade_label' => data_get($summary, 'grade_label')
                    ?? data_get($positionSnapshot, 'grade_label'),
                'grade_value' => data_get($summary, 'grade_value')
                    ?? data_get($positionSnapshot, 'grade_value')
                    ?? $positionGrade,
            ];
        });

        return view('backend.payroll.index', [
            'recentPeriods' => $recentPeriods,
            'periodOptions' => $periodOptions,
            'companies' => $companies,
            'components' => $components,
            'recentRuns' => $recentRuns,
            'payrollEmployees' => $payrollEmployees,
            'insuranceScheduleAvailable' => $insuranceScheduleAvailable,
        ]);
    }

    public function storePeriod(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('createPayrollPeriod', [
            'name' => ['required', 'string', 'max:255', 'unique:payroll_periods,name'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
        ]);

        PayrollPeriod::create([
            'name' => $data['name'],
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
            'status' => 'draft',
        ]);

        return redirect()
            ->route('backend.payroll.index')
            ->with('status', '薪資期間已建立。');
    }

    public function storeRun(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('createPayrollRun', [
            'payroll_period_id' => ['required', 'exists:payroll_periods,id'],
            'company_id' => ['nullable', 'exists:companies,id'],
        ]);

        $exists = PayrollRun::query()
            ->where('payroll_period_id', $data['payroll_period_id'])
            ->where(function ($query) use ($data) {
                if (isset($data['company_id'])) {
                    $query->where('company_id', $data['company_id']);
                } else {
                    $query->whereNull('company_id');
                }
            })
            ->exists();

        if ($exists) {
            return redirect()
                ->route('backend.payroll.index')
                ->withErrors(['payroll_period_id' => '已存在相同公司／期間的薪資批次。'], 'createPayrollRun')
                ->withInput();
        }

        PayrollRun::create([
            'payroll_period_id' => $data['payroll_period_id'],
            'company_id' => $data['company_id'] ?? null,
            'status' => 'draft',
        ]);

        return redirect()
            ->route('backend.payroll.index')
            ->with('status', '薪資批次已建立。');
    }
}
