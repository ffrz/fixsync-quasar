<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ServiceOrder;
use App\Models\Technician;

class DashboardController extends Controller
{
    public function index()
    {
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');

        $days = [];
        for ($i = 1; $i <= substr($end_date, 8, 2); $i++) {
            $days[(int)$i] = 0;
        }

        $openedOrders = ServiceOrder::openedOrderByPeriod($start_date, $end_date);
        $monthly_opened_orders = $days;
        foreach ($openedOrders as $order) {
            $monthly_opened_orders[(int)substr($order->order_date, 8, 2)] = $order->total_order;
        }

        $closedOrders = ServiceOrder::closedOrderByPeriod($start_date, $end_date);
        $monthly_closed_orders = $days;
        foreach ($closedOrders as $order) {
            $monthly_closed_orders[(int)substr($order->order_date, 8, 2)] = $order->total_order;
        }

        $successfullServices = ServiceOrder::successOrderByPeriod($start_date, $end_date);
        $monthly_successfull_services = $days;
        foreach ($successfullServices as $order) {
            $monthly_successfull_services[(int)substr($order->order_date, 8, 2)] = $order->total_order;
        }

        $failedServices = ServiceOrder::failedOrderByPeriod($start_date, $end_date);
        $monthly_failed_services = $days;
        foreach ($failedServices as $order) {
            $monthly_failed_services[(int)substr($order->order_date, 8, 2)] = $order->total_order;
        }

        return inertia('admin/dashboard/Index', [
            'data' => [
                'active_order_count' => ServiceOrder::activeOrderCount(),
                'received_order_count' => ServiceOrder::receivedOrderCount(),
                'in_progress_order_count' => ServiceOrder::inProgressCount(),
                'pickable_order_count' => ServiceOrder::pickableOrderCount(),
                'total_billable_order' => ServiceOrder::totalBillable(),
                'total_active_bill' => ServiceOrder::totalActiveBill(),
                'total_active_downpayment' => ServiceOrder::totalActiveDownPayment(),
                'active_customer_count' => Customer::activeCustomerCount(),
                'active_technician_count' => Technician::activeTechnicianCount(),
                'top_customers' => ServiceOrder::topCustomers($start_date, $end_date),
                'top_technicians' => ServiceOrder::topTechnicians($start_date, $end_date),
                'chart1_data' => [
                    'x_axis_label_data' => array_keys($monthly_opened_orders),
                    'data' => [
                        [
                            'label' => 'Diterima',
                            'data' => array_values($monthly_opened_orders),
                        ],
                        [
                            'label' => 'Closing',
                            'data' => array_values($monthly_closed_orders),
                        ],
                        [
                            'label' => 'Sukses',
                            'data' => array_values($monthly_successfull_services),
                        ],
                        [
                            'label' => 'Gagal',
                            'data' => array_values($monthly_failed_services),
                        ]
                    ]
                ],
            ]
        ]);
    }

    /**
     * This method exists here for testing purpose only.
     */
    public function test()
    {
        return inertia('admin/dashboard/Test');
    }
}
