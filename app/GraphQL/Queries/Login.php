<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Actions\Api\Auth\LoginInterface;
use App\Exceptions\GraphQL\AuthException;

final class Login
{
    public function __construct(
        private readonly LoginInterface $login
    ) {
    }

    /**
     * @param  null  $_
     * @param  array{name: string, password: string, remember: bool}  $args
     */
    public function __invoke($_, array $args): string
    {
        try {
            return $this->login->withUserAndPassword($args['name'], $args['password'], $args['remember']);
        } catch (\Exception $err) {
            throw new AuthException($err->getMessage(), 'The reason is that you always wrong');
        }
    }
}
