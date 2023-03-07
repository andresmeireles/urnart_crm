<?php

declare(strict_types=1);

namespace App\Exceptions\GraphQL;

use Exception;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;

final class NotFoundException extends Exception implements RendersErrorsExtensions
{
    public function __construct(
        protected readonly string $errorMessage,
        private readonly string $reason = 'some reason',
        private readonly string $category = 'unknown'
    ) {
        logger()->warning($this->errorMessage);
        parent::__construct($this->errorMessage);
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
        return $this->category;
    }
}
