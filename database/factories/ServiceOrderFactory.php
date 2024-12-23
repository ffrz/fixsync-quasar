<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\ServiceOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceOrder>
 */
class ServiceOrderFactory extends Factory
{
    public static $devices = [
        'Asus X441S',
        'Asus X441M',
        'Asus X453',
        'Asus A407J',
        'Lenovo T440S',
        'Lenovo T450S',
        'Lenovo T460S',
        'Lenovo T470S',
        'Epson L120',
        'Epson L121',
        'Epson L3110',
        'Epson L3210',
        'Canon IP2770',
        'Canon MP287',
        'Canon G2020',
        'Canon G1010',
        'HP LaserJet 1020',
        'HP LaserJet 1022'
    ];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customer = Customer::find(Customer::where('company_id', 1)->pluck('id')->random());
        $orderStatus = $this->faker->randomElement(array_keys(ServiceOrder::OrderStatuses));
        $createdDateTime = $this->faker->dateTimeThisMonth;

        return [
            'company_id' => 1,
            'customer_id' => $customer->id,

            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'customer_address' => $customer->address,

            'order_status' => $orderStatus,
            'service_status' => $this->faker->randomElement(array_keys(ServiceOrder::ServiceStatuses)),
            'payment_status' => $this->faker->randomElement(array_keys(ServiceOrder::PaymentStatuses)),
            'repair_status' => $this->faker->randomElement(array_keys(ServiceOrder::RepairStatuses)),

            // Tanggal ini juga harus rasional, yaitu berurutan
            'created_datetime' => $createdDateTime,
            'created_by_uid' => 1,

            // closed_date time harus lebih besar dari created dan jika status order nya closed / canceled, null jika masih open
            'closed_datetime' => $this->faker->dateTimeThisMonth,
            'closed_by_uid' => 1,

            // updated date time bole sama dengan created time  saja
            'updated_datetime' => $this->faker->dateTimeThisMonth,
            'updated_by_uid' => 1,

            'device' => $this->faker->randomElement(static::$devices),
            'equipments' => $this->faker->sentence, // Ganti dengan peralatan yang umum
            'device_sn' => $this->faker->word, // Ganti dengan nomor
            'problems' => $this->faker->sentence, // Ganti dengan masalah umum
            'actions' => $this->faker->sentence, // Ganti dengan aksi yang umum

            // buat tanggal lebih logis dengan waktu yang berurutan
            'received_datetime' => $this->faker->dateTimeThisMonth,
            'checked_datetime' => $this->faker->dateTimeThisMonth,
            'worked_datetime' => $this->faker->dateTimeThisMonth,
            'completed_datetime' => $this->faker->dateTimeThisMonth,
            'picked_datetime' => $this->faker->dateTimeThisMonth,

            // buat keuangan lebih logis dengan jumlah acak yang sesuai
            'down_payment' => $this->faker->randomFloat(2, 1, 50) * 25000,
            'estimated_cost' => $this->faker->randomFloat(2, 1, 50) * 25000,
            'total_cost' => $this->faker->randomFloat(2, 1, 50) * 25000,
            'technician_id' => 2,

            // garansi bisa dibuat random kalau dan diisi berdasarkan statusnya selesai / tidak
            'warranty_start_date' => null,
            'warranty_day_count' => 0,

            'notes' => $this->faker->paragraph,
        ];
    }
}
