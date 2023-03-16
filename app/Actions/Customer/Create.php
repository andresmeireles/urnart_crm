<?php

declare(strict_types=1);

namespace App\Actions\Customer;

use App\Models\Customer;
use App\Models\User;

class Create
{
    public function create(User $user, string $tradeName, string $companyName, ?int $number = null): Customer
    {
        $customer = new Customer();
        $customer->trade_name = $tradeName;
        $customer->company_name = $companyName;
        $customer->number = $number ?? $this->getCustomNumber();
        $customer->created_by = $user->id;
        $customer->save();
        return $customer;
    }

    private function getCustomNumber(): int
    {
        $customerNumbers = Customer::select(['number'])->orderByDesc('number')->get();
        if ($customerNumbers->isEmpty()) {
            return 1;
        }
        return ($customerNumbers->first()->number ?? 0) + 1;
    }
}
