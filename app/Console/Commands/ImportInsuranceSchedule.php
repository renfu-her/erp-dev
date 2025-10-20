<?php

namespace App\Console\Commands;

use App\Models\InsuranceBracket;
use App\Support\InsuranceSchedule;
use Illuminate\Console\Command;

class ImportInsuranceSchedule extends Command
{
    protected $signature = 'insurance:import-schedule {--path=} {--flush : Remove existing records before import}';

    protected $description = 'Import labour and health insurance brackets from storage JSON file.';

    public function handle(): int
    {
        $path = $this->option('path');

        try {
            $schedule = InsuranceSchedule::fromStorage($path);
        } catch (\Throwable $e) {
            $this->error('無法載入投保級距表：' . $e->getMessage());

            return self::FAILURE;
        }

        if ($this->option('flush')) {
            InsuranceBracket::truncate();
            $this->info('已清除既有的級距資料。');
        }

        $count = 0;

        foreach ($schedule->brackets() as $row) {
            InsuranceBracket::updateOrCreate(
                ['grade' => $row['grade'] ?? null],
                [
                    'label' => $row['label'] ?? '',
                    'labor_employee_local' => $row['labor_employee_local'] ?? null,
                    'labor_employer_local' => $row['labor_employer_local'] ?? null,
                    'labor_employee_foreign' => $row['labor_employee_foreign'] ?? null,
                    'labor_employer_foreign' => $row['labor_employer_foreign'] ?? null,
                    'health_employee' => $row['health_employee'] ?? null,
                    'health_employer' => $row['health_employer'] ?? null,
                    'pension_employer' => $row['pension_employer'] ?? null,
                ]
            );
            $count++;
        }

        $this->info("匯入完成，共更新 {$count} 筆級距資料。");

        return self::SUCCESS;
    }
}
