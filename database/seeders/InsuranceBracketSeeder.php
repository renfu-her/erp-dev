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

        $this->command?->info('開始更新 2025 年保險級距資料...');

        foreach ($schedule->brackets() as $row) {
            if (! isset($row['grade'], $row['salary'])) {
                continue;
            }

            InsuranceBracket::updateOrCreate(
                ['grade' => $row['grade']],
                [
                    'label' => $row['label'] ?? ('投保級距 ' . $row['grade']),
                    'salary' => $row['salary'],
                    'labor_employee_local' => $row['labor_employee_local'] ?? 0,
                    'labor_employer_local' => $row['labor_employer_local'] ?? 0,
                    'labor_employee_foreign' => $row['labor_employee_foreign'] ?? 0,
                    'labor_employer_foreign' => $row['labor_employer_foreign'] ?? 0,
                    'health_employee' => $row['health_employee'] ?? 0,
                    'health_employer' => $row['health_employer'] ?? 0,
                    'occupational_employee' => $row['occupational_employee'],
                    'occupational_employer' => $row['occupational_employer'] ?? 0,
                    'labor_pension_6_percent' => $row['labor_pension_6_percent'] ?? 0,
                    'pension_employer' => $row['pension_employer'],
                ]
            );
        }

        $this->command?->info('2025 年保險級距資料更新完成！');
    }
}

