<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as LaravelModel;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Model extends LaravelModel
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
