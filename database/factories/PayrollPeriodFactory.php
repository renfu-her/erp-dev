<?php

namespace Database\Factories;

use App\Models\PayrollPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PayrollPeriod>
 */
class PayrollPeriodFactory extends Factory
{
    protected $model = PayrollPeriod::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 year', 'now');
        $end = (clone $start)->modify('+1 month -1 day');

        return [
            'name' => fake()->monthName() . ' ' . $start->format('Y'),
            'period_start' => $start->format('Y-m-d'),
            'period_end' => $end->format('Y-m-d'),
            'status' => fake()->randomElement(['draft', 'processing', 'completed']),
            'metadata' => null,
        ];
    }
}
