<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function store(Request $request, string $action): RedirectResponse
    {
        $typeMap = [
            'check-in' => 'check_in',
            'check-out' => 'check_out',
        ];

        if (! array_key_exists($action, $typeMap)) {
            abort(404);
        }

        $user = $request->user();

        if (! $user || ! $user->employee) {
            return back()->withErrors(['attendance' => '未綁定員工帳號，無法打卡。']);
        }

        $employee = $user->employee;
        $type = $typeMap[$action];

        $lastLog = $employee->attendanceLogs()->orderByDesc('recorded_at')->first();

        if ($lastLog && $lastLog->type === $type) {
            return back()->withErrors(['attendance' => '已經完成相同的打卡操作。']);
        }

        AttendanceLog::create([
            'employee_id' => $employee->id,
            'type' => $type,
            'recorded_at' => now(),
            'source' => 'portal',
        ]);

        return back()->with('status', $type === 'check_in' ? '已完成上班打卡。' : '已完成下班打卡。');
    }
}
