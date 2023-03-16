<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderActiveSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'order_id'
    ];
}
