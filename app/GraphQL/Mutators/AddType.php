<?php

declare(strict_types=1);

namespace App\GraphQL\Mutators;

use App\Actions\Product\CreateType;
use Illuminate\Support\Facades\Auth;

class AddType
{
    public function __construct(private readonly CreateType $createColor)
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
