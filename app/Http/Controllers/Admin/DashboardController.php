<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return inertia('admin/dashboard/Index');
    }

    /**
     * This method exists here for testing purpose only.
     */
    public function test()
    {
        return inertia('admin/dashboard/Test');
    }
}
