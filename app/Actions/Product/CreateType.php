<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Models\Type;
use App\Models\User;

class CreateType
{
    public function __construct(private readonly User $user)
    {
    }

    public function create(string $name): Type
    {
        $type = new Type();
        $type->name = $name;
        $type->createdBy($this->user);
        $type->save();
        return $type;
    }
}
