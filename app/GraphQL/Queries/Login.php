<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Actions\Api\Auth\LoginInterface;

final class Login
{
    public function __construct(
        private readonly LoginInterface $login
    ) {
    }

    /**
     * @param  null  $_
     * @param  array{name: string, password: string}  $args
     */
    public function __invoke($_, array $args): string
    {
        return $this->login->withUserAndPassword($args['name'], $args['password'], $args['remember']);
    }
}
