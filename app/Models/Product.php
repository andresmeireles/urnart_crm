<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Model as ProductModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'type_id',
        'color_id',
        'spec_id',
        'created_by',
        'price',
        'height'
    ];

    public function model(): HasOne
    {
        return $this->hasOne(ProductModel::class, 'id', 'model_id');
    }

    public function type(): HasOne
    {
        return $this->hasOne(Type::class, 'id', 'type_id');
    }

    public function color(): HasOne
    {
        return $this->hasOne(Color::class, 'id', 'color_id');
    }

    public function spec(): HasOne
    {
        return $this->hasOne(Spec::class, 'id', 'spec_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
