<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'total_amount',
        'discount',
        'amount_paid',
        'change_due',
        'payment_method',
        'transaction_year',
        'is_void',
        'voided_by',
        'voided_at',
        'void_reason',
    ];

    protected $casts = [
        'is_void' => 'boolean',
        'voided_at' => 'datetime',
        'transaction_year' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function voidRequests()
    {
        return $this->hasMany(VoidRequest::class);
    }

    public function voidLogs()
    {
        return $this->hasMany(VoidLog::class);
    }

    public function voidedBy()
    {
        return $this->belongsTo(User::class, 'voided_by');
    }
}