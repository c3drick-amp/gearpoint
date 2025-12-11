<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'address',
        'vehicle_info',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function serviceJobs()
    {
        return $this->hasMany(ServiceJob::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}