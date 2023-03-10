<?php declare(strict_types = 1);

namespace App\Utils\Exceptions;

/**
 * Exception para quando tentado acessar as configurações com a casse config não iniciada ou removida
 */
final class NotFoundParameterException extends \Exception
{
}
