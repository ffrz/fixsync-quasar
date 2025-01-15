<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'user_id', 'name', 'phone', 'address', 'email', 'active'
    ];

    /**
     * Get the company that owns the technician.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
