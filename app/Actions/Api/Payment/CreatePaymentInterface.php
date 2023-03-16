<?php

namespace App\Actions\Api\Payment;

use App\Models\Payment;
use App\Models\User;

interface CreatePaymentInterface
{
    public function create(string $name, User $user, bool $installmentable): Payment;
}
