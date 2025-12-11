<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'category',
        'description',
        'labor_fee',
        'estimated_duration',
    ];

    public function serviceJobServices()
    {
        return $this->hasMany(ServiceJobService::class);
    }
}
