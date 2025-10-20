<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use App\Models\PositionLevel;
use App\Support\InsuranceContributionSummary;
use App\Support\InsuranceSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PositionManagementController extends Controller
{
    protected bool $insuranceScheduleLoaded = false;

    protected ?InsuranceSchedule $insuranceSchedule = null;

    public function index(Request $request): View
    {
        $filters = $request->only(['department_id', 'search']);

        $positions = Position::with(['department.company', 'level'])
            ->when(
                $filters['department_id'] ?? null,
                fn ($query, $departmentId) => $query->where('department_id', $departmentId)
            )
            ->when(
                $filters['search'] ?? null,
                function ($query, $term) {
                    $query->where(function ($sub) use ($term) {
                        $sub->where('title', 'like', "%{$term}%")
                            ->orWhere('grade', 'like', "%{$term}%");
                    });
                }
            )
            ->orderBy('title')
            ->paginate(15)
            ->withQueryString();

        return view('backend.positions.index', [
            'positions' => $positions,
            'departments' => Department::with('company')->orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }

    public function create(Request $request): View
    {
        $insuranceContext = $this->resolveFormInsuranceContext($request);

        return view('backend.positions.create', [
            'departments' => Department::with('company')->orderBy('name')->get(),
            'levels' => PositionLevel::orderByDesc('rank')->orderBy('name')->get(),
            'insuranceOptions' => $insuranceContext['options'],
            'insuranceSummary' => $insuranceContext['summary'],
            'insuranceScheduleAvailable' => $insuranceContext['available'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePosition($request);

        $data = $this->applyInsuranceAttributes($data);
        $data['is_managerial'] = $request->boolean('is_managerial');

        Position::create($data);

        return redirect()
            ->route('backend.positions.index')
            ->with('status', '職位已建立。');
    }

    public function edit(Position $position, Request $request): View
    {
        $insuranceContext = $this->resolveFormInsuranceContext($request, $position);

        return view('backend.positions.edit', [
            'position' => $position,
            'departments' => Department::with('company')->orderBy('name')->get(),
            'levels' => PositionLevel::orderByDesc('rank')->orderBy('name')->get(),
            'insuranceOptions' => $insuranceContext['options'],
            'insuranceSummary' => $insuranceContext['summary'],
            'insuranceScheduleAvailable' => $insuranceContext['available'],
        ]);
    }

    public function update(Request $request, Position $position): RedirectResponse
    {
        $data = $this->validatePosition($request);

        $data = $this->applyInsuranceAttributes($data);
        $data['is_managerial'] = $request->boolean('is_managerial');

        $position->update($data);

        return redirect()
            ->route('backend.positions.index')
            ->with('status', '職位已更新。');
    }

    public function destroy(Position $position): RedirectResponse
    {
        if ($position->employees()->exists()) {
            return redirect()
                ->route('backend.positions.index')
                ->withErrors(['position' => '仍有員工使用此職位，無法刪除。']);
        }

        $position->delete();

        return redirect()
            ->route('backend.positions.index')
            ->with('status', '職位已刪除。');
    }

    protected function resolveFormInsuranceContext(Request $request, ?Position $position = null): array
    {
        $referenceSalaryInput = $request->old('reference_salary');
        $insuranceGradeInput = $request->old('insurance_grade');

        if (is_null($referenceSalaryInput) && $position) {
            $referenceSalaryInput = $position->reference_salary;
        }

        if (is_null($insuranceGradeInput) && $position) {
            $insuranceGradeInput = $position->insurance_grade;
        }

        $referenceSalary = $this->normalizeReferenceSalary($referenceSalaryInput);
        $insuranceGrade = $this->normalizeInsuranceGrade($insuranceGradeInput);
        $schedule = $this->insuranceSchedule();

        return [
            'options' => $schedule ? $schedule->brackets() : [],
            'summary' => $schedule ? InsuranceContributionSummary::make($referenceSalary, $insuranceGrade, $schedule) : null,
            'available' => (bool) $schedule,
        ];
    }

    protected function applyInsuranceAttributes(array $data): array
    {
        $referenceSalary = $this->normalizeReferenceSalary($data['reference_salary'] ?? null);
        $insuranceGrade = $this->normalizeInsuranceGrade($data['insurance_grade'] ?? null);

        $data['reference_salary'] = $referenceSalary;
        $data['insurance_grade'] = $insuranceGrade;

        $schedule = $this->insuranceSchedule();

        if (! $schedule) {
            $data['insurance_snapshot'] = null;

            return $data;
        }

        $summary = InsuranceContributionSummary::make($referenceSalary, $insuranceGrade, $schedule);

        if (! $summary) {
            $data['insurance_snapshot'] = null;

            return $data;
        }

        $data['insurance_grade'] = $summary['grade_value'];
        $data['insurance_snapshot'] = [
            'grade_label' => $summary['grade_label'],
            'grade_value' => $summary['grade_value'],
            'base_salary' => $summary['base_salary'],
            'labor_local' => $summary['labor_local'],
            'labor_foreign' => $summary['labor_foreign'],
            'health' => $summary['health'],
            'pension' => $summary['pension'],
        ];

        return $data;
    }

    protected function insuranceSchedule(): ?InsuranceSchedule
    {
        if ($this->insuranceScheduleLoaded) {
            return $this->insuranceSchedule;
        }

        try {
            $this->insuranceSchedule = InsuranceSchedule::fromStorage();
        } catch (\Throwable $e) {
            $this->insuranceSchedule = null;
        }

        $this->insuranceScheduleLoaded = true;

        return $this->insuranceSchedule;
    }

    protected function normalizeReferenceSalary($value): ?float
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        return is_numeric($value) ? (float) $value : null;
    }

    protected function normalizeInsuranceGrade($value): ?int
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        return is_numeric($value) ? (int) $value : null;
    }

    protected function validatePosition(Request $request): array
    {
        return $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'position_level_id' => ['nullable', 'exists:position_levels,id'],
            'title' => ['required', 'string', 'max:255'],
            'grade' => ['nullable', 'string', 'max:32'],
            'reference_salary' => ['nullable', 'numeric', 'min:0'],
            'insurance_grade' => ['nullable', 'integer', 'min:1'],
            'is_managerial' => ['sometimes', 'boolean'],
        ]);
    }
}
