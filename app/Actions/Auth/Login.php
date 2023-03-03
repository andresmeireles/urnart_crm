<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\Api\Auth\LoginInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Login implements LoginInterface
{
    /**
     * @inheritDoc
     */
    public function withUserAndPassword(string $userName, string $password, bool $remember): string
    {
        $user = User::whereName($userName)->firstOrFail();
        if (!Hash::check($password, $user->password)) {
            throw new \Exception('user not found');
        }
        return $user->createToken($user->name, expiresAt: $remember ? null : now()->addHour(2))->plainTextToken;
    }
}
