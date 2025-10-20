<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use App\Support\InsuranceContributionSummary;
use App\Support\InsuranceSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FrontendUserSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $schedule = InsuranceSchedule::resolve();
        } catch (\Throwable $e) {
            $schedule = null;
            $this->command?->warn('無法載入投保級距表，員工將缺少投保級距資訊。');
        }

        $employeeRole = Role::where('slug', 'employee')->first();
        $managerRole = Role::where('slug', 'company-manager')->first();

        if (! $employeeRole || ! $managerRole) {
            $this->command?->warn('Required roles not found. Run AccessControlSeeder first.');

            return;
        }

        $users = [
            [
                'email' => 'employee1@erp.local',
                'name' => 'Employee One',
                'password' => 'password',
                'role' => $employeeRole,
                'company_code' => 'ALPHA',
                'department_name' => 'Human Resources',
                'position_title' => 'HR Specialist',
                'employee_no' => 'ALPHA-001',
                'salary_grade' => 'S1',
                'labor_grade' => 'Labor-1',
                'is_indigenous' => false,
                'is_disabled' => false,
                'base_salary' => 36000,
            ],
            [
                'email' => 'employee2@erp.local',
                'name' => 'Employee Two',
                'password' => 'password',
                'role' => $employeeRole,
                'company_code' => 'ALPHA',
                'department_name' => 'Engineering',
                'position_title' => 'Software Engineer',
                'employee_no' => 'ALPHA-002',
                'salary_grade' => 'S1',
                'labor_grade' => 'Labor-1',
                'is_indigenous' => true,
                'is_disabled' => false,
                'base_salary' => 34500,
            ],
            [
                'email' => 'manager@erp.local',
                'name' => 'Company Manager',
                'password' => 'password',
                'role' => $managerRole,
                'company_code' => 'ALPHA',
                'department_name' => 'Human Resources',
                'position_title' => 'HR Manager',
                'employee_no' => 'ALPHA-MGR',
                'salary_grade' => 'M2',
                'labor_grade' => 'Labor-2',
                'is_indigenous' => false,
                'is_disabled' => true,
                'base_salary' => 52000,
            ],
        ];

        foreach ($users as $data) {
            $company = Company::where('code', $data['company_code'])->first();

            if (! $company) {
                $this->command?->warn("Company {$data['company_code']} not found.");

                continue;
            }

            $department = Department::where('company_id', $company->id)
                ->where('name', $data['department_name'])
                ->first();

            $position = $department
                ? Position::where('department_id', $department->id)
                    ->where('title', $data['position_title'])
                    ->first()
                : null;

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                ]
            );

            $user->roles()->syncWithoutDetaching([
                $data['role']->id => [
                    'rules' => [
                        'scope' => [
                            'type' => 'company',
                            'id' => $company->id,
                        ],
                    ],
                ],
            ]);

            $summary = ($schedule && isset($data['base_salary']))
                ? InsuranceContributionSummary::make((float) $data['base_salary'], null, $schedule)
                : null;

            $employee = Employee::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'company_id' => $company->id,
                    'department_id' => $department?->id,
                    'position_id' => $position?->id,
                    'employee_no' => $data['employee_no'],
                    'first_name' => explode(' ', $data['name'])[0],
                    'last_name' => explode(' ', $data['name'])[1] ?? 'User',
                    'middle_name' => null,
                    'salary_grade' => $data['salary_grade'] ?? null,
                    'labor_grade' => $summary['grade_label'] ?? $data['labor_grade'] ?? null,
                    'is_indigenous' => $data['is_indigenous'] ?? false,
                    'is_disabled' => $data['is_disabled'] ?? false,
                    'status' => 'active',
                    'hired_at' => now()->subMonths(6),
                    'personal_data' => null,
                ]
            );

            if ($employee) {
                $employee->contracts()
                    ->updateOrCreate(
                        ['employee_id' => $employee->id, 'is_active' => true],
                        [
                            'contract_type' => 'full_time',
                            'starts_on' => now()->subMonths(6)->startOfMonth(),
                            'base_salary' => $data['base_salary'] ?? 36000,
                            'currency' => 'TWD',
                            'terms' => null,
                            'is_active' => true,
                        ]
                    );
            }
        }
    }
}
