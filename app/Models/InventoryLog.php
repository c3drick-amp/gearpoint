<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'change',
        'type',
        'reference_id',
        'reference_type',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}