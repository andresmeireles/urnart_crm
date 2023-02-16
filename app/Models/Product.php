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
        return $this->hasOne(ProductModel::class);
    }

    public function type(): HasOne
    {
        return $this->hasOne(Type::class);
    }

    public function color(): HasOne
    {
        return $this->hasOne(Color::class);
    }

    public function spec(): HasOne
    {
        return $this->hasOne(Spec::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
