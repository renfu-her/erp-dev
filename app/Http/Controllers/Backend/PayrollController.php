<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PayrollPeriod;
use App\Models\PayrollRun;
use App\Models\SalaryComponent;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function index(): View
    {
        $periods = PayrollPeriod::orderByDesc('period_start')->limit(6)->get();
        $components = SalaryComponent::orderBy('type')->orderBy('name')->get();
        $recentRuns = PayrollRun::with(['period', 'company'])->orderByDesc('created_at')->limit(5)->get();

        return view('backend.payroll.index', [
            'periods' => $periods,
            'components' => $components,
            'recentRuns' => $recentRuns,
        ]);
    }
}
