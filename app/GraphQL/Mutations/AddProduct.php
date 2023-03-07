<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Actions\Product\Create;
use App\Exceptions\GraphQL\NotFoundException;
use App\Models\Color;
use App\Models\Model;
use App\Models\Spec;
use App\Models\Type;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class AddProduct
{
    public function __construct(private readonly Create $createColor)
    {
    }

    /**
     * @param  null  $_
     * @param  array{model: int, type: int, spec?: int, color?: int, price: float, height: string}  $args
     */
    public function __invoke($_, array $args): void
    {
        $model = Model::find($args['model']);
        $type = Type::find($args['type']);
        if ($model === null || $type === null) {
            throw new NotFoundException('model or type not founded', category: 'model or type');
        }
        $color = Color::find($args['color']) ?? null;
        $spec = Spec::find($args['spec']) ?? null;
        $this->createColor->create(
            Auth::user(),
            $model,
            $type,
            $args['price'],
            $args['height'],
            $color,
            $spec,
        );
    }
}
