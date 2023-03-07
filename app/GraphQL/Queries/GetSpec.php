<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Spec;
use Illuminate\Database\Eloquent\Collection;

class GetSpec
{
    public function __invoke(): Collection
    {
        return Spec::all();
    }
}
