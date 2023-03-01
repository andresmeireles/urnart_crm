<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Models\Model;
use App\Models\User;

class CreateModel
{
    public function __construct(private readonly User $user)
    {
    }

    public function create(string $name): Model
    {
        $model = new Model();
        $model->name = $name;
        $model->createdBy($this->user);
        $model->save();
        return $model;
    }
}
