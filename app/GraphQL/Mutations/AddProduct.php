<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Actions\Product\Create;
use App\Exceptions\GraphQL\NotFoundException;
use App\Models\Color;
use App\Models\Model;
use App\Models\Product;
use App\Models\Spec;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;

class AddProduct
{
    public function __construct(private readonly Create $createColor)
    {
    }

    /**
     * @param  null  $_
     * @param  array{model: int, type?: int, spec?: int, color?: int, price: float, height: string}  $args
     */
    public function __invoke($_, array $args): Product
    {
        $model = Model::find($args['model']);
        if ($model === null) {
            throw new NotFoundException('model or type not founded', category: 'model or type');
        }
        $type = Type::find($args['type'] ?? 0);
        $color = Color::find($args['color'] ?? 0);
        $spec = Spec::find($args['spec'] ?? 0);
        return $this->createColor->create(
            Auth::user(),
            $model,
            $args['price'],
            $args['height'],
            $type,
            $color,
            $spec,
        );
    }
}
