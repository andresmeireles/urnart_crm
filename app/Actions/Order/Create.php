<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderActiveSnapshot;
use App\Models\Payment;
use App\Models\User;

class Create
{
    public function create(
        array $products,
        Customer $customer,
        Payment $payment,
        Delivery $delivery,
        User $createdBy,
        float $freight = 0,
        float $discount = 0,
        float $entry = 0,
        float $deliveryPrice = 0,
        string $portName = '',
        string $deliverer = '',
        string $observation = ''
    ): OrderDto {
        $order = new Order();

        $snapshot = new OrderActiveSnapshot();

        $cart = array_map(
            fn ($item) => Cart::create($item['product_id'], $item['amount'], $item['price'], 1),
            $products
        );

        return new OrderDto($order, $cart);
    }

    public function save(OrderDto $order): void
    {
        try {
            $createdOrder = $order->order->save();
            $snapshot = $this->getOrderSnapshot() ?? new OrderActiveSnapshot();
            $order->orderActiveSnapshot->save();
        } catch () {

        }
    }

    private function getOrderSnapshot(string $orderCode): ?OrderActiveSnapshot
    {
        $snapshot = OrderActiveSnapshot::where('code', '=', $orderCode)->get();
        if ($snapshot->isEmpty()) {
            return null;
        }
        $snapshot->first();
    }
}
