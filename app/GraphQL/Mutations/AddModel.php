<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Actions\Product\CreateModel;
use App\Models\Model;
use Illuminate\Support\Facades\Auth;

final class AddModel
{
    public function __construct(protected readonly CreateModel $create)
    {
    }

    /**
     * @param  null  $_
     * @param  array{name: string}  $args
     */
    public function __invoke($_, array $args): Model
    {
        return $this->create->create($args['name'], Auth::user());
    }
}
