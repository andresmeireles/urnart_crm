<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Actions\Api\Product\CreateInterface;
use App\Models\Spec;
use App\Models\User;

/**
 * @implements CreateInterface<Spec>
 */
class CreateSpec implements CreateInterface
{
    public function create(string $name, User $user): Spec
    {
        $spec = new Spec();
        $spec->name = strtoupper($name);
        $spec->createdBy($user);
        $spec->save();
        return $spec;
    }
}
