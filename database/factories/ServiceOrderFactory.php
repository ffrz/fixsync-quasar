<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceOrder>
 */
class ServiceOrderFactory extends Factory
{
    public static $devices = [
        'Asus X441S', 'Asus X441M', 'Asus X453', 'Asus A407J',
        'Lenovo T440S', 'Lenovo T450S', 'Lenovo T460S', 'Lenovo T470S',
        'Epson L120', 'Epson L121', 'Epson L3110', 'Epson L3210',
        'Canon IP2770', 'Canon MP287', 'Canon G2020', 'Canon G1010',
        'HP LaserJet 1020', 'HP LaserJet 1022'
    ];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => 1,
            'customer_id' => \App\Models\Customer::factory(),
            'order_status' => $this->faker->randomElement(array_keys(SERVICEORDER_ORDER_STATUSES)),
            'service_status' => $this->faker->randomElement(array_keys(SERVICEORDER_SERVICE_STATUSES)),
            'payment_status' => $this->faker->randomElement(array_keys(SERVICEORDER_PAYMENT_STATUSES)),
            'created_datetime' => $this->faker->dateTimeThisMonth,
            'created_by_uid' => 1,
            'closed_datetime' => $this->faker->dateTimeThisMonth,
            'closed_by_uid' => 1,
            'updated_datetime' => $this->faker->dateTimeThisMonth,
            'updated_by_uid' => 1,
            'customer_name' => $this->faker->name,
            'customer_phone' => $this->faker->phoneNumber,
            'customer_address' => $this->faker->address,
            'device' => $this->faker->randomElement(static::$devices),
            'equipments' => $this->faker->sentence,
            'device_sn' => $this->faker->word,
            'problems' => $this->faker->sentence,
            'actions' => $this->faker->sentence,
            'received_datetime' => $this->faker->dateTimeThisMonth,
            'checked_datetime' => $this->faker->dateTimeThisMonth,
            'worked_datetime' => $this->faker->dateTimeThisMonth,
            'completed_datetime' => $this->faker->dateTimeThisMonth,
            'picked_datetime' => $this->faker->dateTimeThisMonth,
            'down_payment' => $this->faker->randomFloat(2, 1, 50) * 25000,
            'estimated_cost' => $this->faker->randomFloat(2, 1, 50) * 25000,
            'total_cost' => $this->faker->randomFloat(2, 1, 50) * 25000,
            'technician_id' => 2,
            'warranty_start_date' => null,
            'warranty_day_count' => 0,
            'notes' => $this->faker->paragraph,
        ];
    }
}
