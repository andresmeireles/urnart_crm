<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Models\Model;
use App\Models\Color;
use App\Models\Product;
use App\Models\Spec;
use App\Models\Type;
use App\Models\User;

class Create
{
    public function create(
        User $user,
        Model $model,
        float $price,
        string $height,
        ?Type $type,
        ?Color $color,
        ?Spec $spec = null
    ): Product {
        $prod = new Product();
        $prod->model_id = $model->id;
        $prod->type_id = $type?->id;
        $prod->color_id = $color?->id;
        $prod->spec_id = $spec?->id;
        $prod->price = $price;
        $prod->height = $height;
        $prod->created_by = $user->id;
        $prod->save();
        return $prod;
    }
}
