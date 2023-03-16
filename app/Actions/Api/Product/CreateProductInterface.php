<?php

namespace App\Actions\Api\Product;

use App\Models\Color;
use App\Models\Model;
use App\Models\Product;
use App\Models\Spec;
use App\Models\Type;
use App\Models\User;

interface CreateProductInterface
{
    public function create(
        User $user,
        Model $model,
        float $price,
        string $height,
        ?Type $type,
        ?Color $color,
        ?Spec $spec = null
    ): Product;
}
