<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GetUser
{
    public function __invoke(): User
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            throw new \Exception('user non founded');
        }
        return $user;
    }
}
