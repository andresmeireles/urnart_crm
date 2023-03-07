<?php

declare(strict_types=1);

namespace App\GraphQL\Mutators;

use App\Actions\Product\Create;
use Illuminate\Support\Facades\Auth;

class AddProduct
{
    public function __construct(private readonly Create $createColor)
    {
    }

    /**
     * @param  null  $_
     * @param  array{name: string}  $args
     */
    public function addColor($_, array $args): void
    {
        $this->createColor->create($args['name'], Auth::user());
    }
}
