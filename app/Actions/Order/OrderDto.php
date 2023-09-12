<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\Cart;
use App\Models\Order;

readonly class OrderDto
{
    /**
     * @var Cart[] $cart
     */
    public array $cart;

    public function __construct(
        public Order $order,
        array $cart
    ) {
        foreach ($cart as $value) {
            if (!$value instanceof Cart) {
                throw new \Exception('value must be Cart instance');
            }
        }
        $this->cart = $cart;
    }
}
