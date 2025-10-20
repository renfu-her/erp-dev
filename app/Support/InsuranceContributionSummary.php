<?php

namespace App\Support;

class InsuranceContributionSummary
{
    public static function make(?float $baseSalary, ?int $grade = null, ?InsuranceSchedule $schedule = null): ?array
    {
        if (is_null($baseSalary) && is_null($grade)) {
            return null;
        }

        try {
            $schedule = $schedule ?? InsuranceSchedule::resolve();
        } catch (\Throwable $e) {
            return null;
        }

        $bracket = null;

        if (! is_null($grade)) {
            $bracket = $schedule->findBracketByGrade($grade);
        }

        if (! $bracket && ! is_null($baseSalary)) {
            $bracket = $schedule->findBracketForSalary($baseSalary);
        }

        if (! $bracket) {
            return null;
        }

        $gradeValue = $bracket['grade'] ?? null;
        $laborEmployee = $bracket['labor_employee_local'] ?? null;
        $laborEmployer = $bracket['labor_employer_local'] ?? null;
        $healthEmployee = $bracket['health_employee'] ?? null;
        $healthEmployer = $bracket['health_employer'] ?? null;

        return [
            'base_salary' => self::normalizeSalary($baseSalary, $gradeValue),
            'grade_label' => $bracket['label'] ?? null,
            'grade_value' => $gradeValue,
            'labor_local' => [
                'employee' => $laborEmployee,
                'employer' => $laborEmployer,
                'total' => self::sum($laborEmployee, $laborEmployer),
            ],
            'labor_foreign' => [
                'employee' => $bracket['labor_employee_foreign'] ?? null,
                'employer' => $bracket['labor_employer_foreign'] ?? null,
            ],
            'health' => [
                'employee' => $healthEmployee,
                'employer' => $healthEmployer,
                'total' => self::sum($healthEmployee, $healthEmployer),
            ],
            'pension' => [
                'employer' => $bracket['pension_employer'] ?? null,
                'rate' => '6%',
            ],
        ];
    }

    public static function sum(?int $employeeShare, ?int $employerShare): ?int
    {
        if (is_null($employeeShare) && is_null($employerShare)) {
            return null;
        }

        return (int) (($employeeShare ?? 0) + ($employerShare ?? 0));
    }

    protected static function normalizeSalary(?float $baseSalary, ?int $gradeValue): ?float
    {
        if (! is_null($baseSalary)) {
            return round((float) $baseSalary, 2);
        }

        return is_null($gradeValue) ? null : (float) $gradeValue;
    }
}
