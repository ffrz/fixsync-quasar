<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'customer_id', 'order_status', 'service_status', 'payment_status', 'created_datetime', 'created_by_uid', 'closed_datetime', 'closed_by_uid', 'updated_datetime', 'updated_by_uid', 'customer_name', 'customer_phone', 'customer_address', 'device', 'equipments', 'device_sn', 'problems', 'actions', 'date_received', 'date_checked', 'date_worked', 'date_completed', 'date_picked', 'down_payment', 'estimated_cost', 'total_cost', 'technician_id', 'notes'
    ];

    public $timestamps = false;

    /**
     * Get the customer that owns the service order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the company that owns the service order.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
