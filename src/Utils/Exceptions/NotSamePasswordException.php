<?php declare(strict_types = 1);

namespace App\Utils\Exceptions;

class NotSamePasswordException extends \Exception
{
    public function __construct(string $message = 'Passwords not match')
    {
        parent::__construct($message, 0, null);
    }
}
