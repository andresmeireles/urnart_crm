<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function createdBy(User $user): void
    {
        if ($this->created_by !== null) {
            throw new \Exception('already filled param');
        }
        $this->created_by = $user->id;
    }
}
