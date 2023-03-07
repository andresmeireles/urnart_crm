<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Model;
use Illuminate\Database\Eloquent\Collection;

class GetModel
{
    public function __invoke(): Collection
    {
        return Model::all();
    }
}
