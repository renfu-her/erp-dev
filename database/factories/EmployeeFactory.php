<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        $companyFactory = Company::factory();
        $departmentFactory = Department::factory()->for($companyFactory, 'company');
        $positionFactory = Position::factory()->for($departmentFactory, 'department');

        return [
            'company_id' => $companyFactory,
            'department_id' => $departmentFactory,
            'position_id' => $positionFactory,
            'employee_no' => strtoupper(Str::random(6)),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'middle_name' => fake()->optional()->firstName(),
            'date_of_birth' => fake()->date(),
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'national_id' => fake()->ssn(),
            'personal_data' => ['marital_status' => fake()->randomElement(['single', 'married'])],
            'status' => 'active',
            'hired_at' => fake()->dateTimeBetween('-5 years', '-1 month'),
        ];
    }
}
