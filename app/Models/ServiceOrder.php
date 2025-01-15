<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceOrder extends Model
{
    use HasFactory;

    /**
     * OrderStatuses
     */
    const OrderStatus_Open = 'open';
    const OrderStatus_Closed = 'closed';
    const OrderStatus_Canceled = 'canceled';

    const OrderStatuses = [
        self::OrderStatus_Open => 'Aktif',
        self::OrderStatus_Closed => 'Selesai',
        self::OrderStatus_Canceled => 'Dibatalkan',
    ];

    /**
     * ServiceStatuses
     */
    const ServiceStatus_Received = 'received';
    const ServiceStatus_Checked = 'checked';
    const ServiceStatus_WaitingParts = 'waiting_parts';
    const ServiceStatus_InProgress = 'in_progress';
    const ServiceStatus_Completed = 'completed';
    const ServiceStatus_Picked = 'picked';

    const ServiceStatuses = [
        self::ServiceStatus_Received => 'Diterima',
        self::ServiceStatus_Checked => 'Diperiksa',
        self::ServiceStatus_WaitingParts => 'Menunggu Sparepart',
        self::ServiceStatus_InProgress => 'Dikerjakan',
        self::ServiceStatus_Completed => 'Selesai',
        self::ServiceStatus_Picked => 'Diambil',
    ];

    /**
     * RepairStatus
     */
    const RepairStatus_NotFinished = 'not_finished';
    const RepairStatus_Success = 'success';
    const RepairStatus_Failed = 'failed';

    const RepairStatuses = [
        self::RepairStatus_NotFinished => 'Belum Selesai',
        self::RepairStatus_Success => 'Sukses',
        self::RepairStatus_Failed => 'Gagal',
    ];

        /**
     * RepairStatus
     */
    const PaymentStatus_Unpaid = 'unpaid';
    const PaymentStatus_PartiallyPaid = 'partially_paid';
    const PaymentStatus_FullyPaid = 'fully_paid';

    const PaymentStatuses = [
        self::PaymentStatus_Unpaid => 'Belum Dibayar',
        self::PaymentStatus_PartiallyPaid => 'Dibayar Sebagian',
        self::PaymentStatus_FullyPaid => 'Lunas',
    ];

    protected $fillable = [
        'company_id',
        'customer_id',
        'order_status',
        'service_status',
        'payment_status',
        'repair_status',
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

    public function technician()
    {
        return $this->belongsTo(Technician::class);
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
            [self::OrderStatus_Open, Auth::user()->company_id]
        )[0]->count;
    }

    public static function receivedOrderCount()
    {
        return DB::select(
            "select count(0) as count from service_orders where service_status=? and company_id=?",
            [self::ServiceStatus_Received, Auth::user()->company_id]
        )[0]->count;
    }

    public static function inProgressCount()
    {
        return DB::select(
            "select count(0) as count from service_orders where service_status=? and company_id=?",
            [self::ServiceStatus_InProgress, Auth::user()->company_id]
        )[0]->count;
    }
}
