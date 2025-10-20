<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use App\Models\PositionLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PositionManagementController extends Controller
{
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

    public function create(): View
    {
        return view('backend.positions.create', [
            'departments' => Department::with('company')->orderBy('name')->get(),
            'levels' => PositionLevel::orderByDesc('rank')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePosition($request);

        $data['is_managerial'] = $request->boolean('is_managerial');

        Position::create($data);

        return redirect()
            ->route('backend.positions.index')
            ->with('status', '職位已建立。');
    }

    public function edit(Position $position): View
    {
        return view('backend.positions.edit', [
            'position' => $position,
            'departments' => Department::with('company')->orderBy('name')->get(),
            'levels' => PositionLevel::orderByDesc('rank')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Position $position): RedirectResponse
    {
        $data = $this->validatePosition($request);

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

    protected function validatePosition(Request $request): array
    {
        return $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'position_level_id' => ['nullable', 'exists:position_levels,id'],
            'title' => ['required', 'string', 'max:255'],
            'grade' => ['nullable', 'string', 'max:32'],
            'is_managerial' => ['sometimes', 'boolean'],
        ]);
    }
}
