<?php

namespace Tests\Feature\Backend;

use App\Models\InsuranceBracket;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Database\Seeders\InsuranceBracketSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsuranceBracketManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateAsHrAdmin(): User
    {
        $this->seed([
            AccessControlSeeder::class,
            InsuranceBracketSeeder::class,
        ]);

        /** @var User $user */
        $user = User::factory()->create();
        $role = Role::where('slug', 'hr-admin')->firstOrFail();
        $user->roles()->attach($role->id);

        return $user;
    }

    public function test_index_requires_permission(): void
    {
        $this->seed([
            AccessControlSeeder::class,
            InsuranceBracketSeeder::class,
        ]);

        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('backend.insurance-brackets.index'));

        $response->assertForbidden();
    }

    public function test_hr_admin_can_view_brackets_list(): void
    {
        $user = $this->authenticateAsHrAdmin();

        $bracket = InsuranceBracket::query()->orderBy('grade')->firstOrFail();

        $indexResponse = $this->actingAs($user)->get(route('backend.insurance-brackets.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('投保薪資 30,300 元');
        $indexResponse->assertDontSee('新增級距');
    }
}
