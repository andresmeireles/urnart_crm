<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Actions\Product\CreateColor;
use App\Models\Color;
use Illuminate\Support\Facades\Auth;

final class AddColor
{
    public function __construct(private readonly CreateColor $createColor)
    {
    }

    /**
     * @param  null  $_
     * @param  array{name: string}  $args
     */
    public function __invoke($_, array $args): Color
    {
        return $this->createColor->create($args['name'], Auth::user());
    }
}
