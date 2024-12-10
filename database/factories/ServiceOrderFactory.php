<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceOrder>
 */
class ServiceOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => \App\Models\Company::factory(),
            'customer_id' => \App\Models\Customer::factory(),
            'order_status' => $this->faker->randomElement([0, 1, 2]),
            'service_status' => $this->faker->randomElement([0, 1, 2]),
            'payment_status' => $this->faker->randomElement([0, 1, 2]),
            'created_datetime' => $this->faker->dateTimeThisMonth,
            'created_by_uid' => \App\Models\User::factory(),
            'closed_datetime' => $this->faker->dateTimeThisMonth,
            'closed_by_uid' => \App\Models\User::factory(),
            'updated_datetime' => $this->faker->dateTimeThisMonth,
            'updated_by_uid' => \App\Models\User::factory(),
            'customer_name' => $this->faker->name,
            'customer_phone' => $this->faker->phoneNumber,
            'customer_address' => $this->faker->address,
            'device' => $this->faker->word,
            'equipments' => $this->faker->sentence,
            'device_sn' => $this->faker->word,
            'problems' => $this->faker->sentence,
            'actions' => $this->faker->sentence,
            'date_received' => $this->faker->dateTimeThisMonth,
            'date_checked' => $this->faker->dateTimeThisMonth,
            'date_worked' => $this->faker->dateTimeThisMonth,
            'date_completed' => $this->faker->dateTimeThisMonth,
            'date_picked' => $this->faker->dateTimeThisMonth,
            'down_payment' => $this->faker->randomFloat(2, 0, 1000),
            'estimated_cost' => $this->faker->randomFloat(2, 0, 1000),
            'total_cost' => $this->faker->randomFloat(2, 0, 1000),
            'technician_id' => \App\Models\User::factory(),
            'notes' => $this->faker->paragraph,
        ];
    }
}
