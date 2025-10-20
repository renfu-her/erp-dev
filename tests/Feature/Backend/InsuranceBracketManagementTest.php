<?php

namespace Tests\Feature\Backend;

use App\Models\InsuranceBracket;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsuranceBracketManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateAsHrAdmin(): User
    {
        $this->seed(AccessControlSeeder::class);

        /** @var User $user */
        $user = User::factory()->create();
        $role = Role::where('slug', 'hr-admin')->firstOrFail();
        $user->roles()->attach($role->id);

        return $user;
    }

    public function test_index_requires_permission(): void
    {
        $this->seed(AccessControlSeeder::class);

        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('backend.insurance-brackets.index'));

        $response->assertForbidden();
    }

    public function test_hr_admin_can_list_create_update_and_delete_brackets(): void
    {
        $user = $this->authenticateAsHrAdmin();

        $bracket = InsuranceBracket::factory()->create([
            'label' => '投保薪資 30,300 元',
            'grade' => 30300,
        ]);

        $indexResponse = $this->actingAs($user)->get(route('backend.insurance-brackets.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('投保薪資 30,300 元');

        $createPayload = [
            'label' => '投保薪資 36,000 元',
            'grade' => 36000,
            'labor_employee_local' => 600,
            'labor_employer_local' => 900,
            'health_employee' => 750,
            'health_employer' => 1050,
            'pension_employer' => 2160,
        ];

        $storeResponse = $this->actingAs($user)->post(route('backend.insurance-brackets.store'), $createPayload);
        $storeResponse->assertRedirect(route('backend.insurance-brackets.index'));
        $this->assertDatabaseHas('insurance_brackets', [
            'label' => '投保薪資 36,000 元',
            'grade' => 36000,
        ]);

        $updateResponse = $this->actingAs($user)->put(route('backend.insurance-brackets.update', $bracket), [
            'label' => '投保薪資 30,300 元（調整）',
            'grade' => 30300,
            'labor_employee_local' => 500,
        ]);
        $updateResponse->assertRedirect(route('backend.insurance-brackets.index'));
        $this->assertDatabaseHas('insurance_brackets', [
            'id' => $bracket->id,
            'label' => '投保薪資 30,300 元（調整）',
            'labor_employee_local' => 500,
        ]);

        $deleteResponse = $this->actingAs($user)->delete(route('backend.insurance-brackets.destroy', $bracket));
        $deleteResponse->assertRedirect(route('backend.insurance-brackets.index'));
        $this->assertDatabaseMissing('insurance_brackets', ['id' => $bracket->id]);
    }
}

