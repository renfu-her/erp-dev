<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
}
