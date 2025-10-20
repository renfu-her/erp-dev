<?php

namespace Database\Factories;

use App\Models\InsuranceBracket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InsuranceBracket>
 */
class InsuranceBracketFactory extends Factory
{
    protected $model = InsuranceBracket::class;

    public function definition(): array
    {
        $grade = fake()->numberBetween(24000, 150000);

        return [
            'label' => $grade . ' 元',
            'grade' => $grade,
            'labor_employee_local' => fake()->numberBetween(300, 2000),
            'labor_employer_local' => fake()->numberBetween(300, 2000),
            'labor_employee_foreign' => fake()->numberBetween(300, 2000),
            'labor_employer_foreign' => fake()->numberBetween(300, 2000),
            'health_employee' => fake()->numberBetween(400, 3000),
            'health_employer' => fake()->numberBetween(400, 3000),
            'pension_employer' => fake()->numberBetween(1000, 5000),
        ];
    }
}

