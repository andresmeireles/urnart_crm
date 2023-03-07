<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Type;
use Illuminate\Database\Eloquent\Collection;

class GetType
{
    public function __invoke(): Collection
    {
        return Type::all();
    }
}
