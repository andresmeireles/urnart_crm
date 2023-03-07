<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Actions\Product\CreateSpec;
use App\Models\Spec;
use Illuminate\Support\Facades\Auth;

class AddSpec
{
    public function __construct(private readonly CreateSpec $createColor)
    {
    }

    /**
     * @param  null  $_
     * @param  array{name: string}  $args
     */
    public function __invoke($_, array $args): Spec
    {
        return $this->createColor->create($args['name'], Auth::user());
    }
}
