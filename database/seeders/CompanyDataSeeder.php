<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Position;
use App\Models\PositionLevel;
use App\Support\InsuranceContributionSummary;
use App\Support\InsuranceSchedule;
use Illuminate\Database\Seeder;

class CompanyDataSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $schedule = InsuranceSchedule::resolve();
        } catch (\Throwable $e) {
            $schedule = null;
            $this->command?->warn('無法載入投保級距表，職位將缺少投保快照。');
        }

        $companies = [
            [
                'code' => 'ALPHA',
                'name' => 'Alpha Manufacturing Co.',
                'tax_id' => '12345678',
                'status' => 'active',
                'metadata' => ['timezone' => 'Asia/Taipei'],
                'departments' => [
                    [
                        'name' => 'Corporate Office',
                        'code' => 'CORP',
                        'description' => 'Executive leadership and corporate strategy.',
                        'positions' => [
                            ['title' => 'Chief Executive Officer', 'grade' => 'L1', 'level_code' => 'CEO', 'is_managerial' => true, 'reference_salary' => 180000],
                            ['title' => 'President', 'grade' => 'L2', 'level_code' => 'PRESIDENT', 'is_managerial' => true, 'reference_salary' => 150000],
                            ['title' => 'Vice President', 'grade' => 'L3', 'level_code' => 'VICE_PRESIDENT', 'is_managerial' => true, 'reference_salary' => 120000],
                            ['title' => 'Special Assistant to CEO', 'grade' => 'L3', 'level_code' => 'SPECIAL_ASSISTANT', 'is_managerial' => true, 'reference_salary' => 90000],
                        ],
                    ],
                    [
                        'name' => 'Human Resources',
                        'code' => 'HR',
                        'description' => 'Recruiting, benefits, and personnel management.',
                        'positions' => [
                            ['title' => 'HR Director', 'grade' => 'M1', 'level_code' => 'DIRECTOR', 'is_managerial' => true, 'reference_salary' => 90000],
                            ['title' => 'HR Manager', 'grade' => 'M2', 'level_code' => 'TEAM_LEAD', 'is_managerial' => true, 'reference_salary' => 68000],
                            ['title' => 'HR Section Chief', 'grade' => 'M2', 'level_code' => 'SECTION_CHIEF', 'is_managerial' => true, 'reference_salary' => 62000],
                            ['title' => 'HR Specialist', 'grade' => 'S1', 'level_code' => 'STAFF', 'is_managerial' => false, 'reference_salary' => 36000],
                        ],
                    ],
                    [
                        'name' => 'Engineering',
                        'code' => 'ENG',
                        'description' => 'Product development and system maintenance.',
                        'positions' => [
                            ['title' => 'Engineering Director', 'grade' => 'M1', 'level_code' => 'DIRECTOR', 'is_managerial' => true, 'reference_salary' => 110000],
                            ['title' => 'Engineering Section Chief', 'grade' => 'M2', 'level_code' => 'SECTION_CHIEF', 'is_managerial' => true, 'reference_salary' => 82000],
                            ['title' => 'Engineering Team Lead', 'grade' => 'M3', 'level_code' => 'TEAM_LEAD', 'is_managerial' => true, 'reference_salary' => 72000],
                            ['title' => 'Software Engineer', 'grade' => 'S1', 'level_code' => 'STAFF', 'is_managerial' => false, 'reference_salary' => 50000],
                            ['title' => 'QA Engineer', 'grade' => 'S1', 'level_code' => 'STAFF', 'is_managerial' => false, 'reference_salary' => 45000],
                        ],
                    ],
                ],
            ],
            [
                'code' => 'BETA',
                'name' => 'Beta Logistics Ltd.',
                'tax_id' => '87654321',
                'status' => 'active',
                'metadata' => ['timezone' => 'Asia/Taipei'],
                'departments' => [
                    [
                        'name' => 'Operations',
                        'code' => 'OPS',
                        'description' => 'Daily logistics operations.',
                        'positions' => [
                            ['title' => 'General Manager', 'grade' => 'L2', 'level_code' => 'PRESIDENT', 'is_managerial' => true, 'reference_salary' => 130000],
                            ['title' => 'Deputy General Manager', 'grade' => 'L3', 'level_code' => 'VICE_PRESIDENT', 'is_managerial' => true, 'reference_salary' => 115000],
                            ['title' => 'Operations Director', 'grade' => 'M1', 'level_code' => 'DIRECTOR', 'is_managerial' => true, 'reference_salary' => 90000],
                            ['title' => 'Operations Section Chief', 'grade' => 'M2', 'level_code' => 'SECTION_CHIEF', 'is_managerial' => true, 'reference_salary' => 70000],
                            ['title' => 'Warehouse Team Lead', 'grade' => 'M3', 'level_code' => 'TEAM_LEAD', 'is_managerial' => true, 'reference_salary' => 55000],
                            ['title' => 'Warehouse Staff', 'grade' => 'S1', 'level_code' => 'STAFF', 'is_managerial' => false, 'reference_salary' => 33000],
                        ],
                    ],
                    [
                        'name' => 'Customer Service',
                        'code' => 'CS',
                        'description' => 'Customer communication and support.',
                        'positions' => [
                            ['title' => 'Customer Service Manager', 'grade' => 'M3', 'level_code' => 'TEAM_LEAD', 'is_managerial' => true, 'reference_salary' => 60000],
                            ['title' => 'Customer Service Supervisor', 'grade' => 'M3', 'level_code' => 'SUPERVISOR', 'is_managerial' => true, 'reference_salary' => 52000],
                            ['title' => 'Customer Service Representative', 'grade' => 'S1', 'level_code' => 'STAFF', 'is_managerial' => false, 'reference_salary' => 32000],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($companies as $companyData) {
            $company = Company::updateOrCreate(
                ['code' => $companyData['code']],
                [
                    'name' => $companyData['name'],
                    'tax_id' => $companyData['tax_id'],
                    'status' => $companyData['status'],
                    'metadata' => $companyData['metadata'] ?? null,
                ]
            );

            foreach ($companyData['departments'] as $departmentData) {
                $department = Department::updateOrCreate(
                    ['company_id' => $company->id, 'name' => $departmentData['name']],
                    [
                        'code' => $departmentData['code'] ?? null,
                        'description' => $departmentData['description'] ?? null,
                    ]
                );

                foreach ($departmentData['positions'] as $positionData) {
                    $levelId = null;
                    if (! empty($positionData['level_code'])) {
                        $level = PositionLevel::where('code', $positionData['level_code'])->first();
                        $levelId = $level?->id;
                    }

                    $positionAttributes = [
                        'position_level_id' => $levelId,
                        'grade' => $positionData['grade'] ?? null,
                        'is_managerial' => $positionData['is_managerial'] ?? false,
                        'metadata' => $positionData['metadata'] ?? null,
                    ];

                    if (isset($positionData['reference_salary'])) {
                        $referenceSalary = (float) $positionData['reference_salary'];
                        $positionAttributes['reference_salary'] = $referenceSalary;

                        if ($schedule) {
                            $summary = InsuranceContributionSummary::make($referenceSalary, null, $schedule);
                            if ($summary) {
                                $positionAttributes['insurance_grade'] = $summary['grade_value'];
                                $positionAttributes['insurance_snapshot'] = [
                                    'grade_label' => $summary['grade_label'],
                                    'grade_value' => $summary['grade_value'],
                                    'base_salary' => $summary['base_salary'],
                                    'labor_local' => $summary['labor_local'],
                                    'labor_foreign' => $summary['labor_foreign'],
                                    'health' => $summary['health'],
                                    'pension' => $summary['pension'],
                                ];
                            }
                        }
                    }

                    Position::updateOrCreate(
                        ['department_id' => $department->id, 'title' => $positionData['title']],
                        $positionAttributes
                    );
                }
            }
        }
    }
}
