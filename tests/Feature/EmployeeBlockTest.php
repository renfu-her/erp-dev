<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeBlockTest extends TestCase
{
    use RefreshDatabase;

    public function test_hr_admin_can_block_employee(): void
    {
        $this->seed(AccessControlSeeder::class);

        /** @var User $user */
        $user = User::factory()->create();
        $role = Role::where('slug', 'hr-admin')->firstOrFail();
        $user->roles()->attach($role->id);

        $employee = Employee::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson(
            route('employees.block', $employee),
            ['reason' => 'Violation of policy']
        );

        $response->assertOk();

        $this->assertNotNull($employee->fresh()->blocked_at);
        $this->assertEquals('Violation of policy', $employee->blocked_reason);
        $this->assertEquals('blocked', $employee->status);

        $this->assertDatabaseHas('approval_requests', [
            'type' => 'employee.block',
            'subject_id' => $employee->id,
            'requested_by' => $user->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'event' => 'employee.blocked',
            'subject_id' => $employee->id,
        ]);
    }

    public function test_user_without_permission_cannot_block_employee(): void
    {
        $this->seed(AccessControlSeeder::class);

        $user = User::factory()->create();
        $employee = Employee::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson(
            route('employees.block', $employee),
            ['reason' => 'Violation of policy']
        );

        $response->assertForbidden();

        $this->assertDatabaseMissing('approval_requests', [
            'subject_id' => $employee->id,
        ]);
    }
}
