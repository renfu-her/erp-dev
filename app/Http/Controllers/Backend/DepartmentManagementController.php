<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DepartmentManagementController extends Controller
{
    public function index(): View
    {
        $departments = Department::with(['company', 'parent', 'lead'])
            ->orderBy('company_id')
            ->orderBy('name')
            ->paginate(15);

        return view('backend.departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('backend.departments.create', [
            'companies' => Company::orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'employees' => Employee::with('company')->orderBy('last_name')->orderBy('first_name')->get(),
        ]);
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        Department::create($request->validated());

        return redirect()->route('backend.departments.index')->with('status', '部門已建立。');
    }

    public function edit(Department $department): View
    {
        return view('backend.departments.edit', [
            'department' => $department,
            'companies' => Company::orderBy('name')->get(),
            'departments' => Department::where('id', '!=', $department->id)->orderBy('name')->get(),
            'employees' => Employee::with('company')->orderBy('last_name')->orderBy('first_name')->get(),
        ]);
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->validated());

        return redirect()->route('backend.departments.index')->with('status', '部門資料已更新。');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->children()->exists()) {
            return redirect()->route('backend.departments.index')->withErrors(['department' => '請先處理子部門後再刪除。']);
        }

        $department->delete();

        return redirect()->route('backend.departments.index')->with('status', '部門已刪除。');
    }
}
