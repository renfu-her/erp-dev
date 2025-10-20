<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveTypeRequest;
use App\Http\Requests\UpdateLeaveTypeRequest;
use App\Models\LeaveType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LeaveTypeManagementController extends Controller
{
    public function index(): View
    {
        return view('backend.leave-types.index', [
            'leaveTypes' => LeaveType::orderBy('name')->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('backend.leave-types.create');
    }

    public function store(StoreLeaveTypeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['requires_approval'] = $request->boolean('requires_approval');
        $data['affects_attendance'] = $request->boolean('affects_attendance');

        if (! empty($data['rules'])) {
            $decoded = json_decode($data['rules'], true);
            $data['rules'] = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        } else {
            unset($data['rules']);
        }

        LeaveType::create($data);

        return redirect()->route('backend.leave-types.index')->with('status', '假別已建立。');
    }

    public function edit(LeaveType $leaveType): View
    {
        return view('backend.leave-types.edit', compact('leaveType'));
    }

    public function update(UpdateLeaveTypeRequest $request, LeaveType $leaveType): RedirectResponse
    {
        $data = $request->validated();

        if ($request->has('requires_approval')) {
            $data['requires_approval'] = $request->boolean('requires_approval');
        }

        if ($request->has('affects_attendance')) {
            $data['affects_attendance'] = $request->boolean('affects_attendance');
        }

        if (! empty($data['rules'])) {
            $decoded = json_decode($data['rules'], true);
            $data['rules'] = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        } else {
            unset($data['rules']);
        }

        $leaveType->update($data);

        return redirect()->route('backend.leave-types.index')->with('status', '假別已更新。');
    }

    public function destroy(LeaveType $leaveType): RedirectResponse
    {
        $leaveType->delete();

        return redirect()->route('backend.leave-types.index')->with('status', '假別已刪除。');
    }
}
