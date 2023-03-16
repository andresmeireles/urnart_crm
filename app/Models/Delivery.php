<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    public $table = 'delivery';

    protected $fillable = [
        'name',
        'is_paid',
        'user_id'
    ];
}
