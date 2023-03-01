<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Models\Spec;
use App\Models\User;

class CreateSpec
{
    public function __construct(private readonly User $user)
    {
    }

    public function create(string $name): Spec
    {
        $spec = new Spec();
        $spec->name = $name;
        $spec->createdBy($this->user);
        $spec->save();
        return $spec;
    }
}
