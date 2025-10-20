<?php

namespace Database\Seeders;

use App\Models\InsuranceBracket;
use App\Support\InsuranceSchedule;
use Illuminate\Database\Seeder;

class InsuranceBracketSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $schedule = InsuranceSchedule::fromStorage();
        } catch (\Throwable $e) {
            $this->command?->warn('無法載入投保級距表：' . $e->getMessage());

            return;
        }

        foreach ($schedule->brackets() as $row) {
            if (! isset($row['grade'])) {
                continue;
            }

            InsuranceBracket::updateOrCreate(
                ['grade' => $row['grade']],
                [
                    'label' => $row['label'] ?? '投保級距 ' . $row['grade'],
                    'labor_employee_local' => $row['labor_employee_local'] ?? null,
                    'labor_employer_local' => $row['labor_employer_local'] ?? null,
                    'labor_employee_foreign' => $row['labor_employee_foreign'] ?? null,
                    'labor_employer_foreign' => $row['labor_employer_foreign'] ?? null,
                    'health_employee' => $row['health_employee'] ?? null,
                    'health_employer' => $row['health_employer'] ?? null,
                    'pension_employer' => $row['pension_employer'] ?? null,
                ]
            );
        }
    }
}

