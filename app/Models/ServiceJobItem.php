<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceJobItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_job_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function serviceJob()
    {
        return $this->belongsTo(ServiceJob::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}