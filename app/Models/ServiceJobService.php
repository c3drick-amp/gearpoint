<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceJobService extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_job_id',
        'service_id',
        'labor_fee',
    ];

    public function serviceJob()
    {
        return $this->belongsTo(ServiceJob::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
