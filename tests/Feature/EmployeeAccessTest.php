<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_permission_can_list_employees(): void
    {
        $this->seed(AccessControlSeeder::class);

        /** @var User $user */
        $user = User::factory()->create();
        $role = Role::where('slug', 'hr-admin')->firstOrFail();
        $user->roles()->attach($role->id);

        Employee::factory()->count(2)->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/backend/employees');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    }

    public function test_user_without_permission_cannot_list_employees(): void
    {
        $this->seed(AccessControlSeeder::class);

        $user = User::factory()->create();
        Employee::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/backend/employees');

        $response->assertForbidden();
    }
}
