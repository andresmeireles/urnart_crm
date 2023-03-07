<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Actions\Api\Product\CreateInterface;
use App\Models\Type;
use App\Models\User;

/**
 * @implements CreateInterface<Type>
 */
class CreateType implements CreateInterface
{
    public function create(string $name, User $user): Type
    {
        $type = new Type();
        $type->name = $name;
        $type->createdBy($user);
        $type->save();
        return $type;
    }
}
