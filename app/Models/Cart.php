<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'amount',
        'price',
        'order_id'
    ];

    public static function create(int $productId, int $amount, float $price, int $orderId): self
    {
        $cart = new self();
        $cart->product_id = $productId;
        $cart->amount = $amount;
        $cart->price = $price;
        $cart->order_id = $orderId;
        return $cart;
    }
}
