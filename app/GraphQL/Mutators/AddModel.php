<?php

declare(strict_types=1);

namespace App\GraphQL\Mutators;

use App\Actions\Product\CreateModel;
use Illuminate\Support\Facades\Auth;

class AddModel
{
    public function __construct(private readonly CreateModel $createColor)
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
