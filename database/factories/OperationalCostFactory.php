<?php

namespace Database\Factories;

use App\Models\OperationalCost;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class OperationalCostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => 1,
            'date' => new Carbon(fake()->dateTimeThisYear()),
            'description' => fake()->sentence(),
            'amount' => fake()->numberBetween(1, 200) * 1000,
            'notes' => fake()->sentence(),
        ];
    }
}
