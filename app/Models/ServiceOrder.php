<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'customer_id',
        'order_status',
        'service_status',
        'payment_status',
        'created_datetime',
        'created_by_uid',
        'closed_datetime',
        'closed_by_uid',
        'updated_datetime',
        'updated_by_uid',
        'customer_name',
        'customer_phone',
        'customer_address',
        'device',
        'equipments',
        'device_sn',
        'problems',
        'actions',
        'received_datetime',
        'checked_datetime',
        'worked_datetime',
        'completed_datetime',
        'picked_datetime',
        'down_payment',
        'estimated_cost',
        'total_cost',
        'technician_id',
        'warranty_start_date',
        'warranty_day_count',
        'notes'
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_uid', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_uid', 'id');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by_uid', 'id');
    }

    public static function activeOrderCount()
    {
        return DB::select(
            "select count(0) as count from service_orders where order_status=? and company_id=?",
            ['open', Auth::user()->company_id]
        )[0]->count;
    }

    public static function notTakenYetCount()
    {
        return DB::select(
            "select count(0) as count from service_orders where order_status='' and company_id=?",
            [Auth::user()->company_id]
        )[0]->count;
    }
}
