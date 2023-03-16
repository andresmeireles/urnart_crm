<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Actions\Api\CreateInterface;
use App\Models\Model;
use App\Models\User;

/**
 * @implements CreateInterface<Model>
 */
class CreateModel implements CreateInterface
{
    public function create(string $name, User $user): Model
    {
        $model = new Model();
        $model->name = strtoupper($name);
        $model->createdBy($user);
        $model->save();
        return $model;
    }
}
