<?php

namespace Tests\Feature\Backend;

use App\Models\Department;
use App\Models\InsuranceBracket;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Database\Seeders\InsuranceBracketSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PositionManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_persists_reference_salary_and_insurance_snapshot(): void
    {
        $this->seed([
            AccessControlSeeder::class,
            InsuranceBracketSeeder::class,
        ]);

        /** @var User $user */
        $user = User::factory()->create();
        $role = Role::where('slug', 'hr-admin')->firstOrFail();
        $user->roles()->attach($role->id);

        $bracket = InsuranceBracket::where('grade', '>=', 36000)->orderBy('grade')->firstOrFail();

        $department = Department::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('backend.positions.store'), [
                'department_id' => $department->id,
                'title' => '生產線班長',
                'grade' => 'M1',
                'reference_salary' => $bracket->grade,
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
        $this->seed([
            AccessControlSeeder::class,
            InsuranceBracketSeeder::class,
        ]);

        $baseBracket = InsuranceBracket::where('grade', '>=', 32000)->orderBy('grade')->firstOrFail();

        $manualBracket = InsuranceBracket::where('grade', '>=', 45000)->orderBy('grade')->firstOrFail();

        /** @var User $user */
        $user = User::factory()->create();
        $role = Role::where('slug', 'hr-admin')->firstOrFail();
        $user->roles()->attach($role->id);

        $position = Position::factory()->create([
            'reference_salary' => $baseBracket->grade,
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
