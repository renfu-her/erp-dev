<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Position>
 */
class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'title' => fake()->jobTitle(),
            'grade' => 'G' . fake()->numberBetween(1, 10),
            'is_managerial' => fake()->boolean(20),
            'metadata' => ['salary_band' => fake()->numberBetween(50000, 150000)],
        ];
    }
}
