<?php

namespace App\GraphQL;

use GraphQL\Error\Error;
use Closure;
use Nuwave\Lighthouse\Execution\ErrorHandler;

class SqlErrorFormatter implements ErrorHandler
{
    public function __invoke(?Error $error, Closure $next): ?array
    {
        if ($error === null) {
            return null;
        }
        $errorMessage = strtolower($error->getMessage());
        if (str_contains($errorMessage, "duplicate entry")) {
            return [
                'message' => "a field with this name already exists"
            ];
        }
        return $next($error);
    }
}
