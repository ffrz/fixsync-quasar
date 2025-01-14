<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\ServiceOrder;
use Carbon\Carbon;
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
        $orderStatus = ServiceOrder::OrderStatus_Open;
        $serviceStatus = $this->faker->randomElement([
            ServiceOrder::ServiceStatus_Received,
            ServiceOrder::ServiceStatus_Checked,
            ServiceOrder::ServiceStatus_WaitingParts,
            ServiceOrder::ServiceStatus_InProgress,
        ]);
        $paymentStatus = $this->faker->randomElement([
            ServiceOrder::PaymentStatus_Unpaid,
            ServiceOrder::PaymentStatus_PartiallyPaid,
            ServiceOrder::PaymentStatus_FullyPaid,
        ]);

        $createdDateTime = Carbon::instance($this->faker->dateTimeThisMonth);
        $received_datetime = $createdDateTime->copy();

        $checked_datetime = null;
        if ($serviceStatus == ServiceOrder::ServiceStatus_Checked ||
            $serviceStatus == ServiceOrder::ServiceStatus_WaitingParts ||
            $serviceStatus == ServiceOrder::ServiceStatus_InProgress) {
            $checked_datetime = $received_datetime->copy()->addDays(1);
        }

        $worked_datetime = null;
        if ($serviceStatus == ServiceOrder::ServiceStatus_WaitingParts ||
            $serviceStatus == ServiceOrder::ServiceStatus_InProgress) {
            $worked_datetime = $checked_datetime->copy()->addDays(1);

            $serviceStatus = $this->faker->randomElement([
                ServiceOrder::ServiceStatus_WaitingParts,
                ServiceOrder::ServiceStatus_InProgress,
                ServiceOrder::ServiceStatus_Completed,
            ]);
        }

        $completed_datetime = null;
        if ($serviceStatus == ServiceOrder::ServiceStatus_Completed) {
            $completed_datetime = $worked_datetime->copy()->addDays(1);
            $serviceStatus = $this->faker->randomElement([
                ServiceOrder::ServiceStatus_Completed,
                ServiceOrder::ServiceStatus_Picked,
            ]);
        }

        $picked_datetime = null;
        if ($serviceStatus == ServiceOrder::ServiceStatus_Picked) {
            $picked_datetime = $completed_datetime->copy()->addDays(1);
        }

        $estimated_cost = $this->faker->randomFloat(2, 1, 50) * 25000;
        $down_payment = 0;
        if ($paymentStatus == ServiceOrder::PaymentStatus_PartiallyPaid) {
            $down_payment = (0.25 * $estimated_cost);
        }
        else if ($paymentStatus == ServiceOrder::PaymentStatus_FullyPaid) {
            $down_payment = $estimated_cost;
        }

        $total_cost = 0;

        // set status perbaikan, inisialisasi dengan belum selesai
        $repair_status = ServiceOrder::RepairStatus_NotFinished;
        if ($serviceStatus == ServiceOrder::ServiceStatus_Completed
            || $serviceStatus == ServiceOrder::ServiceStatus_Picked) {
            // set status perbaikan dengan nilai sukses atau gagal
            $repair_status = $this->faker->randomElement([
                ServiceOrder::RepairStatus_Success,
                ServiceOrder::RepairStatus_Failed,
            ]);
        }

        // set biaya jika selesai / sukses
        if ($repair_status == ServiceOrder::RepairStatus_Success) {
            $total_cost = $estimated_cost;
        }

        if ($serviceStatus == ServiceOrder::ServiceStatus_Picked
            && $paymentStatus == ServiceOrder::PaymentStatus_FullyPaid) {
            $orderStatus = ServiceOrder::OrderStatus_Closed;
        }
        else if ($repair_status != ServiceOrder::RepairStatus_Success) {
            $orderStatus = $this->faker->randomElement([
                ServiceOrder::OrderStatus_Open,
                ServiceOrder::OrderStatus_Canceled,
            ]);
        }

        return [
            'company_id' => 1,
            'customer_id' => $customer->id,

            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'customer_address' => $customer->address,

            'order_status' => $orderStatus,
            'service_status' => $serviceStatus,
            'payment_status' => $paymentStatus,
            'repair_status' => $repair_status,

            'created_datetime' => $createdDateTime,
            'created_by_uid' => 1,
            'closed_datetime' => $picked_datetime,
            'closed_by_uid' => 1,
            'updated_datetime' => $picked_datetime,
            'updated_by_uid' => 1,

            'device' => $this->faker->randomElement(static::$devices),
            'equipments' => $this->faker->sentence, // Ganti dengan peralatan yang umum
            'device_sn' => $this->faker->word, // Ganti dengan nomor
            'problems' => $this->faker->sentence, // Ganti dengan masalah umum
            'actions' => $this->faker->sentence, // Ganti dengan aksi yang umum

            'received_datetime' => $received_datetime,
            'checked_datetime' => $checked_datetime,
            'worked_datetime' => $worked_datetime,
            'completed_datetime' => $completed_datetime,
            'picked_datetime' => $picked_datetime,

            'down_payment' => $down_payment,
            'estimated_cost' => $estimated_cost,
            'total_cost' => $total_cost,
            'technician_id' => 2,

            'warranty_start_date' => $repair_status == ServiceOrder::RepairStatus_Success ? $completed_datetime : null,
            'warranty_day_count' => $repair_status == ServiceOrder::RepairStatus_Success ? $this->faker->randomElement([3, 7, 14, 30]) : 0,

            'notes' => $this->faker->paragraph,
        ];
    }
}
