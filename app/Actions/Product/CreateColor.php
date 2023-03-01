<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Models\Color;
use App\Models\User;

class CreateColor
{
    public function __construct(private readonly User $user)
    {
    }

    public function create(string $name): Color
    {
        $color = new Color();
        $color->name = $name;
        $color->createdBy($this->user);
        $color->save();
        return $color;
    }
}
