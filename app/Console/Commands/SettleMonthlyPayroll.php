<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\PayrollEntry;
use App\Support\InsuranceContributionSummary;
use App\Support\InsuranceSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SettleMonthlyPayroll extends Command
{
    protected $signature = 'payroll:settle {--date= : The reference date (defaults to today)}';

    protected $description = 'Settle last month payroll on demand. base + allowance - (labor + health).';

    public function handle(): int
    {
        $today = Carbon::today();
        $targetDate = $this->option('date') ? Carbon::parse($this->option('date')) : $today;

        $periodStart = $targetDate->copy()->subMonth()->startOfMonth();
        $periodEnd = $targetDate->copy()->subMonth()->endOfMonth();

        $this->info("Settling payroll for {$periodStart->toDateString()} ~ {$periodEnd->toDateString()}");

        try {
            $schedule = InsuranceSchedule::resolve();
        } catch (\Throwable $e) {
            $this->error('Insurance schedule not available: ' . $e->getMessage());
            return self::FAILURE;
        }

        $employees = Employee::with('salaries')->whereIn('status', ['active', 'onboarding'])->get();

        DB::transaction(function () use ($employees, $schedule, $periodEnd) {
            foreach ($employees as $employee) {
                $salaryRow = $employee->effectiveSalaryFor($periodEnd);
                if (! $salaryRow) {
                    continue;
                }

                $base = (float) $salaryRow->base_salary;
                $allowance = (float) ($salaryRow->allowance ?? 0);

                $summary = InsuranceContributionSummary::make($base, null, $schedule);
                $laborEmp = (int) data_get($summary, 'labor_local.employee', 0);
                $healthEmp = (int) data_get($summary, 'health.employee', 0);

                $gross = $base + $allowance;
                $deductions = $laborEmp + $healthEmp;
                $net = $gross - $deductions;

                PayrollEntry::updateOrCreate(
                    [
                        'payroll_run_id' => null, // standalone monthly settlement
                        'employee_id' => $employee->id,
                    ],
                    [
                        'gross_pay' => $gross,
                        'total_deductions' => $deductions,
                        'net_pay' => $net,
                        'metadata' => [
                            'period_end' => $periodEnd->toDateString(),
                            'base_salary' => $base,
                            'allowance' => $allowance,
                            'insurance_summary' => $summary,
                        ],
                    ]
                );
            }
        });

        $this->info('Payroll settled successfully.');
        return self::SUCCESS;
    }
}


