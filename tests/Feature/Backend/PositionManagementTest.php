<?php

namespace Tests\Feature\Backend;

use App\Models\Department;
use App\Models\InsuranceBracket;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PositionManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_persists_reference_salary_and_insurance_snapshot(): void
    {
        $this->seed(AccessControlSeeder::class);

        InsuranceBracket::factory()->create([
            'label' => '投保薪資 36,000 元',
            'grade' => 36000,
            'labor_employee_local' => 500,
            'labor_employer_local' => 700,
            'labor_employee_foreign' => 500,
            'labor_employer_foreign' => 700,
            'health_employee' => 650,
            'health_employer' => 950,
            'pension_employer' => 2160,
        ]);

        /** @var User $user */
        $user = User::factory()->create();
        $role = Role::where('slug', 'hr-admin')->firstOrFail();
        $user->roles()->attach($role->id);

        $department = Department::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('backend.positions.store'), [
                'department_id' => $department->id,
                'title' => '生產線班長',
                'grade' => 'M1',
                'reference_salary' => 36000,
                'insurance_grade' => '',
                'is_managerial' => true,
            ]);

        $response->assertRedirect(route('backend.positions.index'));

        /** @var Position $position */
        $position = Position::latest()->first();

        $this->assertNotNull($position);
        $this->assertSame(36000.0, (float) $position->reference_salary);
        $this->assertNotNull($position->insurance_grade);
        $this->assertNotNull($position->insurance_snapshot);
        $this->assertSame(
            $position->insurance_grade,
            data_get($position->insurance_snapshot, 'grade_value')
        );
        $this->assertNotNull(data_get($position->insurance_snapshot, 'labor_local.employee'));
        $this->assertNotNull(data_get($position->insurance_snapshot, 'health.employee'));
    }

    public function test_update_allows_manual_insurance_grade_override(): void
    {
        $this->seed(AccessControlSeeder::class);

        InsuranceBracket::factory()->create([
            'label' => '投保薪資 32,000 元',
            'grade' => 32000,
            'labor_employee_local' => 450,
            'labor_employer_local' => 600,
            'health_employee' => 600,
            'health_employer' => 800,
            'pension_employer' => 1920,
        ]);

        $manualBracket = InsuranceBracket::factory()->create([
            'label' => '投保薪資 45,000 元',
            'grade' => 45000,
            'labor_employee_local' => 580,
            'labor_employer_local' => 820,
            'health_employee' => 780,
            'health_employer' => 1020,
            'pension_employer' => 2700,
        ]);

        /** @var User $user */
        $user = User::factory()->create();
        $role = Role::where('slug', 'hr-admin')->firstOrFail();
        $user->roles()->attach($role->id);

        $position = Position::factory()->create([
            'reference_salary' => 32000,
        ]);

        $response = $this->actingAs($user)
            ->put(route('backend.positions.update', $position), [
                'department_id' => $position->department_id,
                'title' => $position->title,
                'grade' => $position->grade,
                'reference_salary' => 32000,
                'insurance_grade' => $manualBracket->grade,
                'is_managerial' => $position->is_managerial,
            ]);

        $response->assertRedirect(route('backend.positions.index'));

        $position->refresh();

        $this->assertSame($manualBracket->grade, $position->insurance_grade);
        $this->assertSame(
            $manualBracket->label,
            data_get($position->insurance_snapshot, 'grade_label')
        );
    }
}
