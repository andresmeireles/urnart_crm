<?php

declare(strict_types=1);

namespace App\Actions\Delivery;

use App\Actions\Api\Delivery\CreateDeliveryInterface;
use App\Models\Delivery;
use App\Models\User;

class Create implements CreateDeliveryInterface
{
    public function create(string $name, User $user, bool $isPaid = true): Delivery
    {
        $delivery = new Delivery();
        $delivery->name = $name;
        $delivery->is_paid = $isPaid;
        $delivery->user_id = $user->id;
        $delivery->save();
        return $delivery;
    }
}
