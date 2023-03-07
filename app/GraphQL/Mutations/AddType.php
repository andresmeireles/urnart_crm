<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Actions\Product\CreateType;
use App\Models\Type;
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
    public function __invoke($_, array $args): Type
    {
        return $this->createColor->create($args['name'], Auth::user());
    }
}
