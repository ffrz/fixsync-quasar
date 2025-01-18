<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OperationalCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'date', 'description', 'amount', 'notes'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
