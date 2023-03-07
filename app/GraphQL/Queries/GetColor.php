<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Color;
use Illuminate\Database\Eloquent\Collection;

class GetColor
{
    public function __invoke(): Collection
    {
        return Color::all();
    }
}
