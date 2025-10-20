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

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $schedule = InsuranceSchedule::resolve();
        } catch (\Throwable $e) {
            $schedule = null;
            $this->command?->warn('無法載入投保級距表，系統管理者缺少投保資訊。');
        }

        $role = Role::where('slug', 'system-owner')->first();

        if (! $role) {
            $this->command?->warn('System Owner role not found. Run AccessControlSeeder first.');

            return;
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@erp.local'],
            [
                'name' => 'System Owner',
                'password' => Hash::make('password'),
            ]
        );

        $admin->roles()->syncWithoutDetaching([
            $role->id => ['rules' => ['scope' => ['type' => 'global']]],
        ]);

        $company = Company::where('code', 'ALPHA')->first();
        $department = $company
            ? Department::where('company_id', $company->id)->where('code', 'HR')->first()
            : null;
        $position = $department
            ? Position::where('department_id', $department->id)->where('title', 'HR Manager')->first()
            : null;

        $summary = ($schedule)
            ? InsuranceContributionSummary::make(68000.0, null, $schedule)
            : null;

        if ($company) {
            $employee = Employee::updateOrCreate(
                ['user_id' => $admin->id],
                [
                    'company_id' => $company->id,
                    'department_id' => $department?->id,
                    'position_id' => $position?->id,
                    'employee_no' => 'ALPHA-ADMIN',
                    'first_name' => 'System',
                    'last_name' => 'Owner',
                    'salary_grade' => 'L1',
                    'labor_grade' => $summary['grade_label'] ?? 'A',
                    'is_indigenous' => false,
                    'is_disabled' => false,
                    'middle_name' => null,
                    'status' => 'active',
                    'hired_at' => now()->subYears(3),
                    'personal_data' => null,
                ]
            );

            if ($employee) {
                $employee->contracts()->updateOrCreate(
                    ['employee_id' => $employee->id, 'is_active' => true],
                    [
                        'contract_type' => 'full_time',
                        'starts_on' => now()->subYears(3)->startOfYear(),
                        'base_salary' => 68000,
                        'currency' => 'TWD',
                        'terms' => null,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
