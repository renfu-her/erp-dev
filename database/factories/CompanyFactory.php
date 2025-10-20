<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'code' => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 999),
            'tax_id' => strtoupper(Str::random(10)),
            'status' => 'active',
            'metadata' => ['timezone' => fake()->timezone()],
        ];
    }
}
