<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'name', 'phone', 'address', 'email'
    ];

    /**
     * Get the company that owns the customer.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the service orders for the customer.
     */
    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }
}
