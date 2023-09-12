<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'customer_id',
        'user_id',
        'freight',
        'discount',
        'entry',
        'order_value',
        'products_value',
        'products_amount',
        'payment_id',
        'delivery_id',
        'delivery_price',
        'port_name',
        'deliverer',
        'status_id',
        'valid',
        'created_at'
    ];

    public $timestamps = false;
}
