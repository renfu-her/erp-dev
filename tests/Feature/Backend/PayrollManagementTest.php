<?php

namespace Tests\Feature\Backend;

use App\Models\Company;
use App\Models\Employee;
use App\Models\EmploymentContract;
use App\Models\PayrollPeriod;
use App\Models\Role;
use App\Models\User;
use App\Support\InsuranceContributionSummary;
use Database\Seeders\AccessControlSeeder;
use Database\Seeders\InsuranceBracketSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayrollManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_employee_salary_and_insurance_breakdown(): void
    {
        $this->seed([
            AccessControlSeeder::class,
            InsuranceBracketSeeder::class,
        ]);

        /** @var User $user */
        $user = User::factory()->create();
        $role = Role::where('slug', 'hr-admin')->firstOrFail();
        $user->roles()->attach($role->id);

        /** @var Employee $employee */
        $employee = Employee::factory()->create([
            'employee_no' => 'E1001',
            'first_name' => '小美',
            'last_name' => '林',
        ]);

        EmploymentContract::create([
            'employee_id' => $employee->id,
            'contract_type' => 'full_time',
            'starts_on' => now()->subYear()->startOfMonth(),
            'base_salary' => 42000,
            'currency' => 'TWD',
            'is_active' => true,
        ]);

        $expectedSummary = InsuranceContributionSummary::make(42000.0);

        $response = $this->actingAs($user)
            ->get(route('backend.payroll.index'));

        $response->assertOk();
        $response->assertSee('薪資管理');
        $response->assertSee('E1001');
        $response->assertSee('林小美');
        $response->assertSee('42,000 元');
        $response->assertSee($expectedSummary['grade_label']);
        $response->assertSee(number_format(data_get($expectedSummary, 'labor_local.total')) . ' 元');
        $response->assertSee(number_format(data_get($expectedSummary, 'health.total')) . ' 元');
        $response->assertSee(number_format(data_get($expectedSummary, 'pension.employer')) . ' 元');
        $response->assertDontSee('未載入投保級距表');
    }

    public function test_user_can_create_payroll_period(): void
    {
        $this->seed([
            AccessControlSeeder::class,
            InsuranceBracketSeeder::class,
        ]);

        /** @var User $user */
        $user = User::factory()->create();
        $role = Role::where('slug', 'hr-admin')->firstOrFail();
        $user->roles()->attach($role->id);

        $payload = [
            'name' => '2024-10 月',
            'period_start' => '2024-10-01',
            'period_end' => '2024-10-31',
        ];

        $response = $this->actingAs($user)
            ->post(route('backend.payroll.periods.store'), $payload);

        $response->assertRedirect(route('backend.payroll.index'));
        $response->assertSessionHas('status', '薪資期間已建立。');

        $this->assertDatabaseHas('payroll_periods', [
            'name' => '2024-10 月',
            'period_start' => '2024-10-01',
            'period_end' => '2024-10-31',
            'status' => 'draft',
        ]);
    }

    public function test_user_can_create_payroll_run_for_company(): void
    {
        $this->seed([
            AccessControlSeeder::class,
            InsuranceBracketSeeder::class,
        ]);

        /** @var User $user */
        $user = User::factory()->create();
        $role = Role::where('slug', 'hr-admin')->firstOrFail();
        $user->roles()->attach($role->id);

        $period = PayrollPeriod::factory()->create([
            'name' => '2024-09 月',
            'period_start' => '2024-09-01',
            'period_end' => '2024-09-30',
        ]);

        $company = Company::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('backend.payroll.runs.store'), [
                'payroll_period_id' => $period->id,
                'company_id' => $company->id,
            ]);

        $response->assertRedirect(route('backend.payroll.index'));
        $response->assertSessionHas('status', '薪資批次已建立。');

        $this->assertDatabaseHas('payroll_runs', [
            'payroll_period_id' => $period->id,
            'company_id' => $company->id,
            'status' => 'draft',
        ]);
    }
}
