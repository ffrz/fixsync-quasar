<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ServiceOrder;

class DashboardController extends Controller
{
    public function index()
    {
        return inertia('admin/dashboard/Index', [
            'data' => [
                'active_customer_count' => Customer::activeCustomerCount(),
                'active_order_count' => ServiceOrder::activeOrderCount(),
                'received_order_count' => ServiceOrder::receivedOrderCount(),
                'in_progress_order_count' => ServiceOrder::inProgressCount(),
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
