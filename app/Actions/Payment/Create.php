<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Actions\Api\Payment\CreatePaymentInterface;
use App\Models\User;
use App\Models\Payment;

class Create implements CreatePaymentInterface
{
    public function create(string $name, User $user, bool $installmentable): Payment
    {
        $payment = new Payment();
        $payment->name = $name;
        $payment->installmentable = $installmentable;
        $payment->user_id = $user->id;
        $payment->save();
        return $payment;
    }
}
