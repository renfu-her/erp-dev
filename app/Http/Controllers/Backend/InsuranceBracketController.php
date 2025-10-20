<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\InsuranceBracket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InsuranceBracketController extends Controller
{
    public function index(): View
    {
        $brackets = InsuranceBracket::orderBy('grade')->paginate(20)->withQueryString();

        return view('backend.insurance-brackets.index', [
            'brackets' => $brackets,
        ]);
    }

    public function create(): View
    {
        return view('backend.insurance-brackets.create', [
            'bracket' => new InsuranceBracket(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateBracket($request);

        InsuranceBracket::create($data);

        return redirect()
            ->route('backend.insurance-brackets.index')
            ->with('status', '勞健保級距已新增。');
    }

    public function edit(InsuranceBracket $insuranceBracket): View
    {
        return view('backend.insurance-brackets.edit', [
            'bracket' => $insuranceBracket,
        ]);
    }

    public function update(Request $request, InsuranceBracket $insuranceBracket): RedirectResponse
    {
        $data = $this->validateBracket($request, $insuranceBracket->id);

        $insuranceBracket->update($data);

        return redirect()
            ->route('backend.insurance-brackets.index')
            ->with('status', '勞健保級距已更新。');
    }

    public function destroy(InsuranceBracket $insuranceBracket): RedirectResponse
    {
        $insuranceBracket->delete();

        return redirect()
            ->route('backend.insurance-brackets.index')
            ->with('status', '勞健保級距已刪除。');
    }

    protected function validateBracket(Request $request, ?int $bracketId = null): array
    {
        return $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'grade' => [
                'required',
                'integer',
                'min:0',
                Rule::unique('insurance_brackets', 'grade')->ignore($bracketId),
            ],
            'labor_employee_local' => ['nullable', 'integer', 'min:0'],
            'labor_employer_local' => ['nullable', 'integer', 'min:0'],
            'labor_employee_foreign' => ['nullable', 'integer', 'min:0'],
            'labor_employer_foreign' => ['nullable', 'integer', 'min:0'],
            'health_employee' => ['nullable', 'integer', 'min:0'],
            'health_employer' => ['nullable', 'integer', 'min:0'],
            'pension_employer' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}

