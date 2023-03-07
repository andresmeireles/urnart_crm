<?php

declare(strict_types=1);

namespace App\Exceptions\GraphQL;

use Exception;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;

final class AuthException extends Exception implements RendersErrorsExtensions
{
    public function __construct(
        protected readonly string $errorMessage,
        protected readonly string $reason,
    ) {
        logger()->warning($this->errorMessage);
        parent::__construct('User or password incorrect');
    }

    public function extensionsContent(): array
    {
        return [
            'info' => 'custom information',
            'reason' => $this->reason
        ];
    }

    public function isClientSafe(): bool
    {
        return true;
    }

    public function getCategory(): string
    {
        return 'custom';
    }
}
