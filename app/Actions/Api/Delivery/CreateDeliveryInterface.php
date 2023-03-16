<?php

declare(strict_types=1);

namespace App\Actions\Api\Delivery;

use App\Models\Delivery;
use App\Models\User;

interface CreateDeliveryInterface
{
    public function create(string $name, User $user, bool $isPaid = true): Delivery;
}
