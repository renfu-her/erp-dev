<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyWorkSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class CompanyManagementController extends Controller
{
    public function index(): View
    {
        $companies = Company::orderBy('name')->paginate(12);

        return view('backend.companies.index', compact('companies'));
    }

    public function create(): View
    {
        return view('backend.companies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:32', 'unique:companies,code'],
            'tax_id' => ['nullable', 'string', 'max:64'],
            'status' => ['required', 'in:active,inactive'],
            'metadata' => ['nullable', 'string'],
        ]);

        $payload = $this->transformMetadata($data);

        Company::create($payload);

        return redirect()->route('backend.companies.index')->with('status', '公司資料已建立。');
    }

    public function edit(Company $company): View
    {
        return view('backend.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:32', 'unique:companies,code,' . $company->id],
            'tax_id' => ['nullable', 'string', 'max:64'],
            'status' => ['required', 'in:active,inactive'],
            'metadata' => ['nullable', 'string'],
        ]);

        $payload = $this->transformMetadata($data);

        $company->update($payload);

        return redirect()->route('backend.companies.index')->with('status', '公司資料已更新。');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()->route('backend.companies.index')->with('status', '公司資料已刪除。');
    }

    protected function transformMetadata(array $data): array
    {
        if (! empty($data['metadata'])) {
            $decoded = json_decode($data['metadata'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['metadata'] = $decoded;
            } else {
                unset($data['metadata']);
            }
        } else {
            unset($data['metadata']);
        }

        return $data;
    }

    public function editSchedule(Company $company): View
    {
        $workSchedule = $company->currentWorkSchedule();
        $holidays = Holiday::forYear(2025)->national()->orderBy('date')->get();
        
        return view('backend.companies.schedule', compact('company', 'workSchedule', 'holidays'));
    }

    public function storeSchedule(Request $request, Company $company): RedirectResponse
    {
        $data = $request->validate([
            'effective_from' => ['required', 'date'],
            'effective_until' => ['required', 'date', 'after_or_equal:effective_from'],
            'work_start_time' => ['required', 'date_format:H:i'],
            'work_end_time' => ['required', 'date_format:H:i', 'after:work_start_time'],
            'lunch_start_time' => ['required', 'date_format:H:i'],
            'lunch_end_time' => ['required', 'date_format:H:i', 'after:lunch_start_time'],
        ]);

        // Calculate working hours
        $start = Carbon::parse($data['work_start_time']);
        $end = Carbon::parse($data['work_end_time']);
        $lunchStart = Carbon::parse($data['lunch_start_time']);
        $lunchEnd = Carbon::parse($data['lunch_end_time']);

        $totalHours = $end->diffInHours($start);
        $lunchHours = $lunchEnd->diffInHours($lunchStart);
        $workingHours = $totalHours - $lunchHours;

        $data['company_id'] = $company->id;
        $data['working_hours'] = $workingHours;

        CompanyWorkSchedule::create($data);

        return redirect()->route('backend.companies.edit', $company)
            ->with('status', '工作時間設定已建立。');
    }

    public function updateSchedule(Request $request, Company $company, CompanyWorkSchedule $workSchedule): RedirectResponse
    {
        $data = $request->validate([
            'effective_from' => ['required', 'date'],
            'effective_until' => ['required', 'date', 'after_or_equal:effective_from'],
            'work_start_time' => ['required', 'date_format:H:i'],
            'work_end_time' => ['required', 'date_format:H:i', 'after:work_start_time'],
            'lunch_start_time' => ['required', 'date_format:H:i'],
            'lunch_end_time' => ['required', 'date_format:H:i', 'after:lunch_start_time'],
        ]);

        // Calculate working hours
        $start = Carbon::parse($data['work_start_time']);
        $end = Carbon::parse($data['work_end_time']);
        $lunchStart = Carbon::parse($data['lunch_start_time']);
        $lunchEnd = Carbon::parse($data['lunch_end_time']);

        $totalHours = $end->diffInHours($start);
        $lunchHours = $lunchEnd->diffInHours($lunchStart);
        $workingHours = $totalHours - $lunchHours;

        $data['working_hours'] = $workingHours;

        $workSchedule->update($data);

        return redirect()->route('backend.companies.edit', $company)
            ->with('status', '工作時間設定已更新。');
    }

}
