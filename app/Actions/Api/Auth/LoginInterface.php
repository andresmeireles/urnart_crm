<?php

declare(strict_types=1);

namespace App\Actions\Api\Auth;

use Illuminate\Database\Eloquent\ModelNotFoundException;

interface LoginInterface
{
    /**
     * Login action, returns an api token to add on Authorization Bearer
     * @throws ModelNotFoundException
     */
    public function withUserAndPassword(string $userName, string $password, bool $remember): string;
}
