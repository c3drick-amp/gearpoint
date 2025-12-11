<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'mechanic_id',
        'motorcycle_details',
        'status',
        'total_cost',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function serviceJobItems()
    {
        return $this->hasMany(ServiceJobItem::class);
    }

    public function serviceJobServices()
    {
        return $this->hasMany(ServiceJobService::class);
    }
}