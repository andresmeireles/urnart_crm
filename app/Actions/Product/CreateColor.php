<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Actions\Api\Product\CreateInterface;
use App\Models\Color;
use App\Models\User;

/**
 * @implements CreateInterface<Color>
 */
class CreateColor implements CreateInterface
{
    public function create(string $name, User $user): Color
    {
        $color = new Color();
        $color->name = strtoupper($name);
        $color->createdBy($user);
        $color->save();
        return $color;
    }
}
