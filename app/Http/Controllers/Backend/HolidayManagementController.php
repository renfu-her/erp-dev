<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HolidayManagementController extends Controller
{
    public function index(Request $request): View
    {
        $year = $request->get('year', date('Y'));
        $holidays = Holiday::forYear($year)->national()->orderBy('date')->get();
        $availableYears = Holiday::select('year')->distinct()->orderBy('year')->pluck('year');
        
        return view('backend.holidays.index', compact('holidays', 'year', 'availableYears'));
    }

    public function create(): View
    {
        return view('backend.holidays.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'type' => ['required', 'in:national,custom'],
            'is_working_day' => ['boolean'],
            'year' => ['required', 'integer', 'min:2020', 'max:2030'],
        ]);

        Holiday::create($data);

        return redirect()->route('backend.holidays.index')
            ->with('status', '國定假日已建立。');
    }

    public function edit(Holiday $holiday): View
    {
        return view('backend.holidays.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'type' => ['required', 'in:national,custom'],
            'is_working_day' => ['boolean'],
            'year' => ['required', 'integer', 'min:2020', 'max:2030'],
        ]);

        $holiday->update($data);

        return redirect()->route('backend.holidays.index')
            ->with('status', '國定假日已更新。');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()->route('backend.holidays.index')
            ->with('status', '國定假日已刪除。');
    }
}
