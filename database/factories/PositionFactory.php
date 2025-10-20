<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Position;
use App\Support\InsuranceContributionSummary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Position>
 */
class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition(): array
    {
        $referenceSalary = fake()->numberBetween(28000, 65000);
        $summary = InsuranceContributionSummary::make((float) $referenceSalary);

        return [
            'department_id' => Department::factory(),
            'title' => fake()->jobTitle(),
            'grade' => 'G' . fake()->numberBetween(1, 10),
            'reference_salary' => $referenceSalary,
            'insurance_grade' => $summary['grade_value'] ?? null,
            'insurance_snapshot' => $summary ? [
                'grade_label' => $summary['grade_label'],
                'grade_value' => $summary['grade_value'],
                'base_salary' => $summary['base_salary'],
                'labor_local' => $summary['labor_local'],
                'labor_foreign' => $summary['labor_foreign'],
                'health' => $summary['health'],
                'pension' => $summary['pension'],
            ] : null,
            'is_managerial' => fake()->boolean(20),
            'metadata' => ['salary_band' => fake()->numberBetween(50000, 150000)],
        ];
    }
}
