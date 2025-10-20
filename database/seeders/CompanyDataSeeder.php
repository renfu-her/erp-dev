<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Position;
use App\Models\PositionLevel;
use Illuminate\Database\Seeder;

class CompanyDataSeeder extends Seeder
{
    public function run(): void
    {
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
                            ['title' => 'Chief Executive Officer', 'grade' => 'L1', 'level_code' => 'CEO', 'is_managerial' => true],
                            ['title' => 'President', 'grade' => 'L2', 'level_code' => 'PRESIDENT', 'is_managerial' => true],
                            ['title' => 'Vice President', 'grade' => 'L3', 'level_code' => 'VICE_PRESIDENT', 'is_managerial' => true],
                            ['title' => 'Special Assistant to CEO', 'grade' => 'L3', 'level_code' => 'SPECIAL_ASSISTANT', 'is_managerial' => true],
                        ],
                    ],
                    [
                        'name' => 'Human Resources',
                        'code' => 'HR',
                        'description' => 'Recruiting, benefits, and personnel management.',
                        'positions' => [
                            ['title' => 'HR Director', 'grade' => 'M1', 'level_code' => 'DIRECTOR', 'is_managerial' => true],
                            ['title' => 'HR Manager', 'grade' => 'M2', 'level_code' => 'TEAM_LEAD', 'is_managerial' => true],
                            ['title' => 'HR Section Chief', 'grade' => 'M2', 'level_code' => 'SECTION_CHIEF', 'is_managerial' => true],
                            ['title' => 'HR Specialist', 'grade' => 'S1', 'level_code' => 'STAFF', 'is_managerial' => false],
                        ],
                    ],
                    [
                        'name' => 'Engineering',
                        'code' => 'ENG',
                        'description' => 'Product development and system maintenance.',
                        'positions' => [
                            ['title' => 'Engineering Director', 'grade' => 'M1', 'level_code' => 'DIRECTOR', 'is_managerial' => true],
                            ['title' => 'Engineering Section Chief', 'grade' => 'M2', 'level_code' => 'SECTION_CHIEF', 'is_managerial' => true],
                            ['title' => 'Engineering Team Lead', 'grade' => 'M3', 'level_code' => 'TEAM_LEAD', 'is_managerial' => true],
                            ['title' => 'Software Engineer', 'grade' => 'S1', 'level_code' => 'STAFF', 'is_managerial' => false],
                            ['title' => 'QA Engineer', 'grade' => 'S1', 'level_code' => 'STAFF', 'is_managerial' => false],
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
                            ['title' => 'General Manager', 'grade' => 'L2', 'level_code' => 'PRESIDENT', 'is_managerial' => true],
                            ['title' => 'Deputy General Manager', 'grade' => 'L3', 'level_code' => 'VICE_PRESIDENT', 'is_managerial' => true],
                            ['title' => 'Operations Director', 'grade' => 'M1', 'level_code' => 'DIRECTOR', 'is_managerial' => true],
                            ['title' => 'Operations Section Chief', 'grade' => 'M2', 'level_code' => 'SECTION_CHIEF', 'is_managerial' => true],
                            ['title' => 'Warehouse Team Lead', 'grade' => 'M3', 'level_code' => 'TEAM_LEAD', 'is_managerial' => true],
                            ['title' => 'Warehouse Staff', 'grade' => 'S1', 'level_code' => 'STAFF', 'is_managerial' => false],
                        ],
                    ],
                    [
                        'name' => 'Customer Service',
                        'code' => 'CS',
                        'description' => 'Customer communication and support.',
                        'positions' => [
                            ['title' => 'Customer Service Manager', 'grade' => 'M3', 'level_code' => 'TEAM_LEAD', 'is_managerial' => true],
                            ['title' => 'Customer Service Supervisor', 'grade' => 'M3', 'level_code' => 'SUPERVISOR', 'is_managerial' => true],
                            ['title' => 'Customer Service Representative', 'grade' => 'S1', 'level_code' => 'STAFF', 'is_managerial' => false],
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

                    Position::updateOrCreate(
                        ['department_id' => $department->id, 'title' => $positionData['title']],
                        [
                            'position_level_id' => $levelId,
                            'grade' => $positionData['grade'] ?? null,
                            'is_managerial' => $positionData['is_managerial'] ?? false,
                            'metadata' => $positionData['metadata'] ?? null,
                        ]
                    );
                }
            }
        }
    }
}
